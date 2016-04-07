<?php

include_once("php/include/database.php");

$db = new db;
$db1 = new db;

$xml = simplexml_load_file('data\AllianceList.xml.aspx');

$x=0;

$ally['Pure.'] = '8042BC';
$ally['Utterly  Harmless'] = '105326';
$ally['Free Trade Zone.'] = '770314';
$ally['Executive Outcomes'] = 'a0fc64';
$ally['Tau Ceti Federation'] = 'BC6800';
$ally['Veritas Immortalis'] = '739862';
$ally['Zenith Affinity'] = '932850';
$ally['Zzz'] = '562047';
$ally['Sons of Tangra'] = '166559';
$ally['Atlas Alliance'] = '0051FF';
$ally['Against ALL Authorities'] = '377808';
$ally['Imperial Republic Of the North'] = '4280BC';
$ally['Guardian Federation'] = '774832';
$ally['Frontal Impact'] = '521578';
$ally['Blade.'] = '464406';
$ally['Pandemic Legion'] = 'FF0071';//292982
$ally['Libertas Fidelitas'] = '155885';
$ally['Ethereal Dawn'] = '403294';
$ally['Tygris Alliance'] = '686996';
$ally['Rebellion Alliance'] = 'b2d799';
$ally['Vanguard.'] = '706100';
$ally['Southern Cross Alliance'] = '994880';
$ally['SOLAR WING'] = '502543';
$ally['Blue Sky Consortium'] = '50a1c3';
$ally['THORN Alliance'] = '408142';
$ally['FOUNDATI0N'] = '839271';
$ally['The Red Skull'] = '102619';
$ally['space weaponry and trade'] = '765194';
$ally['Morsus Mihi'] = 'FF6539';//991027
$ally['Band of Brothers'] = '0050FC';
$ally['Intrepid Crossing'] = '00FF00';
$ally['RAZOR Alliance'] = '7d6c27';
$ally['Axiom Empire'] = '396591';
$ally['GoonSwarm'] = 'fcfc00';
$ally['Violent-Tendencies'] = '747666';
$ally['United Freemen Alliance'] = '565342';
$ally['Sylph Alliance'] = '672554';
$ally['Triumvirate.'] = '731581';
$ally['United Legion'] = '58acfc';
$ally['Ivy League'] = '872513';
$ally['Sev3rance'] = '284363';
$ally['Caeruleum Alliance'] = '451579';
$ally['A.X.I.S'] = '32484e';
$ally['Ultima Rati0'] = '876240';
$ally['Kraftwerk.'] = '152576';
$ally['KIA Alliance'] = '74FFFF';//ea6036
$ally['C0VEN'] = '157539';
$ally['Skunk-Works'] = '747074';
$ally['SOLAR FLEET'] = '899259';//899259
$ally['Red Alliance'] = 'fc0400';
$ally['DeaDSpace Coalition'] = '179930';
$ally['Elitist Cowards'] = '364992';
$ally['G00DFELLAS'] = '998510';
$ally['Bionic Dawn'] = '577920';
$ally['Legion of xXDEATHXx'] = 'FF0071';
$ally['Paxton Federation'] = '278447';
$ally['New Eden  Research'] = '696988';
$ally['Daisho Syndicate'] = 'FF1059';//3b1ebe
$ally['Stella Polaris.'] = '686080';
$ally['StarFleet Federation'] = '329647';
$ally['Majesta Empire'] = '996999';
$ally['DEFI4NT'] = '743361';
$ally['Systematic-Chaos'] = '303100';
$ally['Mostly  Harmless'] = '8042BC';
$ally['Shadow of xXDEATHXx'] = '982A7D';
$ally['Arcane Alliance'] = '922786';
$ally['Wildly Inappropriate.'] = 'CCCCCC';
$ally['The Initiative.'] = 'E57406';
$ally['AAA Citizens'] = 'FF84A7';
$ally['Resurgency'] = 'dec387';
$ally['Curatores Veritatis Alliance'] = 'fc6438';
$ally['The Black Isle'] = '7538b9';
$ally['RED.OverLord'] = '8F6BD0';
$ally['KenZoku'] = '0051FF';
$ally['C0VEN'] = 'FF0400';
$ally['Varangians.'] = '808EF9';
$ally['Flame Bridge'] = '9FC1A8';



	$sql_sel = 'SELECT * FROM evealliances';

	$db->query($sql_sel);

	$num = $db->num_rows();


	for ($x=0;$x < $num; $x++) {
		$r = $db->fetch_assoc();

		$gg = $r['name'];


		if($ally[$gg] != ''){
			$sql1 = 'UPDATE evealliances SET color=\''. $ally[$gg] .'\' WHERE name=\''. $r['name'] .'\'';
			$db1->query($sql1);
			//echo $sql1 ."\n\r";
		}


	}



?>