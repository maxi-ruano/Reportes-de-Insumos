<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix'=>'reportes', 'middleware'=>'cors'], function(){
    Route::get('get_errores_precheck', 'PreCheckController@get_errores_precheck')->name('get_errores_precheck');
    Route::get('get_tramites_precheck', 'PreCheckController@get_tramites_precheck')->name('get_tramites_precheck');
    Route::get('get_licencias_emitidas', 'TramitesController@get_licencias_emitidas')->name('get_licencias_emitidas');
    Route::get('get_precheck_comprobantes', 'PreCheckController@get_precheck_comprobantes')->name('get_precheck_comprobantes');
});

Route::group(['prefix'=>'funciones', 'middleware'=>'cors'], function(){
    Route::post('actualizarPaseATurno', 'PreCheckController@actualizarPaseATurno')->name('actualizarPaseATurno');
    Route::post('obtenerSucursales', 'DashboardController@obtenerSucursales')->name('obtenerSucursales');
});