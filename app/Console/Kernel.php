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
        //PRECHECK
        $schedule->call('App\Http\Controllers\MicroservicioController@completarTurnosEnTramitesAIniciar')->weekdays()->at('15:15');//Turnos
        $schedule->call('App\Http\Controllers\MicroservicioController@verificarLibreDeudaDeTramites')->weekdays()->at('15:30');//Libre Deuda
        $schedule->call('App\Http\Controllers\MicroservicioController@completarBoletasEnTramitesAIniciar')->weekdays()->at('15:30');//Buscar Boleta Cenat
        $schedule->call('App\Http\Controllers\MicroservicioController@emitirBoletasVirtualPago')->weekdays()->at('16:30');//Obtener Certificado Virtual Cenat
        $schedule->call('App\Http\Controllers\MicroservicioController@emitirBoletasVirtualPago')->weekdays()->at('18:00');//Obtener Certificado Virtual Cenat
        $schedule->call('App\Http\Controllers\MicroservicioController@verificarBuiTramites')->weekdays()->at('15:30');//Bui
        $schedule->call('App\Http\Controllers\MicroservicioController@revisarValidaciones')->weekdays()->at('15:30');//Turnos Vencidos
        //FIN PRECHECK

        //Turnos Vencidos Precheck
        $schedule->call('App\Http\Controllers\TramitesAIniciarController@revisarTurnosVencidos')->weekdays()->at('16:30');         
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
