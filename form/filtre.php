<form name="frm">
	<!-- Nom -->
	<div class="form-group">
		<label for="nom_parcours">Nom du parcours</label>
		<input type="text" class="form-control" id="nom_parcours" placeholder="Rechercher avec un nom">
	</div>

	<!-- Difficulté / Niveau équestre du parcours -->
	<div class="form-group">
		<label>Niveau équestre du parcours :</label>
		<div class="form_row" id="niveau">
			<?php
				$sql='select id_niveau_ne, nom_ne from niveau_equestre order by id_niveau_ne;';
				$rs=pg_exec($idc,$sql);
				while($ligne=pg_fetch_assoc($rs)){
					print('<div class="form-check">'."\n");
					print('<input class="form-check-input" type="checkbox" id="level'.$ligne['id_niveau_ne'].'" value="'.$ligne['id_niveau_ne'].'" checked>'."\n");
					print('<label class="form-check-label" for="level'.$ligne['id_niveau_ne'].'">'.$ligne['nom_ne'].'</label>'."\n");
					print('</div>'."\n");
				}
			?>
		</div>
	</div>

	<!-- Centre équestre -->
	<div class="form-group">
		<label for="departement">Centre équestre</label>
		<select class="form-control" id="centre">
			<?php
				$sql='select id_centre_ce, nom_ce from centre_equestre order by nom_ce;';
				$rs=pg_exec($idc,$sql);
				print('<option value="0">Tous les centres</option>'."\n");
				while($ligne=pg_fetch_assoc($rs)){
					print('<option value="'.$ligne['id_centre_ce'].'">'.$ligne['nom_ce'].'</option>'."\n");
				}
			?>
		</select>
	</div>

	<!-- Département -->
	<div class="form-group">
		<label for="departement">Département</label>
		<select class="form-control" id="departement">
			<?php
				$sql = 'select id_departement_d, nom_d from departement order by nom_d;';
				$rs = pg_exec($idc,$sql);
				print('<option value="0">Tous les départements</option>'."\n");
				while($ligne=pg_fetch_assoc($rs)){
					print('<option value="'.$ligne['id_departement_d'].'">'.$ligne['nom_d'].'</option>'."\n");
				}
			?>
		</select>
	</div>

	<!-- Bouton "Rechercher" -->
	<button type="button" class="btn btn-warning btn-block" id="btRechercher" onclick="getDataParcoursFiltre();" style="margin-top: 1em;">
		Rechercher
	</button>
</form>
