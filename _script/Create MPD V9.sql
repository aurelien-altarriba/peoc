------------------------------------------------------------
--        Script Postgre
------------------------------------------------------------


------------------------------------------------------------
-- Table: departement
------------------------------------------------------------
CREATE TABLE public.departement(
	id_departement_d	VARCHAR (6) NOT NULL ,
	nom_d        		VARCHAR (50) NOT NULL ,
	CONSTRAINT prk_constraint_departement PRIMARY KEY (id_departement_d)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: pays
------------------------------------------------------------
CREATE TABLE public.pays(
	id_pays_pa			VARCHAR (40) NOT NULL ,
	nom_pa     			VARCHAR (80) NOT NULL ,
	CONSTRAINT prk_constraint_pays PRIMARY KEY (id_pays_pa)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: membre
------------------------------------------------------------
CREATE TABLE public.membre(
	id_membre_m  		SERIAL NOT NULL ,
	nom_m         		VARCHAR (30) NOT NULL ,
	prenom_m      		VARCHAR (30) NOT NULL ,
	dt_naissance_m  	DATE  NOT NULL ,
	adresse_m     		VARCHAR (100)  ,
	cp_m          		VARCHAR (5)  ,
	ville_m     		VARCHAR (80)  ,
	id_pays_m    		VARCHAR (40) NOT NULL ,
	tel_m         		VARCHAR (15)  ,
	mail_m        		VARCHAR (50) NOT NULL ,
	CONSTRAINT prk_constraint_membre PRIMARY KEY (id_membre_m)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: niveau_equestre
------------------------------------------------------------
CREATE TABLE public.niveau_equestre(
	id_niveau_ne 		SERIAL NOT NULL ,
	nom_ne       		VARCHAR (40) NOT NULL ,
	CONSTRAINT prk_constraint_niveau_equestre PRIMARY KEY (id_niveau_ne)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: cavalier
------------------------------------------------------------
CREATE TABLE public.cavalier(
	id_membre_c        	INT  NOT NULL ,
	num_licence_c      	VARCHAR (20) NOT NULL ,
	dt_exp_licence_c 	DATE  NOT NULL ,
	id_niveau_c       	INT  NOT NULL ,
	photo_c            	VARCHAR (2000)   ,
	CONSTRAINT prk_constraint_cavalier PRIMARY KEY (id_membre_c)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: info_connexion
------------------------------------------------------------
CREATE TABLE public.info_connexion(
	login_ic          	VARCHAR (50) NOT NULL ,
	id_membre_ic	  	INT  NOT NULL UNIQUE ,
	mdp_ic            	VARCHAR (255) NOT NULL ,
	dt_inscription_ic  	DATE  NOT NULL ,
	dt_der_connexion_ic	DATE   ,
	CONSTRAINT prk_constraint_info_connexion PRIMARY KEY (login_ic)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: centre_equestre
------------------------------------------------------------
CREATE TABLE public.centre_equestre(
	id_centre_ce  		SERIAL NOT NULL ,
	nom_ce        		VARCHAR (50) NOT NULL ,
	adresse_ce    		VARCHAR (100) NOT NULL ,
	cp_ce         		VARCHAR (5) NOT NULL ,
	ville_ce    		VARCHAR (80) NOT NULL ,
	id_departement_ce 	VARCHAR (6) NOT NULL ,
	tel_ce        		VARCHAR (15) NOT NULL ,
	mail_ce       		VARCHAR (50) ,
	nb_cheval_ce  		INT ,
	id_membre_ce   		INT ,
	url_ce        		VARCHAR (2000)   ,
	logo_ce       		VARCHAR (2000)   ,
	CONSTRAINT prk_constraint_centre_equestre PRIMARY KEY (id_centre_ce)
)WITHOUT OIDS;
SELECT AddGeometryColumn('centre_equestre', 'geom_ce', 4326, 'GEOMETRY', 2);


------------------------------------------------------------
-- Table: parcours
------------------------------------------------------------
CREATE TABLE public.parcours(
	id_parcours_p      	SERIAL NOT NULL ,
	nom_p              	VARCHAR (50) NOT NULL ,
	autonomie_p        	BOOL  NOT NULL ,
	visible_p          	BOOL  NOT NULL ,
	dt_publication_p 	DATE  NOT NULL ,
	id_niveau_p       	INT  NOT NULL ,
	id_departement_p 	VARCHAR (6) NOT NULL ,
	id_membre_p        	INT ,
	id_centre_p       	INT ,
	description_p      	VARCHAR (2000)   ,
	CONSTRAINT prk_constraint_parcours PRIMARY KEY (id_parcours_p)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: categorie_pi
------------------------------------------------------------
CREATE TABLE public.categorie_pi(
	id_categorie_pic 	SERIAL NOT NULL ,
	nom_pic          	VARCHAR (30)  ,
	CONSTRAINT prk_constraint_categorie_pi PRIMARY KEY (id_categorie_pic)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: point_interet
------------------------------------------------------------
CREATE TABLE public.point_interet(
	id_interet_pi   	SERIAL NOT NULL ,
	id_parcours_pi   	INT  NOT NULL ,
	num_point_pi    	INT  NOT NULL ,
	id_categorie_pi 	INT  NOT NULL ,
	url_pi          	VARCHAR (2000)   ,
	photo_pi        	VARCHAR (2000)   ,
	description_pi  	VARCHAR (2000)   ,
	CONSTRAINT prk_constraint_point_interet PRIMARY KEY (id_interet_pi)
)WITHOUT OIDS;
SELECT AddGeometryColumn('point_interet', 'geom_pi', 4326, 'GEOMETRY', 2);


------------------------------------------------------------
-- Table: categorie_pv
------------------------------------------------------------
CREATE TABLE public.categorie_pv(
	id_categorie_pvc 	SERIAL NOT NULL ,
	nom_pvc          	VARCHAR (30) NOT NULL ,
	CONSTRAINT prk_constraint_categorie_pv PRIMARY KEY (id_categorie_pvc)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: point_vigilance
------------------------------------------------------------
CREATE TABLE public.point_vigilance(
	id_vigilance_pv  	SERIAL NOT NULL ,
	id_parcours_pv    	INT  NOT NULL ,
	num_point_pv     	INT  NOT NULL ,
	dt_creation_pv 		DATE  NOT NULL ,
	dt_debut_pv    		DATE  NOT NULL ,
	dt_fin_pv      		DATE   ,
	id_membre_pv      	INT  NOT NULL ,
	id_categorie_pv  	INT  NOT NULL ,
	photo_pv         	VARCHAR (2000)   ,
	description_pv   	VARCHAR (2000)  NOT NULL ,
	CONSTRAINT prk_constraint_point_vigilance PRIMARY KEY (id_vigilance_pv)
)WITHOUT OIDS;
SELECT AddGeometryColumn('point_vigilance', 'geom_pv', 4326, 'GEOMETRY', 2);


------------------------------------------------------------
-- Table: type_allure
------------------------------------------------------------
CREATE TABLE public.type_allure(
	id_type_ta 			SERIAL NOT NULL ,
	nom_ta     			VARCHAR (30) NOT NULL ,
	CONSTRAINT prk_constraint_type_allure PRIMARY KEY (id_type_ta)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: zone_allure
------------------------------------------------------------
CREATE TABLE public.zone_allure(
	id_zone_za     		SERIAL NOT NULL ,
	id_parcours_za 		INT  NOT NULL ,
	id_type_za    		INT  NOT NULL ,
	CONSTRAINT prk_constraint_zone_allure PRIMARY KEY (id_zone_za)
)WITHOUT OIDS;
SELECT AddGeometryColumn('zone_allure', 'geom_za', 4326, 'GEOMETRY', 2);


------------------------------------------------------------
-- Table: hierarchie
------------------------------------------------------------
CREATE TABLE public.hierarchie(
	id_hierarchie_h 	SERIAL NOT NULL ,
	nom_h           	VARCHAR (30) NOT NULL ,
	CONSTRAINT prk_constraint_hierarchie PRIMARY KEY (id_hierarchie_h)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: type_terrain
------------------------------------------------------------
CREATE TABLE public.type_terrain(
	id_type_tt 			SERIAL NOT NULL ,
	nom_tt     			VARCHAR (50) NOT NULL ,
	CONSTRAINT prk_constraint_type_terrain PRIMARY KEY (id_type_tt)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: niveau_terrain
------------------------------------------------------------
CREATE TABLE public.niveau_terrain(
	id_niveau_nt 		SERIAL NOT NULL ,
	nom_nt       		VARCHAR (20) NOT NULL ,
	CONSTRAINT prk_constraint_niveau_terrain PRIMARY KEY (id_niveau_nt)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: troncon
------------------------------------------------------------
CREATE TABLE public.troncon(
	id_troncon_t    	SERIAL NOT NULL ,
	id_parcours_t   	INT  NOT NULL ,
	num_position_t  	INT  NOT NULL ,
	id_hierarchie_t 	INT  NOT NULL ,
	id_type_t      		INT  NOT NULL ,
	id_niveau_t    		INT  NOT NULL ,
	duree_estime_t  	FLOAT   ,
	CONSTRAINT prk_constraint_troncon PRIMARY KEY (id_troncon_t)
)WITHOUT OIDS;
SELECT AddGeometryColumn('troncon', 'geom_t', 4326, 'GEOMETRY', 2);


------------------------------------------------------------
-- Table: service
------------------------------------------------------------
CREATE TABLE public.service(
	id_service_s  		SERIAL NOT NULL ,
	nom_s         		VARCHAR (30) NOT NULL ,
	description_s 		VARCHAR (2000)   ,
	CONSTRAINT prk_constraint_service PRIMARY KEY (id_service_s)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: propose
------------------------------------------------------------
CREATE TABLE public.propose(
	id_centre_pr  		INT  NOT NULL ,
	id_parcours_pr 		INT  NOT NULL ,
	id_service_pr  		INT  NOT NULL ,
	dt_jour_pr     		DATE  NOT NULL ,
	quantite_pr    		INT  NOT NULL ,
	prix_pr        		FLOAT   ,
	information_pr 		VARCHAR (2000)   ,
	CONSTRAINT prk_constraint_propose PRIMARY KEY (id_centre_pr,id_parcours_pr,id_service_pr,dt_jour_pr)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: effectue
------------------------------------------------------------
CREATE TABLE public.effectue(
	id_membre_e    		INT  NOT NULL ,
	id_parcours_e  		INT  NOT NULL ,
	dt_jour_e      		DATE  NOT NULL ,
	note_e         		INT ,
	duree_reel_e 		  FLOAT  ,
	commentaire_e  		VARCHAR (2000)   ,
	CONSTRAINT prk_constraint_effectue PRIMARY KEY (id_membre_e,id_parcours_e,dt_jour_e)
)WITHOUT OIDS;



ALTER TABLE public.membre 			ADD CONSTRAINT FK_membre_id_pays_m 					FOREIGN KEY (id_pays_m) 		REFERENCES public.pays(id_pays_pa);
ALTER TABLE public.cavalier 		ADD CONSTRAINT FK_cavalier_id_membre_c 				FOREIGN KEY (id_membre_c) 		REFERENCES public.membre(id_membre_m);
ALTER TABLE public.cavalier 		ADD CONSTRAINT FK_cavalier_id_niveau_c 				FOREIGN KEY (id_niveau_c) 		REFERENCES public.niveau_equestre(id_niveau_ne);
ALTER TABLE public.info_connexion 	ADD CONSTRAINT FK_info_connexion_id_membre_ic 		FOREIGN KEY (id_membre_ic) 		REFERENCES public.membre(id_membre_m);
ALTER TABLE public.centre_equestre 	ADD CONSTRAINT FK_centre_equestre_id_membre_ce 		FOREIGN KEY (id_membre_ce) 		REFERENCES public.membre(id_membre_m);
ALTER TABLE public.centre_equestre 	ADD CONSTRAINT FK_centre_equestre_id_departement_ce FOREIGN KEY (id_departement_ce) REFERENCES public.departement(id_departement_d);
ALTER TABLE public.parcours 		ADD CONSTRAINT FK_parcours_id_membre_p 				FOREIGN KEY (id_membre_p) 		REFERENCES public.cavalier(id_membre_c);
ALTER TABLE public.parcours 		ADD CONSTRAINT FK_parcours_id_centre_p 				FOREIGN KEY (id_centre_p) 		REFERENCES public.centre_equestre(id_centre_ce);
ALTER TABLE public.parcours 		ADD CONSTRAINT FK_parcours_id_niveau_p 				FOREIGN KEY (id_niveau_p) 		REFERENCES public.niveau_equestre(id_niveau_ne);
ALTER TABLE public.parcours 		ADD CONSTRAINT FK_parcours_id_departement_p 		FOREIGN KEY (id_departement_p) 	REFERENCES public.departement(id_departement_d);
ALTER TABLE public.point_interet 	ADD CONSTRAINT FK_point_interet_id_parcours_pi 		FOREIGN KEY (id_parcours_pi) 	REFERENCES public.parcours(id_parcours_p);
ALTER TABLE public.point_interet 	ADD CONSTRAINT FK_point_interet_id_categorie_pi 	FOREIGN KEY (id_categorie_pi) 	REFERENCES public.categorie_pi(id_categorie_pic);
ALTER TABLE public.point_vigilance 	ADD CONSTRAINT FK_point_vigilance_id_membre_pv 		FOREIGN KEY (id_membre_pv) 		REFERENCES public.membre(id_membre_m);
ALTER TABLE public.point_vigilance 	ADD CONSTRAINT FK_point_vigilance_id_parcours_pv 	FOREIGN KEY (id_parcours_pv) 	REFERENCES public.parcours(id_parcours_p);
ALTER TABLE public.point_vigilance 	ADD CONSTRAINT FK_point_vigilance_id_categorie_pv 	FOREIGN KEY (id_categorie_pv) 	REFERENCES public.categorie_pv(id_categorie_pvc);
ALTER TABLE public.zone_allure 		ADD CONSTRAINT FK_zone_allure_id_parcours_za 		FOREIGN KEY (id_parcours_za) 	REFERENCES public.parcours(id_parcours_p);
ALTER TABLE public.zone_allure 		ADD CONSTRAINT FK_zone_allure_id_type_za 			FOREIGN KEY (id_type_za) 		REFERENCES public.type_allure(id_type_ta);
ALTER TABLE public.troncon 			ADD CONSTRAINT FK_troncon_id_parcours_t 			FOREIGN KEY (id_parcours_t) 	REFERENCES public.parcours(id_parcours_p);
ALTER TABLE public.troncon 			ADD CONSTRAINT FK_troncon_id_type_t 				FOREIGN KEY (id_type_t) 		REFERENCES public.type_terrain(id_type_tt);
ALTER TABLE public.troncon 			ADD CONSTRAINT FK_troncon_id_niveau_t 				FOREIGN KEY (id_niveau_t) 		REFERENCES public.niveau_terrain(id_niveau_nt);
ALTER TABLE public.troncon 			ADD CONSTRAINT FK_troncon_id_hierarchie_t 			FOREIGN KEY (id_hierarchie_t) 	REFERENCES public.hierarchie(id_hierarchie_h);
ALTER TABLE public.effectue 		ADD CONSTRAINT FK_effectue_id_membre_e 				FOREIGN KEY (id_membre_e) 		REFERENCES public.membre(id_membre_m);
ALTER TABLE public.effectue 		ADD CONSTRAINT FK_effectue_id_parcours_e 			FOREIGN KEY (id_parcours_e) 	REFERENCES public.parcours(id_parcours_p);
ALTER TABLE public.propose 			ADD CONSTRAINT FK_propose_id_centre_pr 				FOREIGN KEY (id_centre_pr) 		REFERENCES public.centre_equestre(id_centre_ce);
ALTER TABLE public.propose 			ADD CONSTRAINT FK_propose_id_parcours_pr 			FOREIGN KEY (id_parcours_pr) 	REFERENCES public.parcours(id_parcours_p);
ALTER TABLE public.propose 			ADD CONSTRAINT FK_propose_id_service_pr 			FOREIGN KEY (id_service_pr) 	REFERENCES public.service(id_service_s);


--- Suppression d'un membre
ALTER TABLE public.cavalier 		DROP CONSTRAINT FK_cavalier_id_membre_c;
ALTER TABLE public.cavalier 		ADD CONSTRAINT FK_cavalier_id_membre_c 				FOREIGN KEY (id_membre_c) 		REFERENCES public.membre(id_membre_m) ON DELETE CASCADE;
ALTER TABLE public.info_connexion  	DROP CONSTRAINT FK_info_connexion_id_membre_ic;
ALTER TABLE public.info_connexion  	ADD CONSTRAINT FK_info_connexion_id_membre_ic 		FOREIGN KEY (id_membre_ic) 		REFERENCES public.membre(id_membre_m) ON DELETE CASCADE;
ALTER TABLE public.effectue 		DROP CONSTRAINT FK_effectue_id_membre_e;
ALTER TABLE public.effectue 		ADD CONSTRAINT FK_effectue_id_membre_e 				FOREIGN KEY (id_membre_e) 		REFERENCES public.membre(id_membre_m) ON DELETE CASCADE;
ALTER TABLE public.point_vigilance 	DROP CONSTRAINT FK_point_vigilance_id_membre_pv;
ALTER TABLE public.point_vigilance 	ADD CONSTRAINT FK_point_vigilance_id_membre_pv 		FOREIGN KEY (id_membre_pv) 		REFERENCES public.membre(id_membre_m) ON DELETE CASCADE;

--- Suppression d'un centre équestre
ALTER TABLE public.parcours 		DROP CONSTRAINT FK_parcours_id_centre_p;
ALTER TABLE public.parcours 		ADD CONSTRAINT FK_parcours_id_centre_p 				FOREIGN KEY (id_centre_p) 		REFERENCES public.centre_equestre(id_centre_ce) ON DELETE CASCADE;
ALTER TABLE public.propose 			DROP CONSTRAINT FK_propose_id_centre_pr;
ALTER TABLE public.propose 			ADD CONSTRAINT FK_propose_id_centre_pr 				FOREIGN KEY (id_centre_pr) 		REFERENCES public.centre_equestre(id_centre_ce) ON DELETE CASCADE;

--- Suppression d'un cavalier
ALTER TABLE public.parcours 		DROP CONSTRAINT FK_parcours_id_membre_p;
ALTER TABLE public.parcours 		ADD CONSTRAINT FK_parcours_id_membre_p 				FOREIGN KEY (id_membre_p) 		REFERENCES public.cavalier(id_membre_c) ON DELETE CASCADE;

--- Suppression d'un parcours
ALTER TABLE public.effectue 		DROP CONSTRAINT FK_effectue_id_parcours_e;
ALTER TABLE public.effectue 		ADD CONSTRAINT FK_effectue_id_parcours_e 			FOREIGN KEY (id_parcours_e) 	REFERENCES public.parcours(id_parcours_p) ON DELETE CASCADE;
ALTER TABLE public.propose 			DROP CONSTRAINT FK_propose_id_parcours_pr;
ALTER TABLE public.propose 			ADD CONSTRAINT FK_propose_id_parcours_pr 			FOREIGN KEY (id_parcours_pr) 	REFERENCES public.parcours(id_parcours_p) ON DELETE CASCADE;
ALTER TABLE public.point_vigilance 	DROP CONSTRAINT FK_point_vigilance_id_parcours_pv;
ALTER TABLE public.point_vigilance 	ADD CONSTRAINT FK_point_vigilance_id_parcours_pv 	FOREIGN KEY (id_parcours_pv) 	REFERENCES public.parcours(id_parcours_p) ON DELETE CASCADE;
ALTER TABLE public.point_interet 	DROP CONSTRAINT FK_point_interet_id_parcours_pi;
ALTER TABLE public.point_interet 	ADD CONSTRAINT FK_point_interet_id_parcours_pi 		FOREIGN KEY (id_parcours_pi) 	REFERENCES public.parcours(id_parcours_p) ON DELETE CASCADE;
ALTER TABLE public.troncon 			DROP CONSTRAINT FK_troncon_id_parcours_t;
ALTER TABLE public.troncon 			ADD CONSTRAINT FK_troncon_id_parcours_t 			FOREIGN KEY (id_parcours_t) 	REFERENCES public.parcours(id_parcours_p) ON DELETE CASCADE;
ALTER TABLE public.zone_allure 		DROP CONSTRAINT FK_zone_allure_id_parcours_za;
ALTER TABLE public.zone_allure 		ADD CONSTRAINT FK_zone_allure_id_parcours_za 		FOREIGN KEY (id_parcours_za) 	REFERENCES public.parcours(id_parcours_p) ON DELETE CASCADE;



--- ALTER TABLE child_table_name  ADD CONSTRAINT fk_name FOREIGN KEY (child_column_name) REFERENCES parent_table_name(parent_column_name) ON DELETE CASCADE;
--- ALTER TABLE table_y DROP CONSTRAINT id_x_fk, ADD CONSTRAINT id_x_fk FOREIGN KEY (id_y) REFERENCES table_x (id_x) ON DELETE CASCADE;


--- Ajout de la colonne altitude dans la table tronçon :
--- va contenir une chaine de caractères avec les altitudes des points constituant le tronçon.
--- ALTER TABLE public.troncon ADD COLUMN liste_z_t VARCHAR (2000);



--- Changement de SRID de 3857 vers 4326 afin de faciliter l'utilisation de fonction pour les calculs
--- SELECT updateGeometrySRID('centre_equestre', 'geom_ce', 4326);
--- SELECT updateGeometrySRID('point_interet', 'geom_pi', 4326);
--- SELECT updateGeometrySRID('point_vigilance', 'geom_pv', 4326);
--- SELECT updateGeometrySRID('zone_allure', 'geom_za', 4326);
--- SELECT updateGeometrySRID('troncon', 'geom_t', 4326);
