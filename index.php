<!DOCTYPE html>
<html>
<head>
	<title>PE-OC</title>
	<meta charset="utf-8">

	<!-- CSS test modif -->
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
	<script type="text/javascript" src="js/map.js"></script>

	<script type="text/javascript">
		// Quand le document est prêt
		$(document).ready(function() {
			mapLoad();
		});
	</script>
</head>
<body>

	<!-- HEADER -->
	<?php require_once('include/header.php'); ?>

	<!-- CONTENU -->
	<div id="contenu">

		<div id="colonneGauche">

			<!-- FILTRES -->
			<div id="filtres">
				<h3>FILTRES</h3>

				<?php require_once('form/filtre.php'); ?>
			</div>

			<!-- PARCOURS DES FILTRES -->
			<div id="resParcours">
				<h3>PARCOURS</h3>

				<ul class="list-group">
					<li class="list-group-item list-group-item-success">
						Cras justo odio
						<span class="badge badge-primary badge-pill">14 💬</span>
					</li>
					<li class="list-group-item list-group-item-danger">
						Dapibus ac facilisis in
						<span class="badge badge-primary badge-pill">8 💬</span>
					</li>
					<li class="list-group-item list-group-item-warning">
						Morbi leo risus
						<span class="badge badge-primary badge-pill">5 💬</span>
					</li>
					<li class="list-group-item list-group-item-warning">
						Porta ac consectetur ac
						<span class="badge badge-primary badge-pill">7 💬</span>
					</li>
					<li class="list-group-item list-group-item-success">
						Vestibulum at eros
						<span class="badge badge-primary badge-pill">2 💬</span>
					</li>
					<li class="list-group-item list-group-item-danger">
						Cras justo odio
						<span class="badge badge-primary badge-pill">24 💬</span>
					</li>
					<li class="list-group-item list-group-item-success">
						Dapibus ac facilisis in
						<span class="badge badge-primary badge-pill">6 💬</span>
					</li>
					<li class="list-group-item list-group-item-success">
						Morbi leo risus
						<span class="badge badge-primary badge-pill">17 💬</span>
					</li>
					<li class="list-group-item list-group-item-danger">
						Porta ac consectetur ac
						<span class="badge badge-primary badge-pill">0 💬</span>
					</li>

				</ul>
			</div>
		</div>

		<div id="colonneDroite">
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
