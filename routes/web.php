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

//WELCOME
/*Route::get('/', function () {
  return view('welcome');
});*/

//LOGIN
Auth::routes();
Route::get('/', 'HomeController@index')->name('home');
Route::group( ['middleware' => ['auth']], function() {
  Route::resource('users', 'UserController');
  Route::resource('roles', 'RoleController');
});

//TRAMITES
Route::resource('tramitesHabilitados','TramitesHabilitadosController');
Route::get('tramitesHabilitadosHabilitar', ['uses' => 'TramitesHabilitadosController@habilitar','as' => 'tramitesHabilitados.habilitar']);

//end TRAMITES

//DASHBOARD
Route::get('consultaDashboard', ['uses' => 'DashboardController@consultaDashboard','as' => 'consultaDashboard']);
Route::get('consultaDashboardGraf', ['uses' => 'DashboardController@consultaDashboardGraf','as' => 'consultaDashboardGraf']);
Route::get('comparacionPrecheck', ['uses' => 'DashboardController@comparacionPrecheck','as' => 'comparacionPrecheck']);
Route::get('consultaTurnosEnEspera', ['uses' => 'DashboardController@consultaTurnosEnEspera','as' => 'consultaTurnosEnEspera']);
Route::get('consultaTurnosEnEsperaPorSucursal', ['uses' => 'DashboardController@consultaTurnosEnEsperaPorSucursal','as' => 'consultaTurnosEnEsperaPorSucursal']);
//end DASHBOARD


Route::post('rendir_examen',['uses' => 'EtlExamenPreguntaController@getPreguntasExamen','as' => 'rendir_examen']);
Route::get('guardar_respuesta',['uses' => 'EtlExamenPreguntaController@guardarRespuesta','as' => 'guardaRespuesta']);
Route::post('finalizar_examen',['uses' => 'EtlExamenController@calcularYGuardarResultado','as' => 'finalizar_examen']);
// Route::get('/address/{id}/destroy',['uses' => 'AddressesController@destroy','as' => 'sysfile.addresses.destroy']);

Route::get('run', 'MicroservicioController@run');
//SAFIT
Route::get('buscarBoletaPago', 'TramitesAInicarController@buscarBoletaPago');
Route::post('consultarBoletaPago',['uses' => 'TramitesAInicarController@consultarBoletaPago','as' => 'consultarBoletaPago']);
Route::post('generarCenat', ['uses' => 'TramitesAInicarController@generarCenat','as' => 'generarCenat']);
Route::get('checkPreCheck', ['uses' => 'PreCheckController@checkPreCheck','as' => 'checkPreCheck']);
Route::get('consultarPreCheck', ['uses' => 'PreCheckController@consultarPreCheck','as' => 'consultarPreCheck']);
Route::get('buscarTramitesPrecheck', ['uses' => 'PreCheckController@buscarTramitesPrecheck','as' => 'buscarTramitesPrecheck']);
Route::get('testCheckBoletas', ['uses' => 'TramitesAInicarController@testCheckBoletas','as' => 'testCheckBoletas']);
//END SAFIT

//Auth::routes();
Route::get('computadorasMonitor', 'TeoricoPcController@computadorasMonitor');
Route::get('verificarAsignacion', 'TeoricoPcController@verificarAsignacion');
//Route::group(['middleware' => 'web' ], function () {
//Auth::routes();
Route::auth();
  Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
      Route::get('computadoras/active', 'TeoricoPcController@isActive');
      Route::resource('computadoras','TeoricoPcController');
      Route::resource('bedel', 'BedelController');
      Route::resource('disposiciones', 'DisposicionesController');
      Route::get('asignar','BedelController@asignarExamen')->name('asignarExamen');
      Route::post('buscarTramite',['uses' => 'TramitesController@buscarTramite','as' => 'buscarTramite']);

      Route::get('reporteSecuenciaInsumos',['uses' => 'ReportesController@reporteSecuenciaInsumos','as' => 'reporteSecuenciaInsumos']);
      Route::get('reporteControlInsumos',['uses' => 'ReportesController@reporteControlInsumos','as' => 'reporteControlInsumos']);
      Route::get('justificar',['uses' => 'ReportesController@justificar','as' => 'justificar']);
      Route::post('justificaciones.store',['uses' => 'ReportesController@justificacionStore','as' => 'justificaciones.store']);
      Route::get('justificaciones.edit/{id}',['uses' => 'ReportesController@justificar','as' => 'justificaciones.edit']);
      Route::get('justificaciones.show/{id}',['uses' => 'ReportesController@mostrarJustificacion','as' => 'justificaciones.show']);

  });
//});
// Route::get('rendir_examen','EtlExamenController@rendir_examen');
Route::resource('examen', 'EtlExamenController');
Route::resource('preguntas', 'EtlExamenPreguntaController');

///var/www/html/teorico/app/Http/Controllers/EtlExamenController.php

Route::group(['prefix' => 'api'], function () {
  Route::get('aviso_pago', 'SoapServerController@index')->name('aviso_pago');
  Route::post('aviso_pago', 'SoapServerController@index')->name('aviso_pago');
});