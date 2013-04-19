<?php
/**
 * (c) 2013 by http://www.keepitnative.ch
 * all rights reserved
 * db.php class main
 */
if (!defined('MYEXEC' or die('no acceess to this ressource')));
 
require_once PATH_CLASSES.'read_write_ini.php';
class db extends read_write_ini {
	
	var $mysqli = NULL;
	var $host = NULL;
	var $user = NULL;
	var $password = NULL;
	var $database = NULL;
	var $table = NULL;
	var $pfx = NULL;
	var $pfx_before = NULL;
	var $dump_csv = NULL;
	
	public function __construct() {
		parent::__construct();
		
		$this -> read_ini();
		/**
		* http://www.php.net/manual/de/mysqli.quickstart.dual-interface.php
		*/
		$this -> mysqli = new mysqli($this -> host, $this -> user, $this -> password, $this -> database);
		if ($this -> mysqli -> connect_errno) {
			echo "Failed to connect to MySQL: " . $this -> $mysqli -> connect_error;
		}
	}
}
