<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InsumosExport;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use Illuminate\Http\Request;
use App\AnsvLotes;
use App\Exports\CollectionExport;

use App\AnsvControl;
use App\AnsvDescartes;
use App\Exports\BlancosExport;
use App\SysMultivalue;
use App\User;
use App\Exports\CodificadosExport;
use App\Exports\DescartesExport;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Illuminate\Support\Facades\DB;


class ReportesController2 extends Controller
{

 

    
 

function reporteControlInsumos2(Request $request)
{
    $sucursaless = SysMultivalue::where('type', 'SUCU');

    if ($request->sucursal) {
        $sucursaless = $sucursaless->where('id', $request->sucursal);
    }

    $sucursaless = $sucursaless->get();
    $sucursalSeleccionada = $request->sucursal;
    $Todassucursales = SysMultivalue::where('type', 'SUCU')->get();
    $lotesImpresos = [];

    // Obtener los lotes de la sucursal filtrada o todas las sucursales
    $lotesSucursalQuery = AnsvLotes::when($sucursalSeleccionada, function ($query) use ($sucursalSeleccionada) {
        return $query->where('sucursal_id', $sucursalSeleccionada);
    })->orderByDesc('lote_id');

    // Filtrar por número de kit si se ha proporcionado
    if ($request->numero_kit) {
        $numeroKit = $request->numero_kit;
        $lotesSucursalQuery->where('nro_kit', $numeroKit);
    }

    $lotesSucursal = $lotesSucursalQuery->paginate(15)->appends(['sucursal' => $sucursalSeleccionada, 'numero_kit' => $request->numero_kit]);

    foreach ($lotesSucursal as $lote) {
        $descartados = AnsvDescartes::whereBetween('control', [$lote->control_desde, $lote->control_hasta])
            ->distinct()
            ->get(['control']);

        $cantidadDescartados = count($descartados);

        $cantidadLote = $lote->control_hasta - $lote->control_desde + 1;

        $cantidadImpresos = AnsvControl::whereBetween('nro_control', [$lote->control_desde, $lote->control_hasta])
            ->where('liberado', 'false')
            ->whereNotIn('nro_control', $descartados->pluck('control')) // Excluir los descartes de la consulta
            ->count('nro_control');

        $cantidadBlancos = $cantidadLote - ($cantidadImpresos + $cantidadDescartados);

        $nroKit = $lote->getAttribute('nro_kit');
        $nroCaja = $lote->getAttribute('nro_caja');

        $lotesImpresos[] = [
            'sucursal' => $sucursaless->where('id', $lote->sucursal_id)->first()->description,
            'lote_id' => $lote->lote_id,
            'nroKit' => $nroKit,
            'nroCaja' => $nroCaja,
            'cantidadImpresos' => $cantidadImpresos,
            'cantidadLote' => $cantidadLote,
            'control_desde' => $lote->control_desde,
            'control_hasta' => $lote->control_hasta,
            'cantidadBlancos' => $cantidadBlancos,
            'cantidadDescartados' => $cantidadDescartados
        ];
    }

    return view('reportes.reportesControlInsumos2', [
        'sucursales' => $sucursaless,
        'sucursalSeleccionada' => $sucursalSeleccionada,
        'Todassucursales' => $Todassucursales,
        'lotesImpresos' => $lotesImpresos,
        'lotesSucursal' => $lotesSucursal,
    ]);
}


public function obtenerCodificados(Request $request)
{
    $loteId = $request->input('loteId');

    $lote = AnsvLotes::where('lote_id', $loteId)->first();

    $descartados = AnsvDescartes::whereBetween('control', [$lote->control_desde, $lote->control_hasta])
       ->distinct()
        ->get(['control']);

    if (!$lote) {
        return response()->json(['error' => 'Lote no encontrado'], 404);
    }

    $controlDesde = $lote->control_desde;
    $controlHasta = $lote->control_hasta;

    $codificados = AnsvControl::whereBetween('nro_control', [$controlDesde, $controlHasta])
        ->where('liberado', false)
        ->whereNotIn('nro_control', $descartados->pluck('control'))
        ->get(['tramite_id', 'nro_control', 'created_by']);

    // Obtener los nombres de las personas asociadas a los IDs de 'created_by'
    $createdByIDs = $codificados->pluck('created_by');

    $usuarios = User::whereIn('sys_user_id', $createdByIDs)->get(['sys_user_id', 'name']);

    // Reemplazar los IDs por los nombres correspondientes en el resultado
    $codificados = $codificados->map(function ($codificado) use ($usuarios) {
        $usuario = $usuarios->where('sys_user_id', $codificado->created_by)->first();
        if ($usuario) {
            $nombre = $usuario->name; // Obtener el nombre del usuario
            $codificado->created_by = $nombre;
        } else {
            $codificado->created_by = $codificado->created_by; // Establecer 'Desconocido' si no se encuentra el usuario
        }
        return $codificado;
   
    
    });

    return response()->json($codificados);

}


public function obtenerDescartes(Request $request)
{
    $loteId = $request->input('loteId');

    // Obtener el rango de control_desde y control_hasta del lote
    $lote = AnsvLotes::where('lote_id', $loteId)->first();

    // Obtener los descartes dentro del rango de control_desde y control_hasta
    $descartes = AnsvDescartes::whereBetween('control', [$lote->control_desde, $lote->control_hasta])
        ->distinct()
        ->get(['control','descripcion']);

    // Obtener los codificados asociados a los descartes
    $codificados = AnsvControl::whereIn('nro_control', $descartes->pluck('control'))->get(['nro_control', 'tramite_id', 'created_by']);
    // Combinar los datos de descartes y codificados
    $descartes = $descartes->map(function ($descarte) use ($codificados) {
        $codificado = $codificados->where('nro_control', $descarte->control)->first();

        return [
            'tramite_id' => $codificado ? $codificado->tramite_id : 'N.C',
            'control' => $descarte->control,
            'created_by' => $codificado ? $codificado->created_by : 'N.C',
            'descripcion' => $descarte->descripcion,
        ];
    });

    return response()->json($descartes);
 
}



public function obtenerBlancos(Request $request)
{
    $loteId = $request->input('loteId');

    $lote = AnsvLotes::where('lote_id', $loteId)->first();


    if (!$lote) {
        return response()->json(['error' => 'Lote no encontrado'], 404);
    }

    $descartados = AnsvDescartes::whereBetween('control', [$lote->control_desde, $lote->control_hasta])
        ->distinct()
        ->pluck('control');

    $codificados = AnsvControl::whereBetween('nro_control', [$lote->control_desde, $lote->control_hasta])
        ->where('liberado', false)
        ->pluck('nro_control');

    $blancos = [];

    for ($i = $lote->control_desde; $i <= $lote->control_hasta; $i++) {
        if (!$descartados->contains($i) && !$codificados->contains($i)) {
            $blancos[] = $i;
        }
    }

    $cantidadBlancos = count($blancos);

    return response()->json([
        'cantidadBlancos' => $cantidadBlancos,
        'blancos' => $blancos,
        'numeroKit' => $lote->nro_kit
        
    ]);
}


public function exportarExcel(Request $request)
{
    // $sucursalTipo = 'SUCU';
    // $sucursales = Sucursal::where('type', $sucursalTipo)->get();
    $sucursales = SysMultivalue::where('type', 'SUCU')->get();

    $sucursalSeleccionada = $request->input('sucursal');

    // Obtener la descripción de la sucursal seleccionada
    $sucursalesDescripcion = '';
    foreach ($sucursales as $s) {
        if ($s->id == $sucursalSeleccionada) {
            $sucursalesDescripcion = $s->description;
            break;
        }
    }

    // Obtener los datos de cantidadLote y cantidadImpresos para la sucursal seleccionada
    $lotesImpresos = [];
    foreach ($sucursales as $sucursal) {
        if ($sucursalSeleccionada && $sucursal->id != $sucursalSeleccionada) {
            continue;
        }

        $sucursalId = $sucursal->id;
        // $lotesSucursal = AnsvLotes::where('sucursal_id', $sucursalId)->get();
        $lotesSucursal = AnsvLotes::when($sucursalSeleccionada, function ($query) use ($sucursalSeleccionada) {
            return $query->where('sucursal_id', $sucursalSeleccionada);
        })
        ->orderByDesc('lote_id')
        ->paginate(15);

        foreach ($lotesSucursal as $lote) {
            $descartados = AnsvDescartes::whereBetween('control', [$lote->control_desde, $lote->control_hasta])
                ->distinct()
                ->get();

            $cantidadDescartados = count($descartados);

            $cantidadImpresos = AnsvControl::whereBetween('nro_control', [$lote->control_desde, $lote->control_hasta])
                ->where('liberado', 'false')
                ->whereNotIn('nro_control', $descartados->pluck('nro_control'))
                ->count('nro_control');

            $cantidadLote = $lote->control_hasta - $lote->control_desde + 1;
            $cantidadBlancos = $cantidadLote - ($cantidadImpresos + $cantidadDescartados);

            $nroKit = $lote->getAttribute('nro_kit');
            $nroCaja = $lote->getAttribute('nro_caja');

            $lotesImpresos[] = [
                'lote_id' => $lote->lote_id,
                'sucursal' => $sucursal->description,
                'control_desde' => $lote->control_desde,
                'control_hasta' => $lote->control_hasta,
                'cantidadLote' => $cantidadLote,
                'cantidadBlancos' => $cantidadBlancos,
                'cantidadDescartados' =>$cantidadDescartados,
                'cantidadImpresos' => $cantidadImpresos,
                'nroKit' => $nroKit,
                'nroCaja' => $nroCaja,
            ];
        }
    }

    // Crear una nueva instancia de la clase InsumosExport y pasar los datos de $lotesImpresos
    $export = new InsumosExport($lotesImpresos);

    // Establecer el nombre del archivo Excel
    if ($sucursalesDescripcion) {
        $fileName = 'insumos_' . $sucursalesDescripcion . '_' . date('Y-m-d') . '.xlsx';
    } else {
        $fileName = 'insumos_' . 'TODOS'. '_' . date('Y-m-d') . '.xlsx';
    }

    $current_page = $request->input('page', 1);

    $fileName = $current_page . '_' . $fileName;
    // Generar y almacenar el archivo Excel
    $exportPath = storage_path('app/' . $current_page . '_' . $fileName);
    // $exportPath = storage_path('app/' . $fileName);
    // Excel::store($export, $fileName, 'local');
    Excel::store($export, $current_page . '_' . $fileName, 'local');

    // Obtener el archivo Excel generado
    $file = File::get($exportPath);
 
    // Eliminar el archivo generado
    File::delete($exportPath);

    // Crear la respuesta con el archivo Excel adjunto
    $response = Response::make($file, 200);
    $response->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    // $response->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    $response->header('Content-Disposition', 'attachment; filename="' . $current_page . '_' . $fileName . '"');

    return $response;
}


    
public function descargarExcel(Request $request)
{
    $loteId = $request->input('loteId');

    $lote = AnsvLotes::where('lote_id', $loteId)->first();

    $descartados = AnsvDescartes::whereBetween('control', [$lote->control_desde, $lote->control_hasta])
        ->distinct()
        ->get(['control']);

    if (!$lote) {
        return response()->json(['error' => 'Lote no encontrado'], 404);
    }

    $controlDesde = $lote->control_desde;
    $controlHasta = $lote->control_hasta;

    $codificados = AnsvControl::whereBetween('nro_control', [$controlDesde, $controlHasta])
        ->where('liberado', false)
        ->whereNotIn('nro_control', $descartados->pluck('control'))
        ->get(['tramite_id', 'nro_control', 'created_by']);

    // Obtener los nombres de las personas asociadas a los IDs de 'created_by'
    $createdByIDs = $codificados->pluck('created_by');

    $usuarios = User::whereIn('sys_user_id', $createdByIDs)->get(['sys_user_id', 'name']);

    // Reemplazar los IDs por los nombres correspondientes en el resultado
    $codificados = $codificados->map(function ($codificado) use ($usuarios) {
        $usuario = $usuarios->where('sys_user_id', $codificado->created_by)->first();
        if ($usuario) {
            $nombre = $usuario->name; // Obtener el nombre del usuario
            $codificado->created_by = $nombre;
        } else {
            $codificado->created_by = $codificado->created_by; // Establecer 'Desconocido' si no se encuentra el usuario
        }
        return $codificado;
    });

    // Generar el archivo Excel con los datos de codificados
    $export = new CodificadosExport($codificados);

    $fileName = 'codificados_' . $loteId . '_' . date('Y-m-d') . '.xlsx';

    $exportPath = storage_path('app/' . $fileName);
    Excel::store($export, $fileName, 'local');
    
    // Obtener el archivo de Excel generado
    $file = File::get($exportPath);
    
    // Eliminar el archivo generado
    File::delete($exportPath);
    
    // Crear la respuesta con el archivo de Excel adjunto
    $response = Response::make($file, 200);
    $response->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    $response->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    
    return $response;
}


public function descargarExcel2(Request $request)
{
    $loteId = $request->input('loteId');

    // Obtener el rango de control_desde y control_hasta del lote
    $lote = AnsvLotes::where('lote_id', $loteId)->first();

    // Obtener los descartes dentro del rango de control_desde y control_hasta
    $descartes = AnsvDescartes::whereBetween('control', [$lote->control_desde, $lote->control_hasta])
        ->distinct()
        ->get(['control','descripcion']);

    // Obtener los codificados asociados a los descartes
    $codificados = AnsvControl::whereIn('nro_control', $descartes->pluck('control'))->get(['nro_control', 'tramite_id', 'created_by']);

    // Combinar los datos de descartes y codificados
    $descartes = $descartes->map(function ($descarte) use ($codificados) {
        $codificado = $codificados->where('nro_control', $descarte->control)->first();

        return [
            'tramite_id' => $codificado ? $codificado->tramite_id : null,
            'control' => $descarte->control,
            'created_by' => $codificado ? $codificado->created_by : null,
            'descripcion' => $descarte->descripcion
        ];
    });

    // Crear una nueva instancia de la clase DescartesExport y pasar los datos de $descartes
    $export = new DescartesExport($descartes);

    // Establecer el nombre del archivo Excel
    $fileName = 'descartados_' . $loteId . '_' . date('Y-m-d') . '.xlsx';

    // Generar y almacenar el archivo Excel
    $exportPath = storage_path('app/' . $fileName);
    Excel::store($export, $fileName, 'local');

    // Obtener el archivo de Excel generado
    $file = File::get($exportPath);

    // Eliminar el archivo generado
    File::delete($exportPath);

    // Crear la respuesta con el archivo de Excel adjunto
    $response = Response::make($file, 200);
    $response->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    $response->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');

    return $response;
}



// public function descargarExcel3(Request $request)
// {
//     $loteId = $request->input('loteId');

//     $lote = AnsvLotes::where('lote_id', $loteId)->first();


//     if (!$lote) {
//         return response()->json(['error' => 'Lote no encontrado'], 404);
//     }

//     $descartados = AnsvDescartes::whereBetween('control', [$lote->control_desde, $lote->control_hasta])
//         ->distinct()
//         ->pluck('control');

//     $codificados = AnsvControl::whereBetween('nro_control', [$lote->control_desde, $lote->control_hasta])
//         ->where('liberado', false)
//         ->pluck('nro_control');

//     $blancos = [];

//     for ($i = $lote->control_desde; $i <= $lote->control_hasta; $i++) {
//         if (!$descartados->contains($i) && !$codificados->contains($i)) {
//             $blancos[] = $i;
//         }
//     }

//     $cantidadBlancos = count($blancos);

//     return response()->json([
//         'cantidadBlancos' => $cantidadBlancos,
//         'blancos' => $blancos,
//         'numeroKit' => $lote->nro_kit
        
//     ]);

//     $export = new BlancosExport($blancos);

//     // Establecer el nombre del archivo Excel
//     $fileName = 'blancos_' . $loteId . '_' . date('Y-m-d') . '.xlsx';

//     // Generar y almacenar el archivo Excel
//     $exportPath = storage_path('app/' . $fileName);
//     Excel::store($export, $fileName, 'local');

//     // Obtener el archivo de Excel generado
//     $file = File::get($exportPath);

//     // Eliminar el archivo generado
//     File::delete($exportPath);

//     // Crear la respuesta con el archivo de Excel adjunto
//     $response = Response::make($file, 200);
//     $response->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//     $response->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');

//     return $response;




// }

public function descargarExcel3(Request $request)
{
    $loteId = $request->input('loteId');

    $lote = AnsvLotes::where('lote_id', $loteId)->first();

    if (!$lote) {
        return response()->json(['error' => 'Lote no encontrado'], 404);
    }

    $descartados = AnsvDescartes::whereBetween('control', [$lote->control_desde, $lote->control_hasta])
        ->distinct()
        ->pluck('control');

    $codificados = AnsvControl::whereBetween('nro_control', [$lote->control_desde, $lote->control_hasta])
        ->where('liberado', false)
        ->pluck('nro_control');

    $blancos = [];

    for ($i = $lote->control_desde; $i <= $lote->control_hasta; $i++) {
        if (!$descartados->contains($i) && !$codificados->contains($i)) {
            $blancos[] = $i;
        }
    }

    $cantidadBlancos = count($blancos);

    $export = new BlancosExport($blancos);

    // Establecer el nombre del archivo Excel
    $fileName = 'blancos_' . $loteId . '_' . date('Y-m-d') . '.xlsx';

    // Generar y almacenar el archivo Excel
    $exportPath = storage_path('app/' . $fileName);
    Excel::store($export, $fileName, 'local');

    // Obtener el archivo de Excel generado
    $file = File::get($exportPath);

    // Eliminar el archivo generado
    File::delete($exportPath);

    // Crear la respuesta con el archivo de Excel adjunto
    $response = Response::make($file, 200);
    $response->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    $response->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');

    return $response;
}





}

