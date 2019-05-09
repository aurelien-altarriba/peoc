<form name="frm"  action="/fonction/verif_point_interet.php" method="POST" enctype="multipart/form-data">
  <div id="div_point">
    <?php
      //Génération du code html
      // !!! Sous firefox pb positionnement des listes de valeur sur la valeur par défaut
      print('Numéro du point d\'intérêt : <input type="text" name="zs_interet_pi" id="zs_interet_pi" value="'.$id_interet_pi.'" disabled="disabled"/><br/>'."\n");
      print('Parcours: <input type="text" name="zs_parcours_pi" value="'.$id_parcours_pi.'" disabled="disabled"/><input type="text" name="zs_nom_p" value="'.$nom_p.'" disabled="disabled"/><br/>'."\n");
      print('Position : <input type="number" name="zs_num_point_pi" id="zs_num_point_pi" value="'.$num_point_pi.'" required="required"/><br/>'."\n");
      print('Catégorie : ');
      $sql='select id_categorie_pic, nom_pic from categorie_pi order by id_categorie_pic;';
      $rs=pg_exec($idc,$sql);
      print('<select name="zl_nom_pic" id="zl_nom_pic" required="required">'."\n");
      while($ligne=pg_fetch_assoc($rs)){
          if ($ligne['id_categorie_pic']==$id_categorie_pi){
            print('<option value="'.$ligne['id_categorie_pic'].'" selected="selected">'.$ligne['nom_pic'].'</option>'."\n");
          }
          else{
            print('<option value="'.$ligne['id_categorie_pic'].'">'.$ligne['nom_pic'].'</option>'."\n");
          }
      }
      print('</select><br/>'."\n");
      print('Url site : <input type="text" name="zs_url_pi" id="zs_url_pi" value="'.$url_pi.'"/><br/>'."\n");
      print('Photo : <input type="file" name="zs_photo_up"/>');
      if (!empty($photo_pi)){
        $photo_pi = $fichier_dossier_dest.$photo_pi;
      }
      print('<img src="'.$photo_pi.'" name="zs_photo_pi" width="60" alt="Point d\'intérêt" /><br/>'."\n");
      print('Description : <input type="text" name="zs_description_pi" id="zs_description_pi" value="'.$description_pi.'"/><br/>'."\n");
    ?>
  </div>
  
  <div>
    <input type="submit" name="bt_submit_CM" id="bt_submit_CM" value="Créer"/>
    <input type="submit" name="bt_submit_S" id="bt_submit_S" value="Supprimer"/>
  </div>
</form>
