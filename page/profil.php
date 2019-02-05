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
</head>
<body>
	<!-- HEADER -->
	<?php require_once('./../include/header.php'); ?>
	<?php
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
  			//$_SESSION['id_membre'] = 1;

	      //A récupérer de la page qui appelle
	      $id_membre = '';
	      if (isset($_SESSION['membre']['id'])){
	      	$id_membre = $_SESSION['membre']['id'];
				}

				// Initialisation ds variables du formulaire à vide
				$id_membre_m='';
				$nom_m='';
				$prenom_m='';
				$dt_naissance_m='';
				$adresse_m='';
				$ville_m='';
				$cp_m='';
				$id_pays_m='';
				$nom_pa='';
				$tel_m='';
				$mail_m='';
				$num_licence_c='';
				$dt_exp_licence_c='';
				$id_niveau_c='';
				$nom_ne='';
				$photo_c='';
				$login_ic='';
				$mdp_ic='';
				$id_centre_ce='';
				$nom_ce='';
				$fichier_dossier_dest = '../'.$CF['image']['photo'];
				//$fichier_dossier_dest = '../image/photo/';

				// Statut inscrit : visualisation /modification du profil
				if ($id_membre != ''){
	      	// Récupération des informations du membre en base
					$sql='select id_membre_m, nom_m, prenom_m, dt_naissance_m, adresse_m, ville_m, cp_m, id_pays_m, nom_pa, ';
					$sql=$sql.'tel_m, mail_m, num_licence_c, dt_exp_licence_c, id_niveau_c, nom_ne, photo_c, login_ic, mdp_ic, id_centre_ce, nom_ce ';
					$sql=$sql.'from membre ';
					$sql=$sql.'inner join pays on id_pays_pa = id_pays_m ';
					$sql=$sql.'inner join info_connexion on id_membre_ic = id_membre_m ';
					$sql=$sql.'left join cavalier on id_membre_c = id_membre_m ';
					$sql=$sql.'left join niveau_equestre on id_niveau_ne = id_niveau_c ';
					$sql=$sql.'left join centre_equestre on id_membre_ce = id_membre_m ';
					$sql=$sql.'where id_membre_m = '.$id_membre;

					try{
			      $rs=pg_exec($idc,$sql);
			    }
			    catch (Exception $e) {
			      echo $e->getMessage(),"\n";
			    };
					$ligne=pg_fetch_assoc($rs);

					$id_membre_m =$ligne['id_membre_m'];
					$nom_m=$ligne['nom_m'];
					$prenom_m=$ligne['prenom_m'];
					$dt_naissance_m=$ligne['dt_naissance_m'];
					$adresse_m=$ligne['adresse_m'];
					$ville_m=$ligne['ville_m'];
					$cp_m=$ligne['cp_m'];
					$id_pays_m=$ligne['id_pays_m'];
					$nom_pa=$ligne['nom_pa'];
					$tel_m=$ligne['tel_m'];
					$mail_m=$ligne['mail_m'];
					$num_licence_c=$ligne['num_licence_c'];
					$dt_exp_licence_c=$ligne['dt_exp_licence_c'];
					$id_niveau_c=$ligne['id_niveau_c'];
					$nom_ne=$ligne['nom_ne'];
					$photo_c=$ligne['photo_c'];
					$login_ic=$ligne['login_ic'];
					$mdp_ic=$ligne['mdp_ic'];
					$id_centre_ce=$ligne['id_centre_ce'];
					$nom_ce=$ligne['nom_ce'];
				}
			?>
			<!-- PROFIL MEMBRE -->
			<div id="profil">
				<?php require_once('../form/profil.php'); ?>
			</div>
		</div>
	</div>
	<!-- FOOTER -->
	<?php require_once('./../include/footer.php'); ?>
	<script type="text/javascript">
	//Déclenché une fois le document chargé : cache certaines div selon si checkbox cochée ou non
	$(document).ready(function(){
		if (document.getElementById("cc_cavalier").checked == false){
			document.getElementById("div_cavalier").style="visibility: hidden;";
		}
		if (document.getElementById("cc_centre").checked == false){
			document.getElementById("div_centre").style="visibility: hidden;";
		}
		if (document.getElementById("cc_mdp").checked == false){
			document.getElementById("div_mdp").style="visibility: hidden;";
		}

		var val = document.getElementById("zs_membre_m").value;
		if (val != '' & val != null){
			document.getElementById("bt_submit_CM").value="Modifier";
			document.getElementById("zs_login_ic").disabled=true;
		}
		else{
			document.getElementById("cc_mdp").disabled=true;
		}
	});

	//Déclenché sur le click des checkbox : affiche ou cache des div
	function visible(op){
		if (op=='c'){
			if (document.getElementById("cc_cavalier").checked == true){
				document.getElementById("div_cavalier").style="visibility: visible;";
			}
			else {
				document.getElementById("div_cavalier").style="visibility: hidden;";
				vider('c');
			}
		}
		else if (op=='r'){
			if (document.getElementById("cc_centre").checked == true){
				document.getElementById("div_centre").style="visibility: visible;";
			}
			else {
				document.getElementById("div_centre").style="visibility: hidden;";
				vider('t');
			}
		}
		else if (op=='m'){
			if (document.getElementById("cc_mdp").checked == true){
				document.getElementById("div_mdp").style="visibility: visible;";
			}
			else {
				document.getElementById("div_mdp").style="visibility: hidden;";
			}
		}
	}

	//Appellé par fonction visible : réinitialise les valeurs des champs
	function vider(op) {
		if (op=='c'){
			document.getElementById("zs_num_licence_c").value="";
			document.getElementById("zs_dt_exp_licence_c").value="";
			document.getElementById("zl_nom_ne").value="";
  		}
		if (op=='t'){
			document.getElementById("zl_nom_ce").value="";
  		}
    }
	</script>
</body>
</html>
