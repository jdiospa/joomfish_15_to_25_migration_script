<?php
/**
 * (c) 2013 by http://www.keepitnative.ch
 * all rights reserved
 * @file write_jf_modules_to_modules.php
 * @author ps
 */

//change affected table here
define('REF_TABLE', 'modules');

//include mainframe db etc..
require_once ('ajax.php');
require_once ('../classes/migrate_helper.php');

class write_jf_modules_to_modules extends db {
	/**
	 * @file write_jf_modules_to_modules.php
	 * @user ps
	 * migrates module translations in jf_content to native joomla module entries
	 */
	/**
	 * sum of module entries migrated
	 * @var $counterEntries
	 */
	var $counterEntries = 0;

	/**
	 * reorders module records from jf_conent to native joomla 2.5 content table
	 */
	public function __construct() {
		parent::__construct();
		
		//read from config global module type and position
		//@FIXME beta feature fix via php admin module type
		//no info about type and position in jf_contents table
		define('MODULE_TYPE', $this -> module_type);
		define('MODULE_POSITION', $this -> module_position);
		
		$this -> get_records();
	}

	public function get_records() {
		/**
		 * @return void
		 * gets records in order of reference id
		 * and calls group function
		 */
		$data_reform = array();
		$query = "SELECT  *  FROM `" . $this -> pfx_before . $this -> table . "` WHERE reference_table= '" . REF_TABLE . "' GROUP BY reference_id ASC, reference_field ASC";

		if ($result = $this -> mysqli -> query($query)) {

			/* fetch object array */
			while ($row = $result -> fetch_object()) {
				$fields = array($row -> id, $row -> language_id, $row -> reference_id, $row -> reference_table, $row -> reference_field, $row -> value, $row -> original_value, $row -> original_text, $row -> modified, $row -> modified_by, $row -> published);
				foreach ($fields as $k => $v) {
					$fields[$k] = $this -> mysqli -> real_escape_string($v);
				}
				$data_reform[] = implode('--', $fields);
			}
			$this -> group($data_reform);
			//call group function
			/* free result set */
			$result -> close();
		}
		/* close connection */
		$this -> mysqli -> close();
	}

	public function group($recordset) {
		/**
		 * reorder records form 'x' to 'y' axis
		 * and call save_sql function
		 * @return void
		 */
		$out = array();
		$counter = 1;

		$nr_of_same_ref_ids = migrate_helper::get_amount_of_same_ref_id($recordset);
		//get amount of records with same id from helper
		$same_ref_id = 0;
		//get counter for array to have same record through same ref_id
		for ($line_nr = 0; $line_nr < count($recordset); $line_nr++) {//loop trough lines

			$rows = explode("--", $recordset[$line_nr]);
			//get rows of a line
			$out[$same_ref_id]['id'] = $rows[2];
			$out[$same_ref_id]['lang'] = $rows[1];

			switch( $rows[4] ) {
				case 'id' :
					$out[$same_ref_id]['id'] = $rows[2];
					break;
				case 'title' :
					$out[$same_ref_id]['title'] = $rows[5];
					break;
				case 'params' :
					$params = migrate_helper::jsonifyParams($rows[5]);
					$params = $this -> mysqli -> real_escape_string($params);
					$out[$same_ref_id]['params'] = $params;
					break;
				case 'content' :
					$out[$same_ref_id]['content'] = $rows[5];
					break;
			}
			if (count($recordset) > $line_nr + 1) {//@FIXME don' take last entry
				preg_match('/^(.*?)([0-9])--([0-9])--([0-9])(.*)$/s', $recordset[$line_nr + 1], $matches);
			}
			if (count($matches) > 3 ) {
				$ref_id_next = $matches[4];
				//echo $rows[2]."  ==   ".$ref_id_next." ---- ".$same_ref_id."--------".$rows[4]."-----------"."<br />";
				if ($rows[2] != $ref_id_next) {
					$same_ref_id++;
				}
			}
		}
		for ($nr = 0; $nr < count($out); $nr++) {//reorder to output array
			
			if( isset($out[$nr]['title'])) {
				$title = $out[$nr]['title'];
			} else {
				$title = '';
			}
			if( isset($out[$nr]['alias'])) {
				$alias = $out[$nr]['alias'];
			} else {
				$alias = '';
			}
			if( isset($out[$nr]['content'])) {
				$content = $out[$nr]['content'];
			} else {
				$content = '';
			}
			if( isset($out[$nr]['params'])) {
				$params = $out[$nr]['params'];
			} else {
				$params = '';
			}
			if( isset($out[$nr]['lang'])) {
				$lang = $out[$nr]['lang'];
			} else {
				$lang = -1;
			}	
			
			if ($title != '' || $params != '') {
				$counter++;
				$error = $this -> save_sql( $title, $content, $params, $lang);
				//call save function
			}
		}
		if ($error == false) {
			echo '<p class="clear" style="color:rgb(0,128,0);"><span>' . $this -> counterEntries . ' module entries successfully inserted!</span></p><br />';
		} else {
			echo "<p class='clear' style='color:rgb(255,255,255);'><span>Failed to run query: (" . $this -> mysqli -> errno . ") " . $this -> mysqli -> error . "</span></p>";
		}
	}

	public function save_sql( $title, $content, $params, $lang) {
		/**
		 * write reorderd records to content database
		 */
		//alias,  fulltext, id,  introtext, lang, ref_id, title
		switch( $lang ) {
			case 1 :
				$lang = 'en-GB';
				break;
			case 2 :
				$lang = 'de-DE';
				break;
			case 3 :
				$lang = 'fr-FR';
				break;
			case 4 :
				$lang = 'it-IT';
				break;
		}
		$sql = "INSERT INTO `" . $this -> database . "`.`" . $this -> pfx . REF_TABLE . "` (
		`id`, 	`title`,	`note`, 	`content`, 	`ordering`, 	`position`, 	`checked_out`, 	`checked_out_time`, 	`publish_up`, 	`publish_down`, 	`published`, 	`module`, 	`access`, 	`showtitle`, 	`params`, 	`client_id`, 	`language` 
		) VALUES( '','" . $title . "', 
		'','" . $content . "',1,'" . MODULE_POSITION . "',0,'0000-00-00 00:00:00','','',0,'" . MODULE_TYPE . "','1',1,'" . $params . "','','" . $lang . "')";
		$res = mysqli_query($this -> mysqli, $sql);

		if (!$res) {
			return true;
			/*error*/
		} else {
			$this -> counterEntries++;
			return false;
		}
	}

}

//init module migration
new write_jf_modules_to_modules();
