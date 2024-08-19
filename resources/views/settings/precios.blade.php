@extends('layouts.app')

@section('title', 'Precios')

@section('head')
<meta charset="UTF-8">
@endsection

@section('content-principal')
<div>
    @livewire('settings.precios-component')
</div>
@endsection