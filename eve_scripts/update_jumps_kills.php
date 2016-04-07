<?php

$ch = curl_init("http://api.eve-online.com/map/Kills.xml.aspx");
$fp = fopen("data/Kills.xml.aspx", "w");


require('0.3.prepare_kills.php');
require('0.4.prepare_jump.php');

?>
