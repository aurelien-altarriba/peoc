<?php
	//session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>PÉ-OC</title>
	<meta charset="utf-8">

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="/css/header.css">
	<link rel="stylesheet" type="text/css" href="/css/footer.css">
	<link rel="stylesheet" type="text/css" href="/css/index.css">

	<link rel="stylesheet" type="text/css" href="/css/lib/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/css/lib/leaflet.css">

	<!-- JS -->
	<script type="text/javascript" src="/js/lib/jquery.min.js"></script>
	<script type="text/javascript" src="/js/lib/bootstrap.min.js"></script>

	<script type="text/javascript">
		// Se déclenche une fois le document chargé : cache certaines div selon si checkbox cochée ou non
		$(document).ready(function(){
			var val = document.getElementById("id_zone_za").value;
			if (val != '' & val != null){
				document.getElementById("bt_submit1").value="Modifier";
			}
		});
	</script>
</head>
<body>
	<?php
		require_once($_SERVER['DOCUMENT_ROOT'] ."/include/connect.php");
	 	$idc=connect();
	?>

	<!-- HEADER -->
	<?php //require_once($_SERVER['DOCUMENT_ROOT'] ."/include/header.php"); ?>

	<!-- CONTENU -->
	<div id="contenu">

		<div id="colonneGauche">

		</div>

		<div id="colonneDroite">
  			<?php
	          	// A récupérer de la page qui appelle
				//$id_zone = $_SESSION['zone_allure']);
	      		$id_zone = 1;
				//$id_zone = '';

				// Initialisation ds variables du formulaire à vide
				$id_zone_za = '';
				$id_parcours_za = '';
				$nom_p = '';
				$id_type_za = '';

				// Statut non inscrit : création du profil (inscription en bdd donc id membre affecté)
				if ($id_zone == ''){

				}

				// Statut inscrit : visualisation /modification du profil
				else {

	      			// Récupération des informations du membre
					$sql='select id_zone_za, id_parcours_za, nom_p, id_type_za, nom_ta ';
					$sql=$sql.'from zone_allure ';
					$sql=$sql.'inner join parcours on id_parcours_p = id_parcours_za ';
					$sql=$sql.'inner join type_allure on id_type_ta = id_type_za ';
					$sql=$sql.'where id_zone_za = '.$id_zone;

	  				$rs=pg_exec($idc,$sql);
					$ligne=pg_fetch_assoc($rs);

					$id_zone_za = $ligne['id_zone_za'];
					$id_parcours_za = $ligne['id_parcours_za'];
					$nom_p = $ligne['nom_p'];
					$id_type_za = $ligne['id_zone_za'];
					$nom_ta = $ligne['nom_ta'];

				}
			?>
			<form name="frm"  action="/fonction/.php" method="post">
				<div id="div_zone">
					<?php
						// Génération du code html
						// !!! Sous firefox pb positionnement des listes de valeur sur la valeur par défaut
						print('Zone d\'allure : <input type="text" name="zs_zone_za" id="id_zone_za" value="'.$id_zone_za.'" disabled="disabled"/><br/>'."\n");
						print('Parcours: <input type="text" name="zs_parcours_za" value="'.$id_parcours_za.'" disabled="disabled"/><input type="text" name="zs_nom_p" value="'.$nom_p.'" disabled="disabled"/><br/>'."\n");
						print('Type d\'allure : ');
						$sql='select id_type_ta, nom_ta from type_allure order by id_type_ta;';
						$rs=pg_exec($idc,$sql);
						print('<select name="zs_nom_ta">'."\n");
						print('<option value=""></option>'."\n");
						while($ligne=pg_fetch_assoc($rs)){
								if ($ligne['id_type_ta']==$id_type_za){
									print('<option value="'.$ligne['id_type_ta'].'" selected="selected">'.$ligne['nom_ta'].'</option>'."\n");
								}
								else{
									print('<option value="'.$ligne['id_type_ta'].'">'.$ligne['nom_ta'].'</option>'."\n");
								}
						}
						print('</select><br/>'."\n");
					?>
				</div>
				<div>
					<input type="submit" value="Créer" id="bt_submit1"/>
					<input type="submit" value="Supprimer" id="bt_submit2"/>
				</div>
			</form>
		</div>
	<!-- FOOTER -->
	<?php //require_once($_SERVER['DOCUMENT_ROOT'] ."/include/footer.php"); ?>
</body>
</html>
