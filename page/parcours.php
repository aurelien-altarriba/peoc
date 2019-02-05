<?php
	// Si l'id n'est pas définie dans l'URL
 	if(!isset($_GET['id'])) {
		header('Location: ../');

	// Si l'id n'est pas un entier et valide
	} else if( ((int) htmlspecialchars($_GET['id'])) <= 0) {
		header('Location: ../');
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
		<link rel="stylesheet" type="text/css" href="../css/header.css">
		<link rel="stylesheet" type="text/css" href="../css/footer.css">
		<link rel="stylesheet" type="text/css" href="../css/parcours.css">

		<link rel="stylesheet" type="text/css" href="../css/lib/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../css/lib/leaflet.css">

		<!-- JS -->
		<script type="text/javascript" src="../js/lib/jquery.min.js"></script>
		<script type="text/javascript" src="../js/lib/bootstrap.min.js"></script>
		<script type="text/javascript" src="../js/lib/leaflet.js"></script>
		<script type="text/javascript" src="../js/lib/leaflet.active-layers.min.js"></script>
    
		<script type="text/javascript" src="../js/fonctionsData.js"></script>
		<script type="text/javascript" src="../js/map_parcours.js"></script>
	</head>

	<body>
		<?php
			// HEADER
			require_once('../include/header.php');
			$bdd = connect();

			// On récupère les données du parcours
			try {
				$res = pg_query_params($bdd, 'SELECT * FROM parcours WHERE id_parcours_p = $1', array($id_parcours));
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
		?>

		<div id="contenu">
			<div id="colonneGauche">
				<h3><?php echo($parcours['nom_p']) ?></h3>
				<div>Créé le <?php echo date_format(date_create($parcours['dt_publication_p']), 'd/m/Y'); ?> par <?php echo $parcours_rel['prenom_m'] .' '. $parcours_rel['nom_m']; ?> (<?php echo $parcours_rel['nom_pa']; ?>)</div>
				<div>En autonomie : <b><?php echo $parcours['autonomie_p'] ? 'OUI' : 'NON';?></b></div>
				<div>Niveau : <b><?php echo $parcours_rel['nom_ne']; ?></b></div>
				<div>Département : <b><?php echo $parcours_rel['nom_d']; ?> (<?php echo $parcours['id_departement_p']; ?>)</b></div>
				<div><?php echo $parcours['description_p']; ?></div>
			</div>

			<div id="colonneDroite">
				<div id="map"></div>
			</div>
		</div>

		<!-- Formulaire -->
		<?php //require_once('../form/parcours.php'); ?>

		<!-- FOOTER -->
		<?php require_once('../include/footer.php'); ?>
	</body>
</html>
