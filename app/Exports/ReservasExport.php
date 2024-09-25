<?php

namespace App\Exports;

use App\Models\Reservas;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReservasExport implements FromQuery, WithHeadings
{
    protected $parameters;

    /**
     * Constructor para recibir los parámetros del request y aplicarlos al query.
     */
    public function __construct($queryParameters)
    {
        $this->parameters = $queryParameters;
    }

    public function query()
    {
        $filtro = $this->parameters['filtro'] ?? '';
        $estado = $this->parameters['estado'] ?? '';
        $grada = $this->parameters['grada'] ?? '';
        $palco = $this->parameters['palco'] ?? '';
        $sortColumn = $this->parameters['sortColumn'] ?? 'Nombre';
        $sortDirection = $this->parameters['sortDirection'] ?? 'asc';

        // Construcción del query con los mismos filtros de la vista
        return Reservas::query()
            ->when($filtro, function ($query, $filtro) {
                return $query->whereHas('clientes', function ($query) use ($filtro) {
                    $query->where('nombre', 'like', "%{$filtro}%")
                        ->orWhere('apellidos', 'like', "%{$filtro}%")
                        ->orWhere('DNI', 'like', "%{$filtro}%")
                        ->orWhere('movil', 'like', "%{$filtro}%");
                })->orWhereHas('sillas', function ($query) use ($filtro) {
                    $query->where('fila', 'like', "%{$filtro}%")
                          ->orWhere('numero', 'like', "%{$filtro}%")
                          ->orWhereHas('zona', function ($query) use ($filtro) {
                              $query->where('nombre', 'like', "%{$filtro}%");
                          });
                });
            })
            ->when($estado, function ($query, $estado) {
                return $query->where('estado', $estado);
            })
            ->when($grada, function ($query, $grada) {
                return $query->whereHas('sillas', function ($query) use ($grada) {
                    $query->whereHas('grada', function ($query) use ($grada) {
                        $query->where('id', $grada);
                    });
                });
            })
            ->when($palco, function ($query, $palco) {
                return $query->whereHas('sillas', function ($query) use ($palco) {
                    $query->whereHas('palco', function ($query) use ($palco) {
                        $query->where('id', $palco);
                    });
                });
            })
            ->join('clientes', 'reservas.id_cliente', '=', 'clientes.id')
            ->join('sillas', 'reservas.id_silla', '=', 'sillas.id')
            ->leftJoin('gradas', 'sillas.id_grada', '=', 'gradas.id')
            ->leftJoin('palcos', 'sillas.id_palco', '=', 'palcos.id')
            ->join('zonas', 'sillas.id_zona', '=', 'zonas.id')
            ->select(
                'clientes.nombre as Nombre',
                'clientes.apellidos as Apellidos',
                'clientes.DNI as DNI',
                'clientes.movil as Telefono',
                'sillas.fila as Fila',
                'sillas.numero as Asiento',
                'zonas.nombre as Sector',
                'palcos.numero as Palco',
                'gradas.numero as Grada',
                'reservas.fecha as Fecha ',
                'reservas.precio as Precio',
                'reservas.metodo_pago as Metodo',
                'reservas.estado as Estado',

            )
            ->orderBy($sortColumn, $sortDirection);
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Apellidos',
            'DNI',
            'Telefono',
            'Fila',
            'Asiento',
            'Sector',
            'Palco',
            'Grada',
            'Fecha',
            'Precio',
            'Metodo ',
            'Estado',
        ];
    }
}
