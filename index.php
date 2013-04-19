<?php
/**
 * (c) 2013 by http://www.keepitnative.ch
 * all rights reserved
 * @file index.php
 * @author ps
 * www.electrictoolbox.com/php-set-content-encoding-type-header/
 * http://stackoverflow.com/questions/409496/prevent-direct-access-to-a-php-include-file
 * header('Content-Type: text/html; charset=iso-8859-1');
 */

date_default_timezone_set('Europe/Zurich'); 

header('Content-Type: text/html; charset=utf-8');

ini_set('memory_limit', '5120M');
set_time_limit(0);

define('DEBUG', false);

if ( DEBUG == true ) {
	//error_reporting(1);
	ini_set('error_reporting','E_ALL & ~E_NOTICE');
	ini_set('display_errors', 'ON');
} else {
	error_reporting(0);
	ini_set('display_errors', 'OFF');
}

//prevent direct call of files
define( 'MYEXEC', true );

define('ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('PATH_CLASSES', ROOT . 'classes' . DIRECTORY_SEPARATOR);
define('PATH_TEMPLATE', ROOT . 'templates' . DIRECTORY_SEPARATOR);
define('PATH_HELPERS', ROOT . 'helpers' . DIRECTORY_SEPARATOR);
define('PATH_CONFIG', ROOT . 'config' . DIRECTORY_SEPARATOR);
define('PATH_TEMP', ROOT . 'tmp' . DIRECTORY_SEPARATOR);

$protocol = strtolower(substr($_SERVER['SERVER_PROTOCOL'], 0, strlen($_SERVER['SERVER_PROTOCOL']) - 3));
$folder = substr($_SERVER['REQUEST_URI'], 0, strlen($_SERVER['REQUEST_URI']) - 9);
$url = $protocol . "/" . $_SERVER['HTTP_HOST'] . $folder;
define('BASE_URL', $url);

	require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'bootstrap.php';
	new bootstrap();
?>