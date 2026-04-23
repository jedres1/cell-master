@extends('layouts.panel')
@section('page_title', 'Detalle de Asignacion')
@section('page_subtitle', 'Resumen de los objetos vinculados a esta asignacion')
@section('content')
<style>
  .assignment-detail-shell {
    max-width: 1180px;
    margin: 0 auto;
  }

  .assignment-detail-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
    justify-content: flex-end;
  }

  @media (max-width: 575px) {
    .assignment-detail-actions .btn,
    .assignment-detail-actions form {
      width: 100%;
    }

    .assignment-detail-actions form .btn {
      width: 100%;
    }
  }
</style>
<div class="container assignment-detail-shell">
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start flex-wrap">
        <div>
          <h3 class="mb-1">Asignacion #{{ $assignment->id }}</h3>
          <p class="text-muted mb-2">{{ $assignment->itemSummary() }}</p>
          <span class="badge badge-primary">{{ $assignment->statusLabel() }}</span>
        </div>
        <div class="mt-3 mt-md-0 assignment-detail-actions">
          <a class="btn btn-dark" href="{{ url('/assignments') }}">Regresar</a>
          <a class="btn btn-info" href="{{ route('assignments.edit', $assignment) }}">Editar</a>
          <a class="btn btn-warning" href="{{ route('download.acuerdo', $assignment->id) }}">Acuerdo</a>
          <form action="{{ route('assignments.delete', $assignment) }}" method="POST" class="d-inline" onsubmit="return confirm('Deseas eliminar esta asignacion?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Eliminar</button>
          </form>
        </div>
      </div>
      <hr>
      <p class="mb-0"><strong>Nota:</strong> {{ $assignment->note ?: 'Sin nota' }}</p>
    </div>
  </div>

  <div class="row">
    @foreach ($assignment->items as $item)
      <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            @if ($item->assignable_type === \App\Employee::class)
              <span class="badge badge-pill badge-info mb-3">Empleado</span>
              <h4 class="mb-1">{{ $item->assignable->employee_name }}</h4>
              <p class="mb-1 text-muted">{{ $item->assignable->job_title }}</p>
              <p class="mb-1">Empresa: {{ $item->assignable->company->company_name }}</p>
              <p class="mb-0">Departamento: {{ $item->assignable->department->department_name }}</p>
            @elseif ($item->assignable_type === \App\Cellphone::class)
              <span class="badge badge-pill badge-primary mb-3">Celular</span>
              <h4 class="mb-1">{{ $item->assignable->brand }} {{ $item->assignable->model }}</h4>
              <p class="mb-1 text-muted">IMEI: {{ $item->assignable->imei }}</p>
              <p class="mb-1">Empresa: {{ $item->assignable->company->company_name }}</p>
              <p class="mb-0">Departamento: {{ $item->assignable->department->department_name }}</p>
            @else
              <span class="badge badge-pill badge-success mb-3">Numero</span>
              <h4 class="mb-1">{{ $item->assignable->number }}</h4>
              <p class="mb-1 text-muted">Plan: {{ $item->assignable->data_plan ?: 'Sin plan' }}</p>
              <p class="mb-0">Empresa: {{ $item->assignable->company->company_name }}</p>
            @endif
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>
@endsection
