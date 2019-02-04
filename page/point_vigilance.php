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
		//require_once('./../include/header.php');
		//$idc=connect();
		require_once('../include/connect.php');
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
				$fichier_dossier_dest = '../image/photo_pv/';

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
			<form name="frm"  action="../fonction/verif_point_vigilance.php" method="POST" enctype="multipart/form-data">
				<div id="div_point">
					<?php
						//Génération du code html
						// !!! Sous firefox pb positionnement des listes de valeur sur la valeur par défaut
						print('Numéro du point de vigilance : <input type="text" name="zs_vigilance_pv" id="zs_vigilance_pv" value="'.$id_vigilance_pv.'" disabled="disabled"/><br/>'."\n");
						print('Parcours: <input type="text" name="zs_parcours_pv" value="'.$id_parcours_pv.'" disabled="disabled"/><input type="text" name="zs_nom_p" value="'.$nom_p.'" disabled="disabled"/><br/>'."\n");
						print('Membre : <input type="text" name="zs_membre_pv" id="zs_membre_pv" value="'.$id_membre_pv.'" disabled="disabled"/><br/>'."\n");
						print('Position : <input type="number" name="zs_num_point_pv" id="zs_num_point_pv" value="'.$num_point_pv.'" required="required"/><br/>'."\n");
						print('Catégorie : ');
						$sql='select id_categorie_pvc, nom_pvc from categorie_pv order by id_categorie_pvc;';
						$rs=pg_exec($idc,$sql);
						print('<select name="zl_nom_pvc" id="zl_nom_pvc" required="required">'."\n");
						while($ligne=pg_fetch_assoc($rs)){
								if ($ligne['id_categorie_pvc']==$id_categorie_pv){
									print('<option value="'.$ligne['id_categorie_pvc'].'" selected="selected">'.$ligne['nom_pvc'].'</option>'."\n");
								}
								else{
									print('<option value="'.$ligne['id_categorie_pvc'].'">'.$ligne['nom_pvc'].'</option>'."\n");
								}
						}
						print('</select><br/>'."\n");
						print('Date de création : <input type="date" name="zs_dt_creation_pv" value="'.$dt_creation_pv.'"" required="required"/><br/>'."\n");
						print('Date de début : <input type="date" name="zs_dt_debut_pv" value="'.$dt_debut_pv.'"" required="required"/><br/>'."\n");
						print('Date de fin : <input type="date" name="zs_dt_fin_pv" value="'.$dt_fin_pv.'""/><br/>'."\n");
						print('Photo : <input type="file" name="zs_photo_up"/>');
						if (!empty($photo_pv)){
							$photo_pv = $fichier_dossier_dest.$photo_pv;
						}
						print('<img src="'.$photo_pv.'" name="zs_photo_pv" width="60" alt="Point de vigilance" /><br/>'."\n");
	          print('Description : <input type="text" name="zs_description_pv" id="zs_description_pv" value="'.$description_pv.'"/><br/>'."\n");
					?>
				</div>
				<div>
					<input type="submit" name="bt_submit_CM" id="bt_submit_CM" value="Créer"/>
					<input type="submit" name="bt_submit_S" id="bt_submit_S" value="Supprimer"/>
				</div>
			</form>
		</div>
	<!-- FOOTER -->
	<?php require_once('../include/footer.php'); ?>
</body>
</html>
