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
  			$_SESSION['centre_equestre'] = 1;

      	// A récupérer de la page qui appelle
	  		$id_membre = '';
      		if (isset($_SESSION['membre']['id'])){
      			$id_membre = $_SESSION['membre']['id'];
      		}
			 	$id_centre = '';
			 	if (isset($_SESSION['membre']['centre_equestre'])){
					$id_centre = $_SESSION['membre']['centre_equestre'];
				}

				//Initialisation ds variables du formulaire à vide
				$id_centre_ce = '';
				$nom_ce = '';
				$adresse_ce = '';
				$cp_ce = '';
				$ville_ce = '';
				$id_departement_ce = '';
				$tel_ce = '';
				$mail_ce = '';
				$nb_cheval_ce = '';
				$id_membre_ce = '';
				$url_ce = '';
				$logo_ce = '';
				$fichier_dossier_dest = '../'.$CF['image']['logo'];
				//$fichier_dossier_dest = '../image/logo/';

				//Statut modification :
				if (!empty($id_centre)){

	      	//Récupération des informations du centre en base
					$sql='select id_centre_ce, nom_ce, adresse_ce, cp_ce, ville_ce, id_departement_ce, nom_d, tel_ce, mail_ce, nb_cheval_ce,url_ce,logo_ce';
					$sql=$sql.'from centre_equestre ';
					$sql=$sql.'inner join departement on id_departement_d = id_departement_ce ';
					$sql=$sql.'where id_centre_ce = '.$id_centre.' and id_membre_ce = '.$id_membre;

					try{
			      $rs=pg_exec($idc,$sql);
			    }
			    catch (Exception $e) {
			      echo $e->getMessage(),"\n";
			    };
					$ligne=pg_fetch_assoc($rs);

					$id_centre_ce = $ligne['id_centre_ce'];
					$nom_ce = $ligne['nom_ce'];
					$adresse_ce = $ligne['adresse_ce'];
					$cp_ce = $ligne['cp_ce'];
					$ville_ce = $ligne['ville_ce'];
					$id_departement_ce = $ligne['id_departement_ce'];
					$tel_ce = $ligne['tel_ce'];
					$mail_ce = $ligne['mail_ce'];
					$nb_cheval_ce = $ligne['nb_cheval_ce'];
					$id_membre_ce = $ligne['id_membre_ce'];
					$url_ce = $ligne['url_ce'];
					$logo_ce = $ligne['logo_ce'];
				}
			?>
			<!-- FORMULAIRE POINT INTERET -->
			<div id="centre_equestre">
				<?php require_once('../form/centre_equestre.php'); ?>
			</div>
		</div>
	</div>
	<!-- FOOTER -->
	<?php require_once('../include/footer.php'); ?>
</body>
</html>