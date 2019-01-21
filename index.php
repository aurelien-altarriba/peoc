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
	<script type="text/javascript" src="js/map_index.js"></script>
	<script type="text/javascript" src="js/index.js" async></script>
</head>
<body>
	<script type="text/javascript">
	 // Se déclenche une fois le document chargé : charge l'ensemble des parcours
		$(document).ready(function(){
			getDataFiltre();
		});

		// Exécute la requête de chargement des parcours
		function getDataFiltre(){
			var nom_p = document.getElementById('nomParcours').value;

			var niveau_p = '';
			var cpt=0;

			$('#niveau input[type=checkbox]:checked').each(function() {
				if (cpt == 0) {
					niveau_p = $(this).val();
				}
				else{
					niveau_p = niveau_p + " ," + $(this).val();
				}
				cpt++;
			});

			var centre_p = document.getElementById('centre').value;
			var dep_p = document.getElementById('departement').value;

			//liste des parcours
			$.ajax("fonction/recup_data_filtre.php",{
				data:{
					nom: nom_p,
					niveau : niveau_p,
					centre : centre_p,
					departement: dep_p
				}
				,
				success: function(data){
					displayDataFiltre(data);
				}
			})
		}

		// Affiche la liste des parcours
		function displayDataFiltre(data){
			//$("#resParcours .list-group").empty();
			var dataTab = data.split(", ;");
			dataTab.pop();

			dataTab.forEach(function(d){
				var d = d.split(", ");
				$("#resParcours .list-group").append(
				'<li class="list-group-item list-group-item-warning" id="parcours'+ d[0] +'">'+
					d[1] +
					'<span class="badge badge-primary badge-pill">7 💬</span>'+
				'</li>');
			});
		}
	</script>

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
