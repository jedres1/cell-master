<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Assignment;
use App\Employee;
use App\Company;
use App\Department;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::filter($request->only('search'))->orderBy('id','asc')
        ->with(['department','company'])
        ->paginate(10)
        ->appends($request->only('search'));
            
        return view('employees.index',[
            'employees' => $employees,
            'filters'   => $request->all('search')
        ]);
    }

    public function create()
    {
        $employee = new Employee();
        $companies = Company::all();
        $departments = Department::all();
        return view('employees.create',compact('companies','departments','employee'));
    }

    public function store( Request $request)
    {
        $this->validate($request,[
            'employee_name' =>'required',
            'department_id' =>'required',
            'job_title'=>'required',
            'email'=>'required',
            'new_company_name' => 'nullable|string|max:255',
        ]);

        $companyId = $this->resolveCompanyId($request);

        Employee::create([
            'employee_name' => $request->employee_name,
            'company_id' => $companyId,
            'department_id' => $request->department_id,
            'job_title' => $request->job_title,
            'email' => $request->email
            
        ]);
        return redirect('/employees'); 
    }
    public function show(Employee $employee)
    {
        $assignmentHistory = Assignment::with(['items.assignable'])
            ->whereIn('id', function ($query) use ($employee) {
                $query->select('assignment_id')
                    ->from('assignment_items')
                    ->where('assignable_type', Employee::class)
                    ->where('assignable_id', $employee->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $numberTimeline = $assignmentHistory
            ->sortBy('created_at')
            ->map(function ($assignment) {
                return optional($assignment->numberEntity())->id;
            })
            ->filter()
            ->values();

        $numberChanges = 0;
        $previousNumberId = null;

        foreach ($numberTimeline as $numberId) {
            if ($previousNumberId !== null && $previousNumberId !== $numberId) {
                $numberChanges++;
            }

            $previousNumberId = $numberId;
        }

        return view('employees.show', compact('employee', 'assignmentHistory', 'numberChanges'));
    }
    public function edit($id)
    {   
        $employee=Employee::find($id);
        $companies=Company::all();
        $departments=Department::all();
        return view('employees.edit',compact('employee','companies','departments'));
    }
    public function update(Employee $employee)
    {
        request()->validate([
            'employee_name' =>'required',
            'department_id' =>'required',
            'job_title'=>'required',
            'email'=>'required',
            'new_company_name' => 'nullable|string|max:255',
        ]);

        $companyId = $this->resolveCompanyId(request());

        $employee->update([
            'employee_name' => request('employee_name'),
            'company_id' => $companyId,
            'department_id' => request('department_id'),
            'job_title' => request('job_title'),
            'email' => request('email')
        ]);
        return redirect()->route('employees.show',$employee);
    }

    protected function resolveCompanyId(Request $request)
    {
        $selectedCompanyId = $request->input('company_id');
        $newCompanyName = trim((string) $request->input('new_company_name'));

        if ($selectedCompanyId === '__new__') {
            $request->validate([
                'new_company_name' => 'required|string|max:255',
            ]);

            return Company::firstOrCreate([
                'company_name' => $newCompanyName,
            ])->id;
        }

        $request->validate([
            'company_id' => 'required|exists:companies,id',
        ]);

        return $selectedCompanyId;
    }
}
