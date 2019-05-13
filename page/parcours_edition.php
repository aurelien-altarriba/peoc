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
	</body>
</html>
