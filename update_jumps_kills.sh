cd /home/farlab/eve_scripts/data/
unlink Kills.xml.aspx
unlink Jumps.xml.aspx
curl --progress-bar --remote-name http://api.eve-online.com/map/Jumps.xml.aspx
curl --progress-bar --remote-name http://api.eve-online.com/map/Kills.xml.aspx
cd /home/farlab/eve_scripts/
/opt/php/bin/php -c /home/farlab/etc/php.ini /home/farlab/eve_scripts/update_jumps_kills.php
