Source code, make map for game Eve-online. https://www.eveonline.com/

Map generate every day http://eve.farlab.org

Remarks:

This code is far from ideal. Since 2011, I have not followed the changes in eveapi.

At the same time, the project was pretty simple. Since 2009, there were only 2 small incident.

The project is published, because requests to the source code.


How it works?

cron(every hour) -> 
1) download from EVE API kills and jumps data.
2) parse and fill db

cron(every day) ->
1) downlaod from EVE API data: Sovereignty, AllianceList, ConquerableStationList
2) parse and fill db
3) make ally stat for ranking
4) started stored procedure for agregating data kills and jumps.
5) map create:
	- Create image(gd)
	- show jumps claim 0.0
	- show influence
	- show npc outposts
	- show claim system
	- show kills 24 hours
	- show jumps in 0.0
	- show jumps activities
	- show systems
	- show claim system change
	- show region name
	- show ally stat
	- show sov change list
	- show allianceName
	- show top10 kills


12.09.2010.rar - mysql dump ~50mb unpack

