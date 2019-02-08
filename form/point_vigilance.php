<form name="frm"  action="../fonction/verif_point_vigilance.php" method="POST" enctype="multipart/form-data">
  <div id="div_point">
    <?php
      //Génération du code html
      // !!! Sous firefox pb positionnement des listes de valeur sur la valeur par défaut
      print('Numéro du point de vigilance : <input type="text" name="zs_vigilance_pv" id="zs_vigilance_pv" value="'.$id_vigilance_pv.'" disabled="disabled"/><br/>'."\n");
      print('Parcours: <input type="text" name="zs_parcours_pv" value="'.$id_parcours_pv.'" disabled="disabled"/><input type="text" name="zs_nom_p" value="'.$nom_p.'" disabled="disabled"/><br/>'."\n");
      print('Membre : <input type="text" name="zs_membre_pv" id="zs_membre_pv" value="'.$id_membre_pv.'" disabled="disabled"/><br/>'."\n");
      print('Position : <input type="number" name="zs_num_point_pv" id="zs_num_point_pv" value="'.$num_point_pv.'" required="required"/><br/>'."\n");
      print('Catégorie : ');
      $sql='select id_categorie_pvc, nom_pvc from categorie_pv order by id_categorie_pvc;';
      $rs=pg_exec($idc,$sql);
      print('<select name="zl_nom_pvc" id="zl_nom_pvc" required="required">'."\n");
      while($ligne=pg_fetch_assoc($rs)){
          if ($ligne['id_categorie_pvc']==$id_categorie_pv){
            print('<option value="'.$ligne['id_categorie_pvc'].'" selected="selected">'.$ligne['nom_pvc'].'</option>'."\n");
          }
          else{
            print('<option value="'.$ligne['id_categorie_pvc'].'">'.$ligne['nom_pvc'].'</option>'."\n");
          }
      }
      print('</select><br/>'."\n");
      print('Date de création : <input type="date" name="zs_dt_creation_pv" value="'.$dt_creation_pv.'"" required="required"/><br/>'."\n");
      print('Date de début : <input type="date" name="zs_dt_debut_pv" value="'.$dt_debut_pv.'"" required="required"/><br/>'."\n");
      print('Date de fin : <input type="date" name="zs_dt_fin_pv" value="'.$dt_fin_pv.'""/><br/>'."\n");
      print('Photo : <input type="file" name="zs_photo_up"/>');
      if (!empty($photo_pv)){
        $photo_pv = $fichier_dossier_dest.$photo_pv;
      }
      print('<img src="'.$photo_pv.'" name="zs_photo_pv" width="60" alt="Point de vigilance" /><br/>'."\n");
      print('Description : <input type="text" name="zs_description_pv" id="zs_description_pv" value="'.$description_pv.'"/><br/>'."\n");
    ?>
  </div>
  <div>
    <input type="submit" name="bt_submit_CM" id="bt_submit_CM" value="Créer"/>
    <input type="submit" name="bt_submit_S" id="bt_submit_S" value="Supprimer"/>
  </div>
</form>
