<?php
/**
 * Set the environment you would like to use.
 *
 * production 	= should point to live site.
 * 				  (https://andrewmichaeljensen.com)
 * demo 		= live demo site
 * 				  (https://demo.1.andrewmichaeljensen.com)
 * qa 			= live qa site
 * 				  (https://qa.1.andrewmichaeljensen.com)
 * dev 			= live dev environment.
 * 				  (https://dev.1.andrewmichaeljensen.com)
 * local 		= synchronized local environement.
 *
 *
 */

defined('ENVIRONMENT') ? null : define('ENVIRONMENT', 'local'); // production | demo | qa | dev | local

//set this 
$_CONN_BYPASS = true;

// DIRECTORY_SEPARATOR is a PHP pre-defined constant
// (\ for Windows, / for Unix)
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

error_reporting(1);

if ( ENVIRONMENT == 'production' ) {

	require_once(DS.'var'.DS.'www'.DS.'shared'.DS.'inc'.DS.'initialize.php');

/**
 * local should point to your local environment directories.
 * You can disable error handling if desired.
 */
} elseif ( ENVIRONMENT == 'local' ) {

	//enable this if you would like to get errors and notices in browser
	ini_set('display_errors',1);
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

	require_once('C:'.DS.'xampp'.DS.'htdocs'.DS.'github'.DS.'backend-framework'.DS.'initialize.php');

} else {

	require_once(DS.'var'.DS.'www'.DS.'shared'.DS.'inc_'.ENVIRONMENT.DS.'initialize.php');

}

/**
 * Access point for all API calls
 */
set_include_path('.' . PATH_SEPARATOR . './library/JensenApi' . PATH_SEPARATOR . './library/Api');

/**
 * Construct paths and other resources
 */
$current_dir 						= str_replace('\\', '/', dirname(__FILE__));
$last_slash_pos 					= strrpos($current_dir, '/');

defined('PATH') 					? null : define('PATH', substr($current_dir, 0, $last_slash_pos) . DS . 'library');
defined('PATH_API') 				? null : define('PATH_API', PATH . DS.'Api');
defined('PATH_API_HOOK') 			? null : define('PATH_API_HOOK', PATH_API . DS.'Hook');
defined('PATH_API_SERVICE') 		? null : define('PATH_API_SERVICE', PATH_API . DS.'Service');
defined('PATH_API_PARSER') 			? null : define('PATH_API_PARSER', PATH_API . DS.'Parser');

include("library/JensenApi/JensenApi.php");
include("library/Json/Encoder.php");
