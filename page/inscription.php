<!DOCTYPE html>
<html>
<head>
	<title>PÉ-OC</title>
	<meta charset="utf-8">

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="/css/header.css">
	<link rel="stylesheet" type="text/css" href="/css/footer.css">
	<link rel="stylesheet" type="text/css" href="/css/inscription.css">

	<link rel="stylesheet" type="text/css" href="/css/lib/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/css/lib/leaflet.css">

	<!-- JS -->
	<script type="text/javascript" src="/js/lib/jquery.min.js"></script>
	<script type="text/javascript" src="/js/lib/bootstrap.min.js"></script>
</head>
<body>
	<?php
		require_once($_SERVER['DOCUMENT_ROOT'] ."/include/connect.php");
	 	$idc = connect();
	?>

	<!-- HEADER -->
	<?php require_once($_SERVER['DOCUMENT_ROOT'] ."/include/header.php"); ?>

	<!-- CONTENU -->
	<div id="contenu">
		<div>
  		<?php
				$id_membre = ( isset($_SESSION['membre']['id']) ) ? $_SESSION['membre']['id'] : '';
				$fichier_dossier_dest = '/'. $CF['image']['photo'];
				$ligne = null;

				// Statut inscrit : visualisation /modification du profil
				if ($id_membre != '') {

	      	// Récupération des informations du membre en base
					$sql = "SELECT id_membre_m, nom_m, prenom_m, dt_naissance_m, adresse_m, ville_m, cp_m, id_pays_m, nom_pa, tel_m,
										mail_m, num_licence_c, dt_exp_licence_c, id_niveau_c, nom_ne, photo_c, login_ic, mdp_ic, id_centre_ce, nom_ce
									FROM membre
									INNER JOIN pays ON id_pays_pa = id_pays_m
									INNER JOIN info_connexion ON id_membre_ic = id_membre_m
									LEFT JOIN cavalier ON id_membre_c = id_membre_m
									LEFT JOIN niveau_equestre ON id_niveau_ne = id_niveau_c
									LEFT JOIN centre_equestre ON id_membre_ce = id_membre_m
									WHERE id_membre_m = $id_membre";

					try{
			      $rs = pg_exec($idc, $sql);
			    }
			    catch (Exception $e) {
			      echo('Erreur: '. $e->getMessage());
			    };

					$ligne = pg_fetch_assoc($rs);

				}

				$id_membre_m      = ( !empty($ligne['id_membre_m']) )      ? $ligne['id_membre_m'] : '';
				$nom_m            = ( !empty($ligne['nom_m']) )            ? $ligne['nom_m'] : '';
				$prenom_m         = ( !empty($ligne['prenom_m']) )         ? $ligne['prenom_m'] : '';
				$dt_naissance_m   = ( !empty($ligne['dt_naissance_m']) )   ? $ligne['dt_naissance_m'] : '';
				$adresse_m        = ( !empty($ligne['adresse_m']) )        ? $ligne['adresse_m'] : '';
				$ville_m          = ( !empty($ligne['ville_m']) )          ? $ligne['ville_m'] : '';
				$cp_m             = ( !empty($ligne['cp_m']) )             ? $ligne['cp_m'] : '';
				$id_pays_m        = ( !empty($ligne['id_pays_m']) )        ? $ligne['id_pays_m'] : '';
				$nom_pa           = ( !empty($ligne['nom_pa']) )           ? $ligne['nom_pa'] : '';
				$tel_m            = ( !empty($ligne['tel_m']) )            ? $ligne['tel_m'] : '';
				$mail_m           = ( !empty($ligne['mail_m']) )           ? $ligne['mail_m'] : '';
				$num_licence_c    = ( !empty($ligne['num_licence_c']) )    ? $ligne['num_licence_c'] : '';
				$dt_exp_licence_c = ( !empty($ligne['dt_exp_licence_c']) ) ? $ligne['dt_exp_licence_c'] : '';
				$id_niveau_c      = ( !empty($ligne['id_niveau_c']) )      ? $ligne['id_niveau_c'] : '';
				$nom_ne           = ( !empty($ligne['nom_ne']) )           ? $ligne['nom_ne'] : '';
				$photo_c          = ( !empty($ligne['photo_c']) )          ? $ligne['photo_c'] : '';
				$login_ic         = ( !empty($ligne['login_ic']) )         ? $ligne['login_ic'] : '';
				$mdp_ic           = ( !empty($ligne['mdp_ic']) )           ? $ligne['mdp_ic'] : '';
				$id_centre_ce     = ( !empty($ligne['id_centre_ce']) )     ? $ligne['id_centre_ce'] : '';
				$nom_ce           = ( !empty($ligne['nom_ce']) )           ? $ligne['nom_ce'] : '';
			?>

			<!-- PROFIL MEMBRE -->
			<div id="profil">
				<?php require_once($_SERVER['DOCUMENT_ROOT'] ."/form/profil.php"); ?>
			</div>
		</div>
	</div>

	<!-- FOOTER -->
	<?php require_once($_SERVER['DOCUMENT_ROOT'] ."/include/footer.php"); ?>
</body>
</html>
