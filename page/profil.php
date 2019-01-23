<?php
	session_start();
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

	<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../css/leaflet.css">

	<!-- JS -->
	<script type="text/javascript" src="../js/jquery.min.js"></script>
	<script type="text/javascript" src="../js/bootstrap.min.js"></script>
</head>
<body>
	<?php
		require_once('../include/connect.php');
	 	$idc=connect();
	?>

	<!-- HEADER -->
	<?php //require_once('./../include/header.php'); ?>

	<!-- CONTENU -->
	<div id="contenu">

		<div id="colonneGauche">

		</div>

		<div id="colonneDroite">
  			<?php
	       // A récupérer de la page qui appelle
				//$id_membre = $_SESSION['zs_XXXXXXXX']);
	      //$id_membre = $_SESSION['membre']['id_membre_m'];
				$id_membre = '';

				// Initialisation ds variables du formulaire à vide
				$id_membre_m='';
				$nom_m='';
				$prenom_m='';
				$dt_naissance_m='';
				$adresse_m='';
				$ville_m='';
				$cp_m='';
				$id_departement_m='';
				$nom_d='';
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

				// Statut non inscrit : création du profil (inscription en bdd donc id membre affecté)
				if ($id_membre == ''){

				}
				// Statut inscrit : visualisation /modification du profil
				else {

	      	// Récupération des informations du membre en base
					$sql='select id_membre_m, nom_m, prenom_m, dt_naissance_m, adresse_m, ville_m, cp_m, id_departement_m, nom_d, ';
					$sql=$sql.'id_pays_m, nom_pa, tel_m, mail_m, num_licence_c, dt_exp_licence_c, id_niveau_c, nom_ne, photo_c, login_ic, mdp_ic, id_centre_ce, nom_ce ';
					$sql=$sql.'from membre ';
					$sql=$sql.'inner join departement on id_departement_d = id_departement_m ';
					$sql=$sql.'inner join pays on id_pays_pa = id_pays_m ';
					$sql=$sql.'inner join info_connexion on id_membre_ic = id_membre_m ';
					$sql=$sql.'left join cavalier on id_membre_c = id_membre_m ';
					$sql=$sql.'left join niveau_equestre on id_niveau_ne = id_niveau_c ';
					$sql=$sql.'left join centre_equestre on id_membre_ce = id_membre_m ';
					$sql=$sql.'where id_membre_m = '.$id_membre;

	  			$rs=pg_exec($idc,$sql);
					$ligne=pg_fetch_assoc($rs);

					$id_membre_m =$ligne['id_membre_m'];
					$nom_m=$ligne['nom_m'];
					$prenom_m=$ligne['prenom_m'];
					$dt_naissance_m=$ligne['dt_naissance_m'];
					$adresse_m=$ligne['adresse_m'];
					$ville_m=$ligne['ville_m'];
					$cp_m=$ligne['cp_m'];
					$id_departement_m=$ligne['id_departement_m'];
					$nom_d=$ligne['nom_d'];
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
			<form name="frm"  action="../fonction/verif_inscription.php" method="post">
				<div id="div_statut">
					<?php
						if ($num_licence_c!=''){
							print('<input type="checkbox" name="cc_cavalier" id="cc_cavalier" checked="checked" onclick="visible(\'c\')">Cavalier<br/>'."\n");
						}
						else {
							print('<input type="checkbox" name="cc_cavalier" id="cc_cavalier" onclick="visible(\'c\')">Cavalier<br/>'."\n");
						}
						if ($id_centre_ce!=''){
							print('<input type="checkbox" name="cc_centre" id="cc_centre" checked="checked" onclick="visible(\'r\')">Responsable d\'un centre équestre<br/>'."\n");
						}
						else {
							print('<input type="checkbox" name="cc_centre" id="cc_centre" onclick="visible(\'r\')"> Responsable d\'un centre équestre<br/>'."\n");
						}
					?>
				</div>
				<div id="div_membre">
					<?php
						// Génération du code html
						// !!! Sous firefox pb positionnement des listes de valeur sur la valeur par défaut
						print('Numéro de membre : <input type="text" name="zs_membre_m" id="zs_membre_m" value="'.$id_membre_m.'" required="required" disabled="disabled"/><br/>'."\n");
						print('Nom : <input type="text" name="zs_nom_m" value="'.$nom_m.'" required="required" /><br/>'."\n");
						print('Prénom : <input type="text" name="zs_prenom_m" value="'.$prenom_m.'" required="required" /><br/>'."\n");
						print('Date de naissance : <input type="date" name="zs_dt_naissance_m" value="'.$dt_naissance_m.'"" required="required"/><br/>'."\n");
	          print('Adresse : <input type="text" name="zs_adresse_m" value="'.$adresse_m.'"/><br/>'."\n");
						print('Ville : <input type="text" name="zs_ville_m" value="'.$ville_m.'"/><br/>'."\n");
						print('Code postal : <input type="text" name="zs_cp_m" value="'.$cp_m.'"/><br/>'."\n");
						print('Département : ');
						$sql='select id_departement_d, nom_d from departement order by nom_d;';
						$rs=pg_exec($idc,$sql);
						print('<select name="zl_nom_d">'."\n");
						print('<option value=""></option>'."\n");
						while($ligne=pg_fetch_assoc($rs)){
								if ($ligne['id_departement_d']==$id_departement_m){
									print('<option value="'.$ligne['id_departement_d'].'" selected="selected">'.$ligne['nom_d'].'</option>'."\n");
								}
								else{
									print('<option value="'.$ligne['id_departement_d'].'">'.$ligne['nom_d'].'</option>'."\n");
								}
						}
						print('</select><br/>'."\n");
						print('Pays : ');
						$sql='select id_pays_pa, nom_pa from pays order by nom_pa;';
						$rs=pg_exec($idc,$sql);
						print('<select name="zl_nom_pa" required="required">'."\n");
						print('<option value=""></option>'."\n");
						while($ligne=pg_fetch_assoc($rs)){
								if ($ligne['id_pays_pa']==$id_pays_m){
									print('<option value="'.$ligne['id_pays_pa'].'" selected="selected">'.$ligne['nom_pa'].'</option>'."\n");
								}
								else{
									print('<option value="'.$ligne['id_pays_pa'].'">'.$ligne['nom_pa'].'</option>'."\n");
								}
						}
						print('</select><br/>'."\n");
						print('Téléphone : <input type="text" name="zs_tel_m" value="'.$tel_m.'"/><br/>'."\n");
						print('Mail : <input type="email" name="zs_mail_m" value="'.$mail_m.'" required="required"/><br/>'."\n");
					?>
				</div>
				<div id="div_cavalier">
					<?php
						print('Photo : <input type="file" name="zs_photo_up"/>');
						print('<img src="'.$photo_c.'" name="zs_photo_c" width="60" alt="Photo d\'identité" /><br/>'."\n");
						print('Numéro de licence : <input type="text" name="zs_num_licence_c" id="zs_num_licence_c" value="'.$num_licence_c.'"/><br/>'."\n");
						print('Date expiration licence : <input type="date" name="zs_dt_exp_licence_c" id="zs_dt_exp_licence_c" value="'.$dt_exp_licence_c.'"/><br/>'."\n");
						print('Niveau équestre : ');
						$sql='select id_niveau_ne, nom_ne from niveau_equestre order by id_niveau_ne;';
						$rs=pg_exec($idc,$sql);
						print('<select name="zl_nom_ne" id="zl_nom_ne">'."\n");
						print('<option value=""></option>'."\n");
						while($ligne=pg_fetch_assoc($rs)){
								if ($ligne['id_niveau_ne']==$id_niveau_c){
									print('<option value="'.$ligne['id_niveau_ne'].'" selected="selected">'.$ligne['nom_ne'].'</option>'."\n");
								}
								else{
									print('<option value="'.$ligne['id_niveau_ne'].'">'.$ligne['nom_ne'].'</option>'."\n");
								}
						}
						print('</select><br/>'."\n");
					?>
				</div>
				<div id="div_centre">
					<?php
						print('Centre équestre : ');
						$sql='select id_centre_ce, nom_ce from centre_equestre ';
						// Membre pas encore responsable d'un centre équestre :
						// on affiche que les centres sans responsable associé
						if ($id_centre_ce==''){
							$sql=$sql.'where id_membre_ce is null ';
						}
						// Membre déjà responsable d'un centre équestre :
						// on affiche son centre équestre ainsi que les centres sans responsable associé
						else{
						$sql=$sql.'where id_membre_ce is null or id_membre_ce='.$id_membre_m.' ';
						}
						$sql=$sql.'order by nom_ce;';
						$rs=pg_exec($idc,$sql);
						print('<select name="zl_nom_ce" id="zl_nom_ce">'."\n");
						print('<option value=""></option>'."\n");
						while($ligne=pg_fetch_assoc($rs)){
							if ($ligne['id_centre_ce']==$id_centre_ce){
								print('<option value="'.$ligne['id_centre_ce'].'" selected="selected">'.$ligne['nom_ce'].'</option>'."\n");
							}
							else{
								print('<option value="'.$ligne['id_centre_ce'].'">'.$ligne['nom_ce'].'</option>'."\n");
							}
						}
						print('</select><br/>'."\n");
					?>
				</div>
				<div id="div_connexion">
					<?php
						print('Login de connexion : <input type="text" name="zs_login_ic" id="zs_login_ic" value="'.$login_ic.'" required="required"/><br/>'."\n");
					?>
					<?php
						if ($login_ic!=''){
							print('<input type="checkbox" name="cc_mdp" id="cc_mdp" onclick="visible(\'m\')">Modifier mot de passe<br/>'."\n");
						}
						else {
							print('<input type="checkbox" name="cc_mdp" id="cc_mdp" checked="checked" onclick="visible(\'m\')">Modifier mot de passe<br/>'."\n");
						}
					?>
					<div id="div_mdp">
						<?php
							print('Mot de passe : <input type="password" name="zs_mdp_ic" value="'.$mdp_ic.'" required="required"/><br/>'."\n");
							print('Confirmation du mot de passe : <input type="password" name="zs_mdp_ic2" value="'.$mdp_ic.'" required="required"/><br/>'."\n");
	  					?>
  					</div>
				</div>
				<div>
					<input type="submit" name="bt_submit_CM" id="bt_submit_CM" value="S'inscrire"/>
					<input type="submit" name="bt_submit_S" id="bt_submit_S" value="Supprimer"/>
				</div>
			</form>
		</div>
	<!-- FOOTER -->
	<?php //require_once('./../include/footer.php'); ?>
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
			document.getElementById("zs_photo_c").value="";
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
