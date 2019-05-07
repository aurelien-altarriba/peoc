<?php
	// Si l'id n'est pas définie dans l'URL
 	if(!isset($_GET['id'])) {
		header('Location: /');

	// Si l'id n'est pas un entier et valide
	} else if( ((int) htmlspecialchars($_GET['id'])) <= 0) {
		header('Location: /');
	}

	// Si l'id est bon on le récupère
	$id_parcours = (int) htmlspecialchars($_GET['id']);
?>
<!DOCTYPE html>
<html>

	<head>
		<title>PÉ-OC</title>
		<meta charset="utf-8">

		<!-- CSS -->
    <link rel="stylesheet" type="text/css" href="/css/lib/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/css/lib/leaflet.css">

		<link rel="stylesheet" type="text/css" href="/css/header.css">
		<link rel="stylesheet" type="text/css" href="/css/footer.css">
		<link rel="stylesheet" type="text/css" href="/css/parcours.css">

		<!-- JS -->
		<script type="text/javascript" src="/js/lib/jquery.min.js"></script>
		<script type="text/javascript" src="/js/lib/bootstrap.min.js"></script>
		<script type="text/javascript" src="/js/lib/leaflet.js"></script>
		<script type="text/javascript" src="/js/lib/leaflet.active-layers.min.js"></script>

		<script type="text/javascript" src="/js/fonctionsData.js"></script>
		<script type="text/javascript" src="/js/map_parcours.js"></script>

    <!-- A SUPPRIMER -->
    <link rel="stylesheet" type="text/css" href="https://cdn.rawgit.com/gokertanrisever/leaflet-ruler/master/src/leaflet-ruler.css">
    <script src="https://cdn.rawgit.com/gokertanrisever/leaflet-ruler/master/src/leaflet-ruler.js"></script>
	</head>

	<body>
		<?php
			// HEADER
			require_once($_SERVER['DOCUMENT_ROOT'] ."/include/header.php");
			$bdd = connect();

			// On récupère les données du parcours
			try {
				$res = pg_query_params($bdd, 'SELECT * FROM parcours
                                              INNER JOIN (SELECT id_parcours_t, ROUND(SUM(duree_estime_t)::numeric,2) AS duree_t, ROUND(SUM(st_length(geom_t))::numeric,2) AS distance_t
                                              FROM troncon WHERE id_hierarchie_t = $2 GROUP BY id_parcours_t) AS t ON t.id_parcours_t =  $1
                                              LEFT JOIN (SELECT id_parcours_pv, COUNT(*) AS nb_pv
                                              FROM point_vigilance GROUP BY id_parcours_pv) AS pv ON pv.id_parcours_pv =  $1
                                              LEFT JOIN (SELECT id_parcours_pi, COUNT(*) AS nb_pi
                                              FROM point_interet GROUP BY id_parcours_pi) AS pi ON pi.id_parcours_pi =  $1
                                              WHERE id_parcours_p = $1', array($id_parcours,1));
				$parcours = pg_fetch_all($res)[0];
			} catch(Exception $e) {
				echo('erreur : '. $e);
			}

			// On récupère les données relatives au parcours
			try {
				$res_rel = pg_query_params($bdd,
				 'SELECT nom_ne, nom_d, nom_m, prenom_m,
				 	 (SELECT nom_pa
						FROM pays
						INNER JOIN membre ON id_membre_m = $1
						WHERE id_pays_pa = id_pays_m)
					 AS nom_pa
					FROM parcours
					INNER JOIN niveau_equestre ON id_niveau_p = id_niveau_ne
					INNER JOIN departement ON id_departement_p = id_departement_d
					INNER JOIN membre ON id_membre_p = id_membre_m
					WHERE id_parcours_p = $1',

					array($id_parcours));
				$parcours_rel = pg_fetch_all($res_rel)[0];
			} catch(Exception $e) {
				echo('erreur : '. $e);
			}

      // On récupère les données des tronçons du parcours
			try {
				$res = pg_query_params($bdd, 'SELECT * FROM troncon
                                      INNER JOIN niveau_terrain ON id_niveau_nt = id_niveau_t
                                      INNER JOIN type_terrain ON id_type_tt = id_type_t
                                      INNER JOIN hierarchie ON id_hierarchie_h = id_hierarchie_t
                                      WHERE id_parcours_t = $1
                                      ORDER BY num_position_t', array($id_parcours));
				$troncons = pg_fetch_all($res);
			} catch(Exception $e) {
				echo('erreur : '. $e);
			}
		?>

		<div id="contenu">
			<div id="colonneGauche">
				<h3><?php echo($parcours['nom_p']) ?></h3>
				<div class="date">Créé le <?php echo date_format(date_create($parcours['dt_publication_p']), 'd/m/Y'); ?> par <?php echo $parcours_rel['prenom_m'] .' '. $parcours_rel['nom_m']; ?> (<?php echo $parcours_rel['nom_pa']; ?>)</div>
				<div class="autonomie">En autonomie : <span class=<?php echo $parcours['autonomie_p'] ? '"vrai">OUI' : '"faux">NON';?></span></div>
				<div class="niveau">Niveau : <span class="niveau<?php echo $parcours['id_niveau_p']; ?>"><?php echo $parcours_rel['nom_ne']; ?></span></div>
				<div class="departement">Département : <b><?php echo $parcours_rel['nom_d']; ?> (<?php echo $parcours['id_departement_p']; ?>)</b></div>
        <div class="interet">Nombre de points d'intérêt : <b><?php echo $parcours['nb_pi']; ?></b></div>
        <div class="vigilance">Nombre de points de vigilance : <b><?php echo $parcours['nb_pv']; ?></b></div>
        <div class="dureeestime">Durée estimée : <b><?php echo $parcours['duree_t']; ?></b></div>
        <div class="distance">Distance : <b><?php echo $parcours['distance_t']; ?></b></div>
        <div class="description"><?php echo $parcours['description_p']; ?></div>
        <div id="listeTroncon"> Liste des tronçons du parcours :
        <table class="table table-sm table-dark">
          <thead>
            <tr>
              <th scope="col">Position</th>
              <th scope="col">Hiérarchie</th>
              <th scope="col">Difficulté</th>
              <th scope="col">Type terrain</th>
              <th scope="col">Durée estimée</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($troncons as $key => $value) {
              print('<tr><td>'.strval($value['num_position_t']).'</td><td>'.$value['nom_h'].'</td><td>'.$value['nom_nt'].'</td><td>'.$value['nom_tt'].'</td><td>'.strval($value['duree_estime_t']).'</td></tr>');
            }
            ?>
          </tbody>
        </table>
        </div>
			</div>

			<div id="colonneDroite">
				<div id="map"></div>


        <!-- Commentaires -->
        <div id="resComment">
          <h3>Commentaires</h3>
          <table class="table table-sm">
            <thead class="thead-dark">
              <tr>
                <th scope="col"> Nombre de note</th>
                <th scope="col">Note moyenne</th>
                <th scope="col">Durée réelle moyenne</th>
              </tr>
            </thead>
            <tbody id="tabMoyenne">
            </tbody>
          </table>
          <table class="table table-sm table-hover">
            <thead class="thead-dark">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Date</th>
                <th scope="col">Cavalier</th>
                <th scope="col">Note</th>
                <th scope="col">Durée réelle</th>
                <th scope="col">Commentaire</th>
              </tr>
            </thead>
            <tbody id="tabComment">
            </tbody>
          </table>
        </div>
      </div>
		</div>

		<!-- Formulaire -->
		<?php //require_once($_SERVER['DOCUMENT_ROOT'] ."/form/parcours.php"); ?>

		<!-- FOOTER -->
		<?php require_once($_SERVER['DOCUMENT_ROOT'] ."/include/footer.php"); ?>

    <script type="text/javascript">
    // Récupération des informations renseignées par les cavaliers
    // type: "R" global au parcours
    // 			 "L" liste de tous les commentaires
    function getData(id, type) {
    	// Récupération des commentaires
    	$.post('/fonction/recup_data_commentaire.php',
    		// Récupération de l'ID du parcours
    		{
    			id: id,
    			type: type
    		},

    		function(data) {
    			displayData(data, type);
    		}
    	);
    }


    // Fonction d'affichage des informations cavaliers
    function displayData(data, type) {
    	// Récupération des données en JSON
    	var data = JSON.parse(data);

      // données récapitulatives au parcours
    	if (type == "R") {
    		$.each(data, function(index, recap) {
      		//$("#resComment .list-group").append('<li class="list-group-item"> Nombre de note : '+recap.nbnote+' - Note moyenne : '+recap.moynote+' - Durée réelle moyenne : '+recap.dureereelle+'</li>');
          $("#tabMoyenne").append('<tr><td>'+recap.nbnote+'</td><td>'+recap.moynote+'</td><td>'+recap.dureereelle+'</td></tr>');
    		});
      // liste des commentaires
    	} else if (type == "L") {
    		$.each(data, function(index, com) {
    		//$("#resComment .list-group").append('<li> Date : '+com.datejour+' - Cavalier : '+com.id_membre_m+' '+com.nom_m+' '+com.prenom_m+' - Note : '+com.note_e+' - Durée : '+com.duree_reel_e+' - Commentaire : '+com.commentaire_e+'</li>');
        $("#tabComment").append('<tr><th scope="row">'+(index+1)+'</th><td>'+com.datejour+'</td><td>'+com.id_membre_m+' '+com.nom_m+' '+com.prenom_m+'</td><td>'+com.note_e+'</td><td>'+com.duree_reel_e+'</td><td>'+com.commentaire_e+'</td></tr>');
    		});




    	}
    }

    $(document).ready(function() {
      // Récupération des paramètres
      var url = new URLSearchParams(location.search);
      var id = url.get('id');
      getData(id,"R");
      getData(id,"L");
    });
    </script>
	</body>
</html>
