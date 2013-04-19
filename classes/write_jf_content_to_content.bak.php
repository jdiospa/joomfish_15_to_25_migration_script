<?php
/**
 * (c) 2013 by http://www.keepitnative.ch
 * all rights reserved
 * write_jf_content_to_content.bak.php
 */
if (!defined('MYEXEC' or die('no acceess to this ressource')));
define('REF_TABLE', 'content');
class write_jf_content_to_content extends db {
	public function __construct() {
		parent::__construct();
		$this -> write_csv();
	}

	public function get_amount_of_same_ref_id($lines) {
		foreach ($lines as $csv_line) {
			$rows = explode(",,", $csv_line);
			$count = array();
			$group_id = 0;
			$line_nr = 0;
			foreach ($lines as $csv_line) {//gleiche Einträge zählen
				preg_match('/^([0-9]),,([0-9]),,([0-9])(.*)$/', $csv_line, $matches);
				preg_match('/^([0-9]),,([0-9]),,([0-9])(.*)$/', $lines[$line_nr + 1], $match_next);
				if ($matches[2] == $match_next[2]) {
					$count[$group_id] += 1;
				} else {
					$group_id++;
				}
				$line_nr++;
			}
			return count($count);
		}
	}

	public function write_csv() {
		/**
		 * http://www.php-mysql-tutorial.com/wikis/mysql-tutorials/using-php-to-backup-mysql-databases.aspx
		 */
		$csv_output = '';
		$query = "SELECT  id,language_id,reference_id,reference_table,reference_field,value FROM " . $this -> pfx_before . $this -> table . "` WHERE reference_table= '" . REF_TABLE . "' GROUP BY reference_id ASC, reference_field ASC HAVING COUNT(DISTINCT ";
		//echo $query;exit;

		if ($res = $this -> mysqli -> query($query)) {
			if (!$res) {
				echo "Failed to run query: (" . $this -> mysqli -> errno . ") " . $this -> mysqli -> error;
			}
			/* fetch object array */
			while ($row = $res -> fetch_row()) {
				$csv_output .= trim($row[0]) . ',,' . trim($row[1]) . ',,' . trim($row[2]) . ',,' . trim($row[3]) . ',,' . trim($row[4]) . ',,' . $row[5] . ',' . trim($row[6]) . ',,' . trim($row[7]) . ',,' . trim($row[8]) . ',,' . trim($row[9]) . ',,' . trim($row[10]) . "\n";
			}

			/* free result set */
			$res -> close();
		}
		$csv_output = utf8_encode($csv_output);
		$fp = fopen($this -> dump_csv . '.csv', 'w');
		fwrite($fp, $csv_output);
		fclose($fp);
		$this -> read_lines();
	}

	public function group_same_ref_id($csv_line) {

		for ($line_nr = 0; $line_nr < count($csv_line); $line_nr++) {
			if ($csv_line[$line_nr] == '\r') {
				unset($csv_line[$line_nr]);
			}
		}
		$out = array();
		$nr_of_same_ref_ids = $this -> get_amount_of_same_ref_id($csv_line);
		$same_ref_id = 0;
		//get counter for array to have same record through same ref_id
		for ($line_nr = 0; $line_nr < count($csv_line); $line_nr++) {//loop trough lines
			//amount of entries with the same reference_id

			$rows = explode(",,", $csv_line[$line_nr]);
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
			preg_match('/^(.*?)([0-9]),,([0-9]),,([0-9])(.*)$/s', $csv_line[$line_nr + 1], $matches);
			$ref_id_next = $matches[4];
			//echo $rows[2]."  ==   ".$ref_id_next." ---- ".$same_ref_id."--------".$rows[4]."-----------"."<br />";
			if ($rows[2] != $ref_id_next) {
				$same_ref_id++;
			}

		}
		for ($nr = 0; $nr < count($out); $nr++) {
			$id = $out[$nr]['id'];
			$lang = $out[$nr]['lang'];
			$title = $out[$nr]['title'];
			$alias = $out[$nr]['alias'];
			$introtext = $out[$nr]['introtext'];
			$fulltext = $out[$nr]['fulltext'];
			if ($id != '' && $lang != '' && $title != '' && $alias != '' && ($introtext != '' || $fulltext != '')) {
				$counter++;
				$error = $this -> save_sql($id, $title, $alias, $introtext, $fulltext, $lang);
			}
		}
		if ($error == '') {
			echo "<div class='success-msg' style='background:green;color:white;padding:20px;width:370px;display:block;'>" . $counter . " Datens&auml;tze erfolgreich eingef&uuml;gt!</div>";
		} else {
			echo "<div class='success-msg' style='background:red;color:white;padding:20px;width:370px;display:block;'>" . $error . "</div>";
		}
	}

	public function save_sql($id, $title, $alias, $introtext, $fulltext, $lang) {

		if (empty($title)) {
			$title = '';
		} elseif (empty($alias)) {
			$alias = '';
		} elseif (empty($introtext)) {
			$introtext = '';
		} elseif (empty($fulltext)) {
			$fulltext = '';
		} elseif (empty($lang)) {
			$lang = '';
		}

		//alias,  fulltext, id,  introtext, lang, ref_id, title
		switch( $this -> string_sanitize($lang) ) {
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
		$title = htmlentities($title, ENT_QUOTES);
		$alias = htmlentities($alias, ENT_QUOTES);
		$introtext = htmlentities($introtext, ENT_QUOTES);
		$fulltext = htmlentities($fulltext, ENT_QUOTES);

		$sql = "INSERT INTO `" . $this -> database . "`.`" . $this -> table_content . "` (`id`, `asset_id`, `title`, `alias`, `title_alias`, `introtext`, `fulltext`, `state`, `sectionid`, `mask`, `catid`, `created`, `created_by`, `created_by_alias`, `modified`, `modified_by`, `checked_out`, `checked_out_time`, `publish_up`, `publish_down`, `images`, `urls`, `attribs`, `version`, `parentid`, `ordering`, `metakey`, `metadesc`, `access`, `hits`, `metadata`, `featured`, `language`, `xreference`) VALUES( '', 0 ,'" . $title . "', 
		  '" . $alias . "', '" . $alias . "', 
		   '" . $introtext . "', '" . $fulltext . "',  0, 0, 0, 0, '0000-00-00 00:00:00',
		    62, 'petestreet', '0000-00-00 00:00:00', 'petestreet', 0,
		     '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 
		     'none', 'none', 'none', 1, 0, 0, 'none', 'none', 0, 0, '', 0, 
		    '" . $lang . "' , 'none'" . ")";
		//@TODO peter uncomment to get working $res = mysqli_query($this -> mysqli, $sql);
		if (!$res) {
			//return "Failed to run query: (" . $this -> mysqli -> errno . ") " . $this -> mysqli -> error;
		}
	}

	public function read_lines() {
		/**
		 * csv umschreiben/zusammensetzen
		 */
		$csv = file_get_contents($this -> dump_csv . '.csv');
		$lines = explode("\n", $csv);
		$out = array();
		$line_nr = 0;
		$this -> group_same_ref_id($lines);
	}

	public function string_sanitize($s) {
		$result = preg_replace("/[^a-zA-Z0-9]+/", "", html_entity_decode($s, ENT_QUOTES));
		return $result;
	}

}
