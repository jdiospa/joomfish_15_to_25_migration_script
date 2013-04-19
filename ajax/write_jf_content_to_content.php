<?php
/**
 * (c) 2013 by http://www.keepitnative.ch
 * all rights reserved
 * @file write_jf_content_to_content.php
 * @author ps
 */
require_once ('ajax.php');
require_once ('../classes/migrate_helper.php');

define('REF_TABLE', 'content');

class write_jf_content_to_content extends db {
	/**
	 * @file write_jf_content_to_content.php
	 * @user ps
	 * migrates content translations in jf_content to native joomla content entries
	 */
	/**
	 * @var $counterEntries the sum of migrated entries
	 */
	var $counterEntries = 0;

	public function __construct() {
		parent::__construct();
		$this -> get_records();
	}

	public function get_records() {
		/**
		 * @return void
		 * gets records in order of reference id
		 * and calls group function
		 */
		$data_reform = array();
		$query = "SELECT  *  FROM " . $this -> pfx_before . $this -> table . "` WHERE reference_table= '" . REF_TABLE . "' GROUP BY reference_id ASC, reference_field ASC HAVING COUNT(DISTINCT ";

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

	public function group($recordset, $type = 'content') {
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
				case 'title' :
					$out[$same_ref_id]['title'] = $rows['5'];
					break;
				case 'alias' :
					$out[$same_ref_id]['alias'] = $rows['5'];
					break;
				case 'introtext' :
					$out[$same_ref_id]['introtext'] = $rows['5'];
					break;
				case 'fulltext' :
					$out[$same_ref_id]['fulltext'] = $rows['5'];
					break;
			}
			if (count($recordset) > $line_nr + 1) {//@FIXME don' take last entry
				preg_match('/^(.*?)([0-9])--([0-9])--([0-9])(.*)$/s', $recordset[$line_nr + 1], $matches);
				if (count($matches) >= 3) {//check if theres an text in the jf content record
					$ref_id_next = $matches[4];
				}
			}
			//echo $rows[2]."  ==   ".$ref_id_next." ---- ".$same_ref_id."--------".$rows[4]."-----------"."<br />";
			if ($rows[2] != $ref_id_next) {
				$same_ref_id++;
			}

		}
		for ($nr = 0; $nr < count($out); $nr++) {
			if (array_key_exists('id', $out[$nr])) {
				$id = $out[$nr]['id'];
			}
			if (array_key_exists('lang', $out[$nr])) {
				$lang = $out[$nr]['lang'];
			} else {
			}
			if (array_key_exists('title', $out[$nr])) {
				$title = $out[$nr]['title'];
			}
			if (array_key_exists('alias', $out[$nr])) {
				$alias = $out[$nr]['alias'];
			}
			if (array_key_exists('introtext', $out[$nr])) {
				$introtext = $out[$nr]['introtext'];
			} else {
				$introtext = '';
			}
			if (array_key_exists('fulltext', $out[$nr])) {
				$fulltext = $out[$nr]['fulltext'];
			} else {
				$fulltext = '';
			}

			if ($id != '' && $lang != '' && $title != '' && $alias != '' && ($introtext != '' || $fulltext != '')) {
				$counter++;
				$error = $this -> save_sql($id, $title, $alias, $introtext, $fulltext, $lang);
				//call save function
			}
			if ($error == false) {
				echo '<p class="clear" style="color:rgb(0,128,0);"><span>' . $this -> counterEntries . 'content entries successfully inserted!</span></p><br />';
			} else {
				echo "<p class='clear' style='color:rgb(255,255,255);'><span>Failed to run query: (" . $this -> mysqli -> errno . ") " . $this -> mysqli -> error . "</span></p>";
			}
		}
	}

	public function save_sql($id, $title, $alias, $introtext, $fulltext, $lang) {
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
		if (!empty($title)) {
			$alias = $title;
		}
		if (!empty($alias)) {
			$title = $alias;
		}

		$sql = "INSERT INTO `" . $this -> database . "`.`" . $this -> table_content . "` (`id`, `asset_id`, `title`, `alias`, `title_alias`, `introtext`, `fulltext`, `state`, `sectionid`, `mask`, `catid`, `created`, `created_by`, `created_by_alias`, `modified`, `modified_by`, `checked_out`, `checked_out_time`, `publish_up`, `publish_down`, `images`, `urls`, `attribs`, `version`, `parentid`, `ordering`, `metakey`, `metadesc`, `access`, `hits`, `metadata`, `featured`, `language`, `xreference`) VALUES( '', 0 ,'" . $title . "', 
		  '" . $alias . "', '" . $alias . "', 
		   '" . $introtext . "', '" . $fulltext . "',  0, 0, 0, 0, '0000-00-00 00:00:00',
		    62, 'petestreet', '0000-00-00 00:00:00', 'petestreet', 0,
		     '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 
		     'none', 'none', 'none', 1, 0, 0, 'none', 'none', 0, 0, '', 0, 
		    '" . $lang . "' , 'none'" . ")";
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

//init content migration
new write_jf_content_to_content();
