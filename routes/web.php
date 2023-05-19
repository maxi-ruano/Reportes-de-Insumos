<?php
use App\Http\Controllers\InsumosController;
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

//LOGIN
Auth::routes();
Route::get('/', function (){ return redirect('/login'); });

Route::group( ['middleware' => ['auth']], function() {
  Route::get('/home', 'HomeController@index')->name('home');
  Route::resource('users', 'UserController');
  Route::resource('roles', 'RoleController');
  Route::get('/changePassword','Auth\ResetPasswordController@showChangePasswordForm');
  Route::post('/changePassword','Auth\ResetPasswordController@changePassword')->name('changePassword');
  Route::get('activarCuentaUser', ['uses' => 'UserController@activar','as' => 'UserController.activar']);

  //TRAMITES HABILITADOS
  Route::resource('tramitesHabilitados','TramitesHabilitadosController');
  Route::get('tramitesHabilitadosHabilitar', ['uses' => 'TramitesHabilitadosController@habilitar','as' => 'tramitesHabilitados.habilitar']);
  Route::get('buscarDatosPersonales', 'TramitesHabilitadosController@buscarDatosPersonales');
  Route::get('consultarUltimoTurno', 'TramitesHabilitadosController@consultarUltimoTurno');
  Route::get('consultarTurnoSigeci', 'TramitesHabilitadosController@consultarTurnoSigeci');
  Route::get('consultarTramite', 'TramitesController@consultarTramite');
  Route::get('consultarUniversoReimpresion', 'TramitesHabilitadosController@consultarUniversoReimpresion');
  
  //TRAMITES HABILITADOS MOTIVOS
  Route::resource('tramitesHabilitadosMotivos','TramitesHabilitadosMotivosController');
  Route::get('tramitesHabilitadosMotivosHabilitar', ['uses' => 'TramitesHabilitadosMotivosController@habilitar','as' => 'tramitesHabilitadosMotivos.habilitar']);
  Route::resource('roleMotivos','RoleMotivosController');

  //MODIFICACIONES DEL SISTEMA
  Route::resource('precheck', 'PreCheckController');
  Route::get('anularPreCheckComprobante','PreCheckController@anularPrecheckComprobante')->name('anularPrecheckComprobante');
  Route::get('anularPreCheckCenat','PreCheckController@anular_cenat')->name('anular_cenat');
  Route::get('anularPreCheckSinalic','PreCheckController@anular_sinalic')->name('anular_sinalic');
  Route::get('listado_teoicopc','TeoricoPcController@getTeoricoPc')->name('getTeoricoPc');
  Route::get('cambiarPcTeorico','TeoricoPcController@cambiarPcTeorico')->name('cambiarPcTeorico');
  Route::get('desactivarPcTeorico','TeoricoPcController@desactivarPcTeorico')->name('desactivarPcTeorico');
  Route::resource('examen', 'EtlExamenController');
  Route::get('listado_examenes','EtlExamenController@getExamenes')->name('getExamenes');
  Route::get('anular_examen','EtlExamenController@anular')->name('anular');

});

//DASHBOARD
Route::get('consultaDashboard', ['uses' => 'DashboardController@consultaDashboard','as' => 'consultaDashboard']);
Route::get('consultaDashboardGraf', ['uses' => 'DashboardController@consultaDashboardGraf','as' => 'consultaDashboardGraf']);
Route::get('comparacionPrecheck', ['uses' => 'DashboardController@comparacionPrecheck','as' => 'comparacionPrecheck']);
Route::get('consultaTurnosEnEspera', ['uses' => 'DashboardController@consultaTurnosEnEspera','as' => 'consultaTurnosEnEspera']);
Route::get('consultaTurnosEnEsperaPorSucursal', ['uses' => 'DashboardController@consultaTurnosEnEsperaPorSucursal','as' => 'consultaTurnosEnEsperaPorSucursal']);
//end DASHBOARD

//PRECHECK
Route::get('run', 'MicroservicioController@run');
Route::get('runPrecheck','MicroservicioController@runPrecheck')->name('runPrecheck');
Route::get('actualizarPaseATurno', 'PreCheckController@actualizarPaseATurno');
Route::get('generarCenatPrecheck','TramitesAInicarController@generarCenatPrecheck')->name('generarCenatPrecheck');


Route::get('stdReimpresiones','MicroservicioController@tramitesReimpresionStd')->name('stdReimpresiones');


//end PRECHECK

//SAFIT
//Route::get('buscarBoletaPago', 'TramitesAInicarController@buscarBoletaPago');
Route::post('consultarCenat',['uses' => 'TramitesAInicarController@consultarCenat','as' => 'consultarCenat']);
Route::post('generarCenat', ['uses' => 'TramitesAInicarController@generarCenat','as' => 'generarCenat']);
Route::get('checkPreCheck', ['uses' => 'PreCheckController@checkPreCheck','as' => 'checkPreCheck']);
Route::get('consultarPreCheck', ['uses' => 'PreCheckController@consultarPreCheck','as' => 'consultarPreCheck']);
Route::get('buscarTramitesPrecheck', ['uses' => 'PreCheckController@buscarTramitesPrecheck','as' => 'buscarTramitesPrecheck']);
Route::get('testCheckBoletas', ['uses' => 'TramitesAInicarController@testCheckBoletas','as' => 'testCheckBoletas']);
Route::get('buscarBoletaPagoPersona', 'TramitesAInicarController@buscarBoletaPagoPersona');
Route::post('buscarBoletaPagoPersona',['uses' => 'TramitesAInicarController@buscarBoletaPagoPersona','as' => 'buscarBoletaPagoPersona']);
//END SAFIT

Route::group(['prefix' => 'api'], function () {
  Route::get('aviso_pago', 'SoapServerController@index')->name('aviso_pago');
  Route::post('aviso_pago', 'SoapServerController@index')->name('aviso_pago');
});

//LOTES 
Route::get('reporteControlInsumos2',['uses' => 'ReportesController2@reporteControlInsumos2','as' => 'reporteControlInsumos2']);

//EXCELL
Route::get('/exportar-insumos', 'ReportesController2@exportarExcel')->name('exportar.insumos');
Route::get('/reporte/control-insumos', 'ReportesController2@reporteControlInsumos2')->name('reporte.control.insumos');