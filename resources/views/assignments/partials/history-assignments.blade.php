<table class="table table-hover table-dark">
    <thead>
        <tr>
            <th scope="col">Modelo</th>
            <th scope="col">Marca</th>
            <th scope="col">numero</th>
            <th scope="col">imei</th>
            <th scope="col">Empresa</th>
            <th scope="col">Departamento</th>
            <th scope="col">Empleado</th>
            <th colspan="2" scope="col">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($historyAssignments as $assignment)
        
        <tr>
            <td>{{$assignment->cellphone->model}}</td>   
            <td>{{$assignment->cellphone->brand}}</td>
            <td>N/A</td>
            <td>{{$assignment->cellphone->imei}}</td>
            <td>{{$assignment->cellphone->company->company_name}}</td>
            <td>{{$assignment->cellphone->department->department_name}}</td>
            <td>{{$assignment->employee->employee_name}}</td>
            <td>
                <a class="btn btn-icon btn-info btn-sm" href="{{route('download.acuerdo', $assignment)}}">
                    <i class="ni ni-archive-2 text-white"></i>
                </a>
                
            </td>
        </tr>
   
        @endforeach
        
    </tbody>
</table>
{{$historyAssignments->links()}}
