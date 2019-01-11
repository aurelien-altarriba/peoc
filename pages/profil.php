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
	<script type="text/javascript" src="./../js/profil.js"></script>

	<script type="text/javascript">

	</script>

</head>
<body>

	<!-- HEADER -->
	<?php //require_once('./../include/header.php'); ?>

  <?php
    include('./../include/connect.php');
    $idc=connect();
  ?>


	<!-- CONTENU -->
	<div id="contenu">

		<div id="colonneGauche">

		</div>

		<div id="colonneDroite">
      <table>
  			<?php
          // A récupérer de la page qui appelle
					//$id_membre = $_SESSION['zs_XXXXXXXX']);
          $id_membre = 1;
					//$id_membre = '';

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

	          // Récupération des informations du membre
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
  					//while($ligne=pg_fetch_assoc($rs)){
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

					// Génération du code html
					// !!! Sous firefox pb positionnement des listes de valeur sur la valeur par défaut
					print('<tr><td>Photo : </td><td><input type="text" name="zs_photo_c" value="'.$photo_c.'"/></td></tr><br/>'."\n");
					print('<tr><td>Nom : </td><td><input type="text" name="zs_nom_m" value="'.$nom_m.'"/></td></tr><br/>'."\n");
					print('<tr><td>Prénom : </td><td><input type="text" name="zs_prenom_m" value="'.$prenom_m.'"/></td></tr><br/>'."\n");
					print('<tr><td>Date de naissance : </td><td><input type="text" name="zs_dt_naissance_m" value="'.$dt_naissance_m.'"/></td></tr><br/>'."\n");
          print('<tr><td>Adresse : </td><td><input type="text" name="zs_adresse_m" value="'.$adresse_m.'"/></td></tr><br/>'."\n");
					print('<tr><td>Ville : </td><td><input type="text" name="zs_ville_m" value="'.$ville_m.'"/></td></tr><br/>'."\n");
					print('<tr><td>Code postal : </td><td><input type="text" name="zs_cp_m" value="'.$cp_m.'"/></td></tr><br/>'."\n");

					//print('<tr><td>Département : </td><td><input type="text" name="zs_nom_d" value="'.$nom_d.'"/></td></tr><br/>'."\n");
					print('<tr><td>Département : </td>');
					$sql='select id_departement_d, nom_d
								from departement
								order by nom_d;';
					$rs=pg_exec($idc,$sql);
					print('<td><select name="zs_nom_d">');
					while($ligne=pg_fetch_assoc($rs)){
							if ($ligne['id_departement_d']==$id_departement_m){
								print('<option value="'.$ligne['id_departement_d'].'" selected="selected">'.$ligne['nom_d'].'</option>'."\n");
							}
							else{
								print('<option value="'.$ligne['id_departement_d'].'">'.$ligne['nom_d'].'</option>'."\n");
							}
					}
					print('</select></td></tr><br/>'."\n");

					//print('<tr><td>Pays : </td><td><input type="text" name="zs_nom_pa" value="'.$nom_pa.'"/></td></tr><br/>'."\n");
					print('<tr><td>Pays : </td>');
					$sql='select id_pays_pa, nom_pa
								from pays
								order by nom_pa;';
					$rs=pg_exec($idc,$sql);
					print('<td><select name="zs_nom_pa">');
					while($ligne=pg_fetch_assoc($rs)){
							if ($ligne['id_pays_pa']==$id_pays_m){
								print('<option value="'.$ligne['id_pays_pa'].'" selected="selected">'.$ligne['nom_pa'].'</option>'."\n");
							}
							else{
								print('<option value="'.$ligne['id_pays_pa'].'">'.$ligne['nom_pa'].'</option>'."\n");
							}
					}
					print('</select></td></tr><br/>'."\n");

					print('<tr><td>Téléphone : </td><td><input type="text" name="zs_tel_m" value="'.$tel_m.'"/></td></tr><br/>'."\n");
					print('<tr><td>Mail : </td><td><input type="text" name="zs_mail_m" value="'.$mail_m.'"/></td></tr><br/>'."\n");

					print('<tr><td>----------</td><td>----------</td></tr><br/>');
					print('<tr><td><input type="checkbox" name="cc_cavalier" value="1" checked>			Cavalier</td><td></td></tr><br/>');
					print('<tr><td>Numéro de licence : </td><td><input type="text" name="zs_num_licence_c" value="'.$num_licence_c.'"/></td></tr><br/>'."\n");
					print('<tr><td>Date expiration licence : </td><td><input type="text" name="zs_dt_exp_licence_c" value="'.$dt_exp_licence_c.'"/></td></tr><br/>'."\n");
					print('<tr><td>Niveau équestre : </td><td><input type="text" name="zs_nom_ne" value="'.$nom_ne.'"/></td></tr><br/>'."\n");


					print('<tr><td>----------</td><td>----------</td></tr><br/>');
					if ($id_centre_ce!=''){
						print('<tr><td><input type="checkbox" name="cc_responsable" value="1" checked>			Responsable d un centre équestre</td><td></td></tr><br/>');
					}
					else {
						print('<tr><td><input type="checkbox" name="cc_responsable" value="0">			Responsable d un centre équestre</td><td></td></tr><br/>');
					}
					//print('<tr><td>Responsable du centre équestre : </td><td><input type="text" name="zs_nom_ce" value="'.$nom_ce.'"/></td></tr><br/>'."\n");
					print('<tr><td>Responsable du centre équestre : </td>');
					$sql='select id_centre_ce, nom_ce
								from centre_equestre ';
					// Membre pas encore responsable d'un centre équestre :
					// on affiche que les centres sans responsable associé
					if ($id_centre_ce==''){
						$sql=$sql.'where id_membre_ce is null ';
					}
					// Membre responsable d'un centre équestre :
					// on affiche son centre équestre ainsi que les centres sans responsable associé
					else{
					$sql=$sql.'where id_membre_ce is null or id_membre_ce='.$id_membre_m.' ';
					}
					$sql=$sql.'order by nom_ce;';
					$rs=pg_exec($idc,$sql);
					print('<td><select name="zs_nom_ce">');
					while($ligne=pg_fetch_assoc($rs)){
							if ($ligne['id_centre_ce']==$id_centre_ce){
								print('<option value="'.$ligne['id_centre_ce'].'" selected="selected">'.$ligne['nom_ce'].'</option>'."\n");
							}
							else{
								print('<option value="'.$ligne['id_centre_ce'].'">'.$ligne['nom_ce'].'</option>'."\n");
							}
					}

					print('<tr><td>----------</td><td>----------</td></tr><br/>');
					print('<tr><td>Login de connexion : </td><td><input type="text" name="zs_login_ic" value="'.$login_ic.'"/></td></tr><br/>'."\n");
					print('<tr><td><input type="checkbox" name="cc_mdp" value="1" checked>			Modifier mot de passe</td><td></td></tr><br/>');
					print('<tr><td>Mot de passe : </td><td><input type="password" name="zs_mdp_ic" value="'.$mdp_ic.'"/></td></tr><br/>'."\n");
					print('<tr><td>Confirmation du mot de passe : </td><td><input type="password" name="zs_mdp_ic2" value="'.$mdp_ic.'"/></td></tr><br/>'."\n");
  			?>
      </table>
	</div>

	<!-- FOOTER -->
	<?php //require_once('./../include/footer.php'); ?>
</body>
</html>
