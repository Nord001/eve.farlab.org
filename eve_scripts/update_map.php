<?php

set_time_limit(0);

/*
if( file_exists("data/Sovereignty.xml.aspx") ){
	unlink("data/Sovereignty.xml.aspx");
}

if( file_exists("data/AllianceList.xml.aspx") ){
	unlink("data/AllianceList.xml.aspx");
}

if( file_exists("data/Jumps.xml.aspx") ){
	unlink("data/Jumps.xml.aspx");
}
if( file_exists("data/ConquerableStationList.xml.aspx") ){
	unlink("data/ConquerableStationList.xml.aspx");
}
if( file_exists("data/Kills.xml.aspx") ){
	unlink("data/Kills.xml.aspx");
}



$ch = curl_init("http://api.eve-online.com/map/Sovereignty.xml.aspx");
$fp = fopen("data/Sovereignty.xml.aspx", "w");

curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);

curl_exec($ch);
curl_close($ch);
fclose($fp);


$ch = curl_init("http://api.eve-online.com/eve/AllianceList.xml.aspx");
$fp = fopen("data/AllianceList.xml.aspx", "w");

curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);

curl_exec($ch);
curl_close($ch);
fclose($fp);


$ch = curl_init("http://api.eve-online.com/map/Jumps.xml.aspx");
$fp = fopen("data/Jumps.xml.aspx", "w");

curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);

curl_exec($ch);
curl_close($ch);
fclose($fp);


$ch = curl_init("http://api.eve-online.com/eve/ConquerableStationList.xml.aspx");
$fp = fopen("data/ConquerableStationList.xml.aspx", "w");

curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);

curl_exec($ch);
curl_close($ch);
fclose($fp);


$ch = curl_init("http://api.eve-online.com/map/Kills.xml.aspx");
$fp = fopen("data/Kills.xml.aspx", "w");

curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);

curl_exec($ch);
curl_close($ch);
fclose($fp);
*/


require('0.0.prepare_Soverignty.php');
echo '0.0.prepare_Soverignty.php'."\n";

require('0.1.prepare_ally.php');
echo '0.1.prepare_ally.php'."\n";

require('0.2.prepare_outpost.php');
echo '0.2.prepare_outpost.php'."\n";

//require('0.3.prepare_kills.php');
//echo '0.3.prepare_kills.php'."\n";

//require('0.4.prepare_jump.php');
//echo '0.4.prepare_jump.php'."\n";

require('0.5.prepare_ally_stat.php');
echo '0.5.prepare_ally_stat.php'."\n";

require('0.6.set_color_ally.php');
echo '0.6.set_color_ally.php'."\n";

require('0.7.agregate_kills_jumps.php');
echo '0.7.agregate_kills_jumps.php'."\n";


require('1.map_create.php');
echo '1.map_create.php'."\n";


require('0.8.move_map.php');
echo '0.8.move_map.php'."\n";

?>