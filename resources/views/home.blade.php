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
                        <h3 class="mt-4">0 €</h3>
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
                        <h3 class="mt-4">0 €</h3>
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
                        <h3 class="mt-4">0 €</h3>
                        <div class="progress mt-4" style="height: 4px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 68%" aria-valuenow="68"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p class="text-muted mt-2 mb-0">Respecto al mes anterior<span class="float-right">0%</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
