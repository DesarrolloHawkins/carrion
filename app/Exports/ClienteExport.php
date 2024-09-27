<?php

namespace App\Exports;

use App\Models\Cliente;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClienteExport implements FromQuery, WithHeadings
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
        $abonado = $this->parameters['abonado'] ?? '';
        $tipo_abonado = $this->parameters['tipo_abonado'] ?? '';
        $sortColumn = $this->parameters['sortColumn'] ?? 'nombre';
        $sortDirection = $this->parameters['sortDirection'] ?? 'asc';

        // Construcción del query con los filtros para clientes
        return Cliente::query()
            ->when($filtro, function ($query, $filtro) {
                return $query->where('nombre', 'like', "%{$filtro}%")
                    ->orWhere('apellidos', 'like', "%{$filtro}%")
                    ->orWhere('DNI', 'like', "%{$filtro}%")
                    ->orWhere('movil', 'like', "%{$filtro}%")
                    ->orWhere('email', 'like', "%{$filtro}%");
            })
            ->when($abonado !== '', function ($query, $abonado) {
                return $query->where('abonado', $abonado);
            })
            ->when($tipo_abonado, function ($query, $tipo_abonado) {
                return $query->where('tipo_abonado', $tipo_abonado);
            })
            ->select(
                'nombre as Nombre',
                'apellidos as Apellidos',
                'DNI as DNI',
                'movil as Telefono',
                'email as Email',
                'fijo as Telefono_Fijo',
                'abonado as Abonado',
                'tipo_abonado as Tipo_Abonado'
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
            'Email',
            'Telefono Fijo',
            'Abonado',
            'Tipo de Abonado',
        ];
    }
}
