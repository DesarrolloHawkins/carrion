<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Factura</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <style>
        /* Agrega estilos aquí */
    </style>
</head>

<body style="margin-right: 2rem">
    <div class="row">
        <div class="col-12">
            <img src="{{ public_path('/assets/' . $empresa[0]->photo) }}" alt="" title="" class="img-fluid"
                style="max-width: 200px">
        </div>
    </div>
    <div class="d-inline">
        <div class="col-12">
            <div style="float: left">
             <p class="mb-0">{{$empresa[0]->name}}</p>
            <p class="mb-0">{{$empresa[0]->adress}}</p>
            <p class="mb-0">{{$empresa[0]->ciudad}} - {{$empresa[0]->postCode}}</p>
            <p class="mb-0">{{$empresa[0]->taxNumber}}</p>   
            </div>
            
            <div style="float: right">
                <p class="mb-0"><h5><strong>FACTURA {{$factura->numeroFactura}}-{{$factura->serie}}</strong></h5></p>
                <p class="mb-0">Fecha: {{$factura->fecha}}</p>
            </div>
        </div>
    </div>
    <div class="d-block" style="clear:both; margin:3rem 0 ;">
        <div class="col-12" style="float: left">
            @if ($cliente->tipoCliente == 1)
            <h5>Datos del Cliente</h5>
            <p class="mb-0"><strong>Nombre:</strong> {{$cliente->nameCliente}} {{$cliente->firstSurname}} {{$cliente->lastSurname}}</p>
            <p class="mb-0"><strong>Dirección:</strong> {{$cliente->adressCliente}}</p>
            <p class="mb-0"><strong>DNI:</strong> {{$cliente->dni}}</p>
            <p class="mb-0"><strong>Correo electrónico:</strong> {{$cliente->emailCliente}}</p>
            @else
            <h5>Datos del Cliente</h5>
            <p class="mb-0"><strong>Nombre:</strong> {{$cliente->nameEmpresa}}</p>
            <p class="mb-0"><strong>Dirección:</strong> {{$cliente->address}}</p>
            <p class="mb-0"><strong>Codigo postal:</strong> {{$cliente->postCode}}</p>
            <p class="mb-0"><strong>CIF:</strong> {{$cliente->taxNumber}}</p>
            <p class="mb-0"><strong>Correo electronico:</strong> {{$cliente->email}}</p>
            @endif
        </div> 
    </div>
    
    <div class="d-block" style="clear:both;">
        <div class="col-12" style="float: left; margin-top: 3rem">
            <h5>Detalles de la factura</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>IVA</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Agrega filas aquí para cada producto -->
                    @foreach ($conceptos2 as $concepto)
                        
                    @endforeach
                    <tr>
                        <td>{{$concepto->producto->nombre}}</td>
                        <td>{{$concepto->cantidad}}</td>
                        <td>{{$concepto->precio}} €</td>
                        <td>{{$concepto->iva}}</td>
                        <td>{{$concepto->total}} €</td>
                    </tr>
                </tbody>
            </table>
            <br>
            <p><strong>Total:</strong> {{$factura->total}} €</p>
        </div>
        
    </div>
    <footer>
        <br>
    </footer>
</body>

</html>
