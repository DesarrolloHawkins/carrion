@extends('layouts.app')

@section('title', 'Sillas')

@section('head')
@vite(['resources/sass/productos.scss'])
@vite(['resources/sass/alumnos.scss'])
<meta charset="UTF-8">
@endsection

@section('content-principal')
<div>
    @livewire('sillas.index-component')
</div>
@endsection