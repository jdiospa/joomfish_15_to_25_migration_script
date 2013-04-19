<?php
/**
 * (c) 2013 by http://www.keepitnative.ch
 * all rights reserved
 * http://bigwhoop.ch/artikel/11/2009-03-19/ein-kleiner-ini-file-writerreader-in-php
 * @file ajax/write_conn_data.php
 * @author ps
 */
class write_conn_data {
	/**
	 * @file write_conn_data.php
	 * @user ps
	 * migrates connection settings to ini
	 */	
function __construct($filePath, $data) {
	$this->ini_write($filePath, $data);
}
function ini_write($filePath, array $data_in)
{
    $output = '';
 	$data = array('local' => $data_in );
    foreach ($data as $section => $values)
    {
        //values must be an array
        if (!is_array($values)) {
            continue;
        }
 
        //add section
        $output .= "[$section]\r\n";
 
        //add key/value pairs
        foreach ($values as $key => $val) {
            $output .= $key."=".$val."\r\n";
        }
        $output .= "\r\n";
    }
 
    //write data to file
    $written = FALSE;
    $written = file_put_contents($filePath, trim($output));
	if($written === FALSE ) {
		echo "<span style='color:rgb(255,0,0);'>DB-Connection data could not be written to config ini file...</span><br />";
	} else {
		echo "<span style='color:rgb(0, 128, 0);'>DB-Connection data has been succesfully written to config ini file...</span><br />";
	}
}
}
//get db connection settings from post var
$data = $_POST;
//read path to config file pass it over ajax
$filePath = $_POST['PATH_CONFIG']."config.ini";
//remove task var from post array
unset($data['PATH_CONFIG']);
//init seetings writer async class
new write_conn_data($filePath, $data);
