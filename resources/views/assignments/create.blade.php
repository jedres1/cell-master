@extends('layouts.panel')
@section('page_title', 'Nueva Asignacion')
@section('page_subtitle', 'Construye una asignacion arrastrando empleado, celular y numero')
@section('content')
<div class="container">
  @include('assignments.partials.builder', [
    'action' => route('assignments.store'),
    'method' => 'POST',
    'buttonText' => 'Guardar'
  ])
</div>
@endsection
