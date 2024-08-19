@section('head')

    @vite(['resources/sass/alumnos.scss'])
@endsection

<div class="container">

    <div class="d-flex justify-content-evenly align-items-center">
        <h1 class="">Crear Factura</h1>
    </div>
    <hr>
    <div class="d-flex justify-content-evenly align-items-center">
        <h4 class="">Seleccione el tipo de cliente</h4>
    </div>
    <div class="d-flex justify-content-evenly align-items-center mb-4">
        <div>
            <label for="empresa" class="form-check-label">Empresa</label>
            <input type="radio" name="empresa" id="empresa" value="2" class="form-check-input" wire:model="tipoCliente">
        </div>
        <div>
            <label for="particular" class="form-check-label">Particular</label>
            <input type="radio" name="empresa" id="particular" value="1" class="form-check-input" wire:model="tipoCliente">
        </div>
    </div>

    {{-- {{$tipoCliente}} --}}
    @if ($tipoCliente == 1)
        <form class="container" wire:submit.prevent="submitCliente">
            <input type="hidden" name="csrf-token" value="{{ csrf_token() }}">

            <div class="mb-3 row d-flex align-items-center">
                <label for="particularCliente" class="col-sm-2 col-form-label">Cliente</label>
                <div class="col-sm-10" wire:ignore.self>
                    <select  id="cliente" class="form-control js-example-responsive" wire:model="id_cliente">
                        <option value="">-- Seleccione un cliente --</option>
                        @foreach ($particular as $client)
                        <option value="{{$client->id}}">{{$client->nameCliente}} {{$client->firstSurname}}</option>
                        @endforeach
                    </select>
                    <div>@json($id_cliente)</div>
                    {{-- <input type="text" class="form-control" wire:model="nameCliente" name="nameCliente" id="nameCliente" placeholder="Nombre"> --}}
                    @error('nameCliente') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <input type="hidden" id="manolo" wire:model="id_cliente">

            <div class="mb-3 row d-flex align-items-center">
                <label for="numberFac" class="col-sm-2 col-form-label">Numero de Factura</label>
                <div class="col-sm-10">
                    <input type="text" wire:model="numeroFactura" class="form-control" name="numeroFactura" id="numeroFactura" placeholder="Numero de Factura">
                    @error('numeroFactura') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>



            <div class="mb-3 row d-flex align-items-center">
                <label for="ciudadCliente" class="col-sm-2 col-form-label">Serie</label>
                <div class="col-sm-10">
                    <input type="text" wire:model="serie" class="form-control" name="serie" id="serie" placeholder="Serie">
                    @error('serie') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-3 row d-flex align-items-center">
                <label for="fecha" class="col-sm-2 col-form-label">Fecha</label>
                <div class="col-sm-10">
                    <input type="text" wire:model.defer="fecha" class="form-control"  placeholder="fecha" id="datepicker">
                    @error('fecha') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div>@json($fecha)</div>

            <div class="mb-3 row d-flex align-items-center">
                <label for="descuento" class="col-sm-2 col-form-label">Descuento (%)</label>
                <div class="col-sm-10">
                    <input type="text" wire:model="descuentoTotal" class="form-control" name="descuento"  placeholder="Descuento ejemplo(20)" id="descuento">
                    @error('descuentoTotal') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <h4>Conceptos de la Factura</h4>
            <hr>

            <div class="mb-3 row">
                <div class="col-md-4">
                    <div class="form-group">
                        <select name="producto" id="productos0" wire:model="nameProducto.0" class="form-control" wire:change="setSomeProperty(0)">
                            <option value="">-- Seleccione Producto --</option>
                            @foreach ($productos as $producto)
                            <option value="{{$producto->nombre}}">{{$producto->nombre}}</option>
                            @endforeach
                        </select>
                        {{-- <input type="text" class="form-control" placeholder="Nombre" wire:model="nameProducto.0"> --}}
                        @error('nameProducto.0') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="number" step="any" class="form-control" wire:model="precio.0" placeholder="Precio Total">
                        @error('precio.0') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="number" class="form-control" wire:model="cantidad.0" placeholder="Cantidad">
                        @error('cantidad.0') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <input type="number" class="form-control" wire:model="descuento.0" placeholder="Descuento">
                        @error('descuento.0') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <input type="number" class="form-control" wire:model="iva.0" placeholder="IVA">
                        @error('iva.0') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn text-white btn-info btn-sm" wire:click.prevent="add({{$i}})">Add</button>
                </div>
            </div>
            @foreach($inputs as $key => $value)
                <div class="mb-3 row">
                    <div class="col-md-4">
                        <div class="form-group" >
                            <select name="producto" id="producto" wire:model="nameProducto.{{$value}}" class="form-control" wire:change="setSomeProperty2({{$value}})">
                                <option value="">-- Seleccione Producto --</option>
                                @foreach ($productos as $producto)
                                <option value="{{$producto->nombre}}">{{$producto->nombre}}</option>
                                @endforeach
                            </select>
                            @error('nameProducto.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="number" step="any" class="form-control" wire:model="precio.{{$value}}" placeholder="Precio Total">
                            @error('precio.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="number" class="form-control" wire:model="cantidad.{{$value}}" placeholder="Cantidad">
                            @error('cantidad'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <input type="number" class="form-control" wire:model="descuento.{{$value}}" placeholder="Descuento">
                            @error('descuento.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <input type="number" class="form-control" wire:model="iva.{{$value}}" placeholder="IVA">
                            @error('iva.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-md-2">
                        <button class="btn text-white btn-info btn-sm" wire:click.prevent="add({{$i}})">Add</button>
                    </div>
                </div>
            @endforeach
            <div class="mb-3 row d-flex align-items-center">
                <button type="submit" class="btn btn-outline-info">Guardar</button>
            </div>
        </form>

        <script>
            $.datepicker.regional['es'] = {
                closeText: 'Cerrar',
                prevText: '< Ant',
                nextText: 'Sig >',
                currentText: 'Hoy',
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
                dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
                dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
                weekHeader: 'Sm',
                dateFormat: 'dd/mm/yy',
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''
            };
            $.datepicker.setDefaults($.datepicker.regional['es']);
            document.addEventListener('livewire:load', function () {


            })
            $(document).ready(function() {
                console.log('select2')
                $("#datepicker").datepicker();
                $('#cliente').on('change', function (e) {
                    var data = $('#cliente').select2("val");
                    console.log(data)
                    @this.set('id_cliente', data);
                })

                // $('#productos').select2();
                // $('#productos0').on('change', function (e) {
                //     var data = $('#productos0').select2("val");
                //     console.log(data)
                //     @this.set('nameProducto', data);
                // })

                $("#datepicker").on('change', function(e){
                    @this.set('fecha', $('#datepicker').val());
                    });

            });
        </script>
    @elseif($tipoCliente == 2)
        <form class="container" wire:submit.prevent="submitCliente">
            <input type="hidden" name="csrf-token" value="{{ csrf_token() }}">

            <div class="mb-3 row d-flex align-items-center">
                <label for="particularCliente" class="col-sm-2 col-form-label">Cliente</label>
                <div class="col-sm-10" wire:ignore.self>
                    <select  id="cliente" class="form-control js-example-responsive" wire:model="id_cliente">
                        <option value="">-- Seleccione un cliente --</option>
                        @foreach ($empresa as $client)
                        <option value="{{$client->id}}">{{$client->nameEmpresa}}</option>
                        @endforeach
                    </select>
                    <div>@json($id_cliente)</div>
                    {{-- <input type="text" class="form-control" wire:model="nameCliente" name="nameCliente" id="nameCliente" placeholder="Nombre"> --}}
                    @error('nameCliente') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <input type="hidden" id="manolo" wire:model="id_cliente">

            <div class="mb-3 row d-flex align-items-center">
                <label for="numberFac" class="col-sm-2 col-form-label">Numero de Factura</label>
                <div class="col-sm-10">
                    <input type="text" wire:model="numeroFactura" class="form-control" name="numeroFactura" id="numeroFactura" placeholder="Numero de Factura">
                    @error('numeroFactura') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>



            <div class="mb-3 row d-flex align-items-center">
                <label for="ciudadCliente" class="col-sm-2 col-form-label">Serie</label>
                <div class="col-sm-10">
                    <input type="text" wire:model="serie" class="form-control" name="serie" id="serie" placeholder="Serie">
                    @error('serie') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-3 row d-flex align-items-center">
                <label for="fecha" class="col-sm-2 col-form-label">Fecha</label>
                <div class="col-sm-10">
                    <input type="text" wire:model.defer="fecha" class="form-control"  placeholder="fecha" id="datepicker">
                    @error('fecha') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div>@json($fecha)</div>

            <div class="mb-3 row d-flex align-items-center">
                <label for="descuento" class="col-sm-2 col-form-label">Descuento (%)</label>
                <div class="col-sm-10">
                    <input type="text" wire:model="descuentoTotal" class="form-control" name="descuento"  placeholder="Descuento ejemplo(20)" id="descuento">
                    @error('descuentoTotal') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <h4>Conceptos de la Factura</h4>
            <hr>

            <div class="mb-3 row">
                <div class="col-md-4">
                    <div class="form-group">
                        <select name="producto" id="productos0" wire:model="nameProducto.0" class="form-control" wire:change="setSomeProperty(0)">
                            <option value="">-- Seleccione Producto --</option>
                            @foreach ($productos as $producto)
                            <option value="{{$producto->nombre}}">{{$producto->nombre}}</option>
                            @endforeach
                        </select>
                        {{-- <input type="text" class="form-control" placeholder="Nombre" wire:model="nameProducto.0"> --}}
                        @error('nameProducto.0') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="number" step="any" class="form-control" wire:model="precio.0" placeholder="Precio Total">
                        @error('precio.0') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="number" class="form-control" wire:model="cantidad.0" placeholder="Cantidad">
                        @error('cantidad.0') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <input type="number" class="form-control" wire:model="descuento.0" placeholder="Descuento">
                        @error('descuento.0') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <input type="number" class="form-control" wire:model="iva.0" placeholder="IVA">
                        @error('iva.0') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn text-white btn-info btn-sm" wire:click.prevent="add({{$i}})">Add</button>
                </div>
            </div>
            @foreach($inputs as $key => $value)
                <div class="mb-3 row">
                    <div class="col-md-4">
                        <div class="form-group" >
                            <select name="producto" id="producto" wire:model="nameProducto.{{$value}}" class="form-control" wire:change="setSomeProperty2({{$value}})">
                                <option value="">-- Seleccione Producto --</option>
                                @foreach ($productos as $producto)
                                <option value="{{$producto->nombre}}">{{$producto->nombre}}</option>
                                @endforeach
                            </select>
                            @error('nameProducto.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="number" step="any" class="form-control" wire:model="precio.{{$value}}" placeholder="Precio Total">
                            @error('precio.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="number" class="form-control" wire:model="cantidad.{{$value}}" placeholder="Cantidad">
                            @error('cantidad'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <input type="number" class="form-control" wire:model="descuento.{{$value}}" placeholder="Descuento">
                            @error('descuento.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <input type="number" class="form-control" wire:model="iva.{{$value}}" placeholder="IVA">
                            @error('iva.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-md-2">
                        <button class="btn text-white btn-info btn-sm" wire:click.prevent="add({{$i}})">Add</button>
                    </div>
                </div>
            @endforeach
            <div class="mb-3 row d-flex align-items-center">
                <button type="submit" class="btn btn-outline-info">Guardar</button>
            </div>
        </form>
        <script>
            $.datepicker.regional['es'] = {
                closeText: 'Cerrar',
                prevText: '< Ant',
                nextText: 'Sig >',
                currentText: 'Hoy',
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
                dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
                dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
                weekHeader: 'Sm',
                dateFormat: 'dd/mm/yy',
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''
            };
            $.datepicker.setDefaults($.datepicker.regional['es']);
            document.addEventListener('livewire:load', function () {


            })
            $(document).ready(function() {
                console.log('select2')
                $("#datepicker").datepicker();
                $('#cliente').on('change', function (e) {
                    var data = $('#cliente').select2("val");
                    console.log(data)
                    @this.set('id_cliente', data);
                })

                // $('#productos').select2();
                // $('#productos0').on('change', function (e) {
                //     var data = $('#productos0').select2("val");
                //     console.log(data)
                //     @this.set('nameProducto', data);
                // })

                $("#datepicker").on('change', function(e){
                    @this.set('fecha', $('#datepicker').val());
                    });

            });
        </script>
    @else
    <div class="text-center mt-3 w-50 m-auto">
            <hr class="border-secondary">
            <h5 class="text-secondary fs-6 fst-italic fw-light">Seleccione el tipo de cliente a crear</h5>
        </div>
    @endif

</div>

@section('scripts')
{{-- <script>
    document.addEventListener('livewire:load', function () {
                console.log('select2')
                $('#cliente').select2();
                $("#datepicker").datepicker();
                $('#cliente').on('change', function (e) {
                    var data = $('#cliente').select2("val");
                    @this.set('id_cliente', data);
                })

                $('#productos').select2();
                $('#productos').on('change', function (e) {
                    var data = $('#productos').select2("val");
                    @this.set('nameProducto.0', data);
                })

                $("#datepicker").on('change', function(e){
                    @this.set('fecha', $('#datepicker').val());
                    });

            })
</script> --}}
<script>
    window.initSelect2 = () => {
        jQuery("#cliente").select2({
            minimumResultsForSearch: 2,
            allowClear: false
        });
        // jQuery("#productos0").select2({
        //     minimumResultsForSearch: 2,
        //     allowClear: false
        // });
    }

    initSelect2();
    window.livewire.on('select2', ()=>{
        initSelect2();
    });
</script>
@endsection


