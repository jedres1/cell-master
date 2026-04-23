@extends('layouts.panel')
@section('page_title', 'Editar Empleado')
@section('page_subtitle', 'Actualiza la informacion del empleado seleccionado')
@section('content')
<div class="container">
  <div class="card">
    <div class="card-body">
      <form class="row g-3" action="{{ route('employees.update',$employee) }}" method="POST">
        @method('patch')
        @include('employees.partials.form-employee',['btnText'=>'Actualizar'])
        <div class="col-6 m3">
          <a href="{{ url('/employees/show',$employee) }}" class="btn btn-danger float-right">Cancelar</a>
        </div>
      </form>  
    </div>
  </div>
    
</div>

@endsection
