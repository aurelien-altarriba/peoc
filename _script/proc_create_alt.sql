
DROP FUNCTION IF EXISTS create_elevation(IN v_parc INTEGER);
CREATE FUNCTION create_elevation(IN v_parc INTEGER) RETURNS SETOF varchar AS $$
DECLARE
	ligne record;
	elevation float;

BEGIN
	OPEN curs FOR SELECT (listepoint).path[1] As index, ST_AsText((listepoint).geom) as point, ST_X((listepoint).geom) as lat, ST_Y((listepoint).geom) as lng
								FROM ( SELECT ST_DumpPoints(singleline) AS listepoint
	        							FROM (SELECT ST_AsText(St_MakeLine(multiline)) AS singleline
	             									FROM (SELECT num_position_t, (ST_Dump(geom_t)).geom AS multiline
	                   										FROM troncon WHERE id_parcours_t = 2 AND id_hierarchie_t = v_parc ORDER BY num_position_t) AS req1) AS req2
	) AS req3

	FETCH curs INTO ligne;

	WHILE found LOOP
		SELECT st_value(rast,ligne.point) FROM ign_bd_topo.mnt_lr WHERE st_intersects(ligne.point,rast) INTO elevation;
		RETURN next (ligne.lat::varchar || ' ' || ligne.lng::varchar || ' ' ||  elevation::varchar)
		FETCH curs INTO ligne;
	END LOOP;
	CLOSE curs;
END
$$ LANGUAGE plpgsql ;

---select * from create_elevation(2)
