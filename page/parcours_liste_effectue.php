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

		$id_membre = '';
		if (isset($_SESSION['membre']['id'])){
			$id_membre = $_SESSION['membre']['id'];
		}

  ?>
	<!-- CONTENU -->
	<div id="contenu">
		<table id="tab" class="display table table-striped table-bordered">
			<thead>
				<tr>
					<th>#</th>
					<th>Nom</th>
					<th>Effectué le</th>
					<th>Durée (min)</th>
					<th>Note</th>
					<th>Commentaire</th>
				</tr>
			</thead>

			<tbody>
  			<?php
      	// Requête SQL
				if (!empty($id_membre)){
					$sql = "SELECT id_parcours_p, nom_p, dt_jour_e, duree_reel_e, note_e, commentaire_e
									FROM effectue
									INNER JOIN parcours ON id_parcours_p = id_parcours_e
									WHERE id_membre_p = ".intval($id_membre);

					// Exécution de la requête
					try {
			      $rs = pg_exec($idc,$sql);
			    }
			    catch (Exception $e) {
			      echo $e->getMessage();
			    }
				}

				// Pour chaque ligne
        while($ligne = pg_fetch_assoc($rs)) {
          print(
						'<tr>'.
							'<td>'. $ligne['id_parcours_p'] .'</td>');
	        print(
							'<td><a href="parcours.php?id='. $ligne['id_parcours_p'] .'">'. $ligne['nom_p'] .'</a></td>'.
		          '<td>'. $ligne['dt_jour_e'] .'</td>'.
		          '<td>'. $ligne['duree_reel_e'] .'</td>'.
		          '<td>'. $ligne['note_e'] .'</td>'.
							'<td>'. $ligne['commentaire_e'] .'</td>'.
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
			$('#tab').DataTable();
		});
	</script>
</body>
</html>
