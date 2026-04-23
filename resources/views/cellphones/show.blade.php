@extends('layouts.panel')
@section('page_title', 'Detalle de Celular')
@section('page_subtitle', 'Consulta la informacion completa del equipo celular')
@section('content')
<div class="container">
  <div class="card">
    <div class="card-body">
        <p class="list-group-item">model: {{$cellphone->model}}</p>
        <p class="list-group-item">marca: {{$cellphone->brand}}</p>
        <p class="list-group-item">imei: {{$cellphone->imei}}</p>
        <p class="list-group-item">estado: {{$cellphone->status==0?'Disponible':'Asignado'}}</p>
        <p class="list-group-item">deparmento: {{$cellphone->department->department_name}}</p>
        <p class="list-group-item">empresa: {{$cellphone->company->company_name}}</p>
    </div>
  </div><br>
  <form class="row g-3" action="{{ route('cellphones.edit',$cellphone) }}" method="get">
    <div class="col-6 m3  ">
        <button type="submit" class="btn btn-info">Editar</button>
    </div>
    <div class="col-6 m3  ">
      <a href="{{ url('/cellphones') }}" class="btn btn-danger float-right">Regresar</a>
    </div>
  </form>
</div>
@endsection
<script>

</script>
