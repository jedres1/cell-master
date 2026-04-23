@extends('layouts.panel')
@section('page_title', 'Empleados')
@section('page_subtitle', 'Listado general de empleados registrados')
@section('content')
<div class="container">
    <div>
<a class='btn btn-info' href="{{ url('employees/create') }}">Add Employee</a>
    </div>
    <br>
    <table class="table  table-hover table-dark">
        <thead>
            <tr>
                <th scope="col">Nombre </th>
                <th scope="col">Labora</th>
                <th scope="col">Area</th>
                <th scope="col">Cargo</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $employee)
            <tr>
                <td>{{$employee->employee_name}}</td>
                <td>{{$employee->company->company_name}}</td>
                <td>{{$employee->department->department_name}}</td>
                <td>{{$employee->job_title}}</td>
                <td><a class="btn btn-icon btn-primary btn-sm" href="{{url('employees/show',$employee)}}"><i class="ni ni-circle-08 text-dark"></i></a></td>
            </tr>    
            @endforeach
            
        </tbody>
    </table>
    {{$employees->links()}}
</div>
@endsection
