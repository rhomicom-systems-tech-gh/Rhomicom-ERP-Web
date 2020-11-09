<?php

require '../app_code/cmncde/connect_pg.php';

class client {
    public function __construct() {
        global $app_url;
        $params = array('location' => $app_url . 'xchange/SoapServer.php',
            'uri' => 'urn://192.168.56.201/rho/xchange/SoapServer.php',
            'soap_version' => SOAP_1_2,
            'trace' => 1);
        //$this->instance = new SoapClient(NULL, $params);
        $this->instance = new SoapClient($app_url . "xchange/wsdls/RhoSoapWsdl.php", $params);

        //set the header
        $auth_params = new stdClass();
        $auth_params->username = 'richard';
        $auth_params->password = 'root';

        $header_params = new SoapVar($auth_params, SOAP_ENC_OBJECT);
        $header = new SoapHeader('rho', 'authenticate', $header_params, false);
        $this->instance->__setSoapHeaders(array($header));
    }

    public function getName($id_array) {
        $msg = "<h2>List of Available Functions:</h2><br/>";
        $msg .= join(",<br/>", $this->instance->__getFunctions());

        $return = $this->instance->__soapCall("hello", array(" RHO-WORLD!!! "));
        $msg .= "<br/><br/><br/><b>Returned value of SoapCall1:</b> " . $return;
        $return1 = $this->instance->__soapCall("getStudentName", $id_array);
        $msg .= "<br/><b>Returned value of SoapCall2:</b> " . $return1;


        /* $msg .= "<br/><br/><code>Dumping request headers:<br/>" . str_replace("\n", "<br/>", $this->instance->__getLastRequestHeaders()) . "</code>";
          $msg .= "<br/><code>Dumping request:<br/>" . str_replace("\n", "<br/>", $this->instance->__getLastRequest()) . "</code>";
          $msg .= "<br/><code>Dumping response headers:<br/>" . str_replace("\n", "<br/>", $this->instance->__getLastResponseHeaders()) . "</code>";
          $msg .= "<br/><code>Dumping response:<br/>" . str_replace("\n", "<br/>", $this->instance->__getLastResponse()) . "</code>"; */
        return $msg;
    }

}

$client = new client();
$id_array = array('id' => '1');
echo $client->getName($id_array);

//echo "<br/><br/><br/><br/><br/><b>Soap Server URL: </b>" . $app_url . 'xchange/SoapServer.php';
echo "<br/><b>WSDL URL: </b>" . $app_url . "xchange/wsdls/RhoSoapWsdl.php";
echo "<br/><b>TEST CLIENT URL: </b>" . $app_url . "xchange/indexClient.php";
?>