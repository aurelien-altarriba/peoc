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
			var val = document.getElementById("id_interet_pi").value;
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
				//$id_point = $_SESSION['point_interet']);
	      		$id_point = 1;
				//$id_point = '';

				// Initialisation ds variables du formulaire à vide
				$id_interet_pi = '';
				$id_parcours_pi = '';
				$nom_p = '';
				$num_point_pi = '';
				$id_categorie_pi = '';
				$nom_pic = '';
				$url_pi = '';
				$photo_pi = '';
				$description_pi = '';


				// Statut non inscrit : création du profil (inscription en bdd donc id membre affecté)
				if ($id_point == ''){

				}

				// Statut inscrit : visualisation /modification du profil
				else {

	      			// Récupération des informations du membre
					$sql='select id_interet_pi, id_parcours_pi, nom_p, num_point_pi, id_categorie_pi, nom_pic, url_pi, photo_pi, description_pi ';
					$sql=$sql.'from point_interet ';
					$sql=$sql.'inner join parcours on id_parcours_p = id_parcours_pi ';
					$sql=$sql.'inner join categorie_pi on id_categorie_pic = id_categorie_pi ';
					$sql=$sql.'where id_interet_pi = '.$id_point;

	  				$rs=pg_exec($idc,$sql);
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
			<form name="frm"  action="./../fonction/.php" method="post">
				<div id="div_point">
					<?php
						// Génération du code html
						// !!! Sous firefox pb positionnement des listes de valeur sur la valeur par défaut
						print('Numéro du point d\'intérêt : <input type="text" name="zs_interet_pi" id="id_interet_pi" value="'.$id_interet_pi.'" disabled="disabled"/><br/>'."\n");
						print('Parcours: <input type="text" name="zs_parcours_pi" value="'.$id_parcours_pi.'" disabled="disabled"/><input type="text" name="zs_nom_p" value="'.$nom_p.'" disabled="disabled"/><br/>'."\n");
						print('Position : <input type="text" name="zs_num_point_pi" value="'.$num_point_pi.'"/><br/>'."\n");
						print('Catégorie : ');
						$sql='select id_categorie_pic, nom_pic from categorie_pi order by nom_pic;';
						$rs=pg_exec($idc,$sql);
						print('<select name="zs_nom_pic">'."\n");
						print('<option value=""></option>'."\n");
						while($ligne=pg_fetch_assoc($rs)){
								if ($ligne['id_categorie_pic']==$id_categorie_pi){
									print('<option value="'.$ligne['id_categorie_pic'].'" selected="selected">'.$ligne['nom_pic'].'</option>'."\n");
								}
								else{
									print('<option value="'.$ligne['id_categorie_pic'].'">'.$ligne['nom_pic'].'</option>'."\n");
								}
						}
						print('</select><br/>'."\n");
						print('Url site : <input type="text" name="zs_url_pi" value="'.$url_pi.'"/><br/>'."\n");
	          			print('photo_pi : <input type="text" name="zs_photo_pi" value="'.$photo_pi.'"/><br/>'."\n");
						print('Description : <input type="text" name="zs_description_pi" value="'.$description_pi.'"/><br/>'."\n");
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
