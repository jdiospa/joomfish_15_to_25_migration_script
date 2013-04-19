<?php
/**
 * (c) 2013 by http://www.keepitnative.ch
 * all rights reserved
 * migrate_joomfish_tables.php
 */
if (!defined('MYEXEC' or die('no acceess to this ressource')));
?>
class migrate_joomfish_tables extends db {
	var $result = NULL;
	var $result2 = NULL;
	
	public function __construct() {
		parent::__construct();
		$this -> migrate();
	}

	public function migrate() {
		$query = "SELECT * FROM " . $this -> pfx_before . "jf_content ORDER by id";
		$counter = 0;
		$content = 0;
		if ($this -> result = $this -> mysqli -> query($query)) {
			/* fetch object array */
			while ($row = $this -> result -> fetch_object()) {
				$counter++;
				if ($row -> reference_table == 'content') {
					$content++;
				}
				//$insert_query = "INSERT INTO ".$this -> pfx."jf_content VALUES (".$row->id.";'".$row->language_id."';'".$row->reference_id."';'".$row->reference_table."';'".$row->reference_field."';'".$row->value."';'".$row->original_value."';'".$row->original_text."';'".$row->modified."';'".$row->modified_by."';'".$row->published."';)";

				$fields = array($row -> id, $row -> language_id, $row -> reference_id, $row -> reference_table, $row -> reference_field, $row -> value, $row -> original_value, $row -> original_text, $row -> modified, $row -> modified_by, $row -> published);
				foreach ($fields as $k => $v) {
					$fields[$k] = $this -> mysqli -> real_escape_string($v);
				}
				$insert_query = "
			INSERT INTO " . $this -> pfx . "jf_content
			VALUES ('".implode("','",$fields)."')";
				$result = $this -> mysqli->query($insert_query);
				if ($result !== false) {
					echo '<p style="color:green"><span>' . $row -> id . '  </span><span>' . $row -> reference_table . '  </span><span>' . $row -> modified . '</span></p><br />';
				} else {
					echo '<p style="color:red">ERROR: <span>' . $row -> id . '  </span><span>' . $row -> reference_table . '  </span><span>' . $row -> modified . '</span></p><br />';
				}
			}
			$content = floor($content / 6) - 10;
			echo 'Total: ' . $counter . 'Rows, estimated content entries, about:' . $content;
		}
		//jos_jf_tableinfo
		$counter = 0;
		$query_tableinfo = "SELECT * FROM " . $this -> pfx_before . "jf_tableinfo ORDER by id";
		if ($this -> result2 = $this -> mysqli -> query($query_tableinfo)) {

			/* fetch object array */

			if ($this -> result2) {
				while ($row = $this -> result2 -> fetch_object()) {
					$counter++;
					$insert_query_tableinfo = "INSERT INTO " . $this -> pfx . "jf_tableinfo VALUES ('" . $row -> id . "','" . $row -> joomlatablename . "','" . $row -> tablepkID . "');";
					$result3 = $this->mysqli->query($insert_query_tableinfo);
					
					//echo '<p style="color:green"><span>'.$row->id.'  </span><span>'.$row->reference_table.'  </span><span>'.$row->modified.'</span></p><br />';
				}
				echo " --- Nr of table info added: $counter";
			}else {
				echo '<p style="color:red">ERROR: <span>' . $row -> id . '  </span><span>' . $row -> joomlatablename . '  </span><span>' . $row -> tablepkID . '</span></p><br />';
			}
		}
	}

}
