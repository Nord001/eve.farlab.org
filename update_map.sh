cd /home/farlab/eve_scripts/data/
unlink AllianceList.xml.aspx
unlink ConquerableStationList.xml.aspx
unlink Sovereignty.xml.aspx
curl --progress-bar --remote-name http://api.eve-online.com/map/Jumps.xml.aspx
curl --progress-bar --remote-name http://api.eve-online.com/map/Kills.xml.aspx
curl --progress-bar --remote-name http://api.eve-online.com/map/Sovereignty.xml.aspx
curl --progress-bar --remote-name http://api.eve-online.com/eve/AllianceList.xml.aspx
curl --progress-bar --remote-name http://api.eve-online.com/eve/ConquerableStationList.xml.aspx
cd /home/farlab/eve_scripts/
/opt/php/bin/php -c /home/farlab/etc/php.ini /home/farlab/eve_scripts/update_map.php
