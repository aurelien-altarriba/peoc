<?php
  //session_start();
  session_unset();

  $_SESSION['membre']['id'] = 1;

  // HEADER
  require_once('../include/header.php');
  $idc = connect();

  if(isset($_GET['id'])) {
    $id = htmlspecialchars($_GET['id']);
  }
?>

<?php echo('<h2>'. $_SESSION['membre']['prenom_m'] .' '. $_SESSION['membre']['nom_m'] .'</h2>'); ?>

<form>

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
    <div>
      <label for="zs_nom_p">Nom du parcours </label> <input type="text" name="zs_nom_p" id="zs_nom_p" maxlength="50" required>
    </div>

    <!-- Zone de saisie de la description du parcours -->
    <div>
      <label for="zs_description_p">Description </label> <textarea name="zs_description_p" id="zs_description_p"></textarea>
    </div>

    <!-- Choix du niveau de difficulté du parcours -->
    <div>
      <label>Niveau de difficulté</label>
      <select name="zl_id_niveau_ne" id="zl_id_niveau_ne" required>
      <?php

      $sql='SELECT nom_ne, id_niveau_ne
            FROM niveau_equestre;';

      $rs=pg_exec($idc,$sql);

        while($ligne=pg_fetch_assoc($rs)){
          print('<option value="'.$ligne['id_niveau_ne'].'">'.$ligne['nom_ne'].'</option>');

        }
      ?>
      </select>
    </div>

    <!-- Choix du département du parcours -->
    <div>
      <label>Département</label>
      <select name="zl_id_departement_p" id="zl_id_departement_p" required>
      <?php

      $sql='SELECT nom_d, id_departement_d
            FROM departement;';

      $rs=pg_exec($idc,$sql);

        while($ligne=pg_fetch_assoc($rs)){
          print('<option value="'.$ligne['id_departement_d'].'">'.$ligne['nom_d'].'</option>');
        }
      ?>
      </select>
    </div>

    <!-- Le parcours est-il faisable en autonomie ou pas -->
    <div>

        <p>Parcours autonome <br/>
        <input type="radio" name="autonomie_p" id="autonomie_p_oui" value="TRUE"/>  <label for="autonomie_p_oui">Oui </label> <br/>
        <input type="radio" name="autonomie_p" id="autonomie_p_non" value="FALSE"/>  <label for="autonomie_p_non">Non </label>
        </p>
    </div>

    <!-- Choix de rendre le parcours visible au public -->
    <div>

        <p>Rendre le parcours public <br/>
        <input type="radio" name="visible_p" id="visible_p_oui" value="TRUE" ><label for="visible_p_oui">Oui</label> <br/>
        <input type="radio" name="visible_p" id="visible_p_non" value="FALSE" ><label for="visible_p_non">Non</label>
        </p>
    </div>

    <!-- Bouton d'enregistrement -->
    <input type="submit" name="bt_submit_data" id="bt_submit_data" value="Enregistrer le parcours" />

  <?php if(isset($id)) { ?>
    <!-- Bouton de suppression du parcours -->
    <input type="submit" name="bt_submit_suppression" id="bt_submit_suppression" value="Supprimer le parcours" />
  <?php } ?>

  </form>

  <form method="get" action="../page/troncon.php">
    <button type="submit">Créer un tronçon</button>
  </form>
