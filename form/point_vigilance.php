<?php
  $idc = connect();
?>

<form onsubmit="return(false);">
  <div class="form-group">
    <label for="zs_dt_debut_pv">Date de début</label>
    <input type="date" name="zs_dt_debut_pv" id="zs_dt_debut_pv"/>
  </div>

  <div class="form-group">
    <label for="zs_dt_fin_pv">Date de fin</label>
    <input type="date" name="zs_dt_fin_pv" id="zs_dt_fin_pv"/>
  </div>

  <!-- Choix de la catégorie du point de vigilance -->
  <div class="form-group">
    <label for="zs_categorie_pv">Catégorie</label>
    <select class="form-control" name="zs_categorie_pv" id="zs_categorie_pv">
      <?php
      $sql='SELECT id_categorie_pvc, nom_pvc
            FROM categorie_pv';

      $rs=pg_exec($idc,$sql);

        while($ligne=pg_fetch_assoc($rs)){
          print('<option value="'.$ligne['id_categorie_pvc'].'">'.$ligne['nom_pvc'].'</option>');
        }
      ?>
    </select>
  </div>

  <div class="form-group">
    <label for="exampleFormControlFile1">Photo</label>
    <input type="file" class="form-control-file" id="zs_photo_pv" name="zs_photo_pv">
  </div>

  <div class="form-group">
    <label for="zs_categorie_pv">Description</label>
    <textarea class="form-control" name="zs_categorie_pv" id="zs_commentaire_e" rows="5"></textarea>
  </div>

  <button class="btn btn-primary btn-block" type="submit" name="bt_submit_comm" id="bt_submit_comm"/>Valider le point de vigilance</button><br/>
</form>
