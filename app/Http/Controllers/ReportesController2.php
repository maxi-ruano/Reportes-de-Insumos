<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Insumos2;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\InsumosExport;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;



class ReportesController2 extends Controller
{
    function reporteControlInsumos2()
    {
        $insumos = Insumos2::all();
        return view('reportes.reportesControlInsumos2', ['insumos' => $insumos]);
    }
    
    

    
    
    public function exportarExcel()
    {
        $export = new InsumosExport();
        $fileName = 'insumos.xlsx';

        $exportPath = storage_path('app/' . $fileName);

        Excel::store($export, $fileName, 'local');

        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        // return response()->download($exportPath, $fileName, $headers);
           return response()->file($exportPath, $headers);

    }


    // public function exportarExcel()
    // {
    //    return Excel::download(new InsumosExport, 'insumos.xlsx');
    // //    return response()->file($exportPath, $headers);

    // }

}

