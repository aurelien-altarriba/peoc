<!DOCTYPE html>
<html>
<head>
	<title>PÉ-OC</title>
	<meta charset="utf-8">

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/footer.css">
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/leaflet.css">

	<!-- JS -->
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/leaflet.js"></script>
	<script type="text/javascript" src="js/leaflet.active-layers.min.js"></script>
	<script type="text/javascript" src="js/fonctionsData.js"></script>
	<script type="text/javascript" src="js/map_index.js"></script>
	<script type="text/javascript" src="js/index.js" async></script>
</head>
<body>

	<!-- HEADER -->
	<?php
		require_once('include/header.php');
		$idc = connect();
	?>

	<div id="contenu">

		<!-- PARTIE GAUCHE -->
		<div id="colonneGauche">

			<!-- FILTRES -->
			<div id="filtres">
				<h3>FILTRES</h3>
				<?php require_once('form/filtre.php'); ?>
			</div>

			<!-- PARCOURS -->
			<div id="resParcours">
				<h3>PARCOURS</h3>
				<ul class="list-group">
				</ul>
			</div>
		</div>

		<!-- PARTIE DROITE -->
		<div id="colonneDroite">

			<!-- DESCRIPTION -->
			<div id="description">
				<h2>Bienvenue sur PÉ-OC !</h2>
				<p>PÉ-OC est une plateforme de création et de partage de randonnées équestre en Occitanie</p>
			</div>

			<!-- CARTE -->
			<div id="map"></div>
		</div>
	</div>

	<!-- FOOTER -->
	<?php require_once('include/footer.php'); ?>
</body>
</html>
