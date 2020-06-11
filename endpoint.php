<?php

require_once("inc/initialize.php");

// Creates API request object
$Request 								= new Api_Request();

if ( ENVIRONMENT == 'local' ) {
	$Request->setParams(array("local1", "local2", "local3", "version", "service", "method", "key", "token"));
} else {
	$Request->setParams(array("version", "service", "method", "key", "token"));
}

$Request->analyze();

// Load config
$config 								= parse_ini_file("config.ini", true);

// Creates API instance being called
$JensenApi 								= new JensenApi();

// Hooks to modify the behavior and flows of API calls 
// @example Block abusive IPs, set IP API request limits, API credential check, modify parser output
$JensenApi->addHook("JensenApi_Hook_ApiKey", Api_Hook_Base::HOOK_BEFORE_SERVICE_EXECUTE);
$JensenApi->addHook("Api_Hook_BlockIp", Api_Hook_Base::HOOK_BEFORE_SERVICE_EXECUTE);
$JensenApi->addHook("Api_Hook_RequestLimit", Api_Hook_Base::HOOK_BEFORE_SERVICE_EXECUTE);
$JensenApi->addHook("Api_Hook_ParserModify", Api_Hook_Base::HOOK_MODIFY_PARSER);

// Handle api request and catch errors
try {

	$JensenApi->handle($Request, $config);

} catch(Api_Error $error) {

	$Response 							= new Api_Response($error->getCode(), null, $error);

	$JensenApi->send($Response);

}

require_once SITE_PATH . DS . 'close.php';
