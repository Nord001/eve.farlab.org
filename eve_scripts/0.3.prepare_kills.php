<?php
set_time_limit(0); 

include_once("php/include/database.php");

/*

CREATE TABLE `kills` (
	`solarSystemID` int(11) default NULL,
	`shipKills` int(11) default 0,
	`factionKills` int(11) default 0,
	`podKills` int(11) default 0,
	`timestamp` int(11) default 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


*/

$db = new db;

$log = new loggs;

$log->event('', '===Start 0.3.prepare_kills.php===');


if(file_exists('data/Kills.xml.aspx') == 0){
	$log->event('ERROR', 'not available Kills.xml.aspx');
}

//readfile('data/Kills.xml.aspx');
$xml = simplexml_load_file('data/Kills.xml.aspx');



//var_dump($xml);

//echo $xml;

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

$sql_sel = 'SELECT * FROM kills where timestamp=\''. $timestamp .'\' limit 5';

$log->event('', $sql_sel);

$db->query($sql_sel);

if($db->num_rows == 0){

	foreach ($xml->result[0]->rowset[0]->row as $row) {

		//echo 'name: '. $row['name'] ."\n\r";
		//echo 'memberCount: '. $row['memberCount'] ."\n\r";


		$sql_select_nullsec_solar = 'SELECT * FROM mapsolarsystems WHERE security > 0 and solarSystemID=\''. $row['solarSystemID'] .'\'';

		$db->query($sql_select_nullsec_solar);

		$num_select_nullsec_solar = $db->num_rows();

		//проверяем данные относятся к нулям
		if($num_select_nullsec_solar == 0){
			//добавляем только более 0 килов в час
			//if($row['shipKills'] > 0){
				$sql1 = 'INSERT INTO kills SET solarSystemID=\''. $row['solarSystemID'] .'\', shipKills=\''. $row['shipKills'] .'\', factionKills=\''. $row['factionKills'] .'\', podKills=\''. $row['podKills'] .'\', timestamp=(select UNIX_TIMESTAMP())';

				$db->query($sql1);
			//}
		}
	}
}

//delete old data
$sql_del = 'DELETE FROM kills WHERE timestamp<(select UNIX_TIMESTAMP() - 86400)';
$db->query($sql_del);


$log->event('', '===End 0.3.prepare_kills.php===');


?>