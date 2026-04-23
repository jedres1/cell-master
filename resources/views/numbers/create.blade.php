@extends('layouts.panel')
@section('page_title', 'Nuevo Numero')
@section('page_subtitle', 'Registro de una nueva linea telefonica')
@section('content')
<div class="container">
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  @if (session('import_status'))
    <div class="alert alert-success">
      {{ session('import_status') }}
    </div>
  @endif

  @if (session('import_errors'))
    <div class="alert alert-warning">
      <ul class="mb-0">
        @foreach (session('import_errors') as $importError)
          <li>{{ $importError }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card mb-4">
    <div class="card-body">
      <h4 class="mb-3">Carga masiva desde Excel</h4>
      <p class="text-muted mb-3">Sube un archivo <strong>.xlsx</strong> cuya primera fila tenga exactamente estos encabezados: <strong>numero</strong>, <strong>empresa</strong>, <strong>estado</strong>, <strong>plan_datos</strong>. La empresa puede venir por nombre o por ID. El estado acepta 1, 2, Asignado o No Asignado.</p>
      <form action="{{ route('numbers.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-row align-items-end">
          <div class="col-md-8">
            <label for="numbers_file" class="form-label">Archivo Excel</label>
            <input type="file" class="form-control" name="numbers_file" id="numbers_file" accept=".xlsx" required>
          </div>
          <div class="col-md-4">
            <button type="submit" class="btn btn-primary">Importar numeros</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="card">
    <div class="card-body">
      <form class="row g-3" action="{{ route('numbers.store') }}" method="POST">
        @include('numbers\partials\form',['btnText'=>'Guardar'])
        <div class="col-6 m3">
          <a href="{{ url('/numbers') }}" class="btn btn-danger float-right">Cancelar</a>
        </div>
      </form>  
    </div>
  </div>
</div>

@endsection
