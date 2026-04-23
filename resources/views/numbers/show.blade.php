@extends('layouts.panel')
@section('page_title', 'Detalle de Numero')
@section('page_subtitle', 'Consulta la informacion completa del numero')
@section('content')
<div class="container">
  <div class="card">
    <div class="card-body">
        <p class="list-group-item">numero: {{$number->number}}</p>
        <p class="list-group-item">empresa: {{$number->company->company_name}}</p>
        <p class="list-group-item">estado: {{$number->status==1?'Asignado':'No Asignado'}}</p>
        <p class="list-group-item">Plan: {{$number->data_plan}}</p>
    </div>
  </div><br>
  <form class="row g-3" action="{{ route('numbers.edit',$number) }}" method="get">
    <div class="col-6 m3  ">
        <button type="submit" class="btn btn-info">Editar</button>
    </div>
    <div class="col-6 m3  ">
      <a href="{{ url('/numbers') }}" class="btn btn-danger float-right">Regresar</a>
    </div>
  </form>
</div>
@endsection
