<?php
set_time_limit(0); 

include_once("php/include/database.php");

$db = new db;

$log = new loggs;

$log->event('', '===Start 0.0.prepare_Soverignty.php===');

//читаем файло с данными
$xml = simplexml_load_file('data/Sovereignty.xml.aspx');

if(file_exists('data/Sovereignty.xml.aspx') == 0){
	$log->event('ERROR', 'not available Sovereignty.xml.aspx');
}




/*
$sql_del = 'DELETE FROM sovchangelog';

$db->query($sql_sel);
*/

//ищем время кеша в файле
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

//конвертируем время в слепок
$timestamp = mktime ($hour, $minute, $second, $month, $day , $year );


//data uploaded?
//проверяем есть ли данные этого файла в бд
$sql_sel = 'SELECT * FROM sovchangelog where date=\''. $timestamp .'\' limit 5';

echo $sql_sel;

$db->query($sql_sel);

if($DEBUG == 1){
	$log->event('DEBUG', $sql_sel);
}

//определяем кол-во записей
$nummer = $db->num_rows();
echo $nummer;

$log->event('', 'num rows sovchangelog '. $nummer);

//если данные новые и нет их в бд
if($nummer == 0){


	$x=0;
	foreach ($xml->result[0]->rowset[0]->row as $row) {

		//ищем эту систему в нашей бд
		$sql_sel = 'SELECT * FROM mapsolarsystems '.
		   'WHERE solarSystemID=\''. $row['solarSystemID'] .'\'';

		$db->query($sql_sel);
		$num = $db->num_rows();

		//if($DEBUG == 1){
		//	$log->event('DEBUG', ''. $sql_sel .' - find system: '. $num);
		//}
			//если система найдена
			if($num > 0){
				$r = $db->fetch_assoc();

				//если новая инфа о клайме не соотвествует текущей
				if($r["allianceID"] != $row['allianceID']){
					//sov change new ally
					$sql_upd_change = 'INSERT INTO sovchangelog SET fromAllianceID=\''. $r["allianceID"] .'\', toAllianceID=\''. $row['allianceID'] .'\', systemID=\''. $row['solarSystemID'] .'\', date=\''. $timestamp .'\'';
					$resr_query =   $db->query($sql_upd_change);
					//echo $sql_upd_change;

					//if($DEBUG == 1){
						$log->event('DEBUG', 'result: '. $resr_query .' sov change new ally: '. $sql_upd_change);
					//}

				}
				//если новая инфа о клайме соотвествует, но уровень клайма не соотвествует
				else if(($r["allianceID"] == $row['allianceID']) && ($r["sovereigntyLevel"] != $row['sovereigntyLevel'])){
					//sov lvl change 
					$sql_upd_change = 'INSERT INTO sovchangelog SET fromAllianceID=\''. $r["allianceID"] .'\', toAllianceID=\''. $row['allianceID'] .'\', systemID=\''. $row['solarSystemID'] .'\', sovereigntyLevel=\''. $row['sovereigntyLevel'] .'\', date=\''. $timestamp .'\'';
					$resr_query =  $db->query($sql_upd_change);
					//echo $sql_upd_change;

					//if($DEBUG == 1){
						$log->event('DEBUG', 'result: '. $resr_query .' sov lvl: '. $sql_upd_change);
					//}
					unset($resr_query);
				}



				//обновляем инфу о текущем клайме
				$sql =  'UPDATE mapsolarsystems SET allianceID=\''. $row['allianceID'] .'\', '.
					'sovereigntyLevel=\''. $row['sovereigntyLevel'] .'\', '.
					'constellationSov=\''. $row['constellationSovereignty'] .'\' '.
					'WHERE solarSystemID=\''. $row['solarSystemID'] .'\'';

				//echo $sql ."\n\r";

				$resr_query = $db->query($sql);

				if(! $resr_query){
					$log->event('DEBUG', 'result: '. $resr_query .' update: '. $sql);
				}

				unset($resr_query);
			}


		$x++;
	}

}

$log->event('', 'End 0.0.prepare_Soverignty.php');

?>