<?php
set_time_limit(10000);	
ini_set("memory_limit","25M"); 
//ini_set('display_errors', 'On');
//error_reporting(E_ALL);
require_once("inc.functions.makes_models.inc.php");
require_once("class.ncsApi.php");
/* http://home.quirkcars.com/api/v2 */
$arr = $_POST;
$aNcs = new ncsApi();
$aNcs->apiDataArr = $arr;
$aNcs->handleRequest();
$response = $aNcs->retXml; 
print $response;
?>
									
