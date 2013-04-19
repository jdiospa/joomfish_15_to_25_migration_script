<?php
/**
 * (c) 2013 by http://www.keepitnative.ch
 * all rights reserved
 * @file bootstrap.php
 * @author ps
 */
if (!defined('MYEXEC' or die('no acceess to this ressource')));

require_once PATH_CLASSES . 'db.php';

class bootstrap extends db {
	public function __construct() {
		parent::__construct();
		$this -> load_helper();
		$this -> load_templates();
		//$this -> write_csv();
		if (isset($_POST['task'])) {
			$task = $_POST['task'];
			$this -> execute($task);
		}
	}

	public function load_helper() {
		require_once PATH_HELPERS . 'helper.php';
	}

	public function load_templates() {
		require_once PATH_TEMPLATE . 'header_template.php';
		require_once PATH_TEMPLATE . 'info_template.php';
		require_once PATH_TEMPLATE . 'terminal_template.php';
		require_once PATH_TEMPLATE . 'db_template.php';
		require_once PATH_TEMPLATE . 'migrate_tables_template.php';
		require_once PATH_TEMPLATE . 'content_template.php';
		require_once PATH_TEMPLATE . 'module_template.php';
		require_once PATH_TEMPLATE . 'menu_template.php';
		require_once PATH_TEMPLATE . 'categories_template.php';
		require_once PATH_TEMPLATE . 'footer_template.php';
	}

	public function __autoload($class) {
		// die bösesten zeichen in klassennamen mal sicherheitshalber verbieten
		if (strpos($class, '.') !== false || strpos($class, '/') !== false || strpos($class, '\\') !== false || strpos($class, ':') !== false) {
			return;
		}
		if (file_exists(PATH_CLASSES . $class . '.php')) {
			include_once PATH_CLASSES . 'migrate_helper.php';

			include_once PATH_CLASSES . $class . '.php';
			new $class;

		}
	}

	public function execute($task = NULL) {
		$class = $task;
		switch ( $task ) {
			case 'write_jf_content_to_content' :
				$this -> __autoload($class);
				break;
			case 'migrate_joomfish_tables' :
				$this -> __autoload($class);
				break;
			case 'write_jf_modules_to_modules' :
				$this -> __autoload($class);
				break;
			default :
				break;
		}
	}

}
