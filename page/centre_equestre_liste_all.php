<!DOCTYPE html>
<html>
<head>
	<title>PÉ-OC</title>
	<meta charset="utf-8">

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="/css/header.css">
	<link rel="stylesheet" type="text/css" href="/css/footer.css">
	<link rel="stylesheet" type="text/css" href="/css/centre_equestre_liste.css">

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
					<th>Adresse</th>
					<th>Code postal</th>
					<th>Ville</th>
					<th>Département</th>
					<th>Tél</th>
					<th>Mail</th>
					<th>Nombre de cheval</th>
					<th>Responsable</th>
					<th>site internet</th>
				</tr>
			</thead>

			<tbody>
  			<?php
				$sql = "SELECT id_centre_ce, nom_ce, adresse_ce, cp_ce, ville_ce, nom_d, tel_ce, mail_ce, nb_cheval_ce, nom_m, url_ce
								FROM centre_equestre
								LEFT JOIN membre ON id_membre_ce = id_membre_m
								LEFT JOIN departement ON id_departement_ce = id_departement_d
								";
				// Exécution de la requête
				try {
		      $rs = pg_exec($idc,$sql);
		    }
		    catch (Exception $e) {
		      echo $e->getMessage();
		    }

				// Pour chaque centre_equestre
        while($ligne = pg_fetch_assoc($rs)) {
          print(
						'<tr>'.
							'<td>'. $ligne['id_centre_ce'] .'</td>'.
							'<td><a href="/page/parcours_liste_all.php?ce='. $ligne['nom_ce'] .'">'. $ligne['nom_ce'] .'</a></td>'.
							'<td>'. $ligne['adresse_ce'] .'</td>'.
							'<td>'. $ligne['cp_ce'] .'</td>'.
							'<td>'. $ligne['ville_ce'] .'</td>'.
							'<td>'. $ligne['nom_d'] .'</td>'.
							'<td>'. $ligne['tel_ce'] .'</td>'.
							'<td>'. $ligne['mail_ce'] .'</td>'.
							'<td>'. $ligne['nb_cheval_ce'] .'</td>'.
							'<td>'. $ligne['nom_m'] .'</td>'.
							'<td>'. $ligne['url_ce'] .'</td>');
         }
				?>
			</tbody>
		</table>
	</div>
	<!-- FOOTER -->
	<?php require_once($_SERVER['DOCUMENT_ROOT'] ."/include/footer.php"); ?>

	<script type="text/javascript">
		$(document).ready(function(){
			$('#tab').DataTable();
		});
	</script>
</body>
</html>
