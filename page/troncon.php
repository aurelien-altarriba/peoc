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

	<script type="text/javascript">
		// Se déclenche une fois le document chargé : cache certaines div selon si checkbox cochée ou non
		$(document).ready(function(){
			var val = document.getElementById("id_troncon_t").value;
			if (val != '' & val != null){
				document.getElementById("bt_submit1").value="Modifier";
			}
		});
	</script>
</head>
<body>
	<?php
		require_once('./../include/connect.php');
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
				//$id_troncon = $_SESSION['troncon']);
	      		$id_troncon = 1;
				//$id_troncon = '';

				// Initialisation ds variables du formulaire à vide
				$id_troncon_t = '';
				$id_parcours_t = '';
				$nom_p = '';
				$num_position_t = '';
				$id_hierarchie_t = '';
				$nom_h = '';
				$id_type_t = '';
				$nom_tt = '';
				$id_niveau_t = '';
				$nom_nt = '';
				$duree_t = '';


				// Statut non inscrit : création du profil (inscription en bdd donc id membre affecté)
				if ($id_troncon == ''){

				}

				// Statut inscrit : visualisation /modification du profil
				else {

	      			// Récupération des informations du membre
					$sql='select id_troncon_t, id_parcours_t, nom_p, num_position_t, id_hierarchie_t, nom_h, id_type_t, nom_tt, id_niveau_t, nom_nt, duree_estime_t ';
					$sql=$sql.'from troncon ';
					$sql=$sql.'inner join parcours on id_parcours_p = id_parcours_t ';
					$sql=$sql.'inner join hierarchie on id_hierarchie_h = id_hierarchie_t ';
					$sql=$sql.'inner join type_terrain on id_type_tt = id_type_t ';
					$sql=$sql.'inner join niveau_terrain on id_niveau_nt = id_niveau_t ';
					$sql=$sql.'where id_troncon_t = '.$id_troncon;

	  				$rs=pg_exec($idc,$sql);
					$ligne=pg_fetch_assoc($rs);

					$id_troncon_t = $ligne['id_troncon_t'];
					$id_parcours_t = $ligne['id_parcours_t'];
					$nom_p = $ligne['nom_p'];
					$num_position_t = $ligne['num_position_t'];
					$id_hierarchie_t = $ligne['id_hierarchie_t'];
					$nom_h = $ligne['nom_h'];
					$id_type_t = $ligne['id_type_t'];
					$nom_tt = $ligne['nom_tt'];
					$id_niveau_t = $ligne['id_niveau_t'];
					$nom_nt = $ligne['nom_nt'];
					$duree_t = $ligne['duree_estime_t'];
				}
			?>
			<form name="frm"  action="./../fonction/.php" method="post">
				<div id="div_point">
					<?php
						// Génération du code html
						// !!! Sous firefox pb positionnement des listes de valeur sur la valeur par défaut
						print('Numéro du tronçon : <input type="text" name="zs_troncon_t" id="id_troncon_t" value="'.$id_troncon_t.'" disabled="disabled"/><br/>'."\n");
						print('Parcours: <input type="text" name="zs_parcours_t" value="'.$id_parcours_t.'" disabled="disabled"/><input type="text" name="zs_nom_p" value="'.$nom_p.'" disabled="disabled"/><br/>'."\n");
						print('Position : <input type="text" name="zs_num_position_t" value="'.$num_position_t.'"/><br/>'."\n");
						print('Hiérarchie : ');
						$sql='select id_hierarchie_h, nom_h from hierarchie order by id_hierarchie_h;';
						$rs=pg_exec($idc,$sql);
						print('<select name="zs_nom_h">'."\n");
						print('<option value=""></option>'."\n");
						while($ligne=pg_fetch_assoc($rs)){
								if ($ligne['id_hierarchie_h']==$id_hierarchie_t){
									print('<option value="'.$ligne['id_hierarchie_h'].'" selected="selected">'.$ligne['nom_h'].'</option>'."\n");
								}
								else{
									print('<option value="'.$ligne['id_hierarchie_h'].'">'.$ligne['nom_h'].'</option>'."\n");
								}
						}
						print('</select><br/>'."\n");
						print('Type terrain : ');
						$sql='select id_type_tt, nom_tt from type_terrain order by nom_tt;';
						$rs=pg_exec($idc,$sql);
						print('<select name="zs_nom_tt">'."\n");
						print('<option value=""></option>'."\n");
						while($ligne=pg_fetch_assoc($rs)){
								if ($ligne['id_type_tt']==$id_type_t){
									print('<option value="'.$ligne['id_type_tt'].'" selected="selected">'.$ligne['nom_tt'].'</option>'."\n");
								}
								else{
									print('<option value="'.$ligne['id_type_tt'].'">'.$ligne['nom_tt'].'</option>'."\n");
								}
						}
						print('</select><br/>'."\n");
						print('Niveau du terrain : ');
						$sql='select id_niveau_nt, nom_nt from niveau_terrain order by id_niveau_nt;';
						$rs=pg_exec($idc,$sql);
						print('<select name="zs_nom_nt">'."\n");
						print('<option value=""></option>'."\n");
						while($ligne=pg_fetch_assoc($rs)){
								if ($ligne['id_niveau_nt']==$id_niveau_t){
									print('<option value="'.$ligne['id_niveau_nt'].'" selected="selected">'.$ligne['nom_nt'].'</option>'."\n");
								}
								else{
									print('<option value="'.$ligne['id_niveau_nt'].'">'.$ligne['nom_nt'].'</option>'."\n");
								}
						}
						print('</select><br/>'."\n");
						print('Durée estimée : <input type="text" name="zs_duree_t" value="'.$duree_t.'"/><br/>'."\n");
					?>
				</div>
				<div>
					<input type="submit" value="Créer" id="bt_submit1"/>
					<input type="submit" value="Supprimer" id="bt_submit2"/>
				</div>
			</form>
		</div>
	<!-- FOOTER -->
	<?php //require_once('./../include/footer.php'); ?>
</body>
</html>
