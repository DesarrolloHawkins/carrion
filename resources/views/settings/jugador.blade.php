@extends('layouts.app')

@section('title', 'Jugador')

@section('head')
<meta charset="UTF-8">
@endsection

@section('content-principal')
<div>
    @livewire('settings.jugador-component')
</div>
@endsection