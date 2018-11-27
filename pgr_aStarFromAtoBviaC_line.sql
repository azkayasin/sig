create or replace function
	pgr_aStarFromAtoBviaC_line(IN tbl character varying,
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
	declare
	arrayLengthHalf integer;
	a integer;
	x1 double precision;
	b integer;
	y1 double precision;
	sql_tsp text;
	rec_tsp record;
	source_var integer;
	target_var integer;
	node record;
	sql_astar text;
	sql_text text;
	rec_astar record;
	begin
	drop table if exists matrix;

	create temporary table matrix(id integer, node_id integer, x double precision, y double precision);
	arrayLengthHalf=(array_length($2,1))/2;

	For i in 1..arrayLengthHalf Loop
	a:=i*2-1;
	x1:=$2[a];
	b:=a+1;
	y1:=$2[b];

	execute 'insert into matrix (id, node_id, x, y) select '||i||', id, st_x(the_geom)::double precision from ways_vertices_pgr ORDER BY the_geom <-> ST_GeometryFromText(''Point('||x1||' '||y1||')'',4326) limit 1;';
	End Loop;

	sql_tsp := 'select seq id1, id2, round(cost::numeric,5) AS cost from pgr_tsp(''select id,x,y from matrix order by id'',1,'||arrayLengthHalf||')';
	seq := 0;
	source_var := -1;
	FOR rec_tsp IN EXECUTE sql_tsp
	LOOP
	If(source_var= -1) Then
		execute 'select node_id from matrix where id='||rec_tsp.id2||'' into node;
		source_var := node.node_id;
	Else
		execute 'select node_id from matrix where id='||rec_tsp.id2||'' into node;
		target_var := node.node_id;
		sql_text:='SELECT b.gid, a.cost, b.the_geom, b.name, b.source, b.target, b.x1 AS x, b.y1 AS y FROM' || 'pgr_astar(''SELECT gid::integer AS id, source::integer, target::integer,' || 'cost_clearroute * penalty::double precision AS cost, reverse_cost_s * penalty::double precision AS reverse_cost, x1, y1, x2, y2 FROM %I AS r JOIN configuration USING(tag_id), (SELECT ST_Expand(ST_Extent(the_geom),0.1) as box FROM backup_ways as l1 WHERE l1.source = '||source_var||'OR l1.target'||target_var||') as box WHERE r.the_geom && box.box'','||source_var||','||target_var||', true, true) AS a LEFT JOIN %I AS b ON (a.id2=b.gid) ORDER BY a.seq';
		select format(sql_text,tbl,tbl) into sql_astar;
		For rec_astar in execute sql_astar
		Loop
		seq:=seq+1;
		gid:=rec_astar.gid;
		name:=rec_astar.name;
		cost:=rec_astar.cost;
		geom:=rec_astar.the_geom;
		x:=rec_astar.x;
		y:=rec_astar.y;
		RETURN NEXT;
		End Loop;
		source_var:=target_var;
		END IF;
		END LOOP;
		return;

		--EXCEPTION
		--WHEN internal_error THEN
		--seq := seq +1;
		--gid := rec_astar.gid;
		--name := rect_astar.name;
		--cost := 9999.9999;
		--geom := rec_astar.the_geom;

		end;
		$body$
		language plpgsql volatile STRICT;