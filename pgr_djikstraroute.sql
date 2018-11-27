create or replace function pgr_djikstraroute(IN tbl character varying,
variadic double precision[],
OUT seq integer,
OUT gid integer,
OUT name text,
OUT cost double precision,
OUT geom geometry,
OUT x double precision,
OUT y double precision)
RETURNS SETOF record AS
$body$
DECLARE
arrayLengthHalf integer;
a integer;
x1 double precision;
b integer;
y1 double precision;
sql_node text;
REC_ROUTE record;
source_var integer;
target_var integer;
node record;
sql_text text;
sql_astar text;
rec_astar record;

BEGIN
--menghapus tabel sementara apabila sudah ada
DROP TABLE if exists tmp;
--membuat tabel semetara
CREATE TEMPORARY TABLE tmp(id integer, node_id integer, x double precision, y double precision);
--mendefinisikan ukuran array
--($2, 1) berarti parameter kedua dan array nya merupakan array 1 dimensi
arrayLengthHalf = (array_length($2,1))/2;
--Untuk perulangan sesuai dengan tabel yang dibuat, index 0 diabaikan dan dimunali dari 2[1]
For i in 1..arrayLengthHalf Loop
a := i*2-1;
x1 := $2[a];
b := a+1;
y1 := $2[b];
RAISE NOTICE '%, %, %, %',a,x1,b,y1;
--Memasukkan node id yang didapat dari query di bawah, ke dalam tabel sementara
execute 'insert into tmp (id, node_id, x, y) select '||i||', id, st_x(the_geom)::double precision, st_y(the_geom)::double precision from ways_vertices_pgr ORDER BY the_geom <-> ST_GeometryFromText(''Point('||y1||' '||x1||')'',4326) limit 1;';
RAISE NOTICE 'masuk1';
End Loop;

sql_node := 'SELECT * FROM tmp';
--Mengambil kolom geom dari tabel sementara
seq := 0;
source_var := -1;
FOR REC_ROUTE IN EXECUTE sql_node
LOOP
RAISE NOTICE 'masuk';
--Mengecek apakah parameter merupakan koordinat awal
If (source_var = -1) Then
execute 'select node_id from tmp where node_id='||REC_ROUTE.node_id||'' into node;
source_var := node.node_id;
Else
execute 'select node_id from tmp where node_id='||REC_ROUTE.node_id||'' into node;
target_var := node.node_id;
-- sql_astar := 'SELECT b.gid, a.cost, b.the_geom, b.name, b.source, b.target, b.x1 AS x,b.y1 AS y FROM ' || 'pgr_astar(''SELECT gid::integer AS id, source::integer, target::integer, ' || 'cost_clearroute * penalty::double precision AS cost, reverse_cost_s * penalty::double precision AS reverse_cost, x1, y1, x2, y2 FROM ' || qoute_ident(tbl) || 'AS r JOIN configuration USING (tag_id), (SELECT ST_Expand(ST_Extent(the_geom,0.1) as box FROM backup_ways as l1 WHERE l1.source = '||source_var||'OR l1.target = ' || target_var ||')as box WHERE r.the_geom && box.box'','||source_var||','||target_var||', true, true) AS a LEFT JOIN ' || qoute_ident(tbl) || 'AS b ON (a.id2=b.gid) ORDER BY a.seq';
--Menjalankan fungsi algoritma A star pada pgrouting, dan mengembalikan hasilnya
sql_text := 'SELECT b.gid, a.cost, b.the_geom, b.name, b.source, b.target, b.x1 AS x,b.y1 AS y FROM pgr_dijkstra(''SELECT gid::integer AS id, source::integer, target::integer , cost_clearroute * penalty::double precision AS cost, reverse_cost_s * penalty::double precision AS reverse_cost, x1, y1, x2, y2 FROM %I AS r JOIN configuration USING (tag_id), (SELECT ST_Expand(ST_Extent(the_geom),0.1) as box FROM backup_ways as l1 WHERE l1.source = '||source_var||' OR l1.target = ' || target_var ||')as box WHERE r.the_geom && box.box'','||source_var||','||target_var||', true, true) AS a LEFT JOIN %I AS b ON (a.id2=b.gid) ORDER BY a.seq';
RAISE NOTICE '%',source_var;
RAISE NOTICE '%',target_var;
select format(sql_text,tbl,tbl) into sql_astar;
RAISE NOTICE '%',sql_astar;
For rec_astar in execute sql_astar
Loop
seq := seq +1;
gid := rec_astar.gid;
name :=rec_astar.name;
cost := rec_astar.cost;
geom := rec_astar.the_geom;
x := rec_astar.x;
y := rec_astar.y;
RAISE NOTICE 'masuk3';
RETURN NEXT;
End Loop;
END IF;
END LOOP;
return;

--EXCEPTION
--WHEN internal_error THEN
--seq:=seq+1;
--gid:=rec_astar.gid;
--name:=rec_astar.name;
--cost:=9999.9999;
--geom:=rec_astar.the_geom;

end;
$body$
language plpgsql volatile STRICT;