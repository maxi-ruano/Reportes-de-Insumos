<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DescartesExport implements FromCollection, WithHeadings
{
    protected $descartes;

    public function __construct($descartes)
    {
        $this->descartes = $descartes;
    }

    public function collection()
    {
        return $this->descartes;
    }

    public function headings(): array
    {
        return [
            'Trámite ID',
            'Número de Control',
            'Creado por',
            'Descripcion',
        ];
    }
}