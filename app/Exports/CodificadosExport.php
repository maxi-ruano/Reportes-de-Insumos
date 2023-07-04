<?php

namespace App\Exports;









use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


// class CodificadosExport implements FromCollection
// {
//     protected $codificados;

//     public function __construct($codificados)
//     {
//         $this->codificados = $codificados;
//     }

//     public function collection()
//     {
//         return $this->codificados;
//     }
// }


class CodificadosExport implements FromCollection, WithHeadings
{
    protected $codificados;

    public function __construct($codificados)
    {
        $this->codificados = $codificados;
    }

    public function collection()
    {
        return $this->codificados;
    }

    public function headings(): array
    {
        return [
            'Trámite ID',
            'Número de Control',
            'Creado por',
        ];
    }
}