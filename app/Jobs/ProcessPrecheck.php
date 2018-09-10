<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\Controllers\TramitesAInicarController;
use App\TramitesHabilitados;
use App\Http\Controllers\Controller;

class ProcessPrecheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $tramitesHabilitado;
    public $tries = 1;

    public function __construct($tramiteshabilitados)
    {
        $this->tramitesHabilitado = $tramiteshabilitados;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

            $controller = new Controller();
            $controller->crearConstantes();

            $t = TramitesHabilitados::find($this->tramitesHabilitado->id);
            \Log::info('['.date('h:i:s').'] '.'Se inicio el proceso del tramite habilitado ID: '.$t->id);
            $tramite = new TramitesAInicarController();
            $tramite->iniciarTramiteEnPrecheck($t);
            \Log::info('['.date('h:i:s').'] '.'Se finalizo el proceso del tramite habilitado ID: '.$t->id);
        } catch (\Exception $e) {
            \Log::warning('['.date('h:i:s').'] '.'Ocurrio un problema con el tramite habilitado ID: '.$t->id.' Error: '.$e->getMessage());
        }        
        
    }
}
