@extends('layouts.app')

@section('title', 'Torneos')

@section('head')
<meta charset="UTF-8">
@endsection

@section('content-principal')
<div>
    @livewire('torneos.editduos-component', ['identificador'=>$id])
</div>
@endsection

