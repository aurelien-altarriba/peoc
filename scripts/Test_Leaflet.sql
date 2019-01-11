DROP TABLE g_point;
DROP TABLE g_ligne;
DROP TABLE g_surface;

CREATE TABLE g_point(id serial PRIMARY KEY,libelle TEXT);
SELECT AddGeometryColumn( 'g_point', 'geom', 3857, 'GEOMETRY', 2 );

Delete from g_point;
INSERT INTO g_point VALUES ( 1,'Point un',ST_GeomFromText( 'POINT(5.36497 43.30457)', 3857) );
INSERT INTO g_point VALUES ( 2,'Point deux',ST_GeomFromText( 'POINT(5.36562 43.31369)', 3857) ); 
INSERT INTO g_point VALUES ( 3,'Point trois',ST_GeomFromText( 'POINT(5.36256 43.32048)', 3857) );

CREATE TABLE g_ligne(id serial PRIMARY KEY,libelle TEXT);
SELECT AddGeometryColumn( 'g_ligne', 'geom', 3857, 'GEOMETRY', 2 );

Delete from g_ligne;
INSERT INTO g_ligne VALUES ( 1,'Route une',ST_GeomFromText( 'LINESTRING(5.3590666 43.3162158, 5.3577 43.3187)', 3857) );
INSERT INTO g_ligne VALUES ( 2,'Route deux',ST_GeomFromText( 'LINESTRING(5.34651 43.32827, 5.35132 43.3298)', 3857) ); 

CREATE TABLE g_surface(id serial PRIMARY KEY,libelle TEXT);
SELECT AddGeometryColumn( 'g_surface', 'geom', 3857, 'GEOMETRY', 2 );

Delete from g_surface;
INSERT INTO g_surface VALUES ( 1,'Surface une',ST_GeomFromText( 'POLYGON((5.3372 43.3211, 5.3395 43.3252, 5.3375 43.3271, 5.3347 43.3137, 5.3372 43.3211))', 3857) );
INSERT INTO g_surface VALUES ( 2,'Surface deux',ST_GeomFromText( 'POLYGON((5.3472 43.3411, 5.3495 43.3452, 5.3475 43.3471, 5.3447 43.3437, 5.3472 43.3411))', 3857) ); 


