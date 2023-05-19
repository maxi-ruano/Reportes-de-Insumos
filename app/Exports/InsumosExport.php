<?php

namespace App\Exports;

use App\Insumos2;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InsumosExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $insumos = Insumos2::select('lote_id', 'sucursal_id', 'control_desde', 'control_hasta', 'habilitado', 'nro_kit', 'nro_caja')->get();

        
        // return Insumos2::all();
        return $insumos;

    }

    public function headings(): array
    {
        return [
            'Lote_id',
            'Sucursal_id',
            'Control desde',
            'Control hasta',
            'Habilitado',
            'N° Kit',
            'N° Caja'
        ];
    }
}

