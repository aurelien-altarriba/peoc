DROP FUNCTION IF EXISTS create_allure(IN id_p integer, IN type_za integer, IN lat1 float, IN lng1 float, IN lat2 float, IN lng2 float, IN dist float, OUT id_ret integer, OUT za_ret text, OUT msg_ret varchar);
CREATE FUNCTION create_allure(IN id_p integer, IN type_za integer, IN lat1 float, IN lng1 float, IN lat2 float, IN lng2 float, IN dist float, OUT id_ret integer, OUT za_ret text, OUT msg_ret varchar) AS $$
DECLARE
	id_reg integer;
	curs refcursor;
	res record;
	ligne text;
	za text;
	point1 float;
	point2 float;
  pointTemp float;
BEGIN

  --- Sélectionne ensemble des linestring (tronçons) du parcours pour en former qu'une seule
	OPEN curs FOR SELECT ST_AsText(St_MakeLine(geom_l)) AS trace
		FROM (SELECT num_position_t, (ST_Dump(geom_t)).geom AS geom_l
      			FROM troncon WHERE id_parcours_t = id_p AND id_hierarchie_t = 1 ORDER BY num_position_t) AS sub;
	FETCH curs INTO res;
	CLOSE curs;
	ligne := res.trace;
	RAISE NOTICE 'ligne : (%)', ligne;

  IF ligne IS NOT NULL THEN

    --- Localise le point de départ de la zone d'allure sur la linestring
  	OPEN curs FOR EXECUTE 'SELECT st_line_locate_point(l.geom, p.geom) as point
  			FROM (select ST_GeomFromText('''|| ligne ||''') as geom) as l, (SELECT ST_GeomFromText(''POINT('|| lng1 ||'  '|| lat1 ||')'') as geom) as p
  			WHERE st_dwithin(l.geom, p.geom, '|| dist ||')';
  	FETCH curs INTO res;
  	CLOSE curs;
  	point1 := res.point;
  	RAISE NOTICE 'point1 : (%)', point1;


    --- Localise le point de d'arrivée de la zone d'allure sur la linestring
  	OPEN curs FOR EXECUTE 'SELECT st_line_locate_point(l.geom, p.geom) as point
  			FROM (select ST_GeomFromText('''|| ligne ||''') as geom) as l, (SELECT ST_GeomFromText(''POINT('|| lng2 ||' '|| lat2 ||')'') as geom) as p
  			WHERE st_dwithin(l.geom, p.geom, '|| dist ||')';
  	FETCH curs INTO res;
  	CLOSE curs;
  	point2 := res.point;
  	RAISE NOTICE 'point2 : (%)', point2;


    --- Vérification que le point de fin de la zone allure ne soit pas égal à 0 (cas quand situé à la fin de la linestring)
    IF point2 = 0 THEN
      point2 := point2+1;
    END IF;

    --- On inverse car cela signifie que les points n'ont pas été saisi dans le sens du parcours
    IF point2 < point1 THEN
      pointTemp := point1;
      point1 := point2;
      point2 := pointTemp;
    END IF;

    IF point1 IS NOT NULL AND  point2 IS NOT NULL THEN

      --- Découpe le parcours et récupére la linestring constituant la zone d'allure
    	OPEN curs FOR EXECUTE 'SELECT ST_AsText(ST_Line_Substring('''|| ligne ||''','''|| point1 ||''', '''|| point2 ||''')) AS za, ST_AsGeoJSON(ST_Line_Substring('''|| ligne ||''','''|| point1 ||''', '''|| point2 ||''')) as za_json';
    	FETCH curs INTO res;
    	CLOSE curs;
    	za := res.za;
      za_ret := res.za_json;
    	RAISE NOTICE 'zone allure : (%)', za;


      --- Insére en base de données la zone d'allure
    	OPEN curs FOR EXECUTE 'INSERT INTO zone_allure (id_parcours_za, id_type_za, geom_za) VALUES('||id_p||','||type_za||',ST_GeomFromText('''|| za ||''', 3857)) RETURNING id_zone_za';
     	FETCH curs INTO res;
    	CLOSE curs;
    	id_ret:= res.id_zone_za;
    	RAISE NOTICE 'zone allure : (%)', id_ret;

      msg_ret := 'OK';

    ELSE
      msg_ret := 'KO';
    END IF;

  ELSE
    msg_ret := 'KO';
  END IF;

END
$$ LANGUAGE plpgsql ;

---select id_ret, za_ret, msg_ret from create_allure(2, 1, 42.967, 0.8767, 42.7327, 1.1475, 0.01)
