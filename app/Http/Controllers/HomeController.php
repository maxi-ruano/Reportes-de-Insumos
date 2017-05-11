<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\TeoricoPcController;
use App\Http\Controllers\EtlExamenPreguntaController;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $TeoricoPcController = new TeoricoPcController;
        $active = $TeoricoPcController->isActive($request);
        if ($active[0] != true) {
          //echo "Maquina no habilitada para rendir";
          //print_r($active[1]);
          return view('layouts.block');
        }
        else {
          $EtlExamenPreguntaController = new EtlExamenPreguntaController;
          return $EtlExamenPreguntaController->getPreguntasExamen($active[1]);
        }
        //return view('home');
    }
}
