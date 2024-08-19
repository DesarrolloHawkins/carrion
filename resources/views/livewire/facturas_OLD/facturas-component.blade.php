


<div class="container mb-5">

    <div class="d-flex justify-content-evenly align-items-center">
        <h1 class="">Crear una Factura</h1>
        <button wire:click="alerta()"  class="btn btn-info text-white rounded-circle"><i class="fa-solid fa-plus"></i></button>
    </div>
    <hr>
    <form wire:submit.prevent="submit">
        <input type="hidden" name="csrf-token" value="{{ csrf_token() }}">
        <div class="mb-3 row d-flex align-items-center">
            <label for="serie" class="col-sm-2 col-form-label">Numero de Factura</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" wire:model="serie" name="serie" id="numberFac" placeholder="EMP201712">
                @error('serie') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-sm-5">
                <input type="text" class="form-control" wire:model="numberFac" name="numberFac" id="numberFac" placeholder="Serie 0003">
                @error('numberFac') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="mb-3 row d-flex align-items-center">
            <label for="fecha" class="col-sm-2 col-form-label">Fecha de Factura</label>
            <div class="col-sm-10">
              <input type="date" class="form-control" wire:model="fecha" name="fecha" id="fecha">
              @error('fecha') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <h4>Detalles del cliente</h4>
        <div class="mb-3 row d-flex align-items-center">
            <label for="name" class="col-sm-2 col-form-label">Nombre del Cliente</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" wire:model="name" name="name" id="name" placeholder="Nombre del cliente">
              @error('name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="mb-3 row d-flex align-items-center">
            <label for="firstSurname" class="col-sm-2 col-form-label">Primer Apellido del Cliente</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" wire:model="firstSurname" name="firstSurname" id="firstSurname" placeholder="Primer Apellido del cliente">
              @error('firstSurname') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="mb-3 row d-flex align-items-center">
            <label for="firstSurname" class="col-sm-2 col-form-label">Segundo Apellido del Cliente</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" wire:model="lastSurname" name="lastSurname" id="lastSurname" placeholder="Segundo Apellido del cliente">
              @error('lastSurname') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="mb-3 row d-flex align-items-center">
            <label for="taxNumber" class="col-sm-2 col-form-label">DNI</label>
            <div class="col-sm-10">
              <input type="text" wire:model="taxNumber" class="form-control" name="taxNumber" id="taxNumber" placeholder="DNI (00000000A)">
              @error('taxNumber') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="mb-3 row d-flex align-items-center">
            <label for="address" class="col-sm-2 col-form-label">Direccion de la Compañia</label>
            <div class="col-sm-10">
              <input type="text" wire:model="address" class="form-control" name="address" id="address" placeholder="C/ Direccion de la empresa">
              @error('address') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="mb-3 row d-flex align-items-center">
            <label for="town" class="col-sm-2 col-form-label">Ciudad</label>
            <div class="col-sm-10">
              <input type="text" wire:model="town" class="form-control" name="town" id="town" placeholder="Ciudad">
              @error('town') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="mb-3 row d-flex align-items-center">
            <label for="province" class="col-sm-2 col-form-label">Provincia</label>
            <div class="col-sm-10">
              <input type="text" wire:model="province" class="form-control" name="province" id="province" placeholder="Provincia">
              @error('province') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="mb-3 row d-flex align-items-center">
            <label for="postCode" class="col-sm-2 col-form-label">Codigo Postal</label>
            <div class="col-sm-10">
              <input type="text" wire:model="postCode" class="form-control" name="postCode" id="postCode" placeholder="Codigo Postal (00000)">
              @error('postCode') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <h4>Conceptos del Pedido</h4>
        <hr>

            <div class="mb-3 row">
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Nombre" wire:model="nameProducto.0">
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
                <div class="col-md-2">
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
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Nombre" wire:model="nameProducto.{{$value}}">
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
                <div class="col-md-2">
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
</div>
