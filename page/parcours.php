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

		<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../css/leaflet.css">

		<!-- JS -->
		<script type="text/javascript" src="../js/jquery.min.js"></script>
		<script type="text/javascript" src="../js/bootstrap.min.js"></script>
		<script type="text/javascript" src="../js/leaflet.js"></script>
		<script type="text/javascript" src="../js/leaflet.active-layers.min.js"></script>
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
		?>

		<div id="contenu">
			<div id="colonneGauche">
				<h3><?php echo($parcours['nom_p']) ?></h3>
				<div>En autonomie : <?php echo $parcours['autonomie_p'] ? '<b>OUI</b>' : '<b>NON</b>' ?></div>
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
