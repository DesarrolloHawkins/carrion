

@section('head')
    @vite(['resources/sass/productos.scss'])
@endsection
<div class="container mx-auto">
    <h1>Productos</h1>
    <h2>Editar Productos</h2>
    <br>


            <form wire:submit.prevent="update">
                <input type="hidden" name="csrf-token" value="{{ csrf_token() }}">
                <div class="mb-3 row d-flex align-items-center">
                  <label for="stock" class="col-sm-2 col-form-label">Categoria</label>
                  <div class="col-sm-10">
                    <select name="categoriaProducto" id="id_categoria" wire:model="id_categoria" class="form-control">
                      <option value="">-- Selecciona Categoria --</option>
                      @foreach ($categorias as $categoria)
                        <option value="{{$categoria->id}}">{{$categoria->nombre}}</option>
                      @endforeach
                    </select>
                    @error('id_categoria') <span class="text-danger">{{ $message }}</span> @enderror
                  </div>
                </div>
                <div class="mb-3 row d-flex align-items-center">
                    <label for="cod_producto" class="col-sm-2 col-form-label">Código del producto</label>
                    <div class="col-sm-10">
                      <input type="text" wire:model="cod_producto" class="form-control" name="cod_producto" id="cod_producto" placeholder="COD123">
                      @error('cod_producto') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-3 row d-flex align-items-center">
                    <label for="nombre" class="col-sm-2 col-form-label">Nombre del producto</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" wire:model="nombre" nombre="nombre" id="nombre" placeholder="Nombre del producto...">
                      @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-3 row d-flex align-items-center">
                    <label for="descripcion" class="col-sm-2 col-form-label">Descripción del producto</label>
                    <div class="col-sm-10">
                      <input type="text" wire:model="descripcion" class="form-control" name="descripcion" id="descripcion" placeholder="Mesa de 10x10...">
                      @error('descripcion') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mb-3 row d-flex align-items-center">
                    <label for="precio" class="col-sm-2 col-form-label">Precio</label>
                    <div class="col-sm-10">
                      <input type="text" wire:model="precio" class="form-control" name="precio" id="precio" placeholder="19.99">
                      @error('precio') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mb-3 row d-flex align-items-center">
                    <label for="stock" class="col-sm-2 col-form-label">Stock</label>
                    <div class="col-sm-10">
                      <input type="text" wire:model="stock" class="form-control" name="stock" id="stock" placeholder="50">
                      @error('stock') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-3 row d-flex align-items-center">
                    <button type="submit" class="btn btn-outline-info">Guardar</button>
                </div>


            </form>
            <div class="mb-3 row d-flex align-items-center">
              <button wire:click="destroy" class="btn btn-outline-danger">Eliminar</button>
          </div>
        </div>





</div>

