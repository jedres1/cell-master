@extends('layouts.panel')
@section('page_title', 'Detalle de Empleado')
@section('page_subtitle', 'Consulta la informacion completa del empleado')
@section('content')
<div class="container">
  <div class="card mb-4">
    <div class="card-body">
        <p class="list-group-item ">Nombre: {{$employee->employee_name}}</p>
        <p class="list-group-item ">email: {{$employee->email}}</p>
        <p class="list-group-item ">Empresa: {{$employee->company->company_name}}</p>
        <p class="list-group-item ">Departamento: {{$employee->department->department_name}}</p>
        <p class="list-group-item ">cargo: {{$employee->job_title}}</p>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-body">
      <h4 class="mb-3">Historial de Laminas y Asignaciones</h4>
      <div class="row">
        <div class="col-md-6 mb-3">
          <div class="border rounded p-3 h-100">
            <p class="text-muted mb-1">Asignaciones registradas</p>
            <h3 class="mb-0">{{ $assignmentHistory->count() }}</h3>
          </div>
        </div>
        <div class="col-md-6 mb-3">
          <div class="border rounded p-3 h-100">
            <p class="text-muted mb-1">Veces que se le cambio la lamina</p>
            <h3 class="mb-0">{{ $numberChanges }}</h3>
          </div>
        </div>
      </div>

      @if ($assignmentHistory->isEmpty())
        <p class="mb-0 text-muted">Este empleado no tiene historial de asignaciones.</p>
      @else
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Combinacion</th>
                <th>Numero</th>
                <th>Celular</th>
                <th>Estado</th>
                <th>Fecha</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($assignmentHistory as $historyItem)
                <tr>
                  <td>{{ $historyItem->id }}</td>
                  <td>{{ $historyItem->itemSummary() }}</td>
                  <td>{{ optional($historyItem->numberEntity())->number ?: 'Sin numero' }}</td>
                  <td>
                    @if ($historyItem->cellphoneEntity())
                      {{ $historyItem->cellphoneEntity()->brand }} {{ $historyItem->cellphoneEntity()->model }}
                    @else
                      Sin celular
                    @endif
                  </td>
                  <td>{{ $historyItem->statusLabel() }}</td>
                  <td>{{ optional($historyItem->created_at)->format('d/m/Y H:i') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>

  <form class="row g-3" action="{{ route('employees.edit',$employee) }}" method="get">
    <div class="col-6 m3  ">
        <button type="submit" class="btn btn-info">Editar</button>
    </div>
    <div class="col-6 m3  ">
      <a href="{{ url('/employees') }}" class="btn btn-danger float-right">Regresar</a>
    </div>
  </form>
</div>
@endsection
