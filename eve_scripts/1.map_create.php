<?php

error_reporting(E_ALL);

set_time_limit(0);

include_once("php/include/database.php");

$db = new db;


$image_size_x = 1856;//image size x length pixel

$image_size_y = 2048;//image size y length pixel




$font_pfb = imagepsloadfont('Ariacb__.pfb');

$fontc_pfb = imagepsloadfont('ariac.pfb');


putenv('GDFONTPATH='. realpath('.'));


$font = 'tahoma.ttf';

//$font = 'hoog0555_cyr.ttf';


$im = imagecreatetruecolor($image_size_x, $image_size_y) or die("Cannot Initialize new GD image stream");

$background_color = imagecolorallocate($im, 0, 0, 0);

$text_color_region = imagecolorallocate($im, 255, 255, 255);


////////////////////////////////////////////////////////////////////////////////////////////////////////
echo date(r) .' Create image...'."\n\r";

$white = imagecolorallocate($im, 0xFF, 0xFF, 0xFF);


$sWidth=900*2;
$sHeight=1024*2;
$sX=180;
$sY=0;
 
$scale=4.8445284569785E17 / ( ( $sHeight-20 ) / 2 );

  





////////////////////////////////////////////////////////////////////////////////////////////////////////
echo date(r) .' Starting show jumps claim 0.0...'."\n\r";




$db->query('SELECT 
fromSolarSystemID, 
(select x FROM mapsolarsystems where solarSystemID=mapsolarsystemjumps.fromSolarSystemID) as fromX, 
(select z FROM mapsolarsystems where solarSystemID=mapsolarsystemjumps.fromSolarSystemID) as fromZ,
(select color FROM evealliances where id=(select allianceID FROM mapsolarsystems where solarSystemID=mapsolarsystemjumps.fromSolarSystemID) ) as fromAllyColor,
toSolarSystemID, 
(select x FROM mapsolarsystems where solarSystemID=mapsolarsystemjumps.toSolarSystemID) as toX, 
(select z FROM mapsolarsystems where solarSystemID=mapsolarsystemjumps.toSolarSystemID) as toZ, 
(select color FROM evealliances where id=(select allianceID FROM mapsolarsystems where solarSystemID=mapsolarsystemjumps.toSolarSystemID) ) as toAllyColor 
FROM mapsolarsystemjumps 
LEFT JOIN mapsolarsystems 
ON mapsolarsystemjumps.fromSolarSystemID=mapsolarsystems.solarSystemID 
WHERE mapsolarsystems.security<0');

$num = $db->num_rows();


//красим джампы клаймом


for($x1=0; $x1 < $num; $x1++){
	$r = $db->fetch_assoc();
	
	$from_x_pos = ( $r['fromX'] / $scale ) + $sWidth / 2+ $sX;
	$from_y_pos = ( $r['fromZ'] / $scale ) + $sHeight / 2 + $sY;

	$to_x_pos = ( $r['toX'] / $scale ) + $sWidth / 2+ $sX;
	$to_y_pos = ( $r['toZ'] / $scale ) + $sHeight / 2 + $sY;

//echo 'x: '. $from_x_pos .' y: '. $from_y_pos .' -> '. ' x: '. $to_x_pos .' y: '. $to_y_pos ."\n\r";	



	if($r['fromAllyColor'] != $r['toAllyColor']){


	}
	else if( $r['fromAllyColor'] == $r['toAllyColor'] ){

		$from_ally_R = hexdec(substr($r["fromAllyColor"],0,2));
		$from_ally_G = hexdec(substr($r["fromAllyColor"],2,2));
		$from_ally_B = hexdec(substr($r["fromAllyColor"],4,2));

		imagesetthickness ($im, 6);



		//if( ($r['fromSecurity'] > 0 ) && ($r['fromSecurity'] > 0 ) ){



		imageline($im, $from_x_pos, $from_y_pos, $to_x_pos, $to_y_pos, imagecolorallocatealpha($im, $from_ally_R, $from_ally_G, $from_ally_B, 50));


	}

	imagesetthickness ($im, 1);




	//if($x1 == 90){break;}
	
}



imagefilledrectangle($im, 0, 0, $image_size_x , $image_size_y , imagecolorallocatealpha($im, 0, 0, 0, 70));







////////////////////////////////////////////////////////////////////////////////////////////////////////
echo date(r) .' Starting show influence...'."\n\r";

//рисуем инфлуенцию
//расставляем клайм по системам


$db->query('SELECT 
x, 
z, 
solarSystemID, 
(select name FROM evealliances where id=mapsolarsystems.allianceID ) as AllyName, 
(select color FROM evealliances where id=mapsolarsystems.allianceID ) as AllyColor,
stantion, 
constellationID, 
regionID, 
sovereigntylevel, 
constellationSov 
FROM mapsolarsystems 
where allianceID<>0 AND allianceID<>\''. $Dev_test_ally_id .'\' order by sovereigntylevel desc');


//allianceID<>\'748088119\' Tanis non show

$num = $db->num_rows();


	//$mass[$r["AllyName"]][$r["regionID"]] = 0;




for($x1=0; $x1 < $num; $x1++){
	$r = $db->fetch_assoc();
	
	$x_pos = ( $r['x'] / $scale ) + $sWidth / 2+ $sX;
	$y_pos = ( $r['z'] / $scale ) + $sHeight / 2 + $sY;






	if($r["AllyColor"] != ""){


		$ally_R = hexdec(substr($r["AllyColor"],0,2));
		$ally_G = hexdec(substr($r["AllyColor"],2,2));
		$ally_B = hexdec(substr($r["AllyColor"],4,2));

		$ally_col = imagecolorallocate($im, $ally_R, $ally_G, $ally_B);
		$ally_col_alpha = imagecolorallocatealpha($im, $ally_R, $ally_G, $ally_B, 90);

		$dark = imagecolorallocatealpha($im, 255, 0, 0, 20);

		
		
		if($r["stantion"] != 0){
		//сов 3 лвл и станция
			imagefilledellipse ($im, $x_pos, $y_pos, 23, 23, imagecolorallocatealpha($im, $ally_R, $ally_G, $ally_B, 0));


			//imagestring($im, 4, $x_pos + 20 , $y_pos, $r["AllyName"] , imagecolorallocate($im, $ally_R, $ally_G, $ally_B));

			if($mass[$r["AllyName"]][$r["regionID"]] == 0){
				//imagefttext($im, 10, 0, $x_pos + 20, $y_pos, imagecolorallocate($im, $ally_R, $ally_G, $ally_B), $font, $r["AllyName"]);
				$mass[$r["AllyName"]][$r["regionID"]]++;
			}

		}
		else {

			imagefilledellipse ($im, $x_pos, $y_pos, 12, 12, imagecolorallocatealpha($im, $ally_R, $ally_G, $ally_B, 0));

		}
		
		
		
		
		
		/*
		if($r["sovereigntylevel"] == 4){
		//сов 4
			//imagefilledellipse ($im, $x_pos, $y_pos, 90, 90, imagecolorallocatealpha($im, $ally_R, $ally_G, $ally_B, 0));
			imagefilledellipse ($im, $x_pos, $y_pos, 17, 17, imagecolorallocatealpha($im, $ally_R, $ally_G, $ally_B, 0));


			if($mass[$r["AllyName"]][$r["regionID"]] == 0){


				$mass[$r["AllyName"]][$r["regionID"]]++;
			}
		}

		else if(($r["sovereigntylevel"] == 3) and ($r["stantion"] != 0)){
		//сов 3 лвл и станция
			imagefilledellipse ($im, $x_pos, $y_pos, 20, 20, imagecolorallocatealpha($im, $ally_R, $ally_G, $ally_B, 0));


			//imagestring($im, 4, $x_pos + 20 , $y_pos, $r["AllyName"] , imagecolorallocate($im, $ally_R, $ally_G, $ally_B));

			if($mass[$r["AllyName"]][$r["regionID"]] == 0){
				//imagefttext($im, 10, 0, $x_pos + 20, $y_pos, imagecolorallocate($im, $ally_R, $ally_G, $ally_B), $font, $r["AllyName"]);
				$mass[$r["AllyName"]][$r["regionID"]]++;
			}

		}
		else if($r["sovereigntylevel"] == 3){

			imagefilledellipse ($im, $x_pos, $y_pos, 17, 17, imagecolorallocatealpha($im, $ally_R, $ally_G, $ally_B, 0));

		}

		else if(($r["sovereigntylevel"] == 2) and ($r["stantion"] != 0)){

			imagefilledellipse ($im, $x_pos, $y_pos, 12, 12, imagecolorallocatealpha($im, $ally_R, $ally_G, $ally_B, 0));

		}

		else if($r["sovereigntylevel"] == 2){

			imagefilledellipse ($im, $x_pos, $y_pos, 9, 9, imagecolorallocatealpha($im, $ally_R, $ally_G, $ally_B, 0));

		}

		else if(($r["sovereigntylevel"] == 1) and ($r["stantion"] != 0)){

			imagefilledellipse ($im, $x_pos, $y_pos, 7, 7, imagecolorallocatealpha($im, $ally_R, $ally_G, $ally_B, 0));

		}
		else {
			imagefilledellipse ($im, $x_pos, $y_pos, 4, 4, imagecolorallocatealpha($im, $ally_R, $ally_G, $ally_B, 0));
		}
	*/


	}

}


imagefilledrectangle($im, 0, 0, $image_size_x , $image_size_y , imagecolorallocatealpha($im, 0, 0, 0, 60));






$db->free_result();


imagesetthickness ($im, 0.5);



////////////////////////////////////////////////////////////////////////////////////////////////////////
echo date(r) .'Starting show npc outposts...'."\n\r";

$db->query('SELECT 
x, 
z, 
solarSystemID, 
stantion, 
constellationID, 
regionID, 
sovereigntylevel, 
constellationSov 
FROM mapsolarsystems 
where allianceID=0 AND stantion=1');


$num = $db->num_rows();


for($x1=0; $x1 < $num; $x1++){
	$r = $db->fetch_assoc();
	
	$x_pos = ( $r['x'] / $scale ) + $sWidth / 2+ $sX;
	$y_pos = ( $r['z'] / $scale ) + $sHeight / 2 + $sY;









	$color_for_npc_station = imagecolorallocate($im, 0xAA, 0xAA, 0xAA);

		//$ally_col_alpha = imagecolorallocatealpha($im, $ally_R, $ally_G, $ally_B, 90);

		//$dark = imagecolorallocatealpha($im, 255, 0, 0, 20);

		
	//if(($r["sovereigntylevel"] == 0) and ($r["stantion"] != 0)){

		//imagefilledellipse ($im, $x_pos, $y_pos, 40, 40, imagecolorallocate($im, 255, 0, 0));

			imagesetthickness ($im, 1.5);
			imagerectangle( $im , $x_pos - 3 , $y_pos - 3 , $x_pos + 3 , $y_pos + 3 , $color_for_npc_station );
			imagesetthickness ($im, 1);


			imageline($im, $x_pos - 3, $y_pos - 3, $x_pos + 3, $y_pos + 3, $color_for_npc_station);

			imageline($im, $x_pos + 3, $y_pos - 3, $x_pos - 3, $y_pos + 3, $color_for_npc_station);


	//}

}



$db->free_result();


imagesetthickness ($im, 0.5);





////////////////////////////////////////////////////////////////////////////////////////////////////////
echo date(r) .' Starting show claim system...'."\n\r";

//расставляем клайм по системам


$db->query('SELECT 
x, 
z, 
solarSystemID, 
(select name FROM evealliances where id=mapsolarsystems.allianceID ) as AllyName, 
(select color FROM evealliances where id=mapsolarsystems.allianceID ) as AllyColor,
stantion, 
constellationID, 
regionID, 
sovereigntylevel, 
constellationSov 
FROM mapsolarsystems 
where allianceID<>0');


$num = $db->num_rows();






for($x1=0; $x1 < $num; $x1++){
	$r = $db->fetch_assoc();
	
	$x_pos = ( $r['x'] / $scale ) + $sWidth / 2+ $sX;
	$y_pos = ( $r['z'] / $scale ) + $sHeight / 2 + $sY;


	if($r["AllyColor"] != ""){




		$ally_R = hexdec(substr($r["AllyColor"],0,2));
		$ally_G = hexdec(substr($r["AllyColor"],2,2));
		$ally_B = hexdec(substr($r["AllyColor"],4,2));

		$ally_col = imagecolorallocate($im, $ally_R, $ally_G, $ally_B);


		if($r["sovereigntylevel"] == 4){

			$values_black = array(
            			$x_pos - 6,  $y_pos - 6,  // Point 1 (x, y)
            			$x_pos,  $y_pos + 7,  // Point 2 (x, y)
            			$x_pos + 6,  $y_pos - 6,  // Point 3 (x, y)
           		 );

			$values = array(
            			$x_pos - 5,  $y_pos - 5,  // Point 1 (x, y)
            			$x_pos,  $y_pos + 5,  // Point 2 (x, y)
            			$x_pos + 5,  $y_pos - 5,  // Point 3 (x, y)
           		 );


			imagepolygon($im, $values_black, 3, imagecolorallocate($im, 0, 0, 0));

			imagepolygon($im, $values, 3, $ally_col);

		}
		else if($r["stantion"] != 0){
			imagesetthickness ($im, 1.5);
			imagerectangle( $im , $x_pos - 4 , $y_pos - 4 , $x_pos + 4 , $y_pos + 4 , imagecolorallocate($im, 0, 0, 0) );
			imagerectangle( $im , $x_pos - 3 , $y_pos - 3 , $x_pos + 3 , $y_pos + 3 , $ally_col );
			imagesetthickness ($im, 1);
		}

		else if($r["constellationSov"] != 0){

			imagefilledellipse( $im , $x_pos , $y_pos , 1.8 * $r["sovereigntylevel"] , 1.8 * $r["sovereigntylevel"], $ally_col );
		}

		else {

			imagefilledellipse( $im , $x_pos , $y_pos , 1.4 * $r["sovereigntylevel"] , 1.4 * $r["sovereigntylevel"], $ally_col );
		}

	}

}



$db->free_result();


////////////////////////////////////////////////////////////////////////////////////////////////////////
echo date(r) .' Starting show kills 24 hours...'."\n\r";


/*
$quer = 'SELECT 
kills.solarSystemID AS solarSystemID, 
sum(kills.shipKills) AS shipKills, 
mapsolarsystems.x AS x, 
mapsolarsystems.z AS z 
FROM kills 
LEFT JOIN 
mapsolarsystems 
ON mapsolarsystems.solarSystemID=kills.solarSystemID 
WHERE 
kills.shipkills>2 AND 
mapsolarsystems.security<0 AND 
kills.timestamp >=  (select (max(timestamp) - 186400) from kills) 
GROUP BY solarSystemID
ORDER BY kills.shipKills DESC';
*/


$quer = 'SELECT 
solarsystem_jumps_kills.solarSystemID AS solarSystemID, 
solarsystem_jumps_kills.shipKills AS shipKills, 
mapsolarsystems.x AS x, 
mapsolarsystems.z AS z 
FROM solarsystem_jumps_kills 
LEFT JOIN 
mapsolarsystems 
ON mapsolarsystems.solarSystemID=solarsystem_jumps_kills.solarSystemID 
WHERE 
solarsystem_jumps_kills.shipkills>9 AND 
mapsolarsystems.security<0 
GROUP BY solarSystemID
ORDER BY solarsystem_jumps_kills.shipKills DESC';







$db->query($quer);

//echo $quer;




$num = $db->num_rows();



for($x1=0; $x1 < $num; $x1++){
	$r = $db->fetch_assoc();
	
	$r_x = ( $r['x'] / $scale ) + $sWidth / 2 + $sX;
	$r_z = ( $r['z'] / $scale ) + $sHeight / 2 + $sY;


			if($r['shipKills'] > 300){
				imagesetthickness ($im, 1);
				imageellipse( $im , $r_x , $r_z , 15 , 15, imagecolorallocatealpha($im, 255, 0, 0, 10) );
				imageellipse( $im , $r_x , $r_z , 20 , 20, imagecolorallocatealpha($im, 255, 0, 0, 9) );
				imageellipse( $im , $r_x , $r_z , 25 , 25, imagecolorallocatealpha($im, 255, 0, 0, 8) );
				imageellipse( $im , $r_x , $r_z , 30 , 30, imagecolorallocatealpha($im, 255, 0, 0, 7) );


				//300+
				imagefilledrectangle($im, $r_x - 7 - 20, $r_z - 13, $r_x + 27, $r_z - 37, imagecolorallocatealpha($im, 51, 51, 51, 60));
				imagepstext($im, $r['shipKills'], $font_pfb, 30, imagecolorallocate($im, 255, 0, 0), imagecolorallocate($im, 30, 30, 30), $r_x - 7 - 20, $r_z - 13, 0, 0, 0, 16);



			}
			else if($r['shipKills'] > 200) {
				imagesetthickness ($im, 1);
				imageellipse( $im , $r_x , $r_z , 15 , 15, imagecolorallocatealpha($im, 255, 0, 0, 10) );
				imageellipse( $im , $r_x , $r_z , 20 , 20, imagecolorallocatealpha($im, 255, 0, 0, 9) );
				imageellipse( $im , $r_x , $r_z , 25 , 25, imagecolorallocatealpha($im, 255, 0, 0, 8) );

				//200+
				imagefilledrectangle($im, $r_x - 7 - 10, $r_z - 13, $r_x + 26, $r_z - 33, imagecolorallocatealpha($im, 51, 51, 51, 60));
				imagepstext($im, $r['shipKills'], $font_pfb, 25, imagecolorallocate($im, 255, 0, 0), imagecolorallocate($im, 20, 20, 20), $r_x - 7 - 10, $r_z - 13, 0, 0, 0, 16);

			}
			else if($r['shipKills'] > 100) {
				imagesetthickness ($im, 1);
				imageellipse( $im , $r_x , $r_z , 15 , 15, imagecolorallocatealpha($im, 255, 0, 0, 10) );
				imageellipse( $im , $r_x , $r_z , 20 , 20, imagecolorallocatealpha($im, 255, 0, 0, 9) );


				//100+
				imagefilledrectangle($im, $r_x - 7 - 10, $r_z - 13, $r_x + 17, $r_z - 30, imagecolorallocatealpha($im, 51, 51, 51, 60));
				imagepstext($im, $r['shipKills'], $font_pfb, 20, imagecolorallocate($im, 255, 0, 0), imagecolorallocate($im, 20, 20, 20), $r_x - 7 - 10, $r_z - 13, 0, 0, 0, 16);

			}
			else {
				imagesetthickness ($im, 1);
				imageellipse( $im , $r_x , $r_z , 15 , 15, imagecolorallocatealpha($im, 255, 0, 0, 10) );

				imagefttext($im, 10, 0, $r_x - 7, $r_z - 13, imagecolorallocatealpha($im, 255, 0, 0, 50), $font, $r['shipKills']);

			}


			//imagepstext($im, $r['shipKills'] , $fontc_pfb, 10, imagecolorallocate($im, 255, 255, 255), imagecolorallocate($im, 20, 20, 20), $r_x - 7, $r_z - 13, 0, 0, 0, 16);


			imagesetthickness ($im, 1);
}



$db->free_result();



/*
////////////////////////////////////////////////////////////////////////////////////////////////////////
echo date(r) .' Starting show jumps 0.0...'."\n\r";


$c333333 = imagecolorallocate($im, 0x00, 0x33, 0x33);

$db->query('SELECT 
fromSolarSystemID, 
(select x FROM mapsolarsystems where solarSystemID=mapsolarsystemjumps.fromSolarSystemID) as fromX, 
(select z FROM mapsolarsystems where solarSystemID=mapsolarsystemjumps.fromSolarSystemID) as fromZ,
toSolarSystemID, 
(select x FROM mapsolarsystems where solarSystemID=mapsolarsystemjumps.toSolarSystemID) as toX, 
(select z FROM mapsolarsystems where solarSystemID=mapsolarsystemjumps.toSolarSystemID) as toZ 
FROM mapsolarsystemjumps 
LEFT JOIN mapsolarsystems 
ON mapsolarsystemjumps.fromSolarSystemID=mapsolarsystems.solarSystemID 
WHERE mapsolarsystems.security<0');

$num = $db->num_rows();


//рисуем джампы


for($x1=0; $x1 < $num; $x1++){
	$r = $db->fetch_assoc();
	
	$from_x_pos = ( $r['fromX'] / $scale ) + $sWidth / 2+ $sX;
	$from_y_pos = ( $r['fromZ'] / $scale ) + $sHeight / 2 + $sY;

	$to_x_pos = ( $r['toX'] / $scale ) + $sWidth / 2+ $sX;
	$to_y_pos = ( $r['toZ'] / $scale ) + $sHeight / 2 + $sY;

//echo 'x: '. $from_x_pos .' y: '. $from_y_pos .' -> '. ' x: '. $to_x_pos .' y: '. $to_y_pos ."\n\r";	



	imageline($im, $from_x_pos, $from_y_pos, $to_x_pos, $to_y_pos, $c333333);


	//imageSmoothAlphaLine ($im, $from_x_pos, $from_y_pos, $to_x_pos, $to_y_pos, 206, 254, 0, 40);


	unset($from_x_pos);
	unset($from_y_pos);

	unset($to_x_pos);
	unset($to_y_pos);

	//if($x1 == 90){break;}
	
}
*/



////////////////////////////////////////////////////////////////////////////////////////////////////////
echo date(r) .' Starting show jumps >0.0...'."\n\r";


$c333333 = imagecolorallocate($im, 0x01, 0x11, 0x11);

$db->query('SELECT 
fromSolarSystemID, 
(select x FROM mapsolarsystems where solarSystemID=mapsolarsystemjumps.fromSolarSystemID) as fromX, 
(select z FROM mapsolarsystems where solarSystemID=mapsolarsystemjumps.fromSolarSystemID) as fromZ,
(select security FROM mapsolarsystems where solarSystemID=mapsolarsystemjumps.fromSolarSystemID) as fromSec,
toSolarSystemID, 
(select x FROM mapsolarsystems where solarSystemID=mapsolarsystemjumps.toSolarSystemID) as toX, 
(select z FROM mapsolarsystems where solarSystemID=mapsolarsystemjumps.toSolarSystemID) as toZ, 
(select security FROM mapsolarsystems where solarSystemID=mapsolarsystemjumps.toSolarSystemID) as toSec 
FROM mapsolarsystemjumps 
LEFT JOIN mapsolarsystems 
ON mapsolarsystemjumps.fromSolarSystemID=mapsolarsystems.solarSystemID 
');
//WHERE mapsolarsystems.security>0
$num = $db->num_rows();


//рисуем джампы


for($x1=0; $x1 < $num; $x1++){
	$r = $db->fetch_assoc();
	
	$from_x_pos = ( $r['fromX'] / $scale ) + $sWidth / 2+ $sX;
	$from_y_pos = ( $r['fromZ'] / $scale ) + $sHeight / 2 + $sY;

	$to_x_pos = ( $r['toX'] / $scale ) + $sWidth / 2+ $sX;
	$to_y_pos = ( $r['toZ'] / $scale ) + $sHeight / 2 + $sY;

//echo 'x: '. $from_x_pos .' y: '. $from_y_pos .' -> '. ' x: '. $to_x_pos .' y: '. $to_y_pos ."\n\r";	

	if( ( $r['fromSec'] < 0 ) &&  ( $r['toSec'] < 0 )){

		imageline($im, $from_x_pos, $from_y_pos, $to_x_pos, $to_y_pos, imagecolorallocate($im, 0x00, 0x33, 0x33));

	}
	else if ( ( $r['fromSec'] < 0 ) or  ( $r['toSec'] < 0 )){
		//imageSmoothAlphaLine ($im, $from_x_pos, $from_y_pos, $to_x_pos, $to_y_pos, 255, 51, 51, 60);
		imageline($im, $from_x_pos, $from_y_pos, $to_x_pos, $to_y_pos, imagecolorallocate($im, 0xff, 0x33, 0x33));

	}
	else {
		imageline($im, $from_x_pos, $from_y_pos, $to_x_pos, $to_y_pos, $c333333);
	}


//imageSmoothAlphaLine ($im, $from_x_pos, $from_y_pos, $to_x_pos, $to_y_pos, 218, 50, 50, 60);


	unset($from_x_pos);
	unset($from_y_pos);

	unset($to_x_pos);
	unset($to_y_pos);

	//if($x1 == 90){break;}
	
}




////////////////////////////////////////////////////////////////////////////////////////////////////////
echo date(r) .' Starting show jumps activities...'."\n\r";




$db->query('SELECT 
mapsolarsystemjumps.fromSolarSystemID AS fromSolarSystemID, 
t1.x as fromX, 
t1.z as fromZ,
sum(j1.shipJumps) as fromshipJumps,

mapsolarsystemjumps.toSolarSystemID AS toolarSystemID, 
t2.x as toX, 
t2.z as toZ, 
sum(j2.shipJumps) as toshipJumps  

FROM mapsolarsystemjumps 
LEFT JOIN 
mapsolarsystems t1
ON t1.solarSystemID=mapsolarsystemjumps.fromSolarSystemID 
LEFT JOIN 
mapsolarsystems t2
ON t2.solarSystemID=mapsolarsystemjumps.toSolarSystemID 
LEFT JOIN 
solarsystem_jumps_kills j1
ON j1.solarSystemID=mapsolarsystemjumps.fromSolarSystemID 
LEFT JOIN 
solarsystem_jumps_kills j2
ON j2.solarSystemID=mapsolarsystemjumps.toSolarSystemID 

WHERE t1.security < 0 and t2.security < 0 
and j1.shipJumps > 50 and j2.shipJumps > 50 
GROUP BY mapsolarsystemjumps.fromSolarSystemID');







//рисуем джампы


for($x1=0; $x1 < $num; $x1++){
	$r = $db->fetch_assoc();
	
	if(($r["fromshipJumps"] > 150) and ($r["toshipJumps"] > 150)){
		imagesetthickness ($im, 3);
	}
	else if(($r["fromshipJumps"] > 50) and ($r["toshipJumps"] > 50)){
		imagesetthickness ($im, 1);
	}



	$from_x_pos = ( $r['fromX'] / $scale ) + $sWidth / 2+ $sX;
	$from_y_pos = ( $r['fromZ'] / $scale ) + $sHeight / 2 + $sY;

	$to_x_pos = ( $r['toX'] / $scale ) + $sWidth / 2+ $sX;
	$to_y_pos = ( $r['toZ'] / $scale ) + $sHeight / 2 + $sY;

//echo 'x: '. $from_x_pos .' y: '. $from_y_pos .' -> '. ' x: '. $to_x_pos .' y: '. $to_y_pos ."\n\r";	



	imageline($im, $from_x_pos, $from_y_pos, $to_x_pos, $to_y_pos, imagecolorallocatealpha($im, 206, 255, 255, 80));

	unset($from_x_pos);
	unset($from_y_pos);

	unset($to_x_pos);
	unset($to_y_pos);

	//if($x1 == 90){break;}
	




}
$db->free_result();


imagesetthickness ($im, 1);








////////////////////////////////////////////////////////////////////////////////////////////////////////
echo date(r) .' Starting show systems 0.0...'."\n\r";


$db->query('SELECT 
x, 
z, 
solarSystemID, 
allianceID, 
stantion, 
constellationID, 
regionID, 
sovereigntylevel, 
constellationSov 
FROM mapsolarsystems WHERE mapsolarsystems.security<0');

$num = $db->num_rows();



for($x1=0; $x1 < $num; $x1++){
	$r = $db->fetch_assoc();
	
	$x_pos = ( $r['x'] / $scale ) + $sWidth / 2+ $sX;
	$y_pos = ( $r['z'] / $scale ) + $sHeight / 2 + $sY;
	
	imageline($im, $x_pos, $y_pos, $x_pos, $y_pos, $white);//рисуем системы
}



$db->free_result();

////////////////////////////////////////////////////////////////////////////////////////////////////////
echo date(r) .' Starting show systems >0.0...'."\n\r";


$db->query('SELECT 
x, 
z, 
solarSystemID, 
allianceID, 
stantion, 
constellationID, 
regionID, 
sovereigntylevel, 
constellationSov 
FROM mapsolarsystems WHERE mapsolarsystems.security>0');

$num = $db->num_rows();



for($x1=0; $x1 < $num; $x1++){
	$r = $db->fetch_assoc();
	
	$x_pos = ( $r['x'] / $scale ) + $sWidth / 2+ $sX;
	$y_pos = ( $r['z'] / $scale ) + $sHeight / 2 + $sY;
	
	imageline($im, $x_pos, $y_pos, $x_pos, $y_pos, imagecolorallocatealpha($im, 204, 204, 204, 98));//рисуем системы
}



$db->free_result();


////////////////////////////////////////////////////////////////////////////////////////////////////////
echo date(r) .' Starting show claim system change...'."\n\r";

$db->query('SELECT 
mapsolarsystems.x AS x, 
mapsolarsystems.z AS y 
FROM sovchangelog 
LEFT JOIN mapsolarsystems 
ON mapsolarsystems.solarSystemID=sovchangelog.SystemID 
where sovchangelog.date>=(select max(date) from sovchangelog s1) and sovchangelog.fromAllianceID<>sovchangelog.toAllianceID');


$num = $db->num_rows();






for($x1=0; $x1 < $num; $x1++){
	$r = $db->fetch_assoc();
	
	$x_pos = ( $r['x'] / $scale ) + $sWidth / 2+ $sX;
	$y_pos = ( $r['y'] / $scale ) + $sHeight / 2 + $sY;







	$col_change = imagecolorallocate($im, 255, 255, 255);

	imageellipse( $im , $x_pos , $y_pos , 10 , 10, $col_change );

}



$db->free_result();





////////////////////////////////////////////////////////////////////////////////////////////////////////
echo date(r) .' Starting show region name...0.0'."\n\r";


$db->query('SELECT r.x as RX, r.z as RZ, r.regionName as NAME FROM mapsolarsystems s LEFT JOIN mapregions r ON r.regionID=s.regionID WHERE s.security<0 GROUP BY r.regionID');

$num = $db->num_rows();



for($x1=0; $x1 < $num; $x1++){
	$r = $db->fetch_assoc();
	
	$r_x = ( $r['RX'] / $scale ) + $sWidth / 2 + $sX;
	$r_z = ( $r['RZ'] / $scale ) + $sHeight / 2 + $sY;
	
	imagestring($im, 4, $r_x, $r_z + 10,  $r['NAME'] , $text_color_region);

}



$db->free_result();


////////////////////////////////////////////////////////////////////////////////////////////////////////
echo date(r) .' Starting show region name...>0.0'."\n\r";


$db->query('SELECT r.x as RX, r.z as RZ, r.regionName as NAME FROM mapsolarsystems s LEFT JOIN mapregions r ON r.regionID=s.regionID WHERE s.security>0 GROUP BY r.regionID');

$num = $db->num_rows();



for($x1=0; $x1 < $num; $x1++){
	$r = $db->fetch_assoc();
	
	$r_x = ( $r['RX'] / $scale ) + $sWidth / 2 + $sX;
	$r_z = ( $r['RZ'] / $scale ) + $sHeight / 2 + $sY;
	
	imagestring($im, 4, $r_x, $r_z + 10,  $r['NAME'] , imagecolorallocatealpha($im, 48, 48, 48, 50));

}



$db->free_result();


////////////////////////////////////////////////////////////////////////////////////////////////////////
echo date(r) .' Starting show ally stat...'."\n\r";

/*
	$sql = 'SELECT 
		evealliances.name AS name, 
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
		where allianceID>0 AND allianceID<>\'748088119\' 
		GROUP BY allianceID 
		ORDER BY sum_station desc, count_claim desc limit 21';
*/


$sql = 'select 
allianceID, 
evealliances.name AS name, 
(select 
rank  
from 
ally_stat a1 
where 
a1.allianceID=ally_stat.allianceID and 
timestamp=(
            select max(timestamp) 
            from 
            ally_stat a2 
            where a2.allianceID=ally_stat.allianceID and 
            timestamp<(
                      select max(timestamp) from ally_stat a3 
                      where a3.allianceID=ally_stat.allianceID
                      )
          )
) AS rank_last, 
rank AS rank_now, 
evealliances.color AS AllyColor, 
capitals AS capitals_now, 
(select 
capitals 
from 
ally_stat a1 
where 
a1.allianceID=ally_stat.allianceID and 
timestamp=(
            select max(timestamp) 
            from 
            ally_stat a2 
            where a2.allianceID=ally_stat.allianceID and 
            timestamp<(
                      select max(timestamp) from ally_stat a3 
                      where a3.allianceID=ally_stat.allianceID
                      )
          )
) AS capitals_last, 
outposts AS outposts_now, 

(select 
outposts 
from 
ally_stat a1 
where 
a1.allianceID=ally_stat.allianceID and 
timestamp=(
            select max(timestamp) 
            from 
            ally_stat a2 
            where a2.allianceID=ally_stat.allianceID and 
            timestamp<(
                      select max(timestamp) from ally_stat a3 
                      where a3.allianceID=ally_stat.allianceID
                      )
          )
) AS outposts_last, 
systems AS systems_now, 
(select 
systems 
from 
ally_stat a1 
where 
a1.allianceID=ally_stat.allianceID and 
timestamp=(
            select max(timestamp) 
            from 
            ally_stat a2 
            where a2.allianceID=ally_stat.allianceID and 
            timestamp<(
                      select max(timestamp) from ally_stat a3 
                      where a3.allianceID=ally_stat.allianceID
                      )
          )
) AS systems_last, 
members AS members_now, 
(select 
members 
from 
ally_stat a1 
where 
a1.allianceID=ally_stat.allianceID and 
timestamp=(
            select max(timestamp) 
            from 
            ally_stat a2 
            where a2.allianceID=ally_stat.allianceID and 
            timestamp<(
                      select max(timestamp) from ally_stat a3 
                      where a3.allianceID=ally_stat.allianceID
                      )
          )
) AS members_last  
from ally_stat 
LEFT JOIN 
evealliances 
ON 
evealliances.id=ally_stat.allianceID 
where timestamp=(select max(timestamp) from ally_stat)';







		$db->query($sql);

		//echo $quer;







		$num = $db->num_rows();

		$start_coordinate_x = $image_size_x - 470;
		$start_coordinate_y = 48;


		//upper horizontal
		imageline($im, $image_size_x - 550, 30, $image_size_x, 30, imagecolorallocatealpha($im, 255, 255, 255, 100));

		//caption Name
		imagefttext($im, 12, 0, $start_coordinate_x - 50, 26, imagecolorallocate($im, 255, 255, 255), $font, 'Name');
		//imagestring($im, 10, 0, $start_coordinate_x - 50, 26, 'Name',  imagecolorallocate($im, 255, 255, 255));


		//imagepstext($im, 'Name' , $fontc_pfb, 12, imagecolorallocate($im, 255, 255, 255), imagecolorallocate($im, 20, 20, 20), $start_coordinate_x - 50, 26, 0, 0, 0, 16);


		//upper vertical 1
		imageline($im, $image_size_x - 530, 10, $image_size_x - 530, 430, imagecolorallocatealpha($im, 255, 255, 255, 100));



		//caption Sov4
		//imagefttext($im, 12, 0, $start_coordinate_x + 138, 26, imagecolorallocate($im, 255, 255, 255), $font, 'Sov. 4');
		//imagepstext($im, 'Sov. 4', $fontc_pfb, 12, imagecolorallocate($im, 255, 255, 255), imagecolorallocate($im, 20, 20, 20), $start_coordinate_x + 138, 26, 0, 0, 0, 16);


		//upper vertical sov4
		imageline($im, $image_size_x - 340, 10, $image_size_x - 340, 430, imagecolorallocatealpha($im, 255, 255, 255, 100));



		//caption Outposts
		imagefttext($im, 12, 0, $start_coordinate_x + 220, 26, imagecolorallocate($im, 255, 255, 255), $font, 'Outposts');
		//imagepstext($im, 'Outposts', $fontc_pfb, 12, imagecolorallocate($im, 255, 255, 255), imagecolorallocate($im, 20, 20, 20), $start_coordinate_x + 220, 26, 0, 0, 0, 16);



		//upper vertical 2
		imageline($im, $image_size_x - 260, 10, $image_size_x - 260, 430, imagecolorallocatealpha($im, 255, 255, 255, 100));


		//caption Systems
		imagefttext($im, 12, 0, $start_coordinate_x + 300, 26, imagecolorallocate($im, 255, 255, 255), $font, 'Systems');
		//imagepstext($im, 'Systems', $fontc_pfb, 12, imagecolorallocate($im, 255, 255, 255), imagecolorallocate($im, 20, 20, 20), $start_coordinate_x + 300, 26, 0, 0, 0, 16);


		//upper vertical 3
		imageline($im, $image_size_x - 180, 10, $image_size_x - 180, 430, imagecolorallocatealpha($im, 255, 255, 255, 100));


		//caption Members
		imagefttext($im, 12, 0, $start_coordinate_x + 380, 26, imagecolorallocate($im, 255, 255, 255), $font, 'Members');
		//imagepstext($im, 'Members', $fontc_pfb, 12, imagecolorallocate($im, 255, 255, 255), imagecolorallocate($im, 20, 20, 20), $start_coordinate_x + 380, 26, 0, 0, 0, 16);



		//upper vertical 4
		imageline($im, $image_size_x - 100, 10, $image_size_x - 100, 430, imagecolorallocatealpha($im, 255, 255, 255, 100));

		//upper vertical 5
		imageline($im, $image_size_x - 18, 10, $image_size_x - 18, 430, imagecolorallocatealpha($im, 255, 255, 255, 100));

		for($x1=1; $x1 < $num; $x1++){
			$r = $db->fetch_assoc();

			$ally_R = hexdec(substr($r["AllyColor"],0,2));
			$ally_G = hexdec(substr($r["AllyColor"],2,2));
			$ally_B = hexdec(substr($r["AllyColor"],4,2));

			$ally_col = imagecolorallocate($im, $ally_R, $ally_G, $ally_B);



			if(strlen($r["rank_now"]) == 1 ){
				imagefttext($im, 11, 0, $start_coordinate_x - 26 - 50, $start_coordinate_y, $ally_col, $font, $r["rank_now"]);
				//imagepstext($im, $r["rank_now"], $fontc_pfb, 13, $ally_col, imagecolorallocate($im, 20, 20, 20), $start_coordinate_x - 26 - 50, $start_coordinate_y, 0, 0, 0, 16);

			}
			else {
				imagefttext($im, 11, 0, $start_coordinate_x - 26 - 50, $start_coordinate_y, $ally_col, $font, $r["rank_now"]);
				//imagepstext($im, $r["rank_now"], $fontc_pfb, 13, $ally_col, imagecolorallocate($im, 20, 20, 20), $start_coordinate_x - 26 - 50, $start_coordinate_y, 0, 0, 0, 16);

			}


			if( ( ($r["rank_now"] - $r["rank_last"]) > 0) && ($r["rank_last"] != '')){
				//arrow down

				$values_arrow_down = array(
            				($start_coordinate_x - 60) - 3,  ($start_coordinate_y - 5) - 3,  // Point 1 (x, y)
            				($start_coordinate_x - 60),  ($start_coordinate_y - 5) + 3,  // Point 2 (x, y)
            				($start_coordinate_x - 60) + 3,  ($start_coordinate_y - 5) - 3,  // Point 3 (x, y)
           		 	);


				imagefilledpolygon($im, $values_arrow_down, 3, $ally_col);

			}
			else if( ( ($r["rank_now"] - $r["rank_last"]) < 0) or ($r["rank_last"] == '')){

				//arrow down

				$values_arrow_up = array(
            				($start_coordinate_x - 60) + 3,  ($start_coordinate_y - 5) + 3,  // Point 1 (x, y)
            				($start_coordinate_x - 60),  ($start_coordinate_y - 5) - 3,  // Point 2 (x, y)
            				($start_coordinate_x - 60) - 3,  ($start_coordinate_y - 5) + 3,  // Point 3 (x, y)
           			 );

				imagefilledpolygon($im, $values_arrow_up, 3, $ally_col);

			}

			imagefttext($im, 11, 0, $start_coordinate_x - 50, $start_coordinate_y, $ally_col, $font, $r["name"]);
			//imagepstext($im, $r["name"], $fontc_pfb, 13, $ally_col, imagecolorallocate($im, 20, 20, 20), $start_coordinate_x - 50, $start_coordinate_y, 0, 0, 0, 16);


			/*
			if( ( $r["capitals_now"] - $r["capitals_last"] ) > 0) {
				$r["capitals_now"] = $r["capitals_now"] .'(+'. ( $r["capitals_now"] - $r["capitals_last"] ) .') ';
			}
			else if ( ( $r["capitals_now"] - $r["capitals_last"] ) < 0){
				$r["capitals_now"] = $r["capitals_now"] .'('. ( $r["capitals_now"] - $r["capitals_last"] ) .') ';
			}
			*/
			//imagefttext($im, 11, 0, $start_coordinate_x + 138, $start_coordinate_y, $ally_col, $font, $r["capitals_now"]);
			//imagepstext($im, $r["capitals_now"], $fontc_pfb, 13, $ally_col, imagecolorallocate($im, 20, 20, 20), $start_coordinate_x + 138, $start_coordinate_y, 0, 0, 0, 16);


			if( ( $r["outposts_now"] - $r["outposts_last"] ) > 0) {
				$r["outposts_now"] = $r["outposts_now"] .'(+'. ( $r["outposts_now"] - $r["outposts_last"] ) .') ';
			}
			else if ( ( $r["outposts_now"] - $r["outposts_last"] ) < 0){
				$r["outposts_now"] = $r["outposts_now"] .'('. ( $r["outposts_now"] - $r["outposts_last"] ) .') ';
			}



			imagefttext($im, 11, 0, $start_coordinate_x + 220, $start_coordinate_y, $ally_col, $font, $r["outposts_now"]);
			//imagepstext($im, $r["outposts_now"], $fontc_pfb, 13, $ally_col, imagecolorallocate($im, 20, 20, 20), $start_coordinate_x + 220, $start_coordinate_y, 0, 0, 0, 16);


			if( ( $r["systems_now"] - $r["systems_last"] ) > 0) {
				$r["systems_now"] = $r["systems_now"] .'(+'. ( $r["systems_now"] - $r["systems_last"] ) .') ';
			}
			else if( ( $r["systems_now"] - $r["systems_last"] ) < 0 ) {
				$r["systems_now"] =  $r["systems_now"] .'('. ( $r["systems_now"] - $r["systems_last"] ) .') ';
			}





			//imagefttext($im, 10, 0, $start_coordinate_x + 366 - (strlen($r["systems_now"]) * 5.7), $start_coordinate_y, $ally_col, $font, $r["systems_now"]);
			imagefttext($im, 11, 0, $start_coordinate_x + 300, $start_coordinate_y, $ally_col, $font, $r["systems_now"]);
			//imagepstext($im, $r["systems_now"], $fontc_pfb, 13, $ally_col, imagecolorallocate($im, 20, 20, 20), $start_coordinate_x + 300, $start_coordinate_y, 0, 0, 0, 16);



			if( ( $r["members_now"] - $r["members_last"] ) > 0) {
				$r["members_now"] = $r["members_now"] .'(+'. ( $r["members_now"] - $r["members_last"] ) .') ';
			}
			else if( ( $r["members_now"] - $r["members_last"] ) < 0) {
				$r["members_now"] = $r["members_now"] .'('. ( $r["members_now"] - $r["members_last"] ) .') ';
			}

			imagefttext($im, 11, 0, $start_coordinate_x + 380, $start_coordinate_y, $ally_col, $font, $r["members_now"]);
			//imagepstext($im, $r["members_now"], $fontc_pfb, 13, $ally_col, imagecolorallocate($im, 20, 20, 20), $start_coordinate_x + 380, $start_coordinate_y, 0, 0, 0, 16);


			$start_coordinate_y += 18;
		}


////////////////////////////////////////////////////////////////////////////////////////////////////////
echo date(r) .' Starting show sov change list...'."\n\r";


	$sql = 'SELECT 
		(select mapregions.regionName FROM mapsolarsystems LEFT JOIN mapregions ON mapregions.regionID=mapsolarsystems.regionID where mapsolarsystems.solarSystemID=sovchangelog.SystemID) as regionName, 
		(select mapsolarsystems.solarSystemName  FROM mapsolarsystems LEFT JOIN mapregions ON mapregions.regionID=mapsolarsystems.regionID where mapsolarsystems.solarSystemID=sovchangelog.SystemID) as SystemName, 
		(select name from evealliances where evealliances.id=sovchangelog.fromAllianceID) as fromAllianceID, 
		(select name from evealliances where evealliances.id=sovchangelog.toAllianceID) as toAllianceID, 
		(select color from evealliances where evealliances.id=sovchangelog.fromAllianceID) as fromAllyColor, 
		(select color from evealliances where evealliances.id=sovchangelog.toAllianceID) as toAllyColor, 
		(select stantion from mapsolarsystems where mapsolarsystems.solarSystemID=sovchangelog.SystemID) as outpost 
		FROM 
		sovchangelog 
		WHERE sovchangelog.fromAllianceID<>sovchangelog.toAllianceID and date>=(select max(date) from sovchangelog s1 ) ORDER BY 
		regionName DESC';

		$db->query($sql);

		//echo $quer;








		$num = $db->num_rows();

		$start_coordinate_x = 10;
		$start_coordinate_y = 38;


		//upper horizontal
		//imageline($im, $image_size_x , 30, $image_size_x, 30, imagecolorallocatealpha($im, 255, 255, 255, 100));

		//caption Sov. lost
		imagefttext($im, 9, 0, $start_coordinate_x, $start_coordinate_y, imagecolorallocate($im, 255, 255, 255), $font, 'Sov. lost');
		//imagepstext($im, 'Sov. lost' , $fontc_pfb, 10, imagecolorallocate($im, 255, 255, 255), imagecolorallocate($im, 20, 20, 20), $start_coordinate_x, $start_coordinate_y);

		//imagettftext($im, 7, 0, $start_coordinate_x, $start_coordinate_y, imagecolorallocate($im, 255, 255, 255), 'ARIALN.TTF', 'Sov. lost');

		//upper vertical 1
		//imageline($im, $image_size_x + 480, 10, $image_size_x - 480, 430, imagecolorallocatealpha($im, 255, 255, 255, 100));





		//caption Sov. gain
		imagefttext($im, 9, 0, $start_coordinate_x + 80, $start_coordinate_y, imagecolorallocate($im, 255, 255, 255), $font, 'Sov. gain');
		//imagepstext($im, 'Sov. gain', $fontc_pfb, 10, imagecolorallocate($im, 255, 255, 255), imagecolorallocate($im, 20, 20, 20), $start_coordinate_x + 80, $start_coordinate_y);







		//upper vertical 2
		//imageline($im, $image_size_x - 260, 10, $image_size_x - 260, 430, imagecolorallocatealpha($im, 255, 255, 255, 100));


		//caption System
		imagefttext($im, 9, 0, $start_coordinate_x + 174, $start_coordinate_y, imagecolorallocate($im, 255, 255, 255), $font, 'System');
		//imagepstext($im, 'System', $fontc_pfb, 10, imagecolorallocate($im, 255, 255, 255), imagecolorallocate($im, 20, 20, 20), $start_coordinate_x + 174, $start_coordinate_y);





		//upper vertical 3
		//imageline($im, $image_size_x - 180, $start_coordinate_y, $image_size_x - 180, 430, imagecolorallocatealpha($im, 255, 255, 255, 100));


		//caption Region
		imagefttext($im, 9, 0, $start_coordinate_x + 225, $start_coordinate_y, imagecolorallocate($im, 255, 255, 255), $font, 'Region');

		//imagepstext($im, 'Region', $fontc_pfb, 10, imagecolorallocate($im, 255, 255, 255), imagecolorallocate($im, 20, 20, 20), $start_coordinate_x + 225, $start_coordinate_y);







		//upper vertical 4
		//imageline($im, $image_size_x - 100, $start_coordinate_y, $image_size_x - 100, 430, imagecolorallocatealpha($im, 255, 255, 255, 100));


		for($x1=1; $x1 < $num; $x1++){
			$r = $db->fetch_assoc();

			$ally_R = hexdec(substr($r["fromAllyColor"],0,2));
			$ally_G = hexdec(substr($r["fromAllyColor"],2,2));
			$ally_B = hexdec(substr($r["fromAllyColor"],4,2));

			$from_ally_col = imagecolorallocate($im, $ally_R, $ally_G, $ally_B);

			$ally_R = hexdec(substr($r["toAllyColor"],0,2));
			$ally_G = hexdec(substr($r["toAllyColor"],2,2));
			$ally_B = hexdec(substr($r["toAllyColor"],4,2));

			$to_ally_col = imagecolorallocate($im, $ally_R, $ally_G, $ally_B);




			imagefttext($im, 8, 0, $start_coordinate_x, $start_coordinate_y + 22, $from_ally_col, $font, substr($r["fromAllianceID"],0,13));
			//imagepstext($im, substr($r["fromAllianceID"],0,13), $fontc_pfb , 10, $from_ally_col, imagecolorallocate($im, 20, 20, 20), $start_coordinate_x, $start_coordinate_y + 22, 0, 0, 0, 16);
			//imagestring($im, 1, $start_coordinate_x, $start_coordinate_y + 22,  substr($r["fromAllianceID"],0,13) , $from_ally_col);
			//imagechar($im, 1, $start_coordinate_x, $start_coordinate_y + 22, substr($r["fromAllianceID"],0,13) , $from_ally_col);

			imagefttext($im, 8, 0, $start_coordinate_x + 80, $start_coordinate_y + 22, $to_ally_col, $font, substr($r["toAllianceID"],0,13));
			//imagepstext($im, substr($r["toAllianceID"],0,13), $fontc_pfb, 10, $to_ally_col, imagecolorallocate($im, 20, 20, 20), $start_coordinate_x + 80, $start_coordinate_y + 22);




			if($r["outpost"] != 0) {

				imagesetthickness ($im, 1.5);

				$x_pos = $start_coordinate_x + 165;
				$y_pos = $start_coordinate_y + 18;


				//color outpost
				if($r["toAllianceID"] != ''){
					$ally_col = $to_ally_col;
				}
				else {
					$ally_col = imagecolorallocate($im, 255, 255, 255);
				}

			//imagefttext($im, 7, 0, $start_coordinate_x + 210, $start_coordinate_y + 12, imagecolorallocate($im, 255, 255, 255), $font, $r["SystemName"]);
				imagerectangle( $im , $x_pos - 4 , $y_pos - 4 , $x_pos + 4 , $y_pos + 4 , imagecolorallocate($im, 0, 0, 0) );
				imagerectangle( $im , $x_pos - 3 , $y_pos - 3 , $x_pos + 3 , $y_pos + 3 , $ally_col );
				imagesetthickness ($im, 1);

			}

			imagefttext($im, 8, 0, $start_coordinate_x + 174, $start_coordinate_y + 22, imagecolorallocate($im, 255, 255, 255), $font, $r["SystemName"]);
			//imagepstext($im, $r["SystemName"], $fontc_pfb, 10, imagecolorallocate($im, 255, 255, 255), imagecolorallocate($im, 20, 20, 20), $start_coordinate_x + 174, $start_coordinate_y + 22);



			imagefttext($im, 8, 0, $start_coordinate_x + 225, $start_coordinate_y + 22, imagecolorallocate($im, 255, 255, 255), $font, $r["regionName"]);

			//imagepstext($im, $r["regionName"], $fontc_pfb, 10, imagecolorallocate($im, 255, 255, 255), imagecolorallocate($im, 20, 20, 20), $start_coordinate_x + 225, $start_coordinate_y + 22);

			$start_coordinate_y += 14;
		}





////////////////////////////////////////////////////////////////////////////////////////////////////////
echo date(r) .' Starting show date generate...'."\n\r";

//imagestring($im, 4, 10 , 10,  'Generate date: '. date('G:i:s d.m.Y') , imagecolorallocatealpha($im, 255, 255, 255, 100));


$fontc_pfb = imagepsloadfont('ariac.pfb');

//imagepstext($im, 'Generate date: '. date('G:i:s d.m.Y') , $fontc_pfb, 13, imagecolorallocate($im, 255, 255, 255), imagecolorallocate($im, 20, 20, 20), 10, 20, 0, 0, 0, 16);

imagefttext($im, 10, 0, 10, 20, imagecolorallocate($im, 255, 255, 255), $font, 'Generate date: '. date('G:i:s d.m.Y') .'   v1.04');
			





////////////////////////////////////////////////////////////////////////////////////////////////////////
echo date(r) .' Starting show legend...'."\n\r";

		$start_coordinate_x = $image_size_x - 770;
		$start_coordinate_y = 18;

			$values = array(
            			$start_coordinate_x - 5,  $start_coordinate_y - 5,  // Point 1 (x, y)
            			$start_coordinate_x,  $start_coordinate_y + 5,  // Point 2 (x, y)
            			$start_coordinate_x + 5,  $start_coordinate_y - 5,  // Point 3 (x, y)
           		 );


		imagepolygon($im, $values, 3, imagecolorallocate($im, 255, 255, 255));
		imagefttext($im, 8, 0, $start_coordinate_x + 8, $start_coordinate_y + 4, imagecolorallocate($im, 255, 255, 255), $font, '- Constellation Capital');
		//imagepstext($im, '- Constellation Capital', $fontc_pfb, 10, imagecolorallocate($im, 255, 255, 255), imagecolorallocate($im, 20, 20, 20), $start_coordinate_x + 8, $start_coordinate_y + 4, 0, 0, 0, 16);




		//imagepstext($im, '- Constellation Capital', $font_pfb, 8, imagecolorallocate($im, 255, 255, 255), imagecolorallocate($im, 20, 20, 20), $start_coordinate_x + 8, $start_coordinate_y + 4, 0, 0, 0, 16);


		imagerectangle( $im , $start_coordinate_x - 3 , $start_coordinate_y + 9 , $start_coordinate_x + 3 , $start_coordinate_y + 15 , imagecolorallocate($im, 255, 255, 255) );
		imagefttext($im, 8, 0, $start_coordinate_x + 8, $start_coordinate_y + 15, imagecolorallocate($im, 255, 255, 255), $font, '- Station');
		//imagepstext($im, '- Station', $fontc_pfb, 10, imagecolorallocate($im, 255, 255, 255), imagecolorallocate($im, 20, 20, 20), $start_coordinate_x + 8, $start_coordinate_y + 15, 0, 0, 0, 16);


		imagefilledellipse( $im , $start_coordinate_x  , $start_coordinate_y + 23 , 1.4 * 2 , 1.4 * 2, imagecolorallocate($im, 255, 255, 255) );
		imagefttext($im, 8, 0, $start_coordinate_x + 8, $start_coordinate_y + 26, imagecolorallocate($im, 255, 255, 255), $font, '- Claimed system');
		//imagepstext($im, '- Claimed system', $fontc_pfb, 10, imagecolorallocate($im, 255, 255, 255), imagecolorallocate($im, 20, 20, 20), $start_coordinate_x + 8, $start_coordinate_y + 26, 0, 0, 0, 16);


		imagesetthickness ($im, 2);
		//imagerectangle( $im , $start_coordinate_x - 7 , $start_coordinate_y + 41 - 8 , $start_coordinate_x + 2 + 8 , $start_coordinate_y + 41 + 8 , imagecolorallocatealpha($im, 255, 0, 0, 50) );


		imageellipse( $im , $start_coordinate_x  , $start_coordinate_y + 41 , 15 , 15, imagecolorallocatealpha($im, 255, 0, 0, 50) );


		imagefttext($im, 8, 0, $start_coordinate_x + 18, $start_coordinate_y + 44, imagecolorallocate($im, 255, 255, 255), $font, '- Ship kills last 24 hours');
		//imagepstext($im, '- Ship kills last 24 hours', $fontc_pfb, 10, imagecolorallocate($im, 255, 255, 255), imagecolorallocate($im, 20, 20, 20), $start_coordinate_x + 18, $start_coordinate_y + 44, 0, 0, 0, 16);


		imagesetthickness ($im, 3);

		imageline($im, $start_coordinate_x - 5, $start_coordinate_y + 64, $start_coordinate_x + 9, $start_coordinate_y + 64, imagecolorallocatealpha($im, 0, 255, 255, 98));

		imagefttext($im, 8, 0, $start_coordinate_x + 18, $start_coordinate_y + 67, imagecolorallocate($im, 255, 255, 255), $font, '- Big jumps last 24 hours');
		//imagepstext($im, '- Big jumps last 24 hours', $fontc_pfb, 10, imagecolorallocate($im, 255, 255, 255), imagecolorallocate($im, 20, 20, 20), $start_coordinate_x + 18, $start_coordinate_y + 67, 0, 0, 0, 16);




////////////////////////////////////////////////////////////////////////////////////////////////////////
echo date(r) .' Starting show allianceName...'."\n\r";

$db->query('SELECT 
x, 
z, 
solarSystemID, 
(select name FROM evealliances where id=mapsolarsystems.allianceID ) as AllyName, 
(select color FROM evealliances where id=mapsolarsystems.allianceID ) as AllyColor,
stantion, 
constellationID, 
regionID, 
sovereigntylevel, 
constellationSov 
FROM mapsolarsystems 
where allianceID<>0 AND allianceID<>\'748088119\' order by sovereigntylevel desc');


$num = $db->num_rows();



	//$font = 'ariac.pfb';





unset($mass);

for($x1=0; $x1 < $num; $x1++){
	$r = $db->fetch_assoc();
	
	$x_pos = ( $r['x'] / $scale ) + $sWidth / 2+ $sX;
	$y_pos = ( $r['z'] / $scale ) + $sHeight / 2 + $sY;


	$str_x_pos = $x_pos + 20;
	$str_y_pos = $y_pos;



	if($r["AllyColor"] != ""){


		$ally_R = hexdec(substr($r["AllyColor"],0,2));
		$ally_G = hexdec(substr($r["AllyColor"],2,2));
		$ally_B = hexdec(substr($r["AllyColor"],4,2));

		$ally_col = imagecolorallocate($im, $ally_R, $ally_G, $ally_B);
		$ally_col_alpha = imagecolorallocatealpha($im, $ally_R, $ally_G, $ally_B, 90);

		$dark = imagecolorallocatealpha($im, 255, 0, 0, 20);

		imagesetthickness ($im, 1);
		
		if($r["sovereigntylevel"] == 4){


			//check already name showed for region 
			if($mass[$r["AllyName"]][$r["regionID"]] == 0){


				//$m_pos_txt[$x1] = $m_pos_tetx[7];

				//$m_pos_tetx = imagefttext($im, 16, 0, $x_pos + 20, $y_pos, -imagecolorallocate($im, $ally_R, $ally_G, $ally_B), $font, $r["AllyName"]);







				for($z1 = 0; $z1 < sizeof($m_pos_txt); $z1++){

					/*

					x3y3        x2y2
					    _______
					   |          |
					   |______|

					x1y1        x4y4

					*/

					//old text rectangle coordinate
					$x1_rect_old = $m_pos_txt[$z1][x1];
					$y1_rect_old = $m_pos_txt[$z1][y1];

					$x2_rect_old = $m_pos_txt[$z1][x2];
					$y2_rect_old = $m_pos_txt[$z1][y2];

					$x3_rect_old = $m_pos_txt[$z1][x3];
					$y3_rect_old = $m_pos_txt[$z1][y3];

					$x4_rect_old = $m_pos_txt[$z1][x4];
					$y4_rect_old = $m_pos_txt[$z1][y4];


					/*

					x3y3        x2y2
					    _______
					   |          |
					   |______|

					x1y1        x4y4

					*/

					//new text rectangle coordinate
					$x1_rect_new = $str_x_pos - 10;
					$y1_rect_new = $str_y_pos + 5;

					$x2_rect_new = $str_x_pos + ( strlen($r["AllyName"]) * 11 );
					$y2_rect_new = $str_y_pos - 20 ;

					$x3_rect_new = $str_x_pos - 10;
					$y3_rect_new = $str_y_pos - 20;

					$x4_rect_new = $str_x_pos +  ( strlen($r["AllyName"]) * 11 );
					$y4_rect_new = $str_y_pos + 5;



					//Checked all rectangle(around new text) coordiante, 
					//
					//x1y1 - new string coordinate
					if(
					  ( $y1_rect_old <= $y1_rect_new ) && 
					  ( $y2_rect_old >= $y1_rect_new )
					  ) 
					 {
						if(
					  	( $x1_rect_old <= $x1_rect_new ) && 
					  	( $x2_rect_old >= $x1_rect_new )
					  	) 
					 	{
 							$str_y_pos -= 15;

						}
echo "n1\n\r";
					 }
					//x2y2 - new string coordinate
					else if(
					  ( $y1_rect_old <= $y2_rect_new ) && 
					  ( $y2_rect_old >= $y2_rect_new )
					  ) 
					 {
						if(
					  	( $x1_rect_old <= $x2_rect_new ) && 
					  	( $x2_rect_old >= $x2_rect_new )
					  	) 
					 	{
 							$str_y_pos -= 25;
							//echo '111'."\n\r";


						}
echo "n2\n\r";
					 }
					//x3y3 - new string coordinate
					else if(
					  ( $y1_rect_old <= $y3_rect_new ) && 
					  ( $y2_rect_old >= $y3_rect_new )
					  ) 
					 {
						if(
					  	( $x1_rect_old >= $x3_rect_new ) && 
					  	( $x2_rect_old <= $x3_rect_new )
					  	) 
					 	{
 							$str_y_pos -= 15;
							//echo '111'."\n\r";


						}
echo "n3\n\r";
					 }
					//x4y4 - new string coordinate
					else if(
					  ( $y1_rect_old <= $y4_rect_new ) && 
					  ( $y2_rect_old >= $y4_rect_new )
					  ) 
					 {
						if(
					  	( $x1_rect_old >= $x4_rect_new ) && 
					  	( $x2_rect_old <= $x4_rect_new )
					  	) 
					 	{
 							$str_y_pos -= 15;
							//echo '111'."\n\r";


						}
echo "n4\n\r";
					 }

				}


//imagerectangle( $im , $str_x_pos, $str_y_pos - 10, $str_x_pos - 0, $str_y_pos - 0 , imagecolorallocate($im, 255, 255, 255) );

//imagerectangle( $im , $str_x_pos + ( strlen($r["AllyName"]) * 11 ) - 10, $str_y_pos - 10, $str_x_pos + ( strlen($r["AllyName"]) * 11 ), $str_y_pos - 0 , imagecolorallocate($im, 255, 255, 255) );



				$m_pos_txt[$x1]['x1'] = $str_x_pos;
				$m_pos_txt[$x1]['y1'] = $str_y_pos - 20;

				$m_pos_txt[$x1]['x2'] =  $str_x_pos + ( strlen($r["AllyName"]) * 11 );
				$m_pos_txt[$x1]['y2'] = $str_y_pos + 5 ;

				$m_pos_txt[$x1]['x3'] = $m_pos_txt[$x1]['x1'];
				$m_pos_txt[$x1]['y3'] = $m_pos_txt[$x1]['y2'];

				$m_pos_txt[$x1]['x4'] = $m_pos_txt[$x1]['x2'];
				$m_pos_txt[$x1]['y4'] = $m_pos_txt[$x1]['y1'];













				imagepstext($im, $r["AllyName"], $font_pfb, 16, imagecolorallocate($im, $ally_R, $ally_G, $ally_B), imagecolorallocate($im, 20, 20, 20), $str_x_pos, $str_y_pos, 0, 0, 0, 16);

				//show rectange around text
				//imagerectangle( $im , $str_x_pos - 10 , $str_y_pos - 20 , $str_x_pos + ( strlen($r["AllyName"]) * 11 ), $str_y_pos + 5 , imagecolorallocate($im, 255, 255, 255) );





				//echo $m_pos_tetx[7] .' - '. $r["AllyName"] ."\n\r";

				$mass[$r["AllyName"]][$r["regionID"]]++;
			}
		}

		else if(($r["sovereigntylevel"] == 3) and ($r["stantion"] != 0)){

			//imagefilledellipse ($im, $x_pos, $y_pos, 40, 40, imagecolorallocatealpha($im, $ally_R, $ally_G, $ally_B, 90));


			//imagestring($im, 4, $x_pos + 20 , $y_pos, $r["AllyName"] , imagecolorallocate($im, $ally_R, $ally_G, $ally_B));

			//check already showed for region
			if($mass[$r["AllyName"]][$r["regionID"]] == 0){
				//imagefttext($im, 10, 0, $x_pos + 20, $y_pos, imagecolorallocate($im, $ally_R, $ally_G, $ally_B), $font, $r["AllyName"]);

				//$str_x_pos = $x_pos + 20;
				//$str_y_pos = $y_pos;


				//$m_pos_txt[$x1]['x1'] = $str_x_pos - 10;
				//$m_pos_txt[$x1]['y1'] = $str_y_pos - 13;

				//$m_pos_txt[$x1]['x2'] =  $str_x_pos + ( strlen($r["AllyName"]) * 6.8 );
				//$m_pos_txt[$x1]['y2'] = $str_y_pos + 5 ;




				for($z1 = 0; $z1 < sizeof($m_pos_txt); $z1++){

					/*

					x3y3        x2y2
					    _______
					   |          |
					   |______|

					x1y1        x4y4

					*/

					//old text rectangle coordinate
					$x1_rect_old = $m_pos_txt[$z1][x1];
					$y1_rect_old = $m_pos_txt[$z1][y1];

					$x2_rect_old = $m_pos_txt[$z1][x2];
					$y2_rect_old = $m_pos_txt[$z1][y2];

					$x3_rect_old = $m_pos_txt[$z1][x3];
					$y3_rect_old = $m_pos_txt[$z1][y3];

					$x4_rect_old = $m_pos_txt[$z1][x4];
					$y4_rect_old = $m_pos_txt[$z1][y4];


					/*

					x3y3        x2y2
					    _______
					   |          |
					   |______|

					x1y1        x4y4

					*/

					//new text rectangle coordinate
					$x1_rect_new = $str_x_pos - 10;
					$y1_rect_new = $str_y_pos + 5;

					$x2_rect_new = $str_x_pos + ( strlen($r["AllyName"]) * 11 );
					$y2_rect_new = $str_y_pos - 20 ;

					$x3_rect_new = $str_x_pos - 10;
					$y3_rect_new = $str_y_pos - 20;

					$x4_rect_new = $str_x_pos +  ( strlen($r["AllyName"]) * 11 );
					$y4_rect_new = $str_y_pos + 5;



					//Checked all rectangle(around new text) coordiante, 
					//
					//x1y1 - new string coordinate
					if(
					  ( $y1_rect_old <= $y1_rect_new ) && 
					  ( $y2_rect_old >= $y1_rect_new )
					  ) 
					 {
						if(
					  	( $x1_rect_old <= $x1_rect_new ) && 
					  	( $x2_rect_old >= $x1_rect_new )
					  	) 
					 	{
 							$str_y_pos -= 15;

						}
echo "n1\n\r";
					 }
					//x2y2 - new string coordinate
					else if(
					  ( $y1_rect_old <= $y2_rect_new ) && 
					  ( $y2_rect_old >= $y2_rect_new )
					  ) 
					 {
						if(
					  	( $x1_rect_old <= $x2_rect_new ) && 
					  	( $x2_rect_old >= $x2_rect_new )
					  	) 
					 	{
 							$str_y_pos -= 25;
							//echo '111'."\n\r";


						}
echo "n2\n\r";
					 }
					//x3y3 - new string coordinate
					else if(
					  ( $y1_rect_old <= $y3_rect_new ) && 
					  ( $y2_rect_old >= $y3_rect_new )
					  ) 
					 {
						if(
					  	( $x1_rect_old >= $x3_rect_new ) && 
					  	( $x2_rect_old <= $x3_rect_new )
					  	) 
					 	{
 							$str_y_pos -= 15;
							//echo '111'."\n\r";


						}
echo "n3\n\r";
					 }
					//x4y4 - new string coordinate
					else if(
					  ( $y1_rect_old <= $y4_rect_new ) && 
					  ( $y2_rect_old >= $y4_rect_new )
					  ) 
					 {
						if(
					  	( $x1_rect_old >= $x4_rect_new ) && 
					  	( $x2_rect_old <= $x4_rect_new )
					  	) 
					 	{
 							$str_y_pos -= 15;
							//echo '111'."\n\r";


						}
echo "n4\n\r";
					 }

				}




				imagefttext($im, 10, 0, $x_pos + 20, $y_pos, imagecolorallocate($im, $ally_R, $ally_G, $ally_B), $font, $r["AllyName"]);

				//imagepstext($im, $r["AllyName"], $font_pfb, 13, imagecolorallocate($im, $ally_R, $ally_G, $ally_B), imagecolorallocate($im, 0, 0, 0), $x_pos + 20, $y_pos, 0, 0, 0, 16);


				//show rectange around text
				//imagerectangle( $im , $str_x_pos - 10 , $str_y_pos - 13 , $str_x_pos + ( strlen($r["AllyName"]) * 6.8 ), $str_y_pos + 5 , imagecolorallocate($im, 255, 255, 255) );


				$mass[$r["AllyName"]][$r["regionID"]]++;
			}

		}
		else if(($r["sovereigntylevel"] < 3) and ($r["stantion"] != 0)){

			//check already showed for region
			if($mass[$r["AllyName"]][$r["regionID"]] == 0){



				imagefttext($im, 10, 0, $x_pos + 20, $y_pos, imagecolorallocate($im, $ally_R, $ally_G, $ally_B), $font, $r["AllyName"]);

				//imagepstext($im, $r["AllyName"], $font_pfb, 9, imagecolorallocate($im, $ally_R, $ally_G, $ally_B), imagecolorallocate($im, 0, 0, 0), $x_pos + 10, $y_pos, 0, 0, 0, 16);


				//show rectange around text
				//imagerectangle( $im , $str_x_pos - 10 , $str_y_pos - 13 , $str_x_pos + ( strlen($r["AllyName"]) * 6.8 ), $str_y_pos + 5 , imagecolorallocate($im, 255, 255, 255) );


				$mass[$r["AllyName"]][$r["regionID"]]++;
			}

		}

	}

}


////////////////////////////////////////////////////////////////////////////////////////////////////////
echo date(r) .' Starting show top10 kills...'."\n\r";

$db->query('SELECT 
solarsystem_jumps_kills.solarSystemID AS solarSystemID, 
solarsystem_jumps_kills.shipKills AS shipKills, 
solarsystem_jumps_kills.podKills AS podKills, 
mapsolarsystems.solarSystemName AS SystemName, 
(select mapregions.regionName FROM mapregions where mapregions.regionID=mapsolarsystems.regionID) as regionName 
FROM solarsystem_jumps_kills 
LEFT JOIN 
mapsolarsystems 
ON mapsolarsystems.solarSystemID=solarsystem_jumps_kills.solarSystemID 
WHERE 
solarsystem_jumps_kills.shipkills>2 AND 
mapsolarsystems.security<0 
GROUP BY solarSystemID
ORDER BY solarsystem_jumps_kills.shipKills DESC limit 21');




		$num = $db->num_rows();

		$start_coordinate_x = 450;
		$start_coordinate_y = 38;

		imagefttext($im, 9, 0, $start_coordinate_x - 25, $start_coordinate_y, imagecolorallocate($im, 255, 255, 255), $font, 'Pod');

		//imagepstext($im, 'Pod' , $fontc_pfb, 10, imagecolorallocate($im, 255, 255, 255), imagecolorallocate($im, 20, 20, 20), $start_coordinate_x - 25, $start_coordinate_y, 0, 0, 0, 16);


		//upper horizontal
		//imageline($im, $image_size_x , 30, $image_size_x, 30, imagecolorallocatealpha($im, 255, 255, 255, 100));

		//caption Sov. lost
		//imagefttext($im, 7, 0, $start_coordinate_x, $start_coordinate_y, imagecolorallocate($im, 255, 255, 255), $font, 'Sov. lost');

		imagefttext($im, 9, 0, $start_coordinate_x, $start_coordinate_y, imagecolorallocate($im, 255, 255, 255), $font, 'Kills');

		//imagepstext($im, 'Kills' , $fontc_pfb, 10, imagecolorallocate($im, 255, 255, 255), imagecolorallocate($im, 20, 20, 20), $start_coordinate_x, $start_coordinate_y, 0, 0, 0, 16);


		//upper vertical 1
		//imageline($im, $image_size_x + 480, 10, $image_size_x - 480, 430, imagecolorallocatealpha($im, 255, 255, 255, 100));


		//caption Sov. gain
		//imagefttext($im, 7, 0, $start_coordinate_x + 110, $start_coordinate_y, imagecolorallocate($im, 255, 255, 255), $font, 'Sov. gain');
		//imagepstext($im, 'System', $fontc_pfb, 10, imagecolorallocate($im, 255, 255, 255), imagecolorallocate($im, 20, 20, 20), $start_coordinate_x + 30, $start_coordinate_y, 0, 0, 0, 16);

		imagefttext($im, 9, 0, $start_coordinate_x + 30, $start_coordinate_y, imagecolorallocate($im, 255, 255, 255), $font, 'System');


		//upper vertical 2
		//imageline($im, $image_size_x - 260, 10, $image_size_x - 260, 430, imagecolorallocatealpha($im, 255, 255, 255, 100));


		//caption System
		//imagefttext($im, 7, 0, $start_coordinate_x + 210, $start_coordinate_y, imagecolorallocate($im, 255, 255, 255), $font, 'System');
		//imagepstext($im, 'Region', $fontc_pfb, 10, imagecolorallocate($im, 255, 255, 255), imagecolorallocate($im, 20, 20, 20), $start_coordinate_x + 90, $start_coordinate_y, 0, 0, 0, 16);
		imagefttext($im, 9, 0, $start_coordinate_x + 90, $start_coordinate_y, imagecolorallocate($im, 255, 255, 255), $font, 'Region');





		for($x1=1; $x1 < $num; $x1++){
			$r = $db->fetch_assoc();

			//$ally_R = hexdec(substr($r["fromAllyColor"],0,2));
			//$ally_G = hexdec(substr($r["fromAllyColor"],2,2));
			//$ally_B = hexdec(substr($r["fromAllyColor"],4,2));

			//$from_ally_col = imagecolorallocate($im, $ally_R, $ally_G, $ally_B);

			//$ally_R = hexdec(substr($r["toAllyColor"],0,2));
			//$ally_G = hexdec(substr($r["toAllyColor"],2,2));
			//$ally_B = hexdec(substr($r["toAllyColor"],4,2));

			//$to_ally_col = imagecolorallocate($im, $ally_R, $ally_G, $ally_B);

			//imagepstext($im, $r["podKills"], $fontc_pfb, 10, imagecolorallocate($im, 51, 204, 51), imagecolorallocate($im, 20, 20, 20), $start_coordinate_x - 25, $start_coordinate_y + 22, 0, 0, 0, 16);
			imagefttext($im, 9, 0, $start_coordinate_x - 25, $start_coordinate_y + 22, imagecolorallocate($im, 51, 204, 51), $font, $r["podKills"]);




			//imagefttext($im, 9, 0, $start_coordinate_x, $start_coordinate_y + 22, $from_ally_col, $font, substr($r["fromAllianceID"],0,13));
			//imagepstext($im, $r["shipKills"], $fontc_pfb, 10, imagecolorallocate($im, 255, 0, 0), imagecolorallocate($im, 20, 20, 20), $start_coordinate_x, $start_coordinate_y + 22, 0, 0, 0, 16);
			imagefttext($im, 9, 0, $start_coordinate_x, $start_coordinate_y + 22, imagecolorallocate($im, 255, 0, 0), $font, $r["shipKills"]);


			//imagefttext($im, 9, 0, $start_coordinate_x + 110, $start_coordinate_y + 22, $to_ally_col, $font, substr($r["toAllianceID"],0,13));
			//imagepstext($im, substr($r["SystemName"],0,13), $fontc_pfb, 11, imagecolorallocate($im, 255, 255, 255), imagecolorallocate($im, 20, 20, 20), $start_coordinate_x + 30, $start_coordinate_y + 22, 0, 0, 0, 16);
			imagefttext($im, 9, 0, $start_coordinate_x + 30, $start_coordinate_y + 22, imagecolorallocate($im, 255, 255, 255), $font, substr($r["SystemName"],0,13));


			//imagepstext($im, substr($r["regionName"],0,13), $fontc_pfb, 11, imagecolorallocate($im, 255, 255, 255), imagecolorallocate($im, 20, 20, 20), $start_coordinate_x + 90, $start_coordinate_y + 22, 0, 0, 0, 16);

			imagefttext($im, 9, 0, $start_coordinate_x + 90, $start_coordinate_y + 22, imagecolorallocate($im, 255, 255, 255), $font, substr($r["regionName"],0,13));


			//if($r["outpost"] != 0) {

				//imagesetthickness ($im, 1.5);

				//$x_pos = $start_coordinate_x + 201;
				//$y_pos = $start_coordinate_y + 18;


				//color outpost
				//if($r["toAllianceID"] != ''){
				//	$ally_col = $to_ally_col;
				//}
				//else {
				//	$ally_col = imagecolorallocate($im, 255, 255, 255);
				//}

			//imagefttext($im, 7, 0, $start_coordinate_x + 210, $start_coordinate_y + 12, imagecolorallocate($im, 255, 255, 255), $font, $r["SystemName"]);
				//imagerectangle( $im , $x_pos - 4 , $y_pos - 4 , $x_pos + 4 , $y_pos + 4 , imagecolorallocate($im, 0, 0, 0) );
				//imagerectangle( $im , $x_pos - 3 , $y_pos - 3 , $x_pos + 3 , $y_pos + 3 , $ally_col );
				//imagesetthickness ($im, 1);

			//}

			//imagefttext($im, 7, 0, $start_coordinate_x + 210, $start_coordinate_y + 22, imagecolorallocate($im, 255, 255, 255), $font, $r["SystemName"]);
			//imagepstext($im, $r["SystemName"], $fontc_pfb, 13, imagecolorallocate($im, 255, 255, 255), imagecolorallocate($im, 20, 20, 20), $start_coordinate_x + 210, $start_coordinate_y + 22, 0, 0, 0, 16);



			//imagefttext($im, 7, 0, $start_coordinate_x + 270, $start_coordinate_y + 22, imagecolorallocate($im, 255, 255, 255), $font, $r["regionName"]);

			//imagepstext($im, $r["regionName"], $fontc_pfb, 13, imagecolorallocate($im, 255, 255, 255), imagecolorallocate($im, 20, 20, 20), $start_coordinate_x + 270, $start_coordinate_y + 22, 0, 0, 0, 16);

			$start_coordinate_y += 14;
		}





$db->free_result();


imagesetthickness ($im, 0.5);

////////////////////////////////////////////////////////////////////////////////////////////////////////
echo date(r) .' Starting show sum kills, jumps, faction kills, pods...'."\n\r";

/*

SELECT 
sum(solarsystem_jumps_kills.shipKills) AS shipKills, 
sum(solarsystem_jumps_kills.podKills) AS podKills, 
sum(solarsystem_jumps_kills.factionKills) AS factionKills, 
sum(solarsystem_jumps_kills.shipJumps) AS Jumps 
FROM solarsystem_jumps_kills

*/

$db->query('SELECT 
sum(solarsystem_jumps_kills.shipKills) AS shipKills, 
sum(solarsystem_jumps_kills.podKills) AS podKills, 
sum(solarsystem_jumps_kills.factionKills) AS factionKills, 
sum(solarsystem_jumps_kills.shipJumps) AS Jumps 
FROM solarsystem_jumps_kills');




		$num = $db->num_rows();

		$r = $db->fetch_assoc();


		//imagepstext($im, 'Kills: '. $r["shipKills"] , $fontc_pfb, 13, imagecolorallocate($im, 255, 0, 0), imagecolorallocate($im, 20, 20, 20), 425, 20, 0, 0, 0, 16);
		imagefttext($im, 11, 0, 425, 20, imagecolorallocate($im, 255, 0, 0), $font, 'Kills: '. number_format($r["shipKills"], 0, '.', ' '));

		//imagepstext($im, 'Pod: '. $r["podKills"] , $fontc_pfb, 13, imagecolorallocate($im, 51, 204, 51), imagecolorallocate($im, 20, 20, 20), 505, 20, 0, 0, 0, 16);
		imagefttext($im, 11, 0, 505, 20, imagecolorallocate($im, 51, 204, 51), $font, 'Pod: '. number_format($r["podKills"], 0, '.', ' '));


		//imagepstext($im, 'Jumps: '. $r["Jumps"] , $fontc_pfb, 13, imagecolorallocate($im, 0, 204, 204), imagecolorallocate($im, 20, 20, 20), 705, 20, 0, 0, 0, 16);
		imagefttext($im, 11, 0, 585, 20, imagecolorallocate($im, 0, 204, 204), $font, 'Jumps: '.  number_format($r["Jumps"], 0, '.', ' '));


		//imagepstext($im, 'Faction: '. $r["factionKills"] , $fontc_pfb, 13, imagecolorallocate($im, 204, 204, 0), imagecolorallocate($im, 20, 20, 20), 585, 20, 0, 0, 0, 16);
		imagefttext($im, 11, 0, 705, 20, imagecolorallocate($im, 204, 204, 0), $font, 'Faction: '. number_format($r["factionKills"], 0, '.', ' '));



echo date(r) .'Done.'."\n\r";


imagepng($im, 'map.png', 4);

imagedestroy($im);
?> 

