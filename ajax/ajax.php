<?php
/**
 * (c) 2013 by http://www.keepitnative.ch
 * all rights reserved
 * ajax mainframe inclusion
 * ajax/ajax.php
 */

$root = str_replace('ajax', '', dirname(__FILE__));

define('ROOT', $root  . DIRECTORY_SEPARATOR);
define('PATH_CLASSES', ROOT . 'classes' . DIRECTORY_SEPARATOR);
define('PATH_TEMPLATE', ROOT . 'templates' . DIRECTORY_SEPARATOR);
define('PATH_HELPERS', ROOT . 'helpers' . DIRECTORY_SEPARATOR);
define('PATH_CONFIG', ROOT . 'config' . DIRECTORY_SEPARATOR);
define('PATH_TEMP', ROOT . 'tmp' . DIRECTORY_SEPARATOR);

//prevent direct script access
if ($_SERVER['SCRIPT_FILENAME'] == ROOT.'ajax/ajax.php') {
    header('HTTP/1.0 403 Forbidden');
    exit('Forbidden');
}

require_once PATH_CLASSES . 'db.php';