<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Response;
use App\Cellphone;
use App\Company;
use App\Department; 
use App\AssignmentCellphoneEmployee;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class CellphoneController extends Controller
{
    public function index(Request $request)
    {
        $movilCellphones = Cellphone::filter($request->only('search'))->orderBy('id','asc')
            ->with(['department','company'])
            ->paginate(25)
            ->appends($request->only('search'));
            
            return view('cellphones.index',[
                'movilCellphones' => $movilCellphones,
                'filters'   => $request->all('search')
            ]);
        
    }

    public function create()
    {
        $cellphone =new Cellphone();
        $companies=Company::all();
        $departments=Department::all();
        return view('cellphones.create',compact('companies','departments','cellphone'));
    }

    public function store( Request $request)
    {
        
        /*$this->validate($request,[
            'imei' =>'required',
            'brand' =>'required',
            'model' =>'required',
            'status'=>'required',
            'department_id' => 'required',
            'company_id' => 'required'
        ]);*/
        
        Cellphone::create([
            'imei' => $request->imei,
            'brand' => $request->brand,
            'model' => $request->model,
            'status' => $request->status,
            'department_id' => $request->department_id,
            'company_id' => $request->company_id,
            'accessories' => $request->accessories
        ]);

        return redirect('/cellphones'); 
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'cellphones_file' => 'required|file|mimes:xlsx'
        ]);

        if (!class_exists(ZipArchive::class)) {
            return back()->withErrors([
                'cellphones_file' => 'La extension ZipArchive no esta disponible en PHP.'
            ]);
        }

        $rows = $this->readExcelRows($request->file('cellphones_file')->getRealPath());

        if (count($rows) <= 1) {
            return back()->withErrors([
                'cellphones_file' => 'El archivo no contiene filas para importar.'
            ]);
        }

        $expectedHeaders = ['modelo', 'marca', 'imei', 'empresa', 'departamento', 'estado', 'accesorios'];
        $headers = array_map(function ($value) {
            return mb_strtolower(trim((string) $value));
        }, array_slice($rows[0], 0, 7));

        if ($headers !== $expectedHeaders) {
            return back()->withErrors([
                'cellphones_file' => 'Los encabezados del archivo deben ser exactamente: modelo, marca, imei, empresa, departamento, estado, accesorios.'
            ]);
        }

        $companyMap = Company::query()
            ->get()
            ->mapWithKeys(function ($company) {
                return [mb_strtolower(trim($company->company_name)) => $company->id];
            });

        $departmentMap = Department::query()
            ->get()
            ->mapWithKeys(function ($department) {
                return [mb_strtolower(trim($department->department_name)) => $department->id];
            });

        $created = 0;
        $skipped = 0;
        $rowErrors = [];

        DB::beginTransaction();

        try {
            foreach (array_slice($rows, 1) as $index => $row) {
                $rowNumber = $index + 2;
                $modelValue = trim((string) ($row[0] ?? ''));
                $brandValue = trim((string) ($row[1] ?? ''));
                $imeiValue = trim((string) ($row[2] ?? ''));
                $companyValue = trim((string) ($row[3] ?? ''));
                $departmentValue = trim((string) ($row[4] ?? ''));
                $statusValue = trim((string) ($row[5] ?? ''));
                $accessoriesValue = trim((string) ($row[6] ?? ''));

                if (
                    $modelValue === '' &&
                    $brandValue === '' &&
                    $imeiValue === '' &&
                    $companyValue === '' &&
                    $departmentValue === '' &&
                    $statusValue === '' &&
                    $accessoriesValue === ''
                ) {
                    continue;
                }

                if ($modelValue === '' || $brandValue === '' || $imeiValue === '' || $companyValue === '' || $departmentValue === '') {
                    $skipped++;
                    $rowErrors[] = 'Fila '.$rowNumber.': faltan campos obligatorios.';
                    continue;
                }

                if (Cellphone::where('imei', $imeiValue)->exists()) {
                    $skipped++;
                    $rowErrors[] = 'Fila '.$rowNumber.': el IMEI "'.$imeiValue.'" ya existe.';
                    continue;
                }

                $companyId = $this->resolveCompanyId($companyValue, $companyMap);

                if (!$companyId) {
                    $skipped++;
                    $rowErrors[] = 'Fila '.$rowNumber.': empresa no valida "'.$companyValue.'".';
                    continue;
                }

                $departmentId = $this->resolveDepartmentId($departmentValue, $departmentMap);

                if (!$departmentId) {
                    $skipped++;
                    $rowErrors[] = 'Fila '.$rowNumber.': departamento no valido "'.$departmentValue.'".';
                    continue;
                }

                $status = $this->normalizeStatus($statusValue);

                if ($status === null) {
                    $skipped++;
                    $rowErrors[] = 'Fila '.$rowNumber.': estado no valido "'.$statusValue.'". Use 0/1 o Disponible/Asignado.';
                    continue;
                }

                Cellphone::create([
                    'model' => $modelValue,
                    'brand' => $brandValue,
                    'imei' => $imeiValue,
                    'status' => $status,
                    'department_id' => $departmentId,
                    'company_id' => $companyId,
                    'accessories' => $accessoriesValue !== '' ? $accessoriesValue : null
                ]);
                $created++;
            }

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();

            return back()->withErrors([
                'cellphones_file' => 'No se pudo procesar el archivo: '.$exception->getMessage()
            ]);
        }

        return redirect()->route('cellphones.create')
            ->with('import_status', 'Importacion completada. Registros creados: '.$created.'. Filas omitidas: '.$skipped.'.')
            ->with('import_errors', $rowErrors);
    }

    public function edit($id)
    {
        $cellphone = Cellphone::find($id);
        $companies=Company::all();
        $departments=Department::all();
        return view('cellphones.edit',compact('cellphone','companies','departments'));
    }
    public function update(Cellphone $cellphone)
    {
       
        $cellphone->update([
            'imei' => request('imei'),
            'brand' => request('brand'),
            'model' => request('model'),
            'status' => request('status'),
            'department_id' => request('department_id'),
            'company_id' => request('company_id'),
            'accessories' => request('accessories')
        ]);
        return redirect('/cellphones');
       
    }
    public function show(Cellphone $cellphone)
    {
        return view('cellphones.show',compact('cellphone'));
    }

    protected function readExcelRows($filePath)
    {
        $zip = new ZipArchive();

        if ($zip->open($filePath) !== true) {
            throw new \RuntimeException('No se pudo abrir el archivo Excel.');
        }

        $sharedStrings = [];
        $sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');

        if ($sharedStringsXml !== false) {
            $sharedStringsDocument = simplexml_load_string($sharedStringsXml);

            if ($sharedStringsDocument !== false) {
                foreach ($sharedStringsDocument->si as $stringItem) {
                    $value = '';

                    if (isset($stringItem->t)) {
                        $value = (string) $stringItem->t;
                    } else {
                        foreach ($stringItem->r as $run) {
                            $value .= (string) $run->t;
                        }
                    }

                    $sharedStrings[] = $value;
                }
            }
        }

        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if ($sheetXml === false) {
            throw new \RuntimeException('No se encontro la primera hoja del archivo.');
        }

        $sheetDocument = simplexml_load_string($sheetXml);

        if ($sheetDocument === false) {
            throw new \RuntimeException('El contenido de la hoja no es valido.');
        }

        $namespaces = $sheetDocument->getNamespaces(true);

        if (isset($namespaces[''])) {
            $sheetDocument->registerXPathNamespace('main', $namespaces['']);
            $rowNodes = $sheetDocument->xpath('//main:sheetData/main:row');
        } else {
            $rowNodes = $sheetDocument->sheetData->row;
        }

        $rows = [];

        foreach ($rowNodes as $rowNode) {
            $values = [];

            foreach ($rowNode->c as $cell) {
                $reference = (string) $cell['r'];
                $columnIndex = $this->columnLetterToIndex(preg_replace('/\d+/', '', $reference));
                $cellType = (string) $cell['t'];
                $value = '';

                if ($cellType === 'inlineStr') {
                    $value = isset($cell->is->t) ? (string) $cell->is->t : '';
                } else {
                    $rawValue = isset($cell->v) ? (string) $cell->v : '';
                    $value = $cellType === 's' ? ($sharedStrings[(int) $rawValue] ?? '') : $rawValue;
                }

                $values[$columnIndex] = trim($value);
            }

            if (!empty($values)) {
                ksort($values);
                $lastColumnIndex = max(array_keys($values));
                $normalizedRow = [];

                for ($columnIndex = 0; $columnIndex <= $lastColumnIndex; $columnIndex++) {
                    $normalizedRow[$columnIndex] = $values[$columnIndex] ?? '';
                }

                $rows[] = $normalizedRow;
            }
        }

        return $rows;
    }

    protected function columnLetterToIndex($letters)
    {
        $letters = strtoupper($letters);
        $index = 0;

        for ($position = 0; $position < strlen($letters); $position++) {
            $index = ($index * 26) + (ord($letters[$position]) - 64);
        }

        return $index - 1;
    }

    protected function resolveCompanyId($companyValue, $companyMap)
    {
        if (is_numeric($companyValue) && Company::where('id', (int) $companyValue)->exists()) {
            return (int) $companyValue;
        }

        return $companyMap[mb_strtolower($companyValue)] ?? null;
    }

    protected function resolveDepartmentId($departmentValue, $departmentMap)
    {
        if (is_numeric($departmentValue) && Department::where('id', (int) $departmentValue)->exists()) {
            return (int) $departmentValue;
        }

        return $departmentMap[mb_strtolower($departmentValue)] ?? null;
    }

    protected function normalizeStatus($statusValue)
    {
        if ($statusValue === '' || $statusValue === null) {
            return 0;
        }

        $normalizedValue = mb_strtolower(trim($statusValue));

        $map = [
            '0' => 0,
            '1' => 1,
            'disponible' => 0,
            'asignado' => 1
        ];

        return $map[$normalizedValue] ?? null;
    }
    //return redirect('/assignments');
}
