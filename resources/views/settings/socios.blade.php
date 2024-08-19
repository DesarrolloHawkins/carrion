@extends('layouts.app')

@section('title', 'Socios')

@section('head')
<meta charset="UTF-8">
@endsection

@section('content-principal')
<div>
    @livewire('settings.socios-component')
</div>
@endsection