
<form name="frm"  action="/fonction/verif_centre_equestre.php" method="POST" enctype="multipart/form-data">

  <div id="div_centre">

      <div class="form-group">
        <label for="zs_centre_ce">Numéro du centre équestre </label>
        <input type="text" class="form-control" name="zs_centre_ce" id="zs_centre_ce" value="<?php echo($id_centre_ce)?>" readonly>
      </div>

      <div class="form-group">
        <label for="zs_nom_ce">Nom </label>
        <input type="text" class="form-control" name="zs_nom_ce" id="zs_nom_ce" value="<?php echo($nom_ce)?>" readonly>
      </div>

      <div class="form-group">
        <label for="zs_nom_ce">Adresse </label>
        <input type="text" class="form-control" name="zs_adresse_ce" id="zs_adresse_ce" value="<?php echo($adresse_ce)?>" readonly>
      </div>

      <div class="form-group">
        <label for="zs_nom_ce">Code postal </label>
        <input type="text" class="form-control" name="zs_cp_ce" id="zs_cp_ce" value="<?php echo($cp_ce)?>" readonly>
      </div>

      <div class="form-group">
        <label for="zs_nom_ce">Adresse </label>
        <input type="text" class="form-control" name="zs_adresse_ce" id="zs_adresse_ce" value="<?php echo($adresse_ce)?>" readonly>
      </div>

      <div class="form-group">
        <label for="zs_nom_ce">Ville </label>
        <input type="text" class="form-control" name="zs_ville_ce" id="zs_ville_ce" value="<?php echo($ville_ce)?>" readonly>
      </div>

      <div class="form-group">
        <label for="zs_nom_ce">Département </label>
        <input type="text" class="form-control" name="zs_id_departement_ce" id="zs_id_departement_ce" value="<?php echo($id_departement_ce)?>" readonly>
      </div>

      <div class="form-group">
        <label for="zs_nom_ce">Téléphone </label>
        <input type="text" class="form-control" name="zs_tel_ce" id="zs_tel_ce" value="<?php echo($tel_ce)?>">
      </div>

      <div class="form-group">
        <label for="zs_nom_ce">Mail </label>
        <input type="text" class="form-control" name="zs_mail_ce" id="zs_mail_ce" value="<?php echo($mail_ce)?>">
      </div>

      <div class="form-group">
        <label for="zs_nom_ce">Nombre de chevaux </label>
        <input type="text" class="form-control" name="zs_nb_cheval_ce" id="zs_nb_cheval_ce" value="<?php echo($nb_cheval_ce)?>">
      </div>

      <div class="form-group">
        <label for="zs_nom_ce">Url site </label>
        <input type="text" class="form-control" name="zs_url_ce" id="zs_url_ce" value="<?php echo($url_ce)?>">
      </div>

      <?php
        if (!empty($logo_ce)){
          $logo_ce = $fichier_dossier_dest.$logo_ce;
        }
      ?>

  		<div class="form-group" id="logo_centre">
  	    <label for="zs_logo_up">Logo du centre</label>
  	    <input type="file" class="form-control-file" name="zs_logo_up" id="zs_logo_up">
  	  </div>

  		<img src="<?php echo($logo_ce); ?>" name="zs_logo_ce" id="zs_logo_ce" />


      <div id="div_modification_bt">
    		<button type="submit" class="btn btn-success btn-lg" name="bt_submit_CM" id="bt_submit_CM"
    			value="Modifier">
    			Modifier
    		</button>
    	</div>

  </div>

</form>

<script type="text/javascript">
	$(document).ready(function(){
    // Fonction de lecture d'une image importée par input
    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
          $('#zs_logo_ce').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
      }
    }

    $("#zs_logo_up").change(function() {
      readURL(this);
    });

    $("#zs_logo_up").onload(function() {
		  readURL(this);
		});
  });
</script>
