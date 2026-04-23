@extends('layouts.panel')
@section('page_title', 'Asignaciones')
@section('page_subtitle', 'Consulta de asignaciones flexibles entre empleados, celulares y numeros')
@section('content')
<style>
    .assignments-page {
        max-width: 1320px;
        margin: 0 auto;
    }

    .assignments-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .assignments-table-wrap {
        overflow-x: auto;
        border-radius: 1rem;
        box-shadow: 0 12px 30px rgba(32, 63, 105, 0.08);
    }

    @media (max-width: 575px) {
        .assignments-toolbar .btn {
            width: 100%;
        }
    }
</style>
<div class="container assignments-page">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="assignments-toolbar">
        <a class='btn btn-info' href="{{ url('assignments/create') }}">Nueva Asignacion Visual</a>
    </div>
    <br>
    <div class="assignments-table-wrap">
        @include('assignments.partials.active-assignments')
    </div>
</div>
@endsection
