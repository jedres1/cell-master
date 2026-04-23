<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Number;
use App\Company;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class NumberController extends Controller
{
    public function index(Request $request)
    {
        $numbers = Number::filter($request->only('search'))->orderBy('id','asc')->paginate(10)->appends($request->only('search'));
            
        return view('numbers.index',[
            'numbers' => $numbers,
            'filters'   => $request->all('search')
        ]);
    }

    public function create()
    {
        $number= new Number();
        $companies = Company::all();
        return view('numbers.create',compact('companies','number'));
    }

    public function store( Request $request)
    {
        $this->validate($request,[
            'number' =>'required',
            'company_id' =>'required'
            
        ]);
        Number::create([
            'number' => $request->number,
            'company_id' => $request->company_id,
            'status' => $request->status,
            'data_plan' => $request->data_plan
            
        ]);
        return redirect('/numbers'); 
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'numbers_file' => 'required|file|mimes:xlsx'
        ]);

        if (!class_exists(ZipArchive::class)) {
            return back()->withErrors([
                'numbers_file' => 'La extension ZipArchive no esta disponible en PHP.'
            ]);
        }

        $rows = $this->readExcelRows($request->file('numbers_file')->getRealPath());

        if (count($rows) <= 1) {
            return back()->withErrors([
                'numbers_file' => 'El archivo no contiene filas para importar.'
            ]);
        }

        $expectedHeaders = ['numero', 'empresa', 'estado', 'plan_datos'];
        $headers = array_map(function ($value) {
            return mb_strtolower(trim((string) $value));
        }, array_slice($rows[0], 0, 4));

        if ($headers !== $expectedHeaders) {
            return back()->withErrors([
                'numbers_file' => 'Los encabezados del archivo deben ser exactamente: numero, empresa, estado, plan_datos.'
            ]);
        }

        $companyMap = Company::query()
            ->get()
            ->mapWithKeys(function ($company) {
                return [mb_strtolower(trim($company->company_name)) => $company->id];
            });

        $created = 0;
        $skipped = 0;
        $rowErrors = [];

        DB::beginTransaction();

        try {
            foreach (array_slice($rows, 1) as $index => $row) {
                $rowNumber = $index + 2;
                $numberValue = trim((string) ($row[0] ?? ''));
                $companyValue = trim((string) ($row[1] ?? ''));
                $statusValue = trim((string) ($row[2] ?? ''));
                $dataPlanValue = trim((string) ($row[3] ?? ''));

                if ($numberValue === '' && $companyValue === '' && $statusValue === '' && $dataPlanValue === '') {
                    continue;
                }

                if ($numberValue === '' || $companyValue === '') {
                    $skipped++;
                    $rowErrors[] = 'Fila '.$rowNumber.': faltan numero o empresa.';
                    continue;
                }

                $companyId = $this->resolveCompanyId($companyValue, $companyMap);

                if (!$companyId) {
                    $skipped++;
                    $rowErrors[] = 'Fila '.$rowNumber.': empresa no valida "'.$companyValue.'".';
                    continue;
                }

                $status = $this->normalizeStatus($statusValue);

                if ($status === null) {
                    $skipped++;
                    $rowErrors[] = 'Fila '.$rowNumber.': estado no valido "'.$statusValue.'". Use 1/2 o Asignado/No Asignado.';
                    continue;
                }

                if (Number::where('number', $numberValue)->exists()) {
                    $skipped++;
                    $rowErrors[] = 'Fila '.$rowNumber.': el numero "'.$numberValue.'" ya existe.';
                    continue;
                }

                Number::create([
                    'number' => $numberValue,
                    'company_id' => $companyId,
                    'status' => $status,
                    'data_plan' => $dataPlanValue !== '' ? $dataPlanValue : null
                ]);

                $created++;
            }

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();

            return back()->withErrors([
                'numbers_file' => 'No se pudo procesar el archivo: '.$exception->getMessage()
            ]);
        }

        return redirect()->route('numbers.create')
            ->with('import_status', 'Importacion completada. Registros creados: '.$created.'. Filas omitidas: '.$skipped.'.')
            ->with('import_errors', $rowErrors);
    }
    public function show(Number $number)
    {
        return view('numbers.show',compact('number'));
    }
    public function edit($id)
    {
        
        $number = Number::find($id);
        
        $companies=Company::all();
        return view('numbers.edit',compact('number','companies'));
    }
    public function update(Number $number)
    {
        $number->update([
            'number' => request('number'),
            'company_id' => request('company_id'),
            'status' => request('status'),
            'data_plan'=> request('data_plan')
        ]);
        
        return redirect()->route('numbers.show',$number);
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

    protected function normalizeStatus($statusValue)
    {
        if ($statusValue === '' || $statusValue === null) {
            return 2;
        }

        $normalizedValue = mb_strtolower(trim($statusValue));

        $map = [
            '1' => 1,
            '2' => 2,
            'asignado' => 1,
            'no asignado' => 2,
            'no_asignado' => 2,
            'disponible' => 2
        ];

        return $map[$normalizedValue] ?? null;
    }
}
