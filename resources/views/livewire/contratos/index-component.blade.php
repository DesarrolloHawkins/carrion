<div class="container-fluid mx-auto">
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4 class="page-title">TODOS LOS CONTRATOS</span></h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-right">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    {{-- <li class="breadcrumb-item"><a href="javascript:void(0);">Contratos</a></li> --}}
                    <li class="breadcrumb-item active">Contratos</li>
                </ol>
            </div>
        </div> <!-- end row -->
    </div>
    <!-- end page-title -->
    {{-- <div class="d-flex justify-content-left align-items-center">
        <h1 class="me-5">Contratos</h1>

    </div> --}}
    {{-- <h2>Todos los contratos</h2>
    <br> --}}
    {{-- <a href="{{ route('contratos.create') }}" class="btn btn-warning">Añadir Contrato</a>
    <br><br><br> --}}
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Listado de todos los contratos</h4>
                    <p class="sub-title../plugins">Listado completo de todos nuestros contratos, para editar o ver la
                        informacion completa pulse el boton de Editar en la columna acciones.
                    </p>
                    @if (count($contratos) > 0)
                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th scope="col">Nº de contrato</th>
                                    <th scope="col">Fecha Evento</th>
                                    <th scope="col">Evento</th>
                                    <th scope="col">Solicitante</th>
                                    <th scope="col">NIF/DNI</th>
                                    <th scope="col">Tipo de pago</th>
                                    <th scope="col">Descuento</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Auth Img</th>
                                    <th scope="col">Auth Img Menores</th>



                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contratos as $contrato)
                                    <tr>
                                        <td>{{ $contrato->id }}</td>
                                        <td>{{ $contrato->dia }}</td>
                                        <td>{{ $this->nombreEvento($contrato->id_presupuesto) }}</td>
                                        <td>{{ $this->nombreApellidoCliente($contrato->id_presupuesto) }}</td>
                                        <td>{{ $this->nifCliente($contrato->id_presupuesto) }}</td>
                                        <td>{{ $contrato->metodoPago }}</td>
                                        <td>{{ $this->getTotalDesc($contrato->id_presupuesto) }}%</td>
                                        <td>{{ number_format($this->getTotal($contrato->id_presupuesto), 2, ',', '.') }}€
                                        </td>
                                        <td>{{ $contrato->authImagen > 0 ? 'Si' : 'No' }}</td>
                                        <td>{{ $contrato->authMenores > 0 ? 'Si' : 'No' }}</td>
                                        <td> <a href="contratos-edit/{{ $contrato->id }}"
                                                class="btn btn-primary">Ver/Editar</a> </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <h6 class="text-center">No hay tenemos ningun contrato</h6>
                    @endif
                </div>
            </div>
        </div>
    </div>





</div>


@section('scripts')
<script src="../assets/js/jquery.slimscroll.js"></script>

<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables/dataTables.bootstrap4.min.js"></script>
<!-- Buttons examples -->
<script src="../plugins/datatables/dataTables.buttons.min.js"></script>
<script src="../plugins/datatables/buttons.bootstrap4.min.js"></script>
<script src="../plugins/datatables/jszip.min.js"></script>
<script src="../plugins/datatables/pdfmake.min.js"></script>
<script src="../plugins/datatables/vfs_fonts.js"></script>
<script src="../plugins/datatables/buttons.html5.min.js"></script>
<script src="../plugins/datatables/buttons.print.min.js"></script>
<script src="../plugins/datatables/buttons.colVis.min.js"></script>
<!-- Responsive examples -->
<script src="../plugins/datatables/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables/responsive.bootstrap4.min.js"></script>
<script src="../assets/pages/datatables.init.js"></script>
@endsection
