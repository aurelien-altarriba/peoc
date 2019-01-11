<?php
	session_start();
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
	<?php require_once('./../include/header.php'); ?>

  <?php
    //include('./../include/connect.php');
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

          // Récupération des informations du membre
  				$sql='select m.id_membre_m, m.nom_m, m.prenom_m, m.date_naiss_m, m.adresse_m, m.ville_m, m.cp_m, m.id_departement_d, d.nom_d, m.id_pays_pa, pa.nom_pa, m.tel_m, m.mail_m, c.num_licence_c, c.date_exp_licence_c, c.id_niveau_ne, ne.nom_ne, c.photo_c
								from membre m
								inner join departement d on d.id_departement_d = m.id_departement_d
								inner join pays pa on pa.id_pays_pa = m.id_pays_pa
								left join cavalier c on c.id_membre_m = m.id_membre_m
								left join niveau_equestre ne on ne.id_niveau_ne = c.id_niveau_ne
								where m.id_membre_m = '.$id_membre;

          $rs=pg_exec($idc,$sql);

  				while($ligne=pg_fetch_assoc($rs)){
            print('<tr><td>Nom : </td><td><input type="text" name="zs_nom" value="'.$ligne['nom_m'].'"/></td></tr><br/>');
/*
						print('<tr><td>Prénom : </td><input type="text" name="zs_nom" value="'.$ligne['m.prenom_m'].'/></td></tr><br/>');
						print('<tr><td>Date de naissance : </td><input type="text" name="zs_nom" value="'.$ligne['m.date_naiss_m'].'/></td></tr><br/>');
            print('<tr><td>Adresse : </td><input type="text" name="zs_nom" value="'.$ligne['m.adresse_m'].'/></td></tr><br/>');
						print('<tr><td>Ville : </td><input type="text" name="zs_nom" value="'.$ligne['m.ville_m'].'/></td></tr><br/>');
						print('<tr><td>Code postal : </td><input type="text" name="zs_nom" value="'.$ligne['m.cp_m'].'/></td></tr><br/>');
						print('<tr><td>Département : </td><input type="text" name="zs_nom" value="'.$ligne['d.nom_d'].'/></td></tr><br/>');
						print('<tr><td>Pays : </td><input type="text" name="zs_nom" value="'.$ligne['pa.nom_pa'].'/></td></tr><br/>');
						print('<tr><td>Téléphone : </td><input type="text" name="zs_nom" value="'.$ligne['m.tel_m'].'/></td></tr><br/>');
						print('<tr><td>Mail : </td><input type="text" name="zs_nom" value="'.$ligne['m.mail_m'].'/></td></tr><br/>');
						print('<tr><td>Numéro de num_licence_c : </td><input type="text" name="zs_nom" value="'.$ligne['c.num_licence_c'].'/></td></tr><br/>');
						print('<tr><td>Date expiration licence : </td><input type="text" name="zs_nom" value="'.$ligne['c.date_exp_licence_c'].'/></td></tr><br/>');
						print('<tr><td>Niveau équestre : </td><input type="text" name="zs_nom" value="'.$ligne['ne.nom_ne'].'/></td></tr><br/>');
						print('<tr><td>Photo : </td><input type="text" name="zs_nom" value="'.$ligne['c.photo_c'].'/></td></tr><br/>');
*/
  				}
  			?>
      </table>
	</div>

	<!-- FOOTER -->
	<?php require_once('./../include/footer.php'); ?>
</body>
</html>
