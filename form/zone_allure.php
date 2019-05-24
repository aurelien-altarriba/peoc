<?php
  $idc = connect();
?>

<form name="frm"  action="/fonction/verif_zone_allure.php" enctype="multipart/form-data" id="form_za">
  <div id="div_za">
      <!-- Choix de la catégorie du point d'intérêt -->
      <div class="form-group">
        <label for="zl_nom_ta">Type</label>
        <select class="form-control" name="zl_nom_ta" id="zl_nom_ta">
          <?php
          $sql = 'SELECT nom_ta, id_type_ta
									FROM type_allure';

          $rs = pg_exec($idc,$sql);

          while ($ligne = pg_fetch_assoc($rs)){
            if ($ligne['id_type_ta'] == $id_categorie_pi){
              print('<option value="'.$ligne['id_type_ta'].'" selected="selected">'.$ligne['nom_ta'].'</option>');
            }
            else{
              print('<option value="'.$ligne['id_type_ta'].'">'.$ligne['nom_ta'].'</option>');
            }
          }
          ?>
        </select>
      </div>
  </div>
</form>
