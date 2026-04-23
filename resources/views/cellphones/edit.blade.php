@extends('layouts.panel')
@section('page_title', 'Editar Celular')
@section('page_subtitle', 'Actualiza la informacion del celular seleccionado')
@section('content')
<div class="container">
  <div class="card">
    <div class="card-body">
      <form class="row g-3" action="{{ route('cellphones.update',$cellphone) }}" method="POST">
        @method('PATCH')
        @include('cellphones\partials\form',['btnText'=>'Actualizar'])  
        <div class="col-6 m3">
          <a href="{{ url('/cellphones/show',$cellphone) }}" class="btn btn-danger float-right">Cancelar</a>
        </div>
      </form>    
    </div>
  </div>
</div>
@endsection
<script>

</script>
