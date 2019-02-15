<?php
	//session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>PÉ-OC</title>
	<meta charset="utf-8">

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="../css/header.css">
	<link rel="stylesheet" type="text/css" href="../css/footer.css">
	<link rel="stylesheet" type="text/css" href="../css/index.css">

	<link rel="stylesheet" type="text/css" href="../css/lib//bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../css/lib/leaflet.css">

	<!-- JS -->
	<script type="text/javascript" src="../js/lib/jquery.min.js"></script>
	<script type="text/javascript" src="../js/lib/bootstrap.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function(){
			var val = document.getElementById("zs_interet_pi").value;
			if (val != '' & val != null){
				document.getElementById("bt_submit_CM").value="Modifier";
			}
		});
	</script>
</head>
<body>
	<!-- HEADER -->
	<?php
		require_once('./../include/header.php');
		//require_once('../include/connect.php');
		$idc=connect();
	?>

	<!-- CONTENU -->
	<div id="contenu">
		<div id="colonneGauche">

		</div>

		<div id="colonneDroite">
  		<?php
				//Test : à commenter
  			$_SESSION['point_interet'] = 4;
  			$_SESSION['parcours'] = 1;

      	// A récupérer de la page qui appelle
			 	$id_point = '';
			 	if (isset($_SESSION['point_interet'])){
					$id_point = $_SESSION['point_interet'];
				}

				//Initialisation des variables
				$fichier_dossier_dest = '../'.$CF['image']['photo_pi'];
				//$fichier_dossier_dest = '../image/photo_pi/';

				//Statut création :
				if (empty($id_point)){
					//Récupérer le numéro du parcours qui appelle la page
					$id_parcours_pi = '';
					if (isset($_SESSION['parcours'])){
						$id_parcours_pi = $_SESSION['parcours'];
					}
				}
				//Statut visualisation /modification / suppression du point
				else {
	      	//Récupération des informations du point en base
					$sql='SELECT id_interet_pi, id_parcours_pi, nom_p, num_point_pi, id_categorie_pi, nom_pic, url_pi, photo_pi, description_pi
								FROM point_interet
								INNER JOIN parcours ON id_parcours_p = id_parcours_pi
								INNER JOIN categorie_pi on id_categorie_pic = id_categorie_pi
								WHERE id_interet_pi = '.$id_point;

					try{
			      $rs=pg_exec($idc,$sql);
			    }
			    catch (Exception $e) {
			      echo $e->getMessage(),"\n";
			    };
					$ligne=pg_fetch_assoc($rs);

					$id_interet_pi = $ligne['id_interet_pi'];
					$id_parcours_pi = $ligne['id_parcours_pi'];
					$nom_p =  $ligne['nom_p'];
					$num_point_pi = $ligne['num_point_pi'];
					$id_categorie_pi = $ligne['id_categorie_pi'];
					$nom_pic = $ligne['nom_pic'];
					$url_pi = $ligne['url_pi'];
					$photo_pi = $ligne['photo_pi'];
					$description_pi = $ligne['description_pi'];
				}
			?>
			<!-- FORMULAIRE POINT INTERET -->
			<div id="point_interet">
				<?php require_once('../form/point_interet.php'); ?>
			</div>
		</div>
	</div>
	<!-- FOOTER -->
	<?php require_once('../include/footer.php'); ?>
</body>
</html>
