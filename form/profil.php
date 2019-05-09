
<form name="frm" id="form_inscription" action="/fonction/verif_profil_inscription.php" method="post" enctype="multipart/form-data">
	<div id="div_statut">

		<!-- CAVALIER -->
		<div class="form-check">
			<input class="form-check-input" type="checkbox" name="cc_cavalier" id="cc_cavalier"
				<?php if ($num_licence_c != '') { echo('checked disabled'); } ?> >
			<label class="form-check-label" for="cc_cavalier">
				Je suis un <b>cavalier</b>
			</label>
		</div>

		<!-- CENTRE ÉQUESTRE -->
		<div class="form-check">
			<input class="form-check-input" type="checkbox" name="cc_centre" id="cc_centre"
				<?php if ($id_centre_ce != '') { echo('checked disabled'); } ?>
			onclick="$('#div_centre').toggle();">
			<label class="form-check-label" for="cc_centre">
				Je suis <b>responsable d'un centre équestre</b>
			</label>
		</div>
	</div>

	<small class="form-text form-group text-muted">Les champs suivis d'un <b>*</b> sont obligatoires</small>

	<!-- INFOS MEMBRE -->
	<div id="div_membre">
		<div class="form-group" id="zone_nbmembre">
			<label for="zs_membre_m">Numéro de membre</label>
			<input type="text" class="form-control form-control-sm" name="zs_membre_m" id="zs_membre_m"
				value="<?php echo($id_membre_m);?>" required readonly>
		</div>

		<div class="form-group">
			<label for="zs_nom_m">Nom *</label>
			<input type="text" class="form-control" name="zs_nom_m" id="zs_nom_m"
				value="<?php echo($nom_m);?>" required>
		</div>

		<div class="form-group">
			<label for="zs_prenom_m">Prénom *</label>
			<input type="text" class="form-control" name="zs_prenom_m" id="zs_prenom_m"
				value="<?php echo($prenom_m);?>" required>
		</div>

		<div class="form-group">
			<label for="zs_dt_naissance_m">Date de naissance *</label>
			<input type="date" class="form-control" name="zs_dt_naissance_m" id="zs_dt_naissance_m"
				value="<?php echo($dt_naissance_m);?>" required>
		</div>

		<div class="form-group">
			<label for="zs_adresse_m">Adresse</label>
			<input type="text" class="form-control" name="zs_adresse_m" id="zs_adresse_m"
				value="<?php echo($adresse_m);?>">
		</div>

		<div class="form-group">
			<label for="zs_ville_m">Ville</label>
			<input type="text" class="form-control" name="zs_ville_m" id="zs_ville_m"
				value="<?php echo($ville_m);?>">
		</div>

		<div class="form-group">
			<label for="zs_cp_m">Code postal</label>
			<input type="text" class="form-control" name="zs_cp_m" id="zs_cp_m"
				value="<?php echo($cp_m);?>">
		</div>

		<div class="form-group">
			<label for="zl_nom_pa">Pays *</label>
			<select class="form-control" name="zl_nom_pa" id="zl_nom_pa" required>
				<option value="null" selected></option>
			</select>
		</div>

		<div class="form-group">
			<label for="zs_mail_m">Mail *</label>
			<input type="email" class="form-control" name="zs_mail_m" id="zs_mail_m"
				value="<?php echo($mail_m);?>" required>
		</div>

		<div class="form-group">
			<label for="zs_tel_m">Téléphone</label>
			<input type="text" class="form-control" name="zs_tel_m" id="zs_tel_m"
				value="<?php echo($tel_m);?>">
		</div>

		<div class="form-group">
			<label for="zs_login_ic">Login de connexion *</label>
			<input type="text" class="form-control" name="zs_login_ic" id="zs_login_ic"
				value="<?php echo($login_ic);?>" placeholder="Choisissez votre login de connexion" required>
		</div>

		<div class="form-group">
	    <label for="zs_mdp_ic">Mot de passe *</label>
	    <input type="password" class="form-control" name="zs_mdp_ic" id="zs_mdp_ic" required>
	  </div>

		<div class="form-group">
	    <label for="zs_mdp_ic2">Confirmer le mot de passe *</label>
	    <input type="password" class="form-control" name="zs_mdp_ic2" id="zs_mdp_ic2" required>
	  </div>
	</div>

	<!-- INFOS CAVALIER -->
	<div id="div_cavalier">
		<h4>Cavalier</h4>

		<?php if (!empty($photo_c)) {
			$photo_c = $fichier_dossier_dest . $photo_c;
		} ?>

		<div class="form-group" id="photo_cavalier">
	    <label for="zs_photo_up">Photo de profil</label>
	    <input type="file" class="form-control-file" name="zs_photo_up" id="zs_photo_up">
	  </div>

		<img src="<?php echo($photo_c); ?>" name="zs_photo_c" id="zs_photo_c" />

		<div class="form-group">
			<label for="zs_num_licence_c">Numéro de licence *</label>
			<input type="number" class="form-control" name="zs_num_licence_c" id="zs_num_licence_c"
				value="<?php echo($num_licence_c);?>">
		</div>

		<div class="form-group">
			<label for="zs_dt_exp_licence_c">Date d'expiration de la licence *</label>
			<input type="date" class="form-control" name="zs_dt_exp_licence_c" id="zs_dt_exp_licence_c"
				value="<?php echo($dt_exp_licence_c);?>">
		</div>

		<div class="form-group">
			<label for="zl_nom_ne">Niveau équestre *</label>
			<select class="form-control" name="zl_nom_ne" id="zl_nom_ne">
				<option value="null" selected></option>
			</select>
		</div>
	</div>

	<!-- INFOS RESPONSABLE CENTRE ÉQUESTRE -->
	<div id="div_centre">
		<h4>Responsable d'un centre équestre</h4>

		<div class="form-group">
			<label for="zl_nom_ce">Centre équestre</label>
			<select class="form-control" name="zl_nom_ce" id="zl_nom_ce">
				<option value="null" selected></option>
			</select>
		</div>
	</div>

	<div id="div_inscription_bt">
		<button type="submit" class="btn btn-success btn-lg" name="bt_submit_CM" id="bt_submit_CM"
			value="1">
			Valider mon inscription
		</button>
	</div>
</form>

<script type="text/javascript">
	$(document).ready(function(){

		$('#div_cavalier').hide();
		$('#div_centre').hide();

		// Si on clique sur cavalier
		$('#cc_cavalier').on('click', function() {
			if (this.checked) {
				$('#div_cavalier').show();

				$('#zs_num_licence_c').attr('required', 'required');
				$('#zs_dt_exp_licence_c').attr('required', 'required');
				$('#zl_nom_ne').attr('required', 'required');
			}

			else {
				$('#div_cavalier').hide();

				$('#zs_num_licence_c').removeAttr('required');
				$('#zs_dt_exp_licence_c').removeAttr('required');
				$('#zl_nom_ne').removeAttr('required');
			}
		});

		// Si on clique sur centre équestre
		$('#cc_centre').on('click', function() {
			if (this.checked) {
				$('#div_centre').show();

				$('#zl_nom_ce').attr('required', 'required');
			}

			else {
				$('#div_centre').hide();

				$('#zl_nom_ce').removeAttr('required');
			}
		});

		<?php if ($id_membre_m == '') { ?>
			$('#zone_nbmembre').hide();
		<?php } ?>

		// On hydrate la liste des pays
		$.post('/fonction/recup_pays.php',
			function(data) {
				var pays = JSON.parse(data);

				$.each(pays, function(index, value) {
					$('#zl_nom_pa').append(`<option value="${value.id_pays_pa}">${value.nom_pa}</option>`);
				});
			}
		)

		// On hydrate la liste des niveaux équestres
		$.post('/fonction/recup_niveau_equestre.php',
			function(data) {
				var niveaux = JSON.parse(data);

				$.each(niveaux, function(index, value) {
					$('#zl_nom_ne').append(`<option value="${value.id_niveau_ne}">${value.nom_ne}</option>`);
				});
			}
		)

		// On hydrate la liste des centres équestres
		$.post('/fonction/recup_centre_equestre.php',
			{
				id_centre: '<?php echo($id_centre_ce); ?>',
				id_membre: '<?php echo($id_membre_m); ?>',
			},
			function(data) {
				var centres = JSON.parse(data);

				$.each(centres, function(index, value) {
					$('#zl_nom_ce').append(`<option value="${value.id_centre_ce}">${value.nom_ce}</option>`);
				});
			}
		)

		// Fonction de lecture d'une image importée par input
		function readURL(input) {
		  if (input.files && input.files[0]) {
		    var reader = new FileReader();

		    reader.onload = function(e) {
		      $('#zs_photo_c').attr('src', e.target.result);
		    }

		    reader.readAsDataURL(input.files[0]);
		  }
		}

		$("#zs_photo_up").change(function() {
		  readURL(this);
		});
	});
</script>
