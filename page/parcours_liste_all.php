<!DOCTYPE html>
<html>
<head>
	<title>PÉ-OC</title>
	<meta charset="utf-8">

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="/css/header.css">
	<link rel="stylesheet" type="text/css" href="/css/footer.css">
	<link rel="stylesheet" type="text/css" href="/css/parcours_liste.css">

	<link rel="stylesheet" type="text/css" href="/css/lib/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/js/lib/DataTables/datatables.min.css"/>

	<!-- JS -->
	<script type="text/javascript" src="/js/lib/jquery.min.js"></script>
	<script type="text/javascript" src="/js/lib/bootstrap.min.js"></script>
	<script type="text/javascript" src="/js/lib/DataTables/datatables.min.js"></script>
</head>
<body>
	<!-- HEADER -->
	<?php
    require_once($_SERVER['DOCUMENT_ROOT'] ."/include/header.php");
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
					<th>Autonomie</th>
					<th>Distance (m)</th>
					<th>Durée</th>
					<th>Créateur</th>
					<th>Profil</th>
					<th>Description</th>
				</tr>
			</thead>

			<tbody>
  			<?php
      	// Requête SQL
				$sql = "SELECT id_parcours_p, nom_p, autonomie_p, visible_p, dt_publication_p, id_niveau_p, nom_ne, id_departement_p,
									nom_d, id_membre_p, nom_m, prenom_m, id_centre_p, nom_ce, description_p, duree_t, distance_t
								FROM parcours AS p
								INNER JOIN (SELECT id_parcours_t, ROUND(SUM(duree_estime_t)::numeric,2) AS duree_t, ROUND(SUM(st_length(geom_t::geography))::numeric,2) AS distance_t
														FROM troncon WHERE id_hierarchie_t = 1 GROUP BY id_parcours_t) AS t ON t.id_parcours_t =  p.id_parcours_p
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
	            '<td><a href="parcours.php?id='. $ligne['id_parcours_p'] .'">'. $ligne['nom_p'] .'</a></td>'.
	            '<td>'. $ligne['dt_publication_p'] .'</td>'.
	            '<td>'. $ligne['nom_d'] .'</td>'.
	            '<td>'. $ligne['nom_ne'] .'</td>'
					);

					if ($ligne['autonomie_p'] == TRUE){
						print('<td><input type="checkbox" name="cc_auto" value="1" checked disabled></td>');
					}
					else {
						print('<td><input type="checkbox" name="cc_auto" value="1" disabled></td>');
					}

          print('<td>'. $ligne['distance_t'] .'</td>'.
            		'<td>'. $ligne['duree_t'] .'</td>');

					if (!empty($ligne['id_membre_p'])){
						print('<td>'. $ligne['nom_m'] .' '. $ligne['prenom_m'] .'</td>');
						print('<td>Cavalier</td>');
					}
					else {
						print('<td>'. $ligne['nom_ce'] .'</td>');
						print('<td>Centre</td>');
					}
					
	        print('<td>'. $ligne['description_p'] .'</td>'.
					'</tr>');
         }
				?>
			</tbody>
		</table>
	</div>
	<!-- FOOTER -->
	<?php require_once($_SERVER['DOCUMENT_ROOT'] ."/include/footer.php"); ?>

	<script type="text/javascript">
		$(document).ready(function(){
			var dataTable = $('#tab').DataTable();

			// Récupération des paramètres
			var url = new URLSearchParams(location.search);

			// Si on modifie le parcours
			if (url.get('ce')) {
				dataTable.search(url.get('ce')).draw();
			}
		});
	</script>
</body>
</html>
