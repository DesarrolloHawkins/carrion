@extends('layouts.app')

@section('title', 'Festivos')

@section('head')
<meta charset="UTF-8">
@endsection

@section('content-principal')
<div>
    @livewire('settings.festivos-component')
</div>
@endsection