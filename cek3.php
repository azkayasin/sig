<?php 
	$dbconn = pg_connect("host=localhost dbname=db_clearroute user=postgres password=postgres")
	or die('Could not connect: ' . pg_last_error());
	$query = "SELECT ST_AsGeoJSON(ST_UNION(c.geom)) AS geojson FROM (SELECT a.seq AS seq, b.gid AS gi, b.name AS name, a.cost AS cost, b.the_geom AS geom, b.source, b.target, b.x1 AS x, b.y1 AS y FROM pgr_dijkstra('SELECT gid::integer AS id, source::integer, target::integer, cost::double precision AS cost, reverse_cost::double precision AS reverse_cost, x1, y1, x2, y2 FROM ways', 912, 920, true, true) AS a LEFT JOIN ways AS b ON (a.id2 = b.gid) ORDER BY a.seq) AS c";
	$result = pg_query($query) or die('Query failed: ' . pg_last_error());
	$line = pg_fetch_array($result, null, PGSQL_ASSOC);
  	$newline=json_decode($line['geojson']);
  	echo (json_encode($newline));
 ?>