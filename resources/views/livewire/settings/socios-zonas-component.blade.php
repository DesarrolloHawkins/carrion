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
            <a href="{{ route('settings.socios-ventajas') }}" class="link" data-view="coaches">Ventajas</a>
            <a class="active link" data-view="students-on-hold">Zonas</a>
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
            <div class="col-md-9 row gap-2">
                @foreach(['trackTypes' => 'Zonas'] as $type => $label)
                <div class="card m-b-30 col-3 rounded">
                    <div class="card-body">
                        <h5>{{ $label }}</h5>
                        <button type="button" class="btn btn-primary mb-2" wire:click="setCurrentType('{{ $type }}')" data-toggle="modal" data-target="#trackTypeModal">Añadir</button>
                        <ul class="list-group">
                            @foreach($dataTypes[$type]['items'] as $index => $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $item['nombre'] }} (Capacidad: {{ $item['max_capacidad'] }})
                                    <button class="btn btn-danger btn-sm" wire:click="removeItem({{ $index }})">Eliminar</button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="col-md-3 justify-content-center">
                <div class="card m-b-30 position-fixed">
                    <div class="card-body">
                        <h5>Opciones de guardado</h5>
                        <div class="row">
                            <div class="col-12">
                                <button class="w-100 btn btn-success mb-2" wire:click="submit">Guardar opciones</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="trackTypeModal" tabindex="-1" role="dialog" aria-labelledby="trackTypeModalLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="trackTypeModalLabel">Añadir/Eliminar Zona</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="zoneName">Nombre de la Zona</label>
                            <input type="text" class="form-control" id="zoneName" wire:model="dataTypes.{{ $currentType }}.newItem.nombre" placeholder="Ingrese el nombre de la zona">
                        </div>
                        <div class="form-group">
                            <label for="zoneCapacity">Capacidad Máxima</label>
                            <input type="number" class="form-control" id="zoneCapacity" wire:model="dataTypes.{{ $currentType }}.newItem.max_capacidad" placeholder="Ingrese la capacidad máxima">
                        </div>
                        <button type="button" class="btn btn-primary mb-2" wire:click="addItem">Añadir Zona</button>
                        <ul class="list-group">
                            @foreach($dataTypes[$currentType]['items'] as $index => $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $item['nombre'] }} (Capacidad: {{ $item['max_capacidad'] }})
                                    <button class="btn btn-danger btn-sm" wire:click="removeItem({{ $index }})">Eliminar</button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            document.addEventListener('livewire:load', function () {
                $("#alertaGuardar").on("click", function() {
                    Swal.fire({
                        title: '¿Estás seguro?',
                        icon: 'warning',
                        showConfirmButton: true,
                        showCancelButton: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Livewire.emit('submit');
                        }
                    });
                });
            });
        </script>
    @endsection
</div>
