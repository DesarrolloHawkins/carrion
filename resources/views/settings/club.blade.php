@extends('layouts.app')

@section('title', 'Club')

@section('head')
<meta charset="UTF-8">
@endsection

@section('content-principal')
<div>
    @livewire('settings.club-component')
</div>
@endsection