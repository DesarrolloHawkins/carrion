
@extends('layouts.app')

@section('title', 'Ver Mapa')

@section('head')
    @vite(['resources/sass/productos.scss'])
    @vite(['resources/sass/alumnos.scss'])
@endsection

@section('content-principal')
<div>
    @livewire('mapa.index-component')
</div>
@endsection
