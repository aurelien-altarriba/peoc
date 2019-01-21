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
					<div>
						<label for="zs_nom_p">Nom </label> : <input type="text" name="zs_nom_p" id="zs_nom_p">
					</div>
					<div>
						<label for="description_p">Description </label> <textarea name="description_p" id="description_p"></textarea>
					</div>

					<p>test</p>
					<div style="display: flex; flex-direction: column;justify-content: center;">
						<div>
							<input type="checkbox" name="service 1" id="nom_s"><label for="nom_s">Services </label>
						</div>

						<div>
							<input type="checkbox" name="service 1" id="nom_s"><label for="nom_s">Services </label>
						</div>
					</div>


				</form>
			</div>
		</div>

	<!-- FOOTER -->
	<?php require_once('./../include/footer.php'); ?>
</body>
</html>
