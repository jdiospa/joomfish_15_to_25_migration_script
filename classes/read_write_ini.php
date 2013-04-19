<?php
/**
 * (c) 2013 by http://www.keepitnative.ch
 * all rights reserved
 */
if (!defined('MYEXEC' or die('no acceess to this ressource')));

class read_write_ini {
 	public function read_write_ini() {
 		$this->read_ini();
 	}
	public function read_ini() {
		/**
		 * http://www.php.net/manual/de/function.parse-ini-file.php
		 */
		$ini_array = parse_ini_file(ROOT."config/config.ini", TRUE);
		$this -> host = $ini_array['local']['host'];
		$this -> user = $ini_array['local']['username'];
		$this -> password = $ini_array['local']['password'];
		$this -> database = $ini_array['local']['database'];
		$this -> pfx = $ini_array['local']['db-prefix-new'];
		$this -> table_content = $this -> pfx . $ini_array['local']['table_content'];
		$this -> pfx_before = $ini_array['local']['db-prefix-before'];
		$this -> table = $ini_array['local']['table'];
		$this -> dump_csv = PATH_TEMP . $this -> table;
		
		$this -> module_type = $ini_array['local']['module_type'];
		$this -> module_position = $ini_array['local']['module_position'];
		
	}
 }
