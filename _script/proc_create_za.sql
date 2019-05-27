DROP FUNCTION IF EXISTS create_allure(IN id_p integer, IN type_za integer, IN lat1 float, IN lng1 float, IN lat2 float, IN lng2 float, IN dist float, IN srid INTEGER, OUT id_za INTEGER, OUT msg_ret varchar);
CREATE FUNCTION create_allure(IN id_p integer, IN type_za integer, IN lat1 float, IN lng1 float, IN lat2 float, IN lng2 float, IN dist float,  IN srid INTEGER, OUT id_za INTEGER, OUT msg_ret varchar) AS $$
DECLARE
	curs refcursor;
	res record;
	ligne text;
	geom_za text;
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

  IF ligne IS NOT NULL THEN


    --- Localise le point de départ de la zone d'allure sur la linestring
  	OPEN curs FOR EXECUTE 'SELECT st_line_locate_point(l.geom, p.geom) as point
  			FROM (select ST_GeomFromText('''|| ligne ||''') as geom) as l, (SELECT ST_GeomFromText(''POINT('|| lng1 ||'  '|| lat1 ||')'') as geom) as p
  			WHERE st_dwithin(l.geom, p.geom, '|| dist ||')';
  	FETCH curs INTO res;
  	CLOSE curs;
  	point1 := res.point;


    --- Localise le point de d'arrivée de la zone d'allure sur la linestring
  	OPEN curs FOR EXECUTE 'SELECT st_line_locate_point(l.geom, p.geom) as point
  			FROM (select ST_GeomFromText('''|| ligne ||''') as geom) as l, (SELECT ST_GeomFromText(''POINT('|| lng2 ||' '|| lat2 ||')'') as geom) as p
  			WHERE st_dwithin(l.geom, p.geom, '|| dist ||')';
  	FETCH curs INTO res;
  	CLOSE curs;
  	point2 := res.point;


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
    	---OPEN curs FOR EXECUTE 'SELECT ST_AsText(ST_Line_Substring('''|| ligne ||''','''|| point1 ||''', '''|| point2 ||''')) AS za, ST_AsGeoJSON(ST_Line_Substring('''|| ligne ||''','''|| point1 ||''', '''|| point2 ||''')) as za_json';
			OPEN curs FOR EXECUTE 'SELECT ST_AsText(ST_Line_Substring('''|| ligne ||''','''|| point1 ||''', '''|| point2 ||''')) AS za';
    	FETCH curs INTO res;
    	CLOSE curs;
    	geom_za := res.za;


      --- Insére en base de données la zone d'allure
    	OPEN curs FOR EXECUTE 'INSERT INTO zone_allure (id_parcours_za, id_type_za, geom_za) VALUES('|| id_p ||','|| type_za ||',ST_GeomFromText('''|| geom_za ||''', '|| srid ||')) RETURNING id_zone_za';
     	FETCH curs INTO res;
    	CLOSE curs;
    	id_za:= res.id_zone_za;
			

      msg_ret := 'OK';

    ELSE
      msg_ret := 'KO';
    END IF;

  ELSE
    msg_ret := 'KO';
  END IF;

END
$$ LANGUAGE plpgsql ;

---select id_ret, za_ret, msg_ret from create_allure(2, 1, 42.967, 0.8767, 42.7327, 1.1475, 0.01, 4326)
