<?php

namespace App\Exports;



use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Support\Arrayable;

class BlancosExport implements FromCollection, WithHeadings
{
    protected $blancos;

    public function __construct(array $blancos)
    {
        $this->blancos = $blancos;
    }

    // public function collection()
    // {
    //     return $this->blancos;
    // }
    public function collection()
{
    $data = [];

    foreach ($this->blancos as $blanco) {
        $data[] = [
            'Número de Control' => $blanco
        ];
    }

    return collect($data);
}

    public function headings(): array
    {
        return ['Número de Control'];
    }

   
    
}



