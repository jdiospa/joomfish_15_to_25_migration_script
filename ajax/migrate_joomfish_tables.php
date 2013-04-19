<?php
/**
 * (c) 2013 by http://www.keepitnative.ch
 * all rights reserved
 * @file migrate_joomfish_tables.php
 * @author ps
 */

require_once ('ajax.php');
class migrate_joomfish_tables extends db {
	var $result = NULL;
	var $result2 = NULL;

	public function __construct() {
		parent::__construct();
		$addsuccess = false;
		$addsuccess= $this -> createTables();
		if($addsuccess == true ) {
		  $this -> migrate();
		} else {
			echo "<p class='clear' style=\'color:rgb(255,0,0);\'>ERROR: <span>Tables already exist or coudn't be added!</span></p><br />";
		}
	}

	public function createTables() {
		$query = "CREATE TABLE IF NOT EXISTS " . $this -> pfx . "jf_content (
				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `language_id` int(11) NOT NULL DEFAULT '0',
				  `reference_id` int(11) NOT NULL DEFAULT '0',
				  `reference_table` varchar(100) NOT NULL DEFAULT '',
				  `reference_field` varchar(100) NOT NULL DEFAULT '',
				  `value` mediumtext NOT NULL,
				  `original_value` varchar(255) DEFAULT NULL,
				  `original_text` mediumtext NOT NULL,
				  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				  `modified_by` int(11) unsigned NOT NULL DEFAULT '0',
				  `published` tinyint(1) unsigned NOT NULL DEFAULT '1',
				  PRIMARY KEY (`id`),
				  KEY `combo` (`reference_id`,`reference_field`,`reference_table`),
				  KEY `jfContent` (`language_id`,`reference_id`,`reference_table`),
				  KEY `jfContentLanguage` (`reference_id`,`reference_field`,`reference_table`,`language_id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1092";
		if (!$this -> resultcontent = $this -> mysqli -> query($query)) {
			echo '<p style="color:rgb(255,0,0);">ERROR: <span>Error-No:' . $this -> mysqli -> errno . "Mysqli Error:" . $this -> mysqli -> error . '</span></p><br />';
		} else {
			echo '<p style="color:rgb(0,128,0);"><span>Table:' . $this -> pfx . 'jf_content successfully added!</span></p><br />';
			return true;
		}
		$query = "CREATE TABLE IF NOT EXISTS " . $this -> pfx . "jf_tableinfo  (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `joomlatablename` varchar(100) NOT NULL DEFAULT '',
				  `tablepkID` varchar(100) NOT NULL DEFAULT '',
				  PRIMARY KEY (`id`)
				  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29912";
		if (!$this -> resulttableinfo = $this -> mysqli -> query($query)) {
			echo '<p style="color:rgb(255,0,0);">ERROR: <span>Error-No:' . $this -> mysqli -> errno . "Mysqli Error:" . $this -> mysqli -> error . '</span></p><br />';
		} else {
			echo '<p style="color:rgb(0,128,0);"><span>Table:' . $this -> pfx . 'jf_tableinfo successfully added!</span></p><br />';
			return true;
		}
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
			VALUES ('" . implode("','", $fields) . "')";
				$result = $this -> mysqli -> query($insert_query);
				if ($result !== false) {
					echo '<p class="clear" style="color:rgb(0,128,0);"><span>' . $row -> id . '  </span><span>' . $row -> reference_table . '  </span><span>' . $row -> modified . '</span></p><br />';
				} else {
					echo '<p class="clear" style="color:rgb(255,0,0);">ERROR: <span>Error-No:' . $this -> mysqli -> errno . "Mysqli Error:" . $this -> mysqli -> error . '</span></p><br />';
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
					$result3 = $this -> mysqli -> query($insert_query_tableinfo);

					//echo '<p style="color:green"><span>'.$row->id.'  </span><span>'.$row->reference_table.'  </span><span>'.$row->modified.'</span></p><br />';
				}
				echo "<p class='clear' style='color:rgb(0,128,0);'><span>Success:  --- Nr of table info added: $counter</span></p><br />";
			}
			echo '<p class="clear" style="color:rgb(255,0,0);">ERROR: <span>Error-No:' . $this -> mysqli -> errno . "Mysqli Error:" . $this -> mysqli -> error . '</span></p><br />';
		}
	}

}

//init class
new migrate_joomfish_tables();
