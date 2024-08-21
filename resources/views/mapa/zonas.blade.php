@extends('layouts.app')

@section('title', 'Ver zonas')

@section('head')
    @vite(['resources/sass/productos.scss'])
    @vite(['resources/sass/alumnos.scss'])
@endsection

@section('content-principal')
<div>
    @livewire('mapa.zonas-component', ['identificador'=>$identificador])
</div>
@endsection
