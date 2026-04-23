@extends('layouts.panel')
@section('page_title', 'Editar Numero')
@section('page_subtitle', 'Actualiza la informacion del numero seleccionado')
@section('content')
<div class="container">
  <div class="card">
    <div class="card-body">
      <form class="row g-3" action="{{ route('numbers.update',$number) }}" method="post">
        @method('patch')
        @include('numbers\partials\form',['btnText'=>'Actualizar'])
        <div class="col-6 m3">
          <a href="{{ url('/numbers/show',$number) }}" class="btn btn-danger float-right">Cancelar</a>
        </div>
      </form>  
    </div>
  </div>
</div>

@endsection
