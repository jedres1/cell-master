@extends('layouts.panel')
@section('page_title', 'Numeros')
@section('page_subtitle', 'Gestion y consulta del inventario de numeros')
@section('content')
<div class="container">
    <div>
        <a class='btn btn-info' href="{{ url('numbers/create') }}">Add Number</a>
    </div>
    <br>
    <table class="table table-hover table-dark">
        <thead>
            <tr>
                <th scope="col">numero</th>
                <th scope="col">Empresa</th>
                <th scope="col">Estado</th>
                <th scope="col">Acciones</th>  
            </tr>
        </thead>
        <tbody>
            @foreach ($numbers as $number)
            <tr>
                <td>{{$number->number}}</td>
                <td>{{$number->company->company_name}}</td>
                <td>{{$number->status==1?"Asignado":"No Asignado"}}</td>
                <td><a class="btn btn-icon btn-primary btn-sm" href="{{url('numbers/show',$number)}}"><i class="ni ni-book-bookmark text-dark"></a></td>
            </tr>    
            @endforeach
            
        </tbody>
    </table>
    {{$numbers->links()}}
</div>
@endsection
