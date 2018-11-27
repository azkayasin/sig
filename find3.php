<?php
	$dbconn = pg_connect("host=localhost dbname=db_clearroute user=postgres password=postgres")
	or die('Could not connect: ' . pg_last_error());
	// $query = "SELECT ST_AsGeoJSON(ST_UNION(c.geom)) AS geojson FROM (SELECT a.seq AS seq, b.gid AS gi, b.name AS name, a.cost AS cost, b.the_geom AS geom, b.source, b.target, b.x1 AS x, b.y1 AS y FROM pgr_dijkstra('SELECT gid::integer AS id, source::integer, target::integer, cost::double precision AS cost, reverse_cost::double precision AS reverse_cost, x1, y1, x2, y2 FROM backup_ways', 912, 920, true, true) AS a LEFT JOIN ways AS b ON (a.id2 = b.gid) ORDER BY a.seq) AS c";
	$x1=$_GET['x1'];
	$y1=$_GET['y1'];
	$x2=$_GET['x2'];
	$y2=$_GET['y2'];
	$query = "SELECT ST_AsGeoJSON(ST_UNION(row.geom)) AS geojson FROM (SELECT * FROM pgr_normalroute('backup_ways',".$x1.",".$y1.",".$x2.",".$y2.")) AS row WHERE row.gid IS NOT NULL";
	//-7.2787819, 112.7901988/-7.2903795, 112.7997735
	//-7.2787819,+112.7901988/-7.290622,112.8003893
	// $query="SELECT gid::integer AS id, source::integer, target::integer , cost_clearroute * penalty::double precision AS cost, reverse_cost_s * penalty::double precision AS reverse_cost, x1, y1, x2, y2 FROM backup_ways AS r JOIN configuration USING (tag_id), (SELECT ST_Expand(ST_Extent(the_geom),0.1) as box FROM backup_ways as l1 WHERE l1.source = 9446 OR l1.target = 9036)as box WHERE r.the_geom && box.box";
	$result = pg_query($query) or die('Query failed: ' . pg_last_error());
	$astarl = pg_fetch_array($result, null, PGSQL_ASSOC);
	//echo(json_encode($line['geojson']));
	$astar=json_decode($astarl['geojson']);
	// $query = "SELECT ST_AsGeoJSON(ST_UNION(row.geom)) AS geojson FROM (SELECT * FROM pgr_djikstraroute('backup_ways',".$x1.",".$y1.",".$x2.",".$y2.")) AS row WHERE row.gid IS NOT NULL";
	// $result = pg_query($query) or die('Query failed: ' . pg_last_error());
	// $dijkstral = pg_fetch_array($result, null, PGSQL_ASSOC);
	// $dijkstra=json_decode($dijkstral['geojson']);
	$final = array(
		// "dijkstra" => $dijkstra,
		"astar" => $astar,
	);
	// $done = array(
	// 	"type" => "FeatureCollection",
	// 	"crs" => array(
	// 		"type" => "name",
	// 		"properties" => array(
	// 			"name" => "EPSG:4326"
	// 		)
	// 	),
	// 	"features" => array(
	// 		0 => array(
	// 			"type" => "Feature",
	// 			"geometry" => $newline
	// 		)
	// 	)
	// );

	// $type_ = $final['astar'];
	// $coor = $type_->coordinates;
	// echo (count($coor));

	echo (json_encode($final));
	$type_ = $final['astar'];
	$coor = $type_->coordinates;
	// echo (count($coor));
	// $temp = $coor[0];
	// for ($i=0; $i < 2; $i++) { 
	// 	echo($temp[$i]);
	// }
	// echo (json_encode($coor));
	//echo (json_encode($done));
	// echo "<table>\n";
	// while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	//     echo "\t<tr>\n";
	//     foreach ($line as $col_value) {
	//         echo "\t\t<td>$col_value</td>\n";
	//     }
	//     echo "\t</tr>\n";
	// }
	// echo "</table>\n";
	// phpinfo();