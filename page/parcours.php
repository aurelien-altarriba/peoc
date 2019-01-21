<?php
	//session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>PÉ-OC</title>
	<meta charset="utf-8">

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="./../css/header.css">
	<link rel="stylesheet" type="text/css" href="./../css/footer.css">
	<link rel="stylesheet" type="text/css" href="./../css/index.css">

	<link rel="stylesheet" type="text/css" href="./../css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="./../css/leaflet.css">

	<!-- JS -->
	<script type="text/javascript" src="./../js/jquery.min.js"></script>
	<script type="text/javascript" src="./../js/bootstrap.min.js"></script>
</head>
<body>
	<!-- HEADER -->
	<?php require_once('../include/header.php');
		$idc = connect();
	?>

	<!-- CONTENU -->
	<div id="contenu">
		<div id="colonneGauche">

		</div>

		<div id="colonneDroite">
			<div id="div_parcours">
			<?php
			try {
				$res = pg_query($idc, ' select *
																from parcours
																inner join niveau_equestre on id_niveau_p = id_niveau_ne
																left join centre_equestre on id_centre_p = id_centre_ce;');
			} catch(Exception $e) {
				echo('erreur : '. $e);
			}


			$res = pg_fetch_all($res);

			// var_dump($res);

			// foreach ($res[0] as $key => $value) {
			// 	print($key .' : <input type="text" name="zs_nom_p" value="'. $value .'"/><br/>');
			// }
			?>

				<form>
					<!-- Zone de saisie du nom du parcours -->
					<div>
						<label for="zs_nom_p">Nom </label> : <input type="text" name="zs_nom_p" id="zs_nom_p">
					</div>
					<!-- Zone de saisie de la description du parcours -->
					<div>
						<label for="description_p">Description </label> <textarea name="description_p" id="description_p"></textarea>
					</div>
					<!-- Choix des services proposés par le centre équestre du parcours -->
					<div style="display: flex; flex-direction: column;justify-content: center;">
						<div>
							<p>Services</p>
							<input type="checkbox" name="service 1" id="nom_s" ><label for="nom_s">Services </label>
						</div>
						<div>
							<input type="checkbox" name="service 1" id="nom_s"><label for="nom_s">Services </label>
						</div>
					</div>
					<!-- Choix de rendre le parcours visible au public -->
					<div style="display: flex; flex-direction: column;justify-content: center;">
						<div>
							<p>Rendre le parcours public</p>
							<input type="radio" name="visible_p" id="visible_p" ><label for="visible_p">Oui </label>
						</div>
						<div>
							<input type="radio" name="visible_p" id="visible_p"><label for="visible_p">Non </label>
						</div>
				  </div>
					<!-- Le parcours est-il faisable en autonomie ou pas -->
					<div style="display: flex; flex-direction: column;justify-content: center;">
						<div>
							<p>Parcours autonome</p>
							<input type="radio" name="autonomie_p" id="autonomie_p" ><label for="autonomie_p">Oui </label>
						</div>
						<div>
							<input type="radio" name="autonomie_p" id="autonomie_p"><label for="autonomie_p">Non </label>
						</div>
				  </div>


				</form>
			</div>
		</div>

	<!-- FOOTER -->
	<?php require_once('./../include/footer.php'); ?>
</body>
</html>
