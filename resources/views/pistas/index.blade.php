@extends('layouts.app')

@section('title', 'Pistas')

@section('head')
<meta charset="UTF-8">
@endsection

@section('content-principal')
<div>
    @livewire('pistas.index')
</div>
@endsection