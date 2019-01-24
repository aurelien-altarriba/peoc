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
	</head>

	<body>

		<?php

			// HEADER
			require_once('../include/header.php');

			$idc = connect();

		?>

		<!-- CONTENU -->
		<div id="contenu">

		<div id="colonneGauche">
		</div>

		<div id="colonneDroite">

					<!-- Déclaration du formulaire parcours -->
					<form method="post" action="???.php">
						<!-- Zone de saisie du nom du parcours -->
						<div>
							<label for="zs_nom_p">Nom </label> <input type="text" name="zs_nom_p" id="zs_nom_p">
						</div>

						<!-- Zone de saisie de la description du parcours -->
						<div>
							<label for="description_p">Description </label> <textarea name="description_p" id="description_p"></textarea>
						</div>

						<!-- Choix du niveau de difficulté du parcours -->
						<div>
							<label>Niveau de difficulté</label>
							<select name="zl_nom_ne" id="zl_nom_ne" required>
							<?php

							$sql='SELECT nom_ne
										FROM niveau_equestre';

							$rs=pg_exec($idc,$sql);

								while($ligne=pg_fetch_assoc($rs)){
									print('<option value="'.$ligne['id_niveau_ne'].'">'.$ligne['nom_ne'].'</option>');
								}
							?>
							</select>
						</div>

						<!-- Choix du centre équestre proposant le parcours -->
						<div>
							<label>Centre équestre</label>
							<select name="zl_nom_ce" id="zl_nom_ce" >
							<?php

							$sql='SELECT nom_ce
										FROM centre_equestre;';

							$rs=pg_exec($idc,$sql);

								while($ligne=pg_fetch_assoc($rs)){
									print('<option value="'.$ligne['id_centre_ce'].'">'.$ligne['nom_ce'].'</option>');
								}
							?>
							</select>
						</div>

						<!-- Le parcours est-il faisable en autonomie ou pas -->
						<div>
							<div>
								<p>Parcours autonome</p>
								<input type="radio" name="autonomie_p" id="autonomie_p" >  <label for="autonomie_p">Oui </label>
							</div>
							<div>
								<input type="radio" name="autonomie_p" id="autonomie_p">  <label for="autonomie_p">Non </label>
							</div>
						</div>

						<!-- Choix des services proposés par le centre équestre du parcours -->
						<div>

							<?php

							$sql='SELECT nom_s
										FROM service;';

							$rs=pg_exec($idc,$sql);

								while($ligne=pg_fetch_assoc($rs)){
									print(

									'<div>
										<input type="checkbox" name=".'$ligne['nom_ce']'." id=".'$ligne['nom_ce']'." > <label for="nom_s">.'$ligne['nom_ce']'.</label>
									</div>'

								);

								}



							?>

						</div>

						<!-- Choix de rendre le parcours visible au public -->
						<div>
							<div>
								<p>Rendre le parcours public</p>
								<input type="radio" name="visible_p" id="visible_p" ><label for="visible_p">Oui </label>
							</div>
							<div>
								<input type="radio" name="visible_p" id="visible_p"><label for="visible_p">Non </label>
							</div>
					  </div>

						<!-- Bouton d'envoi du formulaire -->
						<input type="submit" value="Valider le parcours" />

					</form>

		</div>

		<!-- FOOTER -->
		<?php require_once('./../include/footer.php'); ?>

	</body>
</html>
