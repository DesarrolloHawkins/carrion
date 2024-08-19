<div>
    <div class="container-fluid">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h4 class="page-title">OPCIONES DE SOCIOS</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Opciones</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="tabs-container">
            <a href="{{ route('settings.socios') }}" data-view="membership-types">Tipos de socio</a>
            <a href="{{ route('settings.socios-ventajas') }}" class="active link" data-view="coaches">Ventajas</a>
            <a href="{{ route('settings.socios-zonas') }}" class="link" data-view="students-on-hold">Zonas</a>
        </div>

        <style>
            .tabs-container {
                display: flex;
                flex-direction: row;
                border-bottom: 1px solid rgb(218, 219, 223);
                margin-left: 1rem;
                margin-bottom: 8px;
                gap: 10px;
            }

            .tabs-container > a.active {
                color: #0D003B;
                border-bottom: 2px solid #0D003B;
                font-weight: 600;
            }
        </style>

        <div class="row">
            <div class="col-md">
                <div class="card m-b-30">
                    <div class="card-body">
                        <h5 class="card-title">Ventajas</h5>
                        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#addVentajaModal">Añadir</button>

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre Membresía</th>
                                    <th>Nombre Zona</th>
                                    <th>Tipo Descuento</th>
                                    <th>Descuento</th>
                                    <th>Lunes</th>
                                    <th>Martes</th>
                                    <th>Miércoles</th>
                                    <th>Jueves</th>
                                    <th>Viernes</th>
                                    <th>Sábado</th>
                                    <th>Domingo</th>
                                    <th>Hora Inicio</th>
                                    <th>Hora Fin</th>
                                    <th>Antelación Reserva</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($membresiasVentajas as $ventaja)
                                    <tr>
                                        <td>{{ $ventaja->membresia->nombre ?? 'No disponible' }}</td>
                                        <td>{{ $ventaja->zona->nombre ?? 'No disponible' }}</td>
                                        <td>{{ $ventaja->tipo_descuento }}</td>
                                        <td>{{ $ventaja->descuento }}</td>
                                        <td>{{ $ventaja->lunes ? 'Sí' : 'No' }}</td>
                                        <td>{{ $ventaja->martes ? 'Sí' : 'No' }}</td>
                                        <td>{{ $ventaja->miercoles ? 'Sí' : 'No' }}</td>
                                        <td>{{ $ventaja->jueves ? 'Sí' : 'No' }}</td>
                                        <td>{{ $ventaja->viernes ? 'Sí' : 'No' }}</td>
                                        <td>{{ $ventaja->sabado ? 'Sí' : 'No' }}</td>
                                        <td>{{ $ventaja->domingo ? 'Sí' : 'No' }}</td>
                                        <td>{{ $ventaja->hora_inicio }}</td>
                                        <td>{{ $ventaja->hora_fin }}</td>
                                        <td>{{ $ventaja->antelacion_reserva }}</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm" wire:click="edit({{ $ventaja->id }})" data-toggle="modal" data-target="#addVentajaModal">Editar</button>
                                            <button class="btn btn-danger btn-sm" wire:click="delete({{ $ventaja->id }})">Eliminar</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>

        <!-- Modal para Añadir/Editar Ventaja -->
        <div class="modal fade" id="addVentajaModal" tabindex="-1" role="dialog" aria-labelledby="addVentajaModalLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addVentajaModalLabel">{{ $isEditing ? 'Editar Ventaja' : 'Añadir Ventaja' }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="">
                            <div class="form-group">
                                <label for="tipo_descuento">Tipo de Descuento</label>
                                <select class="form-control" wire:model="tipo_descuento">
                                    <option value="" selected>-- Seleccione tipo descuento --</option>
                                    <option value="Fijo">Fijo</option>
                                    <option value="Porcentaje">Porcentaje</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="descuento">Descuento</label>
                                <input type="number" class="form-control" id="descuento" wire:model="descuento" placeholder="Descuento">
                            </div>
                            <div class="form-group">
                                <label for="hora_inicio">Hora de Inicio</label>
                                <input type="time" class="form-control" id="hora_inicio" wire:model="hora_inicio">
                            </div>
                            <div class="form-group">
                                <label for="hora_fin">Hora de Fin</label>
                                <input type="time" class="form-control" id="hora_fin" wire:model="hora_fin">
                            </div>
                            <div class="form-group">
                                <label for="antelacion_reserva">Antelación para Reserva</label>
                                <input type="number" class="form-control" id="antelacion_reserva" wire:model="antelacion_reserva" placeholder="Antelación en días">
                            </div>
                            <div class="form-group d-flex gap-2 flex-wrap">
                                <div class="d-flex flex-column justify-content-center">
                                    <input type="checkbox" wire:model="lunes">
                                    <label for="lunes">Lunes</label>
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <input type="checkbox" wire:model="martes">
                                    <label for="martes">Martes</label>
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <input type="checkbox" wire:model="miercoles">
                                    <label for="miercoles">Miércoles</label>
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <input type="checkbox" wire:model="jueves">
                                    <label for="jueves">Jueves</label>
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <input type="checkbox" wire:model="viernes">
                                    <label for="viernes">Viernes</label>
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <input type="checkbox" wire:model="sabado">
                                    <label for="sabado">Sábado</label>
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <input type="checkbox" wire:model="domingo">
                                    <label for="domingo">Domingo</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="zonas">Zonas</label>
                                <select id="zonas" class="form-control" wire:model="zona_id">
                                    <option value="">-- Selecciona Zona --</option>
                                    @foreach($zonas as $zona)
                                        <option value="{{ $zona->id }}">{{ $zona->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="membresias">Membresías</label>
                                <select id="membresias" class="form-control" wire:model="membresia_id">
                                    <option value="" selected>-- Selecciona membresia --</option>
                                    @foreach($membresias as $membresia)
                                        <option value="{{ $membresia->id }}">{{ $membresia->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary" wire:click="{{ $isEditing ? 'update' : 'submit' }}" @if($isEditing) data-dismiss="modal"  @endif>{{ $isEditing ? 'Actualizar Ventaja' : 'Añadir Ventaja' }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            document.addEventListener('livewire:load', function () {
                $('#addVentajaModal').on('hide.bs.modal', function () {
                    Livewire.emit('resetForm');
                });
            });
        </script>
    @endsection
</div>
