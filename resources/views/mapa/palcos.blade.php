@extends('layouts.app')

@section('title', 'Ver Sillas')

@section('head')
    @vite(['resources/sass/productos.scss'])
    @vite(['resources/sass/alumnos.scss'])
@endsection

@section('content-principal')
<div>
    @livewire('mapa.palco-component', ['palco'=>$palco])
</div>
@endsection
