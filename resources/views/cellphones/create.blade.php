@extends('layouts.panel')
@section('page_title', 'Nuevo Celular')
@section('page_subtitle', 'Registro de un nuevo equipo celular')
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
      <p class="text-muted mb-3">Sube un archivo <strong>.xlsx</strong> cuya primera fila tenga exactamente estos encabezados: <strong>modelo</strong>, <strong>marca</strong>, <strong>imei</strong>, <strong>empresa</strong>, <strong>departamento</strong>, <strong>estado</strong>, <strong>accesorios</strong>.</p>
      <form action="{{ route('cellphones.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-row align-items-end">
          <div class="col-md-8">
            <label for="cellphones_file" class="form-label">Archivo Excel</label>
            <input type="file" class="form-control" name="cellphones_file" id="cellphones_file" accept=".xlsx" required>
          </div>
          <div class="col-md-4">
            <button type="submit" class="btn btn-primary">Importar celulares</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="card">
    <div class="card-body">
      <form class="row g-3" action="{{ route('cellphones.store') }}" method="post">
        @include('cellphones\partials\form',['btnText'=>'Guardar'])  
        <div class="col-6 m3">
          <a href="{{ url('/cellphones') }}" class="btn btn-danger float-right">Cancelar</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
