<!DOCTYPE html>
<html>
<head>
	<title>G.P.M.M.</title>
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
				<h3>Filtres</h3>

				<?php require_once('form/filtre.php'); ?>				
			</div>

			<!-- DATA DES FILTRES -->
			<div id="datas">
				<h3 id="datas_titre">Données</h3>
					<div id="datas_res">
						<!--
						<div class="list-group">
						  <button type="button" class="list-group-item list-group-item-action active">
						    Cras justo odio
						  </button>
						  <button type="button" class="list-group-item list-group-item-action">Dapibus ac facilisis in</button>
						  <button type="button" class="list-group-item list-group-item-action">Morbi leo risus</button>
						  <button type="button" class="list-group-item list-group-item-action">Porta ac consectetur ac</button>
						  <button type="button" class="list-group-item list-group-item-action" disabled>Vestibulum at eros</button>
						</div>
						-->


						<div class="form-group">
							<?php
								include('./include/connect.php');
								$idc=connect();
								
								$sql='select id, libelle, geom 
										from g_entree;';		
								$rs=pg_exec($idc,$sql);
								
								while($ligne=pg_fetch_assoc($rs)){
									print('<div class="form-check">'."\n");
									print('<input class="form-check-input" type="checkbox" id="'.$ligne['id'].'" value="'.$ligne['id'].'">'."\n");
									print('<label class="form-check-label" for="'.$ligne['id'].'">'.$ligne['libelle'].'</label>'."\n");
									print('</div>'."\n");
								}	
							?>
						</div>

						<!--
						<div class="form-group">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="level1" value="1">
								<label class="form-check-label" for="level1">XXXXX</label>
							</div>

							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="level2" value="2">
								<label class="form-check-label" for="level2">YYYYY</label>
							</div>

							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="level3" value="3">
								<label class="form-check-label" for="level3">ZZZZZ</label>
							</div>
									<div class="form-check">
								<input class="form-check-input" type="checkbox" id="level3" value="3">
								<label class="form-check-label" for="level3">ZZZZZ</label>
							</div>
									<div class="form-check">
								<input class="form-check-input" type="checkbox" id="level3" value="3">
								<label class="form-check-label" for="level3">ZZZZZ</label>
							</div>
									<div class="form-check">
								<input class="form-check-input" type="checkbox" id="level3" value="3">
								<label class="form-check-label" for="level3">ZZZZZ</label>
							</div>
									<div class="form-check">
								<input class="form-check-input" type="checkbox" id="level3" value="3">
								<label class="form-check-label" for="level3">ZZZZZ</label>
							</div>
									<div class="form-check">
								<input class="form-check-input" type="checkbox" id="level3" value="3">
								<label class="form-check-label" for="level3">ZZZZZ</label>
							</div>
									<div class="form-check">
								<input class="form-check-input" type="checkbox" id="level3" value="3">
								<label class="form-check-label" for="level3">ZZZZZ</label>
							</div>
									<div class="form-check">
								<input class="form-check-input" type="checkbox" id="level3" value="3">
								<label class="form-check-label" for="level3">ZZZZZ</label>
							</div>
									<div class="form-check">
								<input class="form-check-input" type="checkbox" id="level3" value="3">
								<label class="form-check-label" for="level3">ZZZZZ</label>
							</div>
						</div>
						-->
					</div>
			</div>
		</div>

		<div id="colonneDroite">
			<div id="description">
				<h2>Bienvenue au G.P.M.M. !</h2>
				<p>Ceci est une plateforme de test</p>
			</div>

			<!-- CARTE -->
			<div id="map"></div>
		</div>
	</div>

	<!-- FOOTER -->
	<?php require_once('include/footer.php'); ?>
</body>
</html>