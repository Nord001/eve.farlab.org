<?php

set_time_limit(0); 

include_once("php/include/database.php");

$db = new db;

$log = new loggs;

$log->event('', '===Start 0.2.prepare_outpost.php===');

$xml = simplexml_load_file('data/ConquerableStationList.xml.aspx');

if(file_exists('data/ConquerableStationList.xml.aspx') == 0){
	$log->event('ERROR', 'not available ConquerableStationList.xml.aspx');
}



$x=0;


foreach ($xml->result[0]->rowset[0]->row as $row) {

	//echo 'name: '. $row['name'] ."\n\r";
	//echo 'memberCount: '. $row['memberCount'] ."\n\r";



	$sql1 = 'UPDATE mapsolarsystems SET stantion=1 where solarSystemID='. $row['solarSystemID'];




	
	$db->query($sql1);

	if(! ($x % 500)){
		echo $x ."\n\r";
		$log->event('', $x);
	}

	$x++;

}

$log->event('', '===End 0.2.prepare_outpost.php===');

?>