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

		<!-- FOOTER -->
		<?php require_once($_SERVER['DOCUMENT_ROOT'] ."/include/footer.php"); ?>

		<?php if ($modif)  { ?>
			<script type="text/javascript" src="/js/map_parcours_edition_chargement.js"></script>
		<?php }?>
	</body>
</html>
