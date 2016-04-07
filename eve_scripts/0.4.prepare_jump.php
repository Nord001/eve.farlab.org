<?php

set_time_limit(0); 

include_once("php/include/database.php");

/*

CREATE TABLE `jumps` (
	`solarSystemID` int(11) default NULL,
	`shipJumps` int(11) default 0,
	`timestamp` int(11) default 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


*/

$db = new db;

$log = new loggs;

$log->event('', '===Start 0.4.prepare_jump.php===');

$xml = simplexml_load_file('data/Jumps.xml.aspx');

if(file_exists('data/Jumps.xml.aspx') == 0){
	$log->event('ERROR', 'not available Jumps.xml.aspx');
}




$x=0;

$dd = $xml->result[0]->dataTime;

$log->event('', $dd);



//convert to timestamp. Example: 2008-09-19 12:17:57

$d_mass = explode(' ', $dd);

$d_mass1 = explode('-', $d_mass[0]);

$year = $d_mass1[0];
$month = $d_mass1[1];
$day = $d_mass1[2];

$d_mass2 = explode(':', $d_mass[1]);

$hour = $d_mass2[0];
$minute = $d_mass2[1];
$second = $d_mass2[2];

$timestamp = mktime ($hour, $minute, $second, $month, $day , $year );


//data uploaded?

$sql_sel = 'SELECT * FROM jumps where timestamp=\''. $timestamp .'\' limit 5';

$db->query($sql_sel);

$log->event('', $sql_sel);



if($db->num_rows == 0){

	foreach ($xml->result[0]->rowset[0]->row as $row) {

		$sql_select_nullsec_solar = 'SELECT * FROM mapsolarsystems WHERE security > 0 and solarSystemID=\''. $row['solarSystemID'] .'\'';

		$db->query($sql_select_nullsec_solar);

		$num_select_nullsec_solar = $db->num_rows();

		//проверяем данные относятся к нулям
		if($num_select_nullsec_solar == 0){


			if($row['shipJumps'] > 10){
				$sql1 = 'INSERT INTO jumps SET solarSystemID=\''. $row['solarSystemID'] .'\', shipJumps=\''. $row['shipJumps'] .'\', timestamp=(select UNIX_TIMESTAMP())';


				$db->query($sql1);
			}
		}
	}
}

//delete old data

$sql_del = 'DELETE FROM jumps where timestamp<(select UNIX_TIMESTAMP() - 86400)';

$db->query($sql_del);

$log->event('', '===End 0.4.prepare_jump.php===');

?>