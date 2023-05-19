<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Insumos2;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InsumosExport;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;
use App\Sucursal;





class ReportesController2 extends Controller
{
    // function reporteControlInsumos2()
    // {
    //     $insumos = Insumos2::all();
    //     return view('reportes.reportesControlInsumos2', ['insumos' => $insumos]);
    // }
    
//     public function reporteControlInsumos2(Request $request)
// {
//     $sucursal = $request->input('sucursal');

//     $query = Insumos2::query();

//     if (!empty($sucursal)) {
//         $query->where('sucursal_id', $sucursal);
//     }

//     $insumos = $query->get();

//     return view('reportes.reportesControlInsumos2', ['insumos' => $insumos]);
// }

// function reporteControlInsumos2(Request $request)
// {
//     $sucursales = Sucursal::all(); // Obtén todas las sucursales

//     $sucursalSeleccionada = $request->input('sucursal');

//     $query = Insumos2::query();

//     if (!empty($sucursalSeleccionada)) {
//         $query->where('sucursal_id', $sucursalSeleccionada);
//     }

//     $insumos = $query->get();

//     return view('reportes.reportesControlInsumos2', [
//         'sucursales' => $sucursales,
//         'insumos' => $insumos,
//         'sucursalSeleccionada' => $sucursalSeleccionada
//     ]);
// }
function reporteControlInsumos2(Request $request)
{
    $sucursalTipo = 'SUCU';

    $sucursales = Sucursal::where('type', $sucursalTipo)->get();

    // dd($sucursales);

    $sucursalSeleccionada = $request->input('sucursal');

    $query = Insumos2::query();

    if (!empty($sucursalSeleccionada)) {
        $query->where('sucursal_id', $sucursalSeleccionada);
    }

    $insumos = $query->get();

    return view('reportes.reportesControlInsumos2', [
        'sucursales' => $sucursales,
        'insumos' => $insumos,
        'sucursalSeleccionada' => $sucursalSeleccionada
    ]);
}









    public function exportarExcel()

    {
        $export = new InsumosExport();
        $fileName = 'insumos.xlsx';
        $exportPath = storage_path('app/' . $fileName);
    
        Excel::store($export, $fileName, 'local');
    
        $file = File::get($exportPath);
        $response = Response::make($file, 200);
        $response->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    
        File::delete($exportPath);
    
        return $response;
    }





    
}

