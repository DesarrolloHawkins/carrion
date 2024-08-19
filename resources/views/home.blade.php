@extends('layouts.app')

@section('title', 'Dashboard')
@section('content-principal')

    <div class="container-fluid">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h4 class="page-title">Dashboard</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 col-xl-4">
            <div class="card">
                <div class="card-heading p-4">
                    <div class="mini-stat-icon float-right">
                        <i class="mdi mdi-cash bg-primary text-white"></i>
                    </div>
                    <div>
                        <h5 class="font-16">Ingresos semanales (caja)</h5>
                    </div>
                    <h3 class="mt-4">{{$ingresos_caja}}€</h3>
                    <div class="progress mt-4" style="height: 4px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"  aria-valuenow="{{'0'}}"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p class="text-muted mt-2 mb-0">Respecto al mes anterior<span class="float-right">{{'0'}}%</span></p>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-4">
            <div class="card">
                <div class="card-heading p-4">
                    <div class="mini-stat-icon float-right">
                        <i class="mdi mdi-cash-register bg-success text-white"></i>
                    </div>
                    <div>
                        <h5 class="font-16">Gastos semanales (caja)</h5>
                    </div>
                    <h3 class="mt-4">  {{$gastos_caja}} €</h3>
                    <div class="progress mt-4" style="height: 4px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 88%" aria-valuenow="88"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p class="text-muted mt-2 mb-0">Respecto al mes anterior<span class="float-right">0%</span></p>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-4">
            <div class="card">
                <div class="card-heading p-4">
                    <div class="mini-stat-icon float-right">
                        <i class="mdi mdi-bank-remove bg-warning text-white"></i>
                    </div>
                    <div>
                        <h5 class="font-16">Resultados semanales (caja)</h5>
                    </div>
                    <h3 class="mt-4">{{$resultados_caja}} €</h3>
                    <div class="progress mt-4" style="height: 4px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 68%" aria-valuenow="68"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p class="text-muted mt-2 mb-0">Respecto al mes anterior<span class="float-right">0%</span></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-1">
            &nbsp;
        </div>
        <div class="col-xl-4">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-4">Eventos de esta semana</h4>
                    <div class="friends-suggestions">
                        @foreach ($eventos as $evento)
                            <a href="#" class="friends-suggestions-list">
                                <div class="border-bottom position-relative">

                                    <div class="suggestion-icon float-right mt-2 pt-1">
                                        <a href="{{ route('eventos.edit', $evento->id) }}"><i class="mdi mdi-plus"></i></a>
                                    </div>
                                    <div class="desc">
                                        <h5 class="font-14 mb-1 pt-2 text-dark"><span
                                                class="badge badge-primary">Evento</span> {{ $categorias->find($evento->eventoNombre)->nombre }}</h5>
                                        <p class="text-muted">{{ $evento->diaEvento }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-xl-2">
                &nbsp;
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-4">Presupuestos sin facturar</h4>
                    <div class="friends-suggestions">
                        @foreach ($presupuestos as $presupuesto)
                            <a href="#" class="friends-suggestions-list">
                                <div class="border-bottom position-relative">
                                    <div class="suggestion-icon float-right mt-2 pt-1">
                                        <a href="{{ route('presupuestos.edit', $presupuesto->id) }}"><i
                                                class="mdi mdi-plus"></i></a>
                                    </div>
                                    <div class="desc">
                                        <h5 class="font-14 mb-1 pt-2 text-dark">
                                            @if($presupuesto->estado == "Aceptado")
                                            <span
                                                class="badge badge-success">Aceptado</span> @elseif($presupuesto->estado == "Pendiente")                                             <span
                                                class="badge badge-warning">Pendiente</span> @endif Presupuesto nº
                                            {{ $presupuesto->id }}</h5>
                                        <p class="text-muted">{{ $presupuesto->fechaEmision }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-1">
            &nbsp;
        </div>
        {{--
            <div class="col-xl-4">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="mt-0 header-title mb-4">Sales Analytics</h4>
                        <div id="morris-line-example" class="morris-chart" style="height: 360px"></div>

                    </div>
                </div>

            </div>

            <div class="col-xl-4">
                <div class="card m-b-30">
                    <div class="card-body">

                        <h4 class="mt-0 header-title mb-4">Recent Activity</h4>
                        <ol class="activity-feed mb-0">
                            <li class="feed-item">
                                <div class="feed-item-list">
                                    <p class="text-muted mb-1">Now</p>
                                    <p class="font-15 mt-0 mb-0">Andrei Coman magna sed porta finibus, risus posted a new
                                        article: <b class="text-primary">Forget UX Rowland</b></p>
                                </div>
                            </li>
                            <li class="feed-item">
                                <p class="text-muted mb-1">Yesterday</p>
                                <p class="font-15 mt-0 mb-0">Andrei Coman posted a new article: <b
                                        class="text-primary">Designer Alex</b></p>
                            </li>
                            <li class="feed-item">
                                <p class="text-muted mb-1">2:30PM</p>
                                <p class="font-15 mt-0 mb-0">Zack Wetass, sed porta finibus, risus Chris Wallace Commented
                                    <b class="text-primary"> Developer Moreno</b>
                                </p>
                            </li>
                            <li class="feed-item pb-1">
                                <p class="text-muted mb-1">12:48 PM</p>
                                <p class="font-15 mt-0 mb-2">Zack Wetass, Chris Wallace Commented <b class="text-primary">UX
                                        Murphy</b></p>
                            </li>

                        </ol>

                    </div>
                </div>
            </div> --}}
    </div>
    </div>
    </div>

@endsection
