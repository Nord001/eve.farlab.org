<?php
set_time_limit(0);


include_once("php/include/database.php");

$db = new db;

$db1 = new db;

$log = new loggs;

$log->event('', '===Start 0.7.agregate_kills_jumps.php===');


//очищаем таблицу
//$sql_sel = 'DELETE FROM solarsystem_jumps_kills';

//$db->query($sql_sel);


//очищаем таблицу
$sql_exec_func = 'SELECT agregate_kills_jumps() AS res';

$db->query($sql_exec_func);



$r = $db->fetch_assoc();

echo 'rows_insert: '. $r["res"] ."\n";


$log->event('', 'rows_insert: '. $r["res"]);


$log->event('', '===end 0.7.agregate_kills_jumps.php===');


/*
//запрашиваем все системы нулей
$sql_sel = 'SELECT solarSystemID, solarSystemName, 
(select sum(shipJumps) from jumps where solarSystemID=mapsolarsystems.solarSystemID and timestamp>=(select UNIX_TIMESTAMP() - 86400) ) AS shipJumps,
(select sum(shipKills) from kills where solarSystemID=mapsolarsystems.solarSystemID and timestamp>=(select UNIX_TIMESTAMP() - 86400) ) AS shipKills 
FROM mapsolarsystems WHERE security<0';

$db->query($sql_sel);

echo $sql_sel;

echo $db->num_rows();

if($db->num_rows() > 0){

	for($x=0; $x < $db->num_rows(); $x++){
		$r = $db->fetch_assoc();


		//$sql_sel_data = 'select 
		//		(select sum(shipJumps) from jumps where solarSystemID='. $r["solarSystemID"] .' and timestamp>=(select UNIX_TIMESTAMP() - 86400) ) AS shipJumps, 
		//		(select sum(shipKills) from kills where solarSystemID='. $r["solarSystemID"] .' and timestamp>=(select UNIX_TIMESTAMP() - 86400) ) AS shipKills';

		//$db1->query($sql_sel_data);


			//if($db1->num_rows() > 0){
				//$r2 = $db1->fetch_assoc();


				$sql_ins_data = 'INSERT INTO solarsystem_jumps_kills SET solarSystemID="'. $r["solarSystemID"] .'", 
				solarSystemName="'. $r["solarSystemName"] .'", 
				shipJumps="'. $r["shipJumps"] .'", 
				shipKills="'. $r["shipKills"] .'", 
				timestamp=(select UNIX_TIMESTAMP())';

//echo $sql_ins_data;		


				$db1->query($sql_ins_data);
			//}

	}

}

*/


?>