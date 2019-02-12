<!DOCTYPE html>
<html>

	<head>
		<title>PÉ-OC</title>
		<meta charset="utf-8">

		<!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../css/lib/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/lib/leaflet.css">

		<link rel="stylesheet" type="text/css" href="../css/header.css">
		<link rel="stylesheet" type="text/css" href="../css/footer.css">
		<link rel="stylesheet" type="text/css" href="../css/parcours.css">

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
    ?>

		<div id="contenu">
			<div id="colonneGauche">
        <?php require_once('../form/parcours.php'); ?>
			</div>

			<div id="colonneDroite">
				<div>
		      <div id="buttonid">
		        <button id="save" type="button" value="Enregistrer">Enregistrer tronçon</button>
		        <button id="new" type="button" value="Nouveau">Nouveau tronçon</button>
		      </div>
		      <br/>
		      <ul id="idLatLng">
		      </ul>
		    </div>
		    <br/>
        <div id="map"></div>
			</div>
		</div>

		<!-- FOOTER -->
		<?php require_once('../include/footer.php'); ?>
	</body>
</html>
