<form name="frm"  action="../fonction/verif_centre_equestre.php" method="POST" enctype="multipart/form-data">
  <div id="div_centre">
    <?php
      //Génération du code html
      // !!! Sous firefox pb positionnement des listes de valeur sur la valeur par défaut
      print('Numéro du centre équestre : <input type="text" name="zs_centre_ce" id="zs_centre_ce" value="'.$id_centre_ce.'" disabled="disabled"/><br/>'."\n");
      print('Nom : <input type="text" name="zs_nom_ce" value="'.$nom_ce.'" disabled="disabled"/><br/>'."\n");
      print('Adresse : <input type="text" name="zs_adresse_ce" value="'.$adresse_ce.'" disabled="disabled"/><br/>'."\n");
      print('Code postal : <input type="text" name="zs_cp_ce" value="'.$cp_ce.'" disabled="disabled"/><br/>'."\n");
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
