<!DOCTYPE html>
<html>
<head>
	<title>PÉ-OC</title>
	<meta charset="utf-8">

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="../css/header.css">
	<link rel="stylesheet" type="text/css" href="../css/footer.css">
	<link rel="stylesheet" type="text/css" href="../css/index.css">

	<link rel="stylesheet" type="text/css" href="../css/lib/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../css/lib/dataTables.bootstrap4.min.css">

	<!-- JS -->
	<script type="text/javascript" src="../js/lib/jquery.min.js"></script>
	<script type="text/javascript" src="../js/lib/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="../js/lib/bootstrap.min.js"></script>
	<script type="text/javascript" src="../js/lib/dataTables.bootstrap4.min.js"></script>
</head>
<body>
	<!-- HEADER -->
	<?php
    require_once('./../include/header.php');
    $idc=connect();
  ?>

	<!-- CONTENU -->
	<div id="contenu">

		<div id="colonneGauche">

		</div>

		<div id="colonneDroite">
  			<?php
				// Initialisation ds variables du formulaire à vide
				$id_parcours_p='';
				$nom_p='';
				$autonomie_p='';
				$visible_p='';
				$dt_publication_p='';
				$id_niveau_p='';
				$nom_ne='';
				$id_departement_p='';
				$nom_d='';
				$id_membre_p='';
				$nom_m='';
				$prenom_m='';
				$id_centre_p='';
				$nom_ce='';
				$description_p='';
				$distance='';
				$denivele='';
				$duree='';
				$type_terrain='';
        $cpt=0;


	      	// Récupération des informations du membre en base
					$sql='select id_parcours_p, nom_p, autonomie_p, visible_p, dt_publication_p, id_niveau_p, nom_ne, id_departement_p, nom_d, ';
					$sql=$sql.'id_membre_p, nom_m, prenom_m, id_centre_p, nom_ce, description_p ';
					$sql=$sql.'from parcours ';
					$sql=$sql.'inner join departement on id_departement_d = id_departement_p ';
					$sql=$sql.'left join cavalier on id_membre_c = id_membre_p ';
					$sql=$sql.'left join membre on id_membre_m = id_membre_c ';
					$sql=$sql.'left join niveau_equestre on id_niveau_ne = id_niveau_p ';
					$sql=$sql.'left join centre_equestre on id_centre_ce = id_centre_p ';
					try{
			      $rs=pg_exec($idc,$sql);
			    }
			    catch (Exception $e) {
			      echo $e->getMessage(),"\n";
			    };

          print('<table id="tab" class="table table-striped table-bordered" style="width:100%">'."\n");
          print('<thead>'."\n".'<tr>'."\n");
          print('<th>#</th>'."\n");
          print('<th>Nom</th>'."\n");
          print('<th>Date publication</th>'."\n");
          print('<th>Département</th>'."\n");
          print('<th>Niveau équestre</th>'."\n");
          print('<th>Distance</th>'."\n");
          print('<th>Dénivelé</th>'."\n");
          print('<th>Durée</th>'."\n");
          print('<th>Type terrain</th>'."\n");
          print('<th>Cavalier</th>'."\n");
          print('<th>Centre équestre</th>'."\n");
          print('<th>Autonomie</th>'."\n");
          print('<th>Visible</th>'."\n");
          print('<th">Description</th>'."\n");
          print('</tr>'."\n".'</thead>'."\n".'<tbody>'."\n");


          while($ligne=pg_fetch_assoc($rs)){
            $cpt+=1;
  					$id_parcours_p =$ligne['id_parcours_p'];
  					$nom_p=$ligne['nom_p'];
  					$autonomie_p=$ligne['autonomie_p'];
  					$visible_p=$ligne['visible_p'];
  					$dt_publication_p=$ligne['dt_publication_p'];
  					$id_niveau_p=$ligne['id_niveau_p'];
  					$nom_ne=$ligne['nom_ne'];
            $id_departement_p=$ligne['id_departement_p'];
            $nom_d=$ligne['nom_d'];
  					$id_membre_p=$ligne['id_membre_p'];
  					$nom_m=$ligne['nom_m'];
  					$prenom_m=$ligne['prenom_m'];
  					$id_centre_p=$ligne['id_centre_p'];
  					$nom_ce=$ligne['nom_ce'];
  					$description_p=$ligne['description_p'];

            print('<tr>'."\n");
            print('<td>'.$id_parcours_p.'</td>'."\n");
            print('<td>'.$nom_p.'</td>'."\n");
            print('<td>'.$dt_publication_p.'</td>'."\n");
            print('<td>'.$nom_d.'</td>'."\n");
            print('<td>'.$nom_ne.'</td>'."\n");
            print('<td>'.$distance.'</td>'."\n");
            print('<td>'.$denivele.'</td>'."\n");
            print('<td>'.$duree.'</td>'."\n");
            print('<td>'.$type_terrain.'</td>'."\n");
            print('<td>'.$nom_m.' '.$prenom_m.'</td>'."\n");
            print('<td>'.$nom_ce.'</td>'."\n");
            print('<td>'.$autonomie_p.'</td>'."\n");
            print('<td>'.$visible_p.'</td>'."\n");
            print('<td>'.$description_p.'</td>'."\n");
            print('</tr>'."\n");
	         }

        print('</tbody>'."\n".'</table>');
			?>
    </div>
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
