<?php

set_time_limit(0); 

include_once("php/include/database.php");

$db = new db;
$db1 = new db;

$log = new loggs;

$log->event('', '===Start 0.5.prepare_ally_stat.php===');

/*

CREATE TABLE `ally_stat` (
	`allianceID` integer(11) default NULL,
	`rank` int(11) default 0,
	`capitals` int(11) default 0,	
	`outposts` int(11) default 0,
	`systems` int(11) default 0,
	`members` int(11) default 0,
	`timestamp` int(11) default 0
) DEFAULT CHARSET=utf8;

ALTER TABLE `ally_stat` ADD INDEX `alliancesID`(`allianceID`, `timestamp`);
*/


$sql_sel = 'SELECT 
		evealliances.name AS name, 
		(select count(solarSystemID) from mapsolarsystems where sovereigntyLevel=4 and allianceID=evealliances.id) AS capitals, 
		evealliances.memberCount AS memberCount, 
		mapsolarsystems.allianceID, 
		sum(mapsolarsystems.stantion) AS sum_station, 
		count(mapsolarsystems.solarSystemID) as count_claim, 
		evealliances.color as AllyColor 
		FROM 
		mapsolarsystems 
		LEFT JOIN 
		evealliances
		ON mapsolarsystems.allianceID=evealliances.id 
		where allianceID>0 AND allianceID<>\''. $Dev_test_ally_id .'\' 
		GROUP BY allianceID 
		ORDER BY sum_station desc, count_claim desc 
		limit 21';


	$db->query($sql_sel);

	$num = $db->num_rows();

	$time_add = time() - 2000;


	if($num > 0){
		
		for($x=1; $x <= $num; $x++){
			$r = $db->fetch_assoc();

			$sql_ins = 'INSERT ally_stat SET rank='. $x .', allianceID=\''. $r['allianceID'] .'\', capitals=\''. $r['capitals'] .'\', outposts=\''. $r['sum_station'] .'\', systems=\''. $r['count_claim']  .'\', members=\''. $r['memberCount']  .'\', timestamp=\''. $time_add .'\'';

$log->event('', $sql_ins);

//echo $sql_ins ."\n\r";
			echo $db1->query($sql_ins);

		}

	}



/*
exit;

foreach ($xml->result[0]->rowset[0]->row as $row) {

	//echo 'name: '. $row['name'] ."\n\r";
	//echo 'memberCount: '. $row['memberCount'] ."\n\r";



		$ally_R = dechex(rand(10, 240));
		$ally_G = dechex(rand(10, 240));
		$ally_B = dechex(rand(10, 240));


		$sql_sel = 'SELECT * FROM evealliances WHERE id='. $row['allianceID'];

		$db->query($sql_sel);

		$num = $db->num_rows();

		if($num > 0){
			$sql1 = 'UPDATE evealliances SET name=\''. $row['name'] .'\', '.
	 			' shortName=\''. $row['shortName'] .'\', memberCount=\''. $row['memberCount'] .'\' WHERE id='. $row['allianceID'];
		}
		else {
			$sql1 = 'INSERT evealliances SET name=\''. $row['name'] .'\', '.
	 			'id=\''. $row['allianceID'] .'\', color=\''. $ally_R .''. $ally_G .''. $ally_B .'\', shortName=\''. $row['shortName'] .'\', memberCount=\''. $row['memberCount'] .'\'';
		}

}

*/

?>