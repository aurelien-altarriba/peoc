<!DOCTYPE html>
<html>
<head>
	<title>PÉ-OC</title>
	<meta charset="utf-8">

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="../css/header.css">
	<link rel="stylesheet" type="text/css" href="../css/footer.css">
	<link rel="stylesheet" type="text/css" href="../css/parcours_liste.css">

	<link rel="stylesheet" type="text/css" href="../css/lib/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../DataTables/datatables.min.css"/>

	<!-- JS -->
	<script type="text/javascript" src="../js/lib/jquery.min.js"></script>
	<script type="text/javascript" src="../js/lib/bootstrap.min.js"></script>
	<script type="text/javascript" src="../DataTables/datatables.min.js"></script>
</head>
<body>
	<!-- HEADER -->
	<?php
    require_once('./../include/header.php');
    $idc=connect();
  ?>

	<!-- CONTENU -->
	<div id="contenu">
		<table id="tab" class="display table table-striped table-bordered">
			<thead>
				<tr>
					<th>#</th>
					<th>Nom</th>
					<th>Date publication</th>
					<th>Département</th>
					<th>Niveau équestre</th>
					<th>Distance</th>
					<th>Dénivelé</th>
					<th>Durée</th>
					<th>Créateur</th>
					<th>Autonomie</th>
					<th>Description</th>
				</tr>
			</thead>

			<tbody>
  			<?php
      	// Requête SQL
				$sql = "SELECT id_parcours_p, nom_p, autonomie_p, visible_p, dt_publication_p, id_niveau_p, nom_ne, id_departement_p,
									nom_d, id_membre_p, nom_m, prenom_m, id_centre_p, nom_ce, description_p
								FROM parcours
								INNER JOIN departement ON id_departement_d = id_departement_p
								LEFT JOIN cavalier ON id_membre_c = id_membre_p
								LEFT JOIN membre ON id_membre_m = id_membre_c
								LEFT JOIN niveau_equestre ON id_niveau_ne = id_niveau_p
								LEFT JOIN centre_equestre ON id_centre_ce = id_centre_p";

				// Exécution de la requête
				try {
		      $rs = pg_exec($idc,$sql);
		    }
		    catch (Exception $e) {
		      echo $e->getMessage();
		    }

				// Pour chaque parcours
        while($ligne = pg_fetch_assoc($rs)) {
          print(
						'<tr>'.
							'<td>'. $ligne['id_parcours_p'] .'</td>'.
	            '<td>'. $ligne['nom_p'] .'</td>'.
	            '<td>'. $ligne['dt_publication_p'] .'</td>'.
	            '<td>'. $ligne['nom_d'] .'</td>'.
	            '<td>'. $ligne['nom_ne'] .'</td>'.
	            '<td> </td>'.
	            '<td> </td>'.
	            '<td> </td>'.
	            '<td>'. $ligne['nom_m'] .' '. $ligne['prenom_m'] .'</td>'.
	            '<td>'. $ligne['autonomie_p'] .'</td>'.
	            '<td>'. $ligne['description_p'] .'</td>'.
						'</tr>'
					);
         }
				?>
			</tbody>
		</table>
	</div>
	<!-- FOOTER -->
	<?php require_once('./../include/footer.php'); ?>

	<script type="text/javascript">
		$(document).ready(function(){
			$('#tab').DataTable();
		});
	</script>
</body>
</html>
