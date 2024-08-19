@extends('layouts.app')

@section('content')

@section('title', 'Productos')


<div>
    @livewire('productos.edit-component', ['identificador'=>$id])
</div>

@endsection

