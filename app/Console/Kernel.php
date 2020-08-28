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
        //Turnos vencidos
        $schedule->call('App\Http\Controllers\MicroservicioController@revisarTurnosVencidos')->weekdays()->at('16:30');
        //Validaciones Completas
        //$schedule->call('App\Http\Controllers\MicroservicioController@revisarValidaciones')->weekdays()->at('16:55');

        //PRECHECK
        $schedule->call('App\Http\Controllers\MicroservicioController@completarTurnosEnTramitesAIniciar')->weekdays()->at('16:40');//Turnos
        $schedule->call('App\Http\Controllers\MicroservicioController@verificarLibreDeudaDeTramites')->weekdays()->at('17:15');//Libre Deuda
        $schedule->call('App\Http\Controllers\MicroservicioController@verificarBuiTramites')->weekdays()->at('17:20');//Bui
        $schedule->call('App\Http\Controllers\MicroservicioController@completarBoletasEnTramitesAIniciar')->weekdays()->at('17:10');//Buscar Boleta Cenat
        $schedule->call('App\Http\Controllers\MicroservicioController@emitirBoletasVirtualPago')->weekdays()->at('17:30');//Obtener Certificado Virtual Cenat
        
        //Revisar validaciones completas e iniciar en Sinalic
	    $schedule->call('App\Http\Controllers\MicroservicioController@revisarValidaciones')->weekdays()->at('18:00');//Validaciones Completas
        $schedule->call('App\Http\Controllers\MicroservicioController@enviarTramitesASinalic')->weekdays()->at('18:10');//Turnos a Enviar a Sinalic
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
