
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

      <div class="form-group">
        <label for="zs_nom_ce">Logo </label>
        <?php print('<img src="'.$logo_ce.'" name="zs_logo_ce" width="60" /><br/>'."\n"); ?>
        <input type="file" class="form-control" name="zs_logo_up" id="zs_logo_up">
      </div> <br/>

  </div>

</form>
