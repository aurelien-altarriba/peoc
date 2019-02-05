<?php
  session_start();
  session_unset();

  $_SESSION['membre']['id'] = 3;

  // HEADER
  require_once('../include/header.php');
  $idc = connect();
?>

<style type="text/css">#header,#connexion{display:none;}</style>

<?php echo('<h2>'. $_SESSION['membre']['prenom_m'] .' '. $_SESSION['membre']['nom_m'] .'</h2>'); ?>

<form method="post" action="../fonction/verif_troncon.php">

  <!-- Zone de saisie de la durée estimée du tronçon -->
  <div>
    <label for="zs_duree_estime_t">Durée estimée (minutes) </label> <input type="number" name="zs_duree_estime_t" id="zs_duree_estime_t" required>
  </div>

  <!-- Choix du type du tronçon -->
  <div>
    <label>type </label>
    <select name="zl_id_type_t" id="zl_id_type_t" required>
    <?php

    $sql='SELECT nom_tt, id_type_tt
          FROM type_terrain;';

    $rs=pg_exec($idc,$sql);

      while($ligne=pg_fetch_assoc($rs)){
        print('<option value="'.$ligne['id_type_tt'].'">'.$ligne['nom_tt'].'</option>');
      }
    ?>
    </select>
  </div>

  <!-- Choix du niveau du tronçon -->
  <div>
    <label>Niveau </label>
    <select name="zl_id_troncon_t" id="zl_id_troncon_t" required>
    <?php

    $sql='SELECT nom_nt, id_niveau_nt
          FROM niveau_terrain;';

    $rs=pg_exec($idc,$sql);

      while($ligne=pg_fetch_assoc($rs)){
        print('<option value="'.$ligne['id_niveau_nt'].'">'.$ligne['nom_nt'].'</option>');
      }
    ?>
    </select>
  </div>

  <!-- Bouton de validation de la création du tronçon -->
  <input type="submit" name="bt_submit_creation" id="bt_submit_creation" value="Valider la création du tronçon" /> </br>

  <!-- Bouton de validation des modifications du tronçon -->
  <input type="submit" name="bt_submit_modification" id="bt_submit_modification" value="Valider les modifications du tronçon" /> </br>

  <!-- Bouton de suppression du tronçon -->
  <input type="submit" name="bt_submit_suppression" id="bt_submit_suppression" value="Supprimer le tronçon" />

</form>