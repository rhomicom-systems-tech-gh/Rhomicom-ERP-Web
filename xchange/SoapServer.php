<?php

require '../app_code/cmncde/connect_pg.php';

class server {

    public static function authenticate($header_params) {
        if ($header_params->username == 'richard' && $header_params->password == 'root') {
            return true;
        } else {
            throw new SoapFault('Wrong user/pass combination', 401);
        }
    }

    public function __construct() {
        
    }

    function getStudentName($id_array) {
        return 'Sam' . $id_array;
    }

    function hello($someone) {
        return "Hello " . $someone . "! - With WSDL";
    }

}

ini_set("soap.wsdl_cache_enabled", "0");
		
$view = isset($_GET["view"])?$_GET["view"]:"";

$params = array('uri' => $app_url . 'xchange/SoapServer.php',
    'cache_wsdl' => WSDL_CACHE_NONE,
    'soap_version' => SOAP_1_2);
//$server = new SoapServer(NULL, $params);
$server = new SoapServer($app_url . '/xchange/wsdls/RhoSoapWsdl.php', $params);
$server->setClass('server');
//$server->addFunction("hello"); 
$server->handle();
?>