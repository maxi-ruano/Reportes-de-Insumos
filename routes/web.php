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
Route::post('guardar_respuesta',['uses' => 'EtlExamenController@guardar_respuesta','as' => 'guardar_respuesta']);
// Route::get('/address/{id}/destroy',['uses' => 'AddressesController@destroy','as' => 'sysfile.addresses.destroy']);
Route::get('/', function () {
    return view('examen.caratulaExamen');
});
Route::group(['prefix' => 'admin'], function () {
    Route::auth();
    //Route::resource('','AdminController');
    Route::resource('computadoras','TeoricoPcController');
});
// Route::get('rendir_examen','EtlExamenController@rendir_examen');
Route::resource('examen', 'EtlExamenController');
Route::resource('preguntas', 'EtlExamenPreguntaController');
