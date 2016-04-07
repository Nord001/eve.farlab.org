<?php

set_time_limit(0); 

include_once("php/include/database.php");

$db = new db;

$log = new loggs;

$log->event('', '===Start 0.1.prepare_ally.php===');


$xml = simplexml_load_file('data/AllianceList.xml.aspx');

if(file_exists('data/AllianceList.xml.aspx') == 0){
	$log->event('ERROR', 'not available AllianceList.xml.aspx');
}




$x=0;
/*
$SQL = 'DELETE FROM evealliances';

$db->query($SQL);
*/


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
			$sql1 = 'UPDATE evealliances SET name=\''. mysql_real_escape_string ( $row['name'] ) .'\', '.
	 			' shortName=\''. mysql_real_escape_string ($row['shortName']) .'\', memberCount=\''. $row['memberCount'] .'\' WHERE id='. $row['allianceID'];

			$db->query($sql1);


		}
		else {
			$sql1 = 'INSERT evealliances SET name=\''. mysql_real_escape_string ($row['name']) .'\', '.
	 			'id=\''. $row['allianceID'] .'\', color=\''. $ally_R .''. $ally_G .''. $ally_B .'\', shortName=\''. mysql_real_escape_string ($row['shortName'] ) .'\', memberCount=\''. $row['memberCount'] .'\'';
			$result_query = $db->query($sql1);

			$log->event('', 'result: '. $result_query .' Insert alliance: '. $sql1);
			unset($result_query);
		}


/*
if(eregi('Dai', $row['name'])){
echo $sql1;
}
*/



//echo ' - '.  ."\n\r";

	if(! ($x % 500)){
		echo $x ."\n\r";
	}

	$x++;

}

$log->event('', 'end 0.1.prepare_ally.php');

?>