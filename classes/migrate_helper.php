<?php
/**
 * (c) 2013 by http://www.keepitnative.ch
 * all rights reserved
 * migrate_helper.php migration helper class
 */
if (!defined('MYEXEC' or die('no acceess to this ressource')))
	;

class migrate_helper extends db {
	public function __construct() {
		parent::__construct();
	}

	static public function get_amount_of_same_ref_id($recordset) {
		foreach ($recordset as $record) {
			$rows = explode("--", $record);
			$group_id = 0;
			$record_nr = 0;
			$count = 0;
			foreach ($recordset as $record_before) {//gleiche Einträge zählen
				preg_match('/^([0-9])--([0-9])--([0-9])(.*)$/', $record_before, $matches);
				if (count($recordset) <= $record_nr) { //check it's the last entry
					preg_match('/^([0-9])--([0-9])--([0-9])(.*)$/', $recordset[$record_nr + 1], $match_next);
					if (count($matches) > 1 && count($match_next) > 1) { //check if a reference id exists
						if ($matches[2] == $match_next[2]) {
							$count++;
						} else {
							$count++;
						}
					}
				}
				$record_nr++;
			}
			return $count;
		}
	}

	static function jsonifyParams($params = NULL) {
			
		$out = array();
		
		$prms = explode('\n', $params);
		
		foreach ($prms as $value) {
			$arr = explode('=', $value);
			if( count( $arr  ) > 0 && count($out) > 0 ) {
				//make key value pairs
				$out[$arr[0]] = $arr[1];
			}
		}
		$json = json_encode($out);
		//transform to json joomla 2.5 format
		return $json;
	}

}
