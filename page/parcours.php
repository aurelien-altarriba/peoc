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

		<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../css/leaflet.css">

		<!-- JS -->
		<script type="text/javascript" src="../js/jquery.min.js"></script>
		<script type="text/javascript" src="../js/bootstrap.min.js"></script>
	</head>

	<body>
		<?php
			// HEADER
			require_once('../include/header.php');
			$idc = connect();
		?>

		<!-- Formulaire -->
		<?php //require_once('../form/parcours.php'); ?>

		<!-- FOOTER -->
		<?php require_once('../include/footer.php'); ?>
	</body>
</html>
