<?php

set_time_limit(0);


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
