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

	<link rel="stylesheet" type="text/css" href="../css/lib/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../css/lib/leaflet.css">

	<!-- JS -->
	<script type="text/javascript" src="../js/lib/jquery.min.js"></script>
	<script type="text/javascript" src="../js/lib/bootstrap.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function(){
			var val = document.getElementById("zs_vigilance_pv").value;
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
		//$idc=connect();
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
  			$_SESSION['point_vigilance'] = '';
  			$_SESSION['parcours'] = 1;
				$_SESSION['membre'] = 1;

      	// A récupérer de la page qui appelle
				$id_membre = '';
		 		if (isset($_SESSION['membre'])){
		 			$id_membre = $_SESSION['membre'];
		 		}
			 	$id_point = '';
				if (isset($_SESSION['point_vigilance'])){
				 $id_point = $_SESSION['point_vigilance'];
				}

				//Initialisation ds variables du formulaire à vide
				$id_vigilance_pv = '';
				$id_parcours_pv = '';
				$nom_p = '';
				$num_point_pv = '';
				$dt_creation_pv = '';
				$dt_debut_pv = '';
				$dt_fin_pv = '';
				$id_membre_pv = '';
				$id_categorie_pv = '';
				$nom_pvc = '';
				$photo_pv = '';
				$description_pv = '';
				$fichier_dossier_dest = '../'.$CF['image']['photo_pv'];
				//$fichier_dossier_dest = '../image/photo_pv/';

				//Statut création :
				if (empty($id_point)){
					//Récupérer le numéro du parcours qui appelle la page
					if (isset($_SESSION['parcours'])){
						$id_parcours_pv = $_SESSION['parcours'];
						$id_membre_pv = $id_membre;
					}
				}
				//Statut visualisation /modification / suppression du point
				else {
	      	//Récupération des informations du point en base
					$sql='select id_vigilance_pv, id_parcours_pv, nom_p, prenom_m, num_point_pv, dt_creation_pv, dt_debut_pv, dt_fin_pv, id_categorie_pv, nom_pvc, id_membre_pv, nom_m, photo_pv, description_pv ';
					$sql=$sql.'from point_vigilance ';
					$sql=$sql.'inner join parcours on id_parcours_p = id_parcours_pv ';
					$sql=$sql.'inner join categorie_pv on id_categorie_pvc = id_categorie_pv ';
					$sql=$sql.'inner join membre on id_membre_m = id_membre_pv ';
					$sql=$sql.'where id_vigilance_pv = '.$id_point;

					try{
						$rs=pg_exec($idc,$sql);
					}
					catch (Exception $e) {
						echo $e->getMessage(),"\n";
					};
					$ligne=pg_fetch_assoc($rs);

					$id_vigilance_pv = $ligne['id_vigilance_pv'];
					$id_parcours_pv = $ligne['id_parcours_pv'];
					$nom_p = $ligne['nom_p'];
					$num_point_pv = $ligne['num_point_pv'];
					$dt_creation_pv = $ligne['dt_creation_pv'];
					$dt_debut_pv = $ligne['dt_debut_pv'];
					$dt_fin_pv = $ligne['dt_fin_pv'];
					$id_membre_pv = $ligne['id_membre_pv'];
					$nom_m = $ligne['nom_m']." ".$ligne['prenom_m'];
					$id_categorie_pv = $ligne['id_categorie_pv'];
					$nom_pvc = $ligne['nom_pvc'];
					$photo_pv = $ligne['photo_pv'];
					$description_pv = $ligne['description_pv'];
				}
			?>
			<!-- FORMULAIRE POINT VIGILANCE -->
			<div id="point_vigilance">
				<?php require_once('../form/point_vigilance.php'); ?>
			</div>
		</div>
	</div>
	<!-- FOOTER -->
	<?php require_once('../include/footer.php'); ?>
</body>
</html>
