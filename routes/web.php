<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::post('rendir_examen',['uses' => 'EtlExamenPreguntaController@getPreguntasExamen','as' => 'rendir_examen']);
Route::get('guardar_respuesta',['uses' => 'EtlExamenPreguntaController@guardarRespuesta','as' => 'guardaRespuesta']);
Route::post('finalizar_examen',['uses' => 'EtlExamenController@calcularYGuardarResultado','as' => 'finalizar_examen']);
// Route::get('/address/{id}/destroy',['uses' => 'AddressesController@destroy','as' => 'sysfile.addresses.destroy']);
Route::get('/', 'HomeController@index');

//Auth::routes();
Route::get('computadorasMonitor', 'TeoricoPcController@computadorasMonitor');
Route::get('verificarAsignacion', 'TeoricoPcController@verificarAsignacion');
Route::group(['prefix' => 'admin'], function () {
    Route::auth();
    //Auth::routes();
    //Route::auth();
    //Route::resource('','AdminController');
    Route::get('computadoras/active', 'TeoricoPcController@isActive');

    Route::resource('computadoras','TeoricoPcController');
    Route::resource('bedel', 'BedelController');
    Route::get('asignar','BedelController@asignarExamen');

});
// Route::get('rendir_examen','EtlExamenController@rendir_examen');
Route::resource('examen', 'EtlExamenController');
Route::resource('preguntas', 'EtlExamenPreguntaController');


///var/www/html/teorico/app/Http/Controllers/EtlExamenController.php
