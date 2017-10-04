<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\WsBoletasSafit;

class SoapServerController extends Controller
{
  /**
   * @var SoapWrapper
   */
  protected $soapWrapper;

  /**
   * SoapController constructor.
   *
   * @param SoapWrapper $soapWrapper
   */
  public function __construct()
  {
	  ini_set("soap.wsdl_cache_enabled", "0"); // disabling WSDL cache
	  ini_set('soap.wsdl_cache_ttl',0);
  }

  /**
   * Use the SoapWrapper
   */
  public function index(Request $request)
  {
	  if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_GET['WSDL']) && !isset($_GET['wsdl']) )
		    dd($_GET);

	  $params = array( 'uri' => url('soaptest') );
    $server = new \SoapServer( '../resources/wsdl/boletas_safit.wsdl', $params);
	  $server->setClass( WsBoletasSafitController::class );
	  $response = new Response();
	  $response->header("Content-Type","text/xml; charset=utf-8");
	  ob_start();
	  $server->handle();
	  $response->setContent(ob_get_clean());
	  return $response;
  }

}
