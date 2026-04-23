@extends('layouts.panel')
@section('page_title', 'Editar Asignacion')
@section('page_subtitle', 'Reordena o reemplaza los objetos de la asignacion')
@section('content')
<div class="container">
  @include('assignments.partials.builder', [
    'action' => route('assignments.update', $assignment),
    'method' => 'PATCH',
    'buttonText' => 'Actualizar'
  ])
</div>
@endsection
