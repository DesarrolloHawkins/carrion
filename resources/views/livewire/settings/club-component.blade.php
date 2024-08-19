<div >
    <div  class="container-fluid">
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h4 class="page-title">Información del Club</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Club</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card m-b-30">
                    <div style="background: #F9F9F9;" class="card-body">

                        <form wire:submit.prevent="" class="d-flex flex-wrap gap-4 mb-4" >
                            <div style="width: 48%; border: 1px solid; padding: 10px; border-radius: 10px; background:white;" class="row">
                                <h3>El club</h3>
                                <div class="form-group col-6">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" wire:model="nombre" placeholder="Nombre del club">
                                    @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div  class="form-group col-6">
                                    <label for="numero_pistas">Número de Pistas</label>
                                    <input type="number" class="form-control" id="numero_pistas" wire:model="numero_pistas" placeholder="Número de pistas">
                                    @error('numero_pistas') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-6">
                                    <label for="pagina_web">Página Web</label>
                                    <input type="url" class="form-control" id="pagina_web" wire:model="pagina_web" placeholder="Página web">
                                    @error('pagina_web') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-6">
                                    <label for="facebook">Facebook</label>
                                    <input type="url" class="form-control" id="facebook" wire:model="facebook" placeholder="Facebook">
                                    @error('facebook') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-6">
                                    <label for="twitter">Twitter</label>
                                    <input type="url" class="form-control" id="twitter" wire:model="twitter" placeholder="Twitter">
                                    @error('twitter') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div style="width: 48%; border: 1px solid; padding: 10px; border-radius: 10px; background:white;" class="row">
                                <h3>Descripción</h3>
                                <div class="form-group">
                                    <label for="descripcion">Descripción</label>
                                    <textarea rows="10" class="form-control" id="descripcion" wire:model="descripcion" rows="3" placeholder="Descripción"></textarea>
                                    @error('descripcion') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div style="width: 48%; border: 1px solid; padding: 10px; border-radius: 10px; background:white;" class="row">
                                <h3>Datos de contacto</h3>
                                <div class="form-group col-6">
                                    <label for="nombre_contacto">Nombre de Contacto</label>
                                    <input type="text" class="form-control" id="nombre_contacto" wire:model="nombre_contacto" placeholder="Nombre de contacto">
                                    @error('nombre_contacto') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-6">
                                    <label for="email_contacto">Email de Contacto</label>
                                    <input type="email" class="form-control" id="email_contacto" wire:model="email_contacto" placeholder="Email de contacto">
                                    @error('email_contacto') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-6">
                                    <label for="telefono">Teléfono</label>
                                    <input type="text" class="form-control" id="telefono" wire:model="telefono" placeholder="Teléfono">
                                    @error('telefono') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                
                            </div>
                            <div style="width: 48%; border: 1px solid; padding: 10px; border-radius: 10px; background:white;" class="row">
                                <h3>Dirección</h3>
                                <div class="form-group col-6">
                                    <label for="pais">País</label>
                                    <input type="text" class="form-control" id="pais" wire:model="pais" placeholder="País">
                                    @error('pais') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-6">
                                    <label for="ciudad">Ciudad</label>
                                    <input type="text" class="form-control" id="ciudad" wire:model="ciudad" placeholder="Ciudad">
                                    @error('ciudad') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-6">
                                    <label for="poblacion">Población</label>
                                    <input type="text" class="form-control" id="poblacion" wire:model="poblacion" placeholder="Población">
                                    @error('poblacion') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-6">
                                    <label for="codigo_postal">Código Postal</label>
                                    <input type="text" class="form-control" id="codigo_postal" wire:model="codigo_postal" placeholder="Código Postal">
                                    @error('codigo_postal') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-6">
                                    <label for="direccion">Dirección</label>
                                    <input type="text" class="form-control" id="direccion" wire:model="direccion" placeholder="Dirección">
                                    @error('direccion') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            <div style="width: 48%; border: 1px solid; padding: 10px; border-radius: 10px; background:white;" class="row">
                                <h3>Apertura</h3>
                                <div></div>
                                @foreach(['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'] as $day)
                                    <div class="form-group col-6">
                                        <label for="{{ $day }}_apertura">{{ ucfirst($day) }} Apertura</label>
                                        <input type="time" class="form-control" id="{{ $day }}_apertura" wire:model="{{ $day }}_apertura">
                                        @error("{$day}_apertura") <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="{{ $day }}_cierre">{{ ucfirst($day) }} Cierre</label>
                                        <input type="time" class="form-control" id="{{ $day }}_cierre" wire:model="{{ $day }}_cierre">
                                        @error("{$day}_cierre") <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                @endforeach
                            </div>

                            <div style="width: 48%; border: 1px solid; padding: 10px; border-radius: 10px; background:white;" class="row">
                                <h3 class="col-12">Reservas</h3>
                                <div class="form-group col-6">
                                    <label for="extracto">Extracto</label>
                                    <textarea rows='17' type="text" class="form-control" id="extracto" wire:model="extracto" placeholder="Extracto"> </textarea>
                                    @error('extracto') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-6">
                                    <label for="limite_reserva">Limite de reserva anticipada (días)</label>
                                    <input type="number" class="form-control" id="limite_reserva" wire:model="limite_reserva" placeholder="limite_reserva">
                                    @error('limite_reserva') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-6">
                                    <label for="limite_reserva">Tiempo de cancelación (horas)</label>
                                    <input type="number" class="form-control" id="tiempo_cancelacion" wire:model="tiempo_cancelacion" placeholder="tiempo_cancelacion">
                                    @error('tiempo_cancelacion') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-6">
                                    <label for="maximo_reservas_dia">Máximo de reservas al día</label>
                                    <input type="number" class="form-control" id="maximo_reservas_dia" wire:model="maximo_reservas_dia" placeholder="maximo_reservas_dia">
                                    @error('maximo_reservas_dia') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="maximo_reservas_activas">Máximo de reservas activas</label>
                                    <input type="number" class="form-control" id="maximo_reservas_activas" wire:model="maximo_reservas_activas" placeholder="maximo_reservas_activas">
                                    @error('maximo_reservas_activas') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            
                            
                            
                            
                        </form>
                        <button type="submit" wire:click="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            document.addEventListener('livewire:load', function () {
                // Additional JavaScript if needed
            });
        </script>
    @endsection
</div>
