<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SoapClient;

class SoapController extends Controller
{
  public function getClienteSoap(){
    $client = null;
    try {
        $context = stream_context_create(array(
            'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
            )
        ));
        $wsdlUrl = config('global.API_SAFIT');
        $soapClientOptions = array(
                'stream_context' => $context,
                'soap_version' => SOAP_1_2,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'trace' => 1
        );
        $client = new SoapClient($wsdlUrl, $soapClientOptions);
      }
      catch(Exception $e) {
          echo $e->getMessage();
      }
      return $client;
  }
/*
  public function show()
   {
     $this->soapWrapper->add('Currency', function ($service) {
       $service
         ->wsdl(config('global.API_SAFIT'))
         ->trace(true)
         ->header()               // Optional: (parameters: $namespace,$name,$data,$mustunderstand,$actor)
         ->customHeader()         // Optional: (parameters: $customerHeader) Use this to add a custom SoapHeader or extended class
         ->cookie()               // Optional: (parameters: $name,$value)
         ->location()             // Optional: (parameter: $location)
         ->certificate()          // Optional: (parameter: $certLocation)
         ->cache(WSDL_CACHE_NONE)
         ->classmap([
           GetConsultarTramite::class,
         ]);
     });

     // With classmap
     $response = $this->soapWrapper->call('Currency.GetConversionAmount', [
       new GetConsultarTramite('35124321', 1, 'm')
     ]);

     var_dump($response);
     exit;
   }
*/
}
