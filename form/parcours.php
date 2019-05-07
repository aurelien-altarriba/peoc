<?php
  $idc = connect();
?>

<form onsubmit="return(false);">

    <!-- Choix du parcours à modifier-->
    <div>
      <?php if(isset($id)) { ?>

      <label>Parcours à modifier</label>
      <select name="zl_id_p" id="zl_id_p" required>
      <?php

      $sql='SELECT nom_p, id_parcours_p
            FROM parcours';

      $rs=pg_exec($idc,$sql);

        while($ligne=pg_fetch_assoc($rs)){
          print('<option value="'.$ligne['id_parcours_p'].'">'.$ligne['nom_p'].'</option>');
        }
      ?>
      </select>
    <?php } ?>
    </div>

    <!-- Zone de saisie du nom du parcours -->
    <div class="form-group">
      <label for="zs_nom_p">Nom du parcours</label>
      <input type="text" class="form-control" name="zs_nom_p" id="zs_nom_p">
    </div>

    <!-- Zone de saisie de la description du parcours -->
    <div class="form-group">
      <label for="zs_description_p">Description</label>
      <textarea class="form-control" name="zs_description_p" id="zs_description_p"></textarea>
    </div>

    <!-- Choix du niveau de difficulté du parcours -->
    <div class="form-group">
      <label for="zl_id_niveau_ne">Niveau de difficulté</label>
      <select class="form-control" name="zl_id_niveau_ne" id="zl_id_niveau_ne" required>
        <?php
        $sql = 'SELECT nom_ne, id_niveau_ne
                FROM niveau_equestre;';

        $rs = pg_exec($idc,$sql);

        while($ligne = pg_fetch_assoc($rs)){
          print('<option value="'.$ligne['id_niveau_ne'].'">'.$ligne['nom_ne'].'</option>');
        } ?>
      </select>
    </div>

    <!-- Choix du département du parcours -->
    <div class="form-group">
      <label for="zl_id_departement_p">Département</label>
      <select class="form-control" name="zl_id_departement_p" id="zl_id_departement_p" required>
        <?php
        $sql = 'SELECT nom_d, id_departement_d
                FROM departement;';

        $rs = pg_exec($idc,$sql);

        while($ligne = pg_fetch_assoc($rs)){
          print('<option value="'.$ligne['id_departement_d'].'">'.$ligne['nom_d'].'</option>');
        }?>
      </select>
    </div>

    <!-- Le parcours est-il faisable en autonomie ou pas -->
    <div class="form-group">
      <label for="zl_id_departement_p">Parcours autonome</label>
      <br>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="autonomie_p" id="autonomie_p_oui" value="TRUE">
        <label class="form-check-label" for="autonomie_p_oui">Oui</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="autonomie_p" id="autonomie_p_non" value="FALSE" checked>
        <label class="form-check-label" for="autonomie_p_non">Non</label>
      </div>
    </div>

    <!-- Choix de rendre le parcours visible au public -->
    <div class="form-group">
      <label for="zl_id_departement_p">Rendre le parcours visible au public</label>
      <br>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="visible_p" id="visible_p_oui" value="TRUE">
        <label class="form-check-label" for="visible_p_oui">Oui</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="visible_p" id="visible_p_non" value="FALSE" checked>
        <label class="form-check-label" for="visible_p_non">Non</label>
      </div>
    </div>

    <!-- Bouton d'enregistrement -->
    <button type="button" class="btn btn-primary" id="bt_submit_data">Enregistrer le parcours</button>

  <?php if(isset($id)) { ?>
    <!-- Bouton de suppression du parcours -->
    <button type="submit" class="btn btn-danger" name="bt_submit_suppression" id="bt_submit_suppression">
      Supprimer le parcours
    </button>
  <?php } ?>
</form>
