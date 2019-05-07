<?php
  $idc = connect();
?>

<form onsubmit="return(false);">

    <!-- Zone de saisie de l'ID du membre -->
    <div>
      <label for="zs_id_membre_e">ID membre </label> <input type="text" name="zs_id_membre_e" id="zs_id_membre_e" maxlength="50" required>
    </div>

    <!-- Zone de saisie de l'ID du parcours -->
    <div>
      <label for="zs_id_parcours_e">ID parcours </label> <input type="text" name="zs_id_parcours_e" id="zs_id_parcours_e" maxlength="50" required>
    </div>

    <!-- Zone de saisie de la date où le parcours parcours à été effectué -->
    <div>
      <label for="zs_dt_jour_e">Date </label> <input type="text" name="zs_dt_jour_e" id="zs_dt_jour_e" maxlength="50" required>
    </div>

    <!-- Zone de saisie de la note du parcours -->
    <div>
      <label for="zs_note_e">Note </label> <input type="text" name="zs_note_e" id="zs_note_e" maxlength="50" required>
    </div>

    <!-- Zone de saisie de la durée réelle du parcours -->
    <div>
      <label for="zs_duree_reel_e">Durée réelle </label> <textarea name="zs_duree_reel_e" id="zs_duree_reel_e"></textarea>
    </div>

    <!-- Zone de saisie du commentaire du parcours -->
    <div>
      <label for="zs_commentaire_e">commentaire </label> <textarea name="zs_commentaire_e" id="zs_commentaire_e"></textarea>
    </div>

    <!-- Bouton d'enregistrement des modifications-->
    <button id="bt_submit_data">Valider les modifications</button>

  <?php if(isset($id)) { ?>
    <!-- Bouton de suppression du parcours -->
    <input type="submit" name="bt_submit_suppression" id="bt_submit_suppression" value="Supprimer le parcours" />
  <?php } ?>

</form>
