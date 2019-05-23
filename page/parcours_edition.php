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
		<link rel="stylesheet" type="text/css" href="/css/lib/leaflet.pm.css">

		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
			integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay"
			crossorigin="anonymous">

		<!-- JS -->
		<script type="text/javascript" src="/js/lib/jquery.min.js"></script>
		<script type="text/javascript" src="/js/lib/bootstrap.min.js"></script>
		<script type="text/javascript" src="/js/lib/leaflet.js"></script>
		<script type="text/javascript" src="/js/lib/leaflet.pm.min.js"></script>
		<script type="text/javascript" src="/js/lib/leaflet.active-layers.min.js"></script>

		<script type="text/javascript" src="/js/fonctionsData.js"></script>
		<script type="text/javascript" src="/js/map_parcours_edition.js"></script>
	</head>

	<body>
		<?php
			// HEADER
			require_once($_SERVER['DOCUMENT_ROOT'] ."/include/header.php");
			$bdd = connect();

			// Si l'id est défini et est un chiffre
			if (isset($_GET['id'])) {
				if (is_numeric($_GET['id'])) {
					$id_parcours = $_GET['id'];

					// On récupère les données du parcours
					try {
						$res = pg_query_params($bdd,
							 'SELECT * FROM parcours
			          WHERE id_parcours_p = $1',
									array($id_parcours));

						$parcours = pg_fetch_all($res)[0];
					} catch(Exception $e) {
						echo('Erreur : '. $e->getMessage());
					}

					$modif = false;

					// On regarde si le membre connecté est propriétaire du parcours
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

					// Si la personne n'a pas le droit de modifier le parcours
					if (!$modif) {
						exit;
					}
				}
			}
    ?>

		<div id="contenu">
			<div id="colonneGauche">
        <?php require_once($_SERVER['DOCUMENT_ROOT'] ."/form/parcours.php"); ?>
			</div>

			<div id="colonneDroite">
				<div id="notif" style="display:none;">
					<b>MODE CRÉATION :</b> <br>Cliquez sur la carte pour créer un marqueur
				</div>
        <div id="map"></div>

				<table class="table" id="listeTroncons">
				  <thead class="thead-dark">
				    <tr>
							<th scope="col">Position</th>
				      <th scope="col">Durée estimée</th>
				      <th scope="col">Type</th>
				      <th scope="col">Niveau</th>
				      <th scope="col"></th>
				    </tr>
				  </thead>
				  <tbody id="contenuTroncon">

				  </tbody>
				</table>
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
						<?php require_once($_SERVER['DOCUMENT_ROOT'] ."/form/point_interet.php"); ?>
						<?php require_once($_SERVER['DOCUMENT_ROOT'] ."/form/point_vigilance.php"); ?>
					</div>

					<div class="modal-footer" id="modalAction">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
						<button type="button" class="btn btn-primary" id="ajouterMarqueurPI">Ajouter le point d'intérêt</button>
						<button type="button" class="btn btn-primary" id="modifierMarqueurPI" style="display: none;">Modifier le point d'intérêt</button>
						<button type="button" class="btn btn-primary" id="ajouterMarqueurPV">Ajouter le point de vigilance</button>
						<button type="button" class="btn btn-primary" id="modifierMarqueurPV" style="display: none;">Modifier le point de vigilance</button>
					</div>
				</div>
			</div>
		</div>

		<!-- FOOTER -->
		<?php require_once($_SERVER['DOCUMENT_ROOT'] ."/include/footer.php"); ?>

		<?php if ($modif)  { ?>
			<script type="text/javascript" src="/js/map_parcours_edition_chargement.js"></script>
		<?php }?>
	</body>
</html>
