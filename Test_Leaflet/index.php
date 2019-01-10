<!DOCTYPE html>
<html>
<head>
	<title>G.P.M.M.</title>
	<meta charset="utf-8">

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/footer.css">
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<link rel="stylesheet" type="text/css" href="lib/boostrap/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="lib/leaflet/leaflet.css">
	<link rel="stylesheet" type="text/css" href="lib/jquery/jquery-ui-1.12.1.custom/jquery-ui.css">

	<!-- JS -->
	<script type="text/javascript" src="lib/jquery/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="lib/jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
	<script type="text/javascript" src="lib/boostrap/bootstrap.min.js"></script>
	<script type="text/javascript" src="lib/leaflet/leaflet.js"></script>
	<script type="text/javascript" src="lib/leaflet/leaflet.active-layers.min.js"></script>
	<script type="text/javascript" src="js/main.js"></script>

	<script type="text/javascript">
		// Quand le document est prêt
		$(document).ready(function() {
			mapLoad();
		});
	</script>
</head>
<body>

	<!-- HEADER -->
	<?php require_once('./php/include/header.php'); ?>

	<!-- CONTENU -->
	<div id="contenu">

		<div id="colonneGauche">

			<div id="form">
				<form action="javascript:submitQuery()">
					<label for="featname">Show features within</label>
					<input type="text" name="featname"></input>
					<label for="distance">km of features named</label>
					<input type="text" name="distance"></input>
					<input type="submit" value="Submit"></input>
				</form>				
			</div>

			<!-- DATA DES FILTRES -->
			<div id="datas">
				<h3 id="datas_titre">Données</h3>
					<div id="datas_res">
						<div class="form-group">
							<?php
								include('./php/include/connect.php');
								$idc=connect();
								
								$sql='select id, libelle, geom 
										from g_point;';		
								$rs=pg_exec($idc,$sql);
								
								while($ligne=pg_fetch_assoc($rs)){
									print('<div class="form-check">'."\n");
									print('<input class="form-check-input" type="checkbox" id="'.$ligne['id'].'" value="'.$ligne['id'].'">'."\n");
									print('<label class="form-check-label" for="'.$ligne['id'].'">'.$ligne['libelle'].'</label>'."\n");
									print('<input type="submit" onclick="f_display('.$ligne['id'].');" />'."\n");
									print('</div>'."\n");
								}	
							?>
						</div>

					</div>
				<script type="text/javascript">
                function f_display(val)
                {
                    var value=document.getElementById(val).value;
                    alert(value);
                }
                </script>
			</div>
		</div>
		<div id="colonneDroite">
			<div id="description">
				<h2>Bienvenue au G.P.M.M. !</h2>
				<p>Ceci est une plateforme de test</p>
			</div>

			<!-- CARTE -->
			<div id="map"></div>
				<script type="text/javascript">
					document.getElementById("map").addEventListener("click",f_event,false);
					function f_event(event){
						alert("test2");
					}
				</script>
		</div>
	</div>

	<!-- FOOTER -->
	<?php require_once('./php/include/footer.php'); ?>
</body>
</html>

 <input type="submit" onclick="f_display();"/>
