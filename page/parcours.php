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
					<form method="post" action="../fonction/verif_parcours.php">

						<!-- Choix du parcours à modifier-->
						<div>
							<label>Parcours à modifier</label>
							<select name="zl_id_p" id="zl_id_p" required>
							<?php

							$sql='SELECT nom_p, id_parcours_p
										FROM parcours';

							$rs=pg_exec($idc,$sql);

								while($ligne=pg_fetch_assoc($rs)){
									print('<option value="'.$ligne['id_parcours_p'].'">'.$ligne['nom_p'].'</option>');
								}
							?>
							</select>
						</div>

						<!-- Zone de saisie du nom du parcours -->
						<div>
							<label for="zs_nom_p">Nom du parcours </label> <input type="text" name="zs_nom_p" id="zs_nom_p" maxlength="50" required>
						</div>

						<!-- Zone de saisie de la description du parcours -->
						<div>
							<label for="zs_description_p">Description </label> <textarea name="zs_description_p" id="zs_description_p"></textarea>
						</div>

						<!-- Choix du niveau de difficulté du parcours -->
						<div>
							<label>Niveau de difficulté</label>
							<select name="zl_id_niveau_ne" id="zl_id_niveau_ne" required>
							<?php

							$sql='SELECT nom_ne, id_niveau_ne
										FROM niveau_equestre;';

							$rs=pg_exec($idc,$sql);

								while($ligne=pg_fetch_assoc($rs)){
									print('<option value="'.$ligne['id_niveau_ne'].'">'.$ligne['nom_ne'].'</option>');

								}
							?>
							</select>
						</div>

						<!-- Choix du département du parcours -->
						<div>
							<label>Département</label>
							<select name="zl_id_departement_p" id="zl_id_departement_p" required>
							<?php

							$sql='SELECT nom_d, id_departement_d
										FROM departement;';

							$rs=pg_exec($idc,$sql);

								while($ligne=pg_fetch_assoc($rs)){
									print('<option value="'.$ligne['id_departement_d'].'">'.$ligne['nom_d'].'</option>');
								}
							?>
							</select>
						</div>

						<!-- Le parcours est-il faisable en autonomie ou pas -->
						<div>
							<div>
								<p>Parcours autonome</p>
								<input type="radio" name="autonomie_p" id="autonomie_p" value="TRUE">  <label for="autonomie_p">Oui </label>
							</div>
							<div>
								<input type="radio" name="autonomie_p" id="autonomie_p" value="FALSE">  <label for="autonomie_p">Non </label>
							</div>
						</div>

						<!-- Choix de rendre le parcours visible au public -->
						<div>
							<div>
								<p>Rendre le parcours public</p>
								<input type="radio" name="visible_p" id="visible_p" value="TRUE"><label for="visible_p">Oui</label>
							</div>
							<div>
								<input type="radio" name="visible_p" id="visible_p" value="FALSE"><label for="visible_p">Non</label>
							</div>
					  </div>

						<!-- Bouton de validation de la création du parcours -->
						<input type="submit" name="bt_submit_creation" id="bt_submit_creation" value="Valider la création du parcours" /> </br>

						<!-- Bouton de validation des modifications du parcours -->
						<input type="submit" name="bt_submit_modification" id="bt_submit_modification" value="Valider les modifications du parcours" /> </br>

						<!-- Bouton de suppression du parcours -->
						<input type="submit" name="bt_submit_suppression" id="bt_submit_suppression" value="Supprimer le parcours" /> </br>

					</form>

		</div>

		<!-- FOOTER -->
		<?php require_once('./../include/footer.php'); ?>

	</body>
</html>
