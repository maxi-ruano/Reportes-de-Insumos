<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\TramitesAIniciar;
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //Turnos Vencidos Precheck
        $schedule->call('App\Http\Controllers\MicroservicioController@revisarTurnosVencidos')->weekdays()->at('15:00');

        //Enviar a Sinalic los tramites con Precheck completados
        $schedule->call('App\Http\Controllers\MicroservicioController@revisarValidaciones')->weekdays()->at('15:05');//Validaciones Completas
        $schedule->call('App\Http\Controllers\MicroservicioController@enviarTramitesASinalic')->weekdays()->at('15:10');//Turnos a Enviar a Sinalic

        //PRECHECK
        $schedule->call('App\Http\Controllers\MicroservicioController@completarTurnosEnTramitesAIniciar')->weekdays()->at('15:30');//Turnos
        $schedule->call('App\Http\Controllers\MicroservicioController@verificarLibreDeudaDeTramites')->weekdays()->at('15:31');//Libre Deuda
        $schedule->call('App\Http\Controllers\MicroservicioController@completarBoletasEnTramitesAIniciar')->weekdays()->at('15:32');//Buscar Boleta Cenat
        $schedule->call('App\Http\Controllers\MicroservicioController@verificarBuiTramites')->weekdays()->at('15:35');//Bui

	    $schedule->call('App\Http\Controllers\MicroservicioController@emitirBoletasVirtualPago')->weekdays()->at('17:00');//Obtener Certificado Virtual Cenat
	    $schedule->call('App\Http\Controllers\MicroservicioController@revisarValidaciones')->weekdays()->at('17:40');//Validaciones Completas

        //Enviar a Sinalic el resto de los completados
        $schedule->call('App\Http\Controllers\MicroservicioController@enviarTramitesASinalic')->weekdays()->at('18:00');//Turnos a Enviar a Sinalic
        //FIN PRECHECK
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
