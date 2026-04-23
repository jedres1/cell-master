<?php

namespace App\Http\Controllers;

use App\Assignment;
use App\AssignmentItem;
use App\Cellphone;
use App\Employee;
use App\Number;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Luecano\NumeroALetras\NumeroALetras;

class AssignmentCellphoneEmployeeController extends Controller
{
    public function index(Request $request)
    {
        $activeAssignments = Assignment::filter($request->only('search'))
            ->with(['items.assignable'])
            ->orderBy('id', 'desc')
            ->paginate(25)
            ->appends($request->only('search'));

        return view('assignments.index', [
            'activeAssignments' => $activeAssignments,
            'filters' => $request->only('search'),
        ]);
    }

    public function create()
    {
        $assignment = new Assignment([
            'status' => 1,
            'note' => '',
        ]);

        return view('assignments.create', $this->formData($assignment));
    }

    public function store(Request $request)
    {
        $payload = $this->validatedPayload($request);

        DB::transaction(function () use ($payload) {
            $assignment = Assignment::create([
                'status' => $payload['status'],
                'note' => $payload['note'],
            ]);

            $this->syncAssignmentItems($assignment, $payload);
        });

        return redirect('/assignments');
    }

    public function show($id)
    {
        $assignment = Assignment::with(['items.assignable'])->findOrFail($id);

        return view('assignments.show', compact('assignment'));
    }

    public function update(Request $request, Assignment $assignment)
    {
        $payload = $this->validatedPayload($request, $assignment->id);

        DB::transaction(function () use ($assignment, $payload) {
            $assignment->update([
                'status' => $payload['status'],
                'note' => $payload['note'],
            ]);

            $this->syncAssignmentItems($assignment, $payload);
        });

        return redirect()->route('assignments.show', $assignment);
    }

    public function download($id)
    {
        $formatter = new NumeroALetras();
        setlocale(LC_ALL, 'es_ES');
        $date = strtolower($formatter->toWords(date('d'))).' de '.ucwords(strftime('%B')).' del '.strtolower($formatter->toWords(date('Y')));
        $assignment = Assignment::with(['items.assignable'])->findOrFail($id);
        $employee = $assignment->employeeEntity();
        $cellphone = $assignment->cellphoneEntity();
        $number = $assignment->numberEntity();

        if (!$employee || !$cellphone || !$number) {
            throw ValidationException::withMessages([
                'assignment' => 'El acuerdo solo se puede generar cuando la asignacion tiene empleado, celular y numero.',
            ]);
        }

        try {
            if ($assignment->status == 3) {
                $template = new \PhpOffice\PhpWord\TemplateProcessor('docs\entrega_salida.docx');
                $template->setValue('name', $employee->employee_name);
                $template->setValue('model', $cellphone->model.' imei: '.$cellphone->imei);
                $template->setValue('company', $cellphone->company->company_name);
                $template->setValue('brand', $cellphone->brand);
                $template->setValue('status', $assignment->note);
                $template->setValue('accessory', $cellphone->accessories);
            } else {
                $template = new \PhpOffice\PhpWord\TemplateProcessor('docs\acuerdo_cell.docx');
                $template->setValue('name', $employee->employee_name);
                $template->setValue('job_title', $employee->job_title);
                $template->setValue('model', $cellphone->model);
                $template->setValue('company', $cellphone->company->company_name);
                $template->setValue('department', $employee->department->department_name);
                $template->setValue('number', $number->number);
                $template->setValue('data_plan', $number->data_plan);
                $template->setValue('imei', $cellphone->imei);
                $template->setValue('legal_representative', $cellphone->company->company_name == 'PUBLIMAGEN' ? 'Orlando LLovera' : 'Juan Gilberto Canas');
                $template->setValue('brand', $cellphone->brand);
                $template->setValue('date', $date);
                $template->setValue('note', $assignment->note);
            }

            $tempFile = tempnam(sys_get_temp_dir(), 'PHPWord');
            $template->saveAs($tempFile);

            $headers = [
                'Content-Type: application\octet-stream',
            ];

            return response()->download($tempFile, $employee->employee_name.'.docx', $headers)->deleteFileAfterSend(true);
        } catch (\PhpOffice\PhpWord\Exception\Exception $exception) {
            return back()->withErrors([
                'assignment' => 'No se pudo generar el acuerdo.',
            ]);
        }
    }

    public function edit($id)
    {
        $assignment = Assignment::with(['items.assignable'])->findOrFail($id);

        return view('assignments.edit', $this->formData($assignment));
    }

    public function destroy(Assignment $assignment)
    {
        DB::transaction(function () use ($assignment) {
            $items = $assignment->items()->get();
            $cellphoneIds = $items->where('assignable_type', Cellphone::class)->pluck('assignable_id')->all();
            $numberIds = $items->where('assignable_type', Number::class)->pluck('assignable_id')->all();

            $assignment->delete();

            $this->refreshAssetStatuses(
                array_unique($cellphoneIds),
                array_unique($numberIds)
            );
        });

        return redirect('/assignments')->with('status', 'Asignacion eliminada correctamente.');
    }

    protected function formData(Assignment $assignment)
    {
        $selectedEmployee = $assignment->employeeEntity();
        $selectedCellphone = $assignment->cellphoneEntity();
        $selectedNumber = $assignment->numberEntity();

        return [
            'assignment' => $assignment,
            'employees' => Employee::with(['company', 'department'])->orderBy('employee_name', 'asc')->get(),
            'cellphones' => $this->availableCellphones(optional($selectedCellphone)->id),
            'numbers' => $this->availableNumbers(optional($selectedNumber)->id),
            'selectedEmployee' => $selectedEmployee,
            'selectedCellphone' => $selectedCellphone,
            'selectedNumber' => $selectedNumber,
        ];
    }

    protected function validatedPayload(Request $request, $ignoreAssignmentId = null)
    {
        $payload = $request->validate([
            'employee_id' => 'nullable|integer|exists:employees,id',
            'cellphone_id' => 'nullable|integer|exists:cellphones,id',
            'number_id' => 'nullable|integer|exists:numbers,id',
            'status' => 'required|integer|in:1,2,3',
            'note' => 'nullable|string|max:255',
        ]);

        $selectedCount = collect([
            $payload['employee_id'] ?? null,
            $payload['cellphone_id'] ?? null,
            $payload['number_id'] ?? null,
        ])->filter()->count();

        if ($selectedCount < 2) {
            throw ValidationException::withMessages([
                'assignment' => 'La asignacion debe contener al menos dos objetos.',
            ]);
        }

        $this->assertAvailability(Cellphone::class, $payload['cellphone_id'] ?? null, $ignoreAssignmentId);
        $this->assertAvailability(Number::class, $payload['number_id'] ?? null, $ignoreAssignmentId);

        return $payload;
    }

    protected function syncAssignmentItems(Assignment $assignment, array $payload)
    {
        $previousItems = $assignment->items()->get();
        $previousCellphoneIds = $previousItems->where('assignable_type', Cellphone::class)->pluck('assignable_id')->all();
        $previousNumberIds = $previousItems->where('assignable_type', Number::class)->pluck('assignable_id')->all();

        $assignment->items()->delete();

        $items = [
            ['type' => Employee::class, 'id' => $payload['employee_id'] ?? null, 'slot' => 'employee', 'sort' => 1],
            ['type' => Cellphone::class, 'id' => $payload['cellphone_id'] ?? null, 'slot' => 'cellphone', 'sort' => 2],
            ['type' => Number::class, 'id' => $payload['number_id'] ?? null, 'slot' => 'number', 'sort' => 3],
        ];

        foreach ($items as $item) {
            if (!$item['id']) {
                continue;
            }

            AssignmentItem::create([
                'assignment_id' => $assignment->id,
                'assignable_type' => $item['type'],
                'assignable_id' => $item['id'],
                'slot' => $item['slot'],
                'sort_order' => $item['sort'],
            ]);
        }

        $currentCellphoneIds = array_filter([$payload['cellphone_id'] ?? null]);
        $currentNumberIds = array_filter([$payload['number_id'] ?? null]);

        $this->refreshAssetStatuses(
            array_unique(array_merge($previousCellphoneIds, $currentCellphoneIds)),
            array_unique(array_merge($previousNumberIds, $currentNumberIds))
        );
    }

    protected function refreshAssetStatuses(array $cellphoneIds, array $numberIds)
    {
        foreach ($cellphoneIds as $cellphoneId) {
            if (!$cellphoneId) {
                continue;
            }

            $hasActiveAssignment = AssignmentItem::query()
                ->where('assignable_type', Cellphone::class)
                ->where('assignable_id', $cellphoneId)
                ->whereHas('assignment', function ($query) {
                    $query->whereIn('status', [1, 2]);
                })
                ->exists();

            Cellphone::where('id', $cellphoneId)->update([
                'status' => $hasActiveAssignment ? 1 : 0,
            ]);
        }

        foreach ($numberIds as $numberId) {
            if (!$numberId) {
                continue;
            }

            $hasActiveNumberAssignment = AssignmentItem::query()
                ->where('assignable_type', Number::class)
                ->where('assignable_id', $numberId)
                ->whereHas('assignment', function ($query) {
                    $query->whereIn('status', [1, 2]);
                })
                ->exists();

            Number::where('id', $numberId)->update([
                'status' => $hasActiveNumberAssignment ? 1 : 2,
            ]);
        }
    }

    protected function availableCellphones($selectedId = null)
    {
        return Cellphone::with(['company', 'department'])
            ->where(function ($query) use ($selectedId) {
                $query->where('status', '<>', 1);

                if ($selectedId) {
                    $query->orWhere('id', $selectedId);
                }
            })
            ->orderBy('brand')
            ->orderBy('model')
            ->get();
    }

    protected function availableNumbers($selectedId = null)
    {
        return Number::with('company')
            ->where(function ($query) use ($selectedId) {
                $query->where('status', '<>', 1);

                if ($selectedId) {
                    $query->orWhere('id', $selectedId);
                }
            })
            ->orderBy('number')
            ->get();
    }

    protected function assertAvailability($type, $id = null, $ignoreAssignmentId = null)
    {
        if (!$id) {
            return;
        }

        $existingItem = AssignmentItem::query()
            ->where('assignable_type', $type)
            ->where('assignable_id', $id)
            ->with(['assignment.items.assignable'])
            ->whereHas('assignment', function ($assignmentQuery) use ($ignoreAssignmentId) {
                $assignmentQuery->whereIn('status', [1, 2]);

                if ($ignoreAssignmentId) {
                    $assignmentQuery->where('id', '<>', $ignoreAssignmentId);
                }
            })
            ->first();

        if ($existingItem) {
            $currentAssignment = $existingItem->assignment;
            $messages = [
                Cellphone::class => 'El celular seleccionado ya pertenece a otra asignacion activa.',
                Number::class => 'El numero seleccionado ya pertenece a otra asignacion activa.',
            ];

            $details = $currentAssignment
                ? ' Asignacion actual #'.$currentAssignment->id.': '.$currentAssignment->itemSummary().' ['.$currentAssignment->statusLabel().'].'
                : '';

            throw ValidationException::withMessages([
                'assignment' => ($messages[$type] ?? 'Uno de los objetos ya pertenece a otra asignacion activa.').$details,
            ]);
        }
    }
}
