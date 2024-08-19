@section('head')

    @vite(['resources/sass/alumnos.scss'])
@endsection

<div class="container">

    <div class="d-flex justify-content-evenly align-items-center">
        <h1 class="">Mostrar Factura</h1>
    </div>
    <hr>
    {{-- {{$tipoCliente}} --}}
    @if ($tipoCliente == 1)
        <div class="container">
            <input type="hidden" name="csrf-token" value="{{ csrf_token() }}">

            <div class="mb-3 row d-flex align-items-center">
                <label for="particularCliente" class="col-sm-2 col-form-label">Nombre</label>
                <div class="col-sm-10" wire:ignore.self>
                    <input disabled type="text" class="form-control" wire:model="nameCliente" name="nameCliente" id="nameCliente" placeholder="Nombre">
                    @error('nameCliente') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="mb-3 row d-flex align-items-center">
                <label for="particularCliente" class="col-sm-2 col-form-label">Primer Apellido</label>
                <div class="col-sm-10" wire:ignore.self>
                    <input disabled type="text" class="form-control" wire:model="firstSurname" name="firstSurname" id="firstSurname" placeholder="Nombre">
                    @error('firstSurname') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="mb-3 row d-flex align-items-center">
                <label for="particularCliente" class="col-sm-2 col-form-label">Segundo Apellido</label>
                <div class="col-sm-10" wire:ignore.self>
                    <input disabled type="text" class="form-control" wire:model="lastSurname" name="lastSurname" id="lastSurname" placeholder="Nombre">
                    @error('lastSurname') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="mb-3 row d-flex align-items-center">
                <label for="particularCliente" class="col-sm-2 col-form-label">DNI</label>
                <div class="col-sm-10" wire:ignore.self>
                    <input disabled type="text" class="form-control" wire:model="dni" name="dni" id="dni" placeholder="Nombre">
                    @error('dni') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-3 row d-flex align-items-center">
                <label for="numeroFactura" class="col-sm-2 col-form-label">Numero de Factura</label>
                <div class="col-sm-10">
                    <input disabled type="text" wire:model="numeroFactura" class="form-control" name="numeroFactura" id="numeroFactura" placeholder="Numero de Factura">
                    @error('numeroFactura') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>



            <div class="mb-3 row d-flex align-items-center">
                <label for="serie" class="col-sm-2 col-form-label">Serie</label>
                <div class="col-sm-10">
                    <input disabled type="text" wire:model="serie" class="form-control" name="serie" id="serie" placeholder="Serie">
                    @error('serie') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-3 row d-flex align-items-center">
                <label for="fecha" class="col-sm-2 col-form-label">Fecha</label>
                <div class="col-sm-10">
                    <input disabled type="text" wire:model.defer="fecha" class="form-control"  placeholder="fecha" id="datepicker">
                    @error('fecha') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-3 row d-flex align-items-center">
                <label for="descuento" class="col-sm-2 col-form-label">Descuento (%)</label>
                <div class="col-sm-10">
                    <input disabled type="text" wire:model="descuentoTotal" class="form-control" name="descuento"  placeholder="Descuento ejemplo(20)" id="descuento">
                    @error('descuentoTotal') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <h4>Conceptos de la Factura</h4>
            <hr>
            <table class="table">
                <thead>
                    <tr>
                        <th>COD</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Cantidada</th>
                        <th>Descuento</th>
                        <th>Iva</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach ($conceptos[0] as $concepto)
                            <td>{{$concepto->producto->cod_producto}}</td>
                            <td>{{$concepto->producto->nombre}}</td>
                            <td>{{$concepto->precio}} €</td>
                            <td>{{$concepto->cantidad}}</td>
                            <td>{{$concepto->iva}}</td>
                            <td>{{$concepto->descuento}}</td>
                            <td>{{$concepto->total}} €</td>
                        @endforeach
                    </tr>
                </tbody>
                <tfoot>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td> <strong>Total:</strong></td>
                    <td><strong>{{$total}} €</strong></td>
                </tfoot>
            </table>
            <div class="acciones">
                <a href="/admin/factura/pdf/{{$identificador}}" class="btn btn-info text-white">Dercargar Factura PDF</a>
                {{-- <button wire:click="pdf" class="btn btn-info text-white">Dercargar Factura PDF</button> --}}
                <a href="/admin/factura/electronica/{{$identificador}}" class="btn btn-primary">Dercargar Factura Electronica</a>
            </div>
        </div>
    @elseif($tipoCliente == 2)
        <div class="container">
            <input type="hidden" name="csrf-token" value="{{ csrf_token() }}">

            <div class="mb-3 row d-flex align-items-center">
                <label for="particularCliente" class="col-sm-2 col-form-label">Nombre</label>
                <div class="col-sm-10" wire:ignore.self>
                    <input disabled type="text" class="form-control" wire:model="nameEmpresa" name="nameEmpresa" id="nameEmpresa" placeholder="Nombre">
                    @error('nameEmpresa') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="mb-3 row d-flex align-items-center">
                <label for="taxNumber" class="col-sm-2 col-form-label">CIF</label>
                <div class="col-sm-10" wire:ignore.self>
                    <input disabled type="text" class="form-control" wire:model="taxNumber" name="taxNumber" id="taxNumber" placeholder="Nombre">
                    @error('taxNumber') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-3 row d-flex align-items-center">
                <label for="numeroFactura" class="col-sm-2 col-form-label">Numero de Factura</label>
                <div class="col-sm-10">
                    <input disabled type="text" wire:model="numeroFactura" class="form-control" name="numeroFactura" id="numeroFactura" placeholder="Numero de Factura">
                    @error('numeroFactura') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>



            <div class="mb-3 row d-flex align-items-center">
                <label for="serie" class="col-sm-2 col-form-label">Serie</label>
                <div class="col-sm-10">
                    <input disabled type="text" wire:model="serie" class="form-control" name="serie" id="serie" placeholder="Serie">
                    @error('serie') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-3 row d-flex align-items-center">
                <label for="fecha" class="col-sm-2 col-form-label">Fecha</label>
                <div class="col-sm-10">
                    <input disabled type="text" wire:model.defer="fecha" class="form-control"  placeholder="fecha" id="datepicker">
                    @error('fecha') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-3 row d-flex align-items-center">
                <label for="descuento" class="col-sm-2 col-form-label">Descuento (%)</label>
                <div class="col-sm-10">
                    <input disabled type="text" wire:model="descuentoTotal" class="form-control" name="descuento"  placeholder="Descuento ejemplo(20)" id="descuento">
                    @error('descuentoTotal') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <h4>Conceptos de la Factura</h4>
            <hr>
            <table class="table">
                <thead>
                    <tr>
                        <th>COD</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Cantidada</th>
                        <th>Descuento</th>
                        <th>Iva</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach ($conceptos[0] as $concepto)
                            <td>{{$concepto->producto->cod_producto}}</td>
                            <td>{{$concepto->producto->nombre}}</td>
                            <td>{{$concepto->precio}} €</td>
                            <td>{{$concepto->cantidad}}</td>
                            <td>{{$concepto->iva}}</td>
                            <td>{{$concepto->descuento}}</td>
                            <td>{{$concepto->total}} €</td>
                        @endforeach
                    </tr>
                </tbody>
                <tfoot>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td> <strong>Total:</strong></td>
                    <td><strong>{{$total}} €</strong></td>
                </tfoot>
            </table>
            <div class="acciones">
                <a href="#" class="btn btn-info text-white">Dercargar Factura PDF</a>    
                <a href="/admin/factura/electronica/{{$identificador}}" class="btn btn-primary">Dercargar Factura Electronica</a>    
            </div>   
        </div>
    @else
    <div class="text-center mt-3 w-50 m-auto">
            <hr class="border-secondary">
            <h5 class="text-secondary fs-6 fst-italic fw-light">Seleccione el tipo de cliente a crear</h5>
        </div>
    @endif

</div>

@section('scripts')

@endsection

