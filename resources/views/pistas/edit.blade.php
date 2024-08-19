@extends('layouts.app')

@section('title', 'Editar presupuesto')

@section('head')
@vite(['resources/sass/productos.scss'])
@vite(['resources/sass/alumnos.scss'])
@endsection

@section('content-principal')
<div>
    @livewire('pistas.edit', ['identificador'=>$id])
</div>
@endsection