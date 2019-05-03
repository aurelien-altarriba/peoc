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
        <input type="text" class="form-control" name="zs_cp_ce" id="zs_cp_ce" value="<?php echo(cp_ce)?>" readonly>
      </div>

      <div class="form-group">
        <label for="zs_nom_ce">Adresse </label>
        <input type="text" class="form-control" name="zs_adresse_ce" id="zs_adresse_ce" value="<?php echo($adresse_ce)?>" readonly>
      </div>

<?php

      print('Ville : <input type="text" name="zs_ville_ce" value="'.$ville_ce.'" disabled="disabled"/><br/>'."\n");
      print('Département : <input type="text" name="zs_id_departement_ce" value="'.$id_departement_ce.'" disabled="disabled"/><br/>'."\n");
      print('Téléphone : <input type="text" name="zs_tel_ce" id="zs_tel_ce" value="'.$tel_ce.'" required="required"/><br/>'."\n");
      print('Mail : <input type="email" name="zs_mail_ce" id="zs_mail_ce" value="'.$mail_ce.'" /><br/>'."\n");
      print('Nombre de chevaux : <input type="number" name="zs_nb_cheval_ce" id="zs_nb_cheval_ce" value="'.$nb_cheval_ce.'" /><br/>'."\n");
      print('Url site : <input type="text" name="zs_url_ce" id="zs_url_ce" value="'.$url_ce.'"/><br/>'."\n");
      print('Logo : <input type="file" name="zs_logo_up"/>');


      if (!empty($logo_ce)){
        $logo_ce = $fichier_dossier_dest.$logo_ce;
      }
      print('<img src="'.$logo_ce.'" name="zs_logo_ce" width="60" alt="Logo" /><br/>'."\n");
    ?>
  </div>
  <div>
    <input type="submit" name="bt_submit_CM" id="bt_submit_CM" value="Modifier"/>
  </div>
</form>
