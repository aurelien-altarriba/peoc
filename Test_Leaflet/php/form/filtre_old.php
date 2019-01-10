<form>
	
	<!-- Nom -->
	<div class="form-group">
		<label for="nomParcours">Nom de la structure </label>
		<input type="text" class="form-control" id="nomParcours" placeholder="Rechercher avec un nom">
	</div>

	<!-- Difficulté -->
	<div class="form-group">
		<label>Taille de la structure</label>

		<div class="form-check">
			<input class="form-check-input" type="checkbox" id="level1" value="1">
			<label class="form-check-label" for="level1">XXXXX</label>
		</div>

		<div class="form-check">
			<input class="form-check-input" type="checkbox" id="level2" value="2">
			<label class="form-check-label" for="level2">YYYYY</label>
		</div>

		<div class="form-check">
			<input class="form-check-input" type="checkbox" id="level3" value="3">
			<label class="form-check-label" for="level3">ZZZZZ</label>
		</div>
	</div>

	<!-- Commune -->
	<div class="form-group">
		<label for="activite">Activités</label>
		<select class="form-control" id="departement">
			<option>Toutes</option>
			<option>XXXXX</option>
			<option>YYYYY</option>
			<option>ZZZZZ</option>
			<option>WWWWW</option>
		</select>
	</div>

	<!-- Bouton "Rechercher" -->
	<button type="submit" class="btn btn-primary" id="btRechercher"
		style="margin-top: 1em; ">
		Rechercher
	</button>
</form>