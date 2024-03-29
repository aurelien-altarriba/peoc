<?php
	// Si l'id n'est pas définie dans l'URL
 	if(!isset($_GET['id'])) {
		header('Location: /');

	// Si l'id n'est pas un entier et valide
	} else if ( ((int) htmlspecialchars($_GET['id'])) <= 0) {
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
		<script type="text/javascript" src="/js/lib/rater.min.js"></script>

		<script type="text/javascript" src="/js/fonctionsData.js"></script>
		<script type="text/javascript" src="/js/map_parcours.js"></script>
	</head>

	<body>
		<?php
			// HEADER
			require_once($_SERVER['DOCUMENT_ROOT'] ."/include/header.php");
			$bdd = connect();

			// On récupère les données du parcours
			try {
				$res = pg_query_params($bdd, 'SELECT * FROM parcours
                                      INNER JOIN (SELECT id_parcours_t, ROUND(SUM(duree_estime_t)::numeric,2) AS duree_t, ROUND(SUM(st_length(geom_t::geography))::numeric,2) AS distance_t
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

      // Si c'est un centre équestre
      if (empty($parcours['id_membre_p'])) {
        try {
  				$res_rel = pg_query_params($bdd,
  				 'SELECT nom_ne, nom_d, nom_ce
            FROM parcours
            INNER JOIN niveau_equestre ON id_niveau_p = id_niveau_ne
            INNER JOIN departement ON id_departement_p = id_departement_d
            INNER JOIN centre_equestre ON id_centre_ce = $2
            WHERE id_parcours_p = $1',
  					array($id_parcours, $parcours['id_centre_p']));

  				$parcours_rel = pg_fetch_all($res_rel)[0];
  			} catch(Exception $e) {
  				echo('erreur : '. $e);
  			}
      }

      else {

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

      // On calcule le nom à afficher selon si CE ou CV en créateur
      $nom = 'inconnu';
      if (!empty($parcours_rel['nom_ce'])) {
        $nom = $parcours_rel['nom_ce'];
      } else {
        $nom = $parcours_rel['prenom_m'] . ' ' . $parcours_rel['nom_m'];
      }
		?>

		<div id="contenu">
			<div id="colonneGauche">
        <h3><?php echo($parcours['nom_p']) ?></h3>
				<div class="date">Créé le <?php echo date_format(date_create($parcours['dt_publication_p']), 'd/m/Y'); ?> par <?= $nom ?> (<?php echo $parcours_rel['nom_pa'] ?: 'France'; ?>)</div>
				<div class="autonomie">En autonomie : <span class=<?php echo $parcours['autonomie_p'] ? '"vrai">OUI' : '"faux">NON';?></span></div>
				<div class="niveau">Niveau : <span class="niveau<?php echo $parcours['id_niveau_p']; ?>"><?php echo $parcours_rel['nom_ne']; ?></span></div>
				<div class="departement">Département : <b><?php echo $parcours_rel['nom_d']; ?> (<?php echo $parcours['id_departement_p']; ?>)</b></div>
        <div class="interet">Nombre de points d'intérêt : <b><?php echo $parcours['nb_pi'] ?: 0; ?></b></div>
        <div class="vigilance">Nombre de points de vigilance : <b><?php echo $parcours['nb_pv'] ?: 0; ?></b></div>
        <div class="dureeestime">Durée estimée (min) : <b><?php echo $parcours['duree_t'] ?: 0; ?></b></div>
        <div class="distance">Distance (m) : <b><?php echo $parcours['distance_t'] ?: 0; ?></b></div>
        <div class="description alert alert-secondary"><?php echo $parcours['description_p'] ?: 'Aucune description :('; ?></div>

        <?php
        // On regarde si l'utilisateur est propriétaire du parcours
        $modif = false;

        if ($parcours['id_centre_p']) {
          $id_ce = ( isset($_SESSION['membre']['ce']['id_centre_ce']) ) ? $_SESSION['membre']['ce']['id_centre_ce'] : 'non';

          if ($id_ce == $parcours['id_centre_p']) {
            $modif = true;
          }
        }
        else if (isset($_SESSION['membre']['id_membre_m'])) {
          $id_membre = $_SESSION['membre']['id'];

          if ($id_membre == $parcours['id_membre_p']) {
            $modif = true;
          }
        }

        // Si il est propriétaire, on affiche le bouton
        if ($modif) {?>
            <a class="btn btn-outline-info" href="/page/parcours_edition.php?id=<?php echo($id_parcours); ?>" role="button">
              Modifier le parcours
            </a>
        <?php }?>

        <hr>

        <div id="listeTroncon">
          <h5>Tronçons du parcours :</h5>
          <table class="table table-sm table-hover">
            <thead class="thead-dark">
              <tr>
                <th scope="col"><small>Position</small></th>
                <th scope="col"><small>Hiérarchie</small></th>
                <th scope="col"><small>Difficulté</small></th>
                <th scope="col"><small>Type terrain</small></th>
                <th scope="col"><small>Durée estimée (min)</small></th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($troncons as $key => $value) {
                print('<tr><td><small>'.strval($value['num_position_t']).'</small></td><td><small>'.$value['nom_h'].'</small></td><td>'.$value['nom_nt'].'</small></td><td><small>'.$value['nom_tt'].'</small></td><td><small>'.strval($value['duree_estime_t']).'</small></td></tr>');
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
          <div class="notes"></div>

          <div class="comments">
            <h3 class="titre_comm">Commentaires</h3>
            <table class="table table-sm table-hover">
              <thead class="thead-dark">
                <tr>
                  <th scope="col"><small>#</small></th>
                  <th scope="col"><small>Date</small></th>
                  <th scope="col"><small>Cavalier</small></th>
                  <th scope="col"><small>Note</small></th>
                  <th scope="col"><small>Durée réelle (min)</small></th>
                  <th scope="col"><small>Commentaire</small></th>
                </tr>
              </thead>
              <tbody id="tabComment">
              </tbody>
            </table>
          </div>
        </div>

        <hr class="ligne_hr">

        <!-- FORMULAIRE COMMENTAIRE -->
        <div id="parcours_commentaire">
          <?php
            if (!empty($_SESSION['membre']['id'])) {
              require_once($_SERVER['DOCUMENT_ROOT'] ."/form/parcours_commentaire.php");
            } else {
              echo("<h5>Vous devez vous connecter pour commenter ce parcours</h5>");
            }
          ?>
        </div>
      </div>
		</div>

    <div class="modal fade" role="dialog" tabindex="-1" id="formulaireMarqueur" style="display: none;" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="titre_form_marqueur">Ajouter un point</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Annuler">
							<span aria-hidden="true">×</span>
						</button>
					</div>

					<div class="modal-body">
						<?php require_once($_SERVER['DOCUMENT_ROOT'] ."/form/point_vigilance.php"); ?>
					</div>

					<div class="modal-footer" id="modalAction">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
						<button type="button" class="btn btn-primary" id="ajouterMarqueurPV">Ajouter le point de vigilance</button>
					</div>
				</div>
			</div>
		</div>

		<!-- FOOTER -->
		<?php require_once($_SERVER['DOCUMENT_ROOT'] ."/include/footer.php"); ?>

    <script type="text/javascript">

    // type: "R" global au parcours
    // 			 "L" liste de tous les commentaires
    function getData(id, type) {

    	// Récupération des commentaires
    	$.post('/fonction/recup_data_commentaire.php',
    		{
    			id: id,    // Récupération de l'ID du parcours
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

      // Données récapitulatives au parcours
    	if (type == "R") {
        $(".notes").html('');

    		$.each(data, function(index, recap) {
          var listeNote = `
            <div class="bloc_note">
              <h4>Notes <span class="badge badge-primary">👤 ${ recap.nbnote }</span></h4>
              <div class="note_globale"></div>
              <div class="txt_note">${ +recap.moynote } / 5</div>
              <div class="alert alert-warning">
                Durée réelle moyenne :<br><b>${ recap.dureereelle } minutes</b>
              </div>
            </div>
          `;

          $(".notes").append(listeNote);

          var options_global = {
            max_value: 5,
            step_size: 1,
            initial_value: +recap.moynote,
            selected_symbol_type: 'cheval',
            symbols: {
              cheval: {
                base: '<img src="/image/cheval.png" class="note_base"/>',
                hover: '<img src="/image/cheval.png" class="note_hover"/>',
                selected: '<img src="/image/cheval.png" class="note_selected"/>',
              }
            },
            cursor: 'default',
            readonly: true,
          }

          $(".note_globale").rate(options_global);
        });
    	}

      // Liste des commentaires
      else if (type == "L") {
        $("#tabComment").html('');

    		$.each(data, function(index, com) {
          $("#tabComment").append('<tr><th scope="row"><small>'+(index+1)+'</small></th><td><small>'+com.datejour+'</small></td><td><small>'+com.id_membre_m+' '+com.nom_m+' '+com.prenom_m+'</small></td><td><small>'+com.note_e+'</small></td><td><small>'+com.duree_reel_e+'</small></td><td><small>'+com.commentaire_e+'</small></td></tr>');
    		});
    	}
    }

    $(document).ready(function() {

      <?php if (empty($_SESSION['membre']['id'])) {
        echo("$('.leaflet-control-custom.bt_custom_map').css('display', 'none');");
      } ?>

      // Récupération des paramètres
      var url = new URLSearchParams(location.search);
      var id = url.get('id');
      getData(id,"R");
      getData(id,"L");

      // Envoi du commentaire
      $('#bt_submit_comm').on('click', function() {
        $.post('/fonction/verif_data_commentaire.php',
          {
            note: _note,
            duree: $('#zs_duree_reel_e').val(),
            commentaire: $('#zs_commentaire_e').val(),
            id_parcours: id
          },
          function(data) {
            if (data == "OK") {
              getData(id,"R");
              getData(id,"L");
              alert('Merci pour votre commentaire !');
            }
            else {
              alert('Erreur : ' + data);
            }
          }
        )
      });

      // Envoi du point de vigilance
      $('#bt_submit_pv').on('click', function() {
        $.post('/fonction/verif_point_vigilance.php',
          {
            id_parcours: id,
            dt_debut: $('#zs_dt_debut_pv').val(),
            dt_fin: $('#zs_dt_fin_pv').val(),
            dt_fin: $('#zs_dt_fin_pv').val(),
            id_categorie: $('#zs_categorie_pv').val(),
            description: $('#zs_description_pv').val(),
            lat: $('#zs_latitude_pv').val(),
            lng: $('#zs_longitude_pv').val()
          }
        )
      });
    });
    </script>
	</body>
</html>
