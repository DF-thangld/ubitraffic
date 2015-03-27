<?php 
require_once('libraries/nusoap/lib/nusoap.php');

$devKey		= "ouluSiriUser135"; 
$password   = "eitoZ1ru"; 
$accountId  = ""; 

// Create the SoapClient instance 
$url         = "http://transitdata.fi/siri/oulu/OuluSiriServices?wsdl"; 
//$client     = new SoapClient($url, array("trace" => 1, "exception" => 0));

$client = new nusoap_client('http://transitdata.fi/siri/oulu/OuluSiriServices?wsdl', 'wsdl',
						"", "", "", "");
$client->setCredentials($devKey,$password,"basic");

//$result = $client->call('GetStopTimetable', array('parameters' => array()), '', '', false, true);

var_dump($client);


?>