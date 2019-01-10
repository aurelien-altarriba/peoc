<form>

	<!-- Nom -->
	<div class="form-group">
		<label for="nomParcours">Nom du parcours</label>
		<input type="text" class="form-control" id="nomParcours" placeholder="Rechercher avec un nom">
	</div>

	<!-- Difficulté -->
	<div class="form-group">
		<label>Difficulté du parcours :</label>

		<div class="form_row">
			<div class="form-check">
				<input class="form-check-input" type="checkbox" id="level1" value="1">
				<label class="form-check-label" for="level1">Facile</label>
			</div>

			<div class="form-check">
				<input class="form-check-input" type="checkbox" id="level2" value="2">
				<label class="form-check-label" for="level2">Moyen</label>
			</div>

			<div class="form-check">
				<input class="form-check-input" type="checkbox" id="level3" value="3">
				<label class="form-check-label" for="level3">Difficile</label>
			</div>
		</div>
	</div>

	<!-- Département -->
	<div class="form-group">
		<label for="departement">Département</label>
		<select class="form-control" id="departement">
			<option>Tous les départements</option>
			<option>Ariège</option>
			<option>Aude</option>
			<option>Aveyron</option>
			<option>Gard</option>
			<option>Haute-Garonne</option>
			<option>Hautes-Pyrénées</option>
			<option>Hérault</option>
			<option>Gers</option>
			<option>Lot</option>
			<option>Lozère</option>
			<option>Pyrénées-Orientales</option>
			<option>Tarn</option>
			<option>Tarn-et-Garonne</option>
		</select>
	</div>

	<!-- Bouton "Rechercher" -->
	<button type="submit" class="btn btn-warning btn-block" id="btRechercher"
		style="margin-top: 1em;">
		Rechercher
	</button>
</form>
