<?php
  session_start();
  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/config.php');
  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
  require_once($_SERVER['DOCUMENT_ROOT'] .'/fonction/verif_upload_image.php');
  $idc = connect();

  // Définition du chemin des photos
  $fichier_dossier_dest = $_SERVER['DOCUMENT_ROOT'].$CF['image']['photo_pv'];

  // On déclare l'id du membre connecté
  if (isset($_SESSION['membre']['id'])) {
    $id_membre_pv = $_SESSION['membre']['id'];
  }
  else {
    return 0;
  }

  // Gestion de la photo
  $fichier_a_charger = 0;
  if (!empty($_FILES['zs_photo_up_pv']['name'])) {
    $fichier_temp = $_FILES['zs_photo_up_pv']['tmp_name'];
    $res_verif = verif_upload_image($fichier_temp);

    // Format OK
    if ($res_verif[0] == 'OK') {
      $fichier_a_charger = 1;

      // extension du fichier
      $fichier_ext = substr($_FILES['zs_photo_up_pv']['name'], strrpos($_FILES['zs_photo_up_pv']['name'], '.') + 1);
    }
    // Format KO
    else {
      $erreur = $res_verif[1];
      echo $erreur;
      return(0);
    }
  }

  // Récupération des données
  $dt_debut_pv = pg_escape_string($_POST['zs_dt_debut_pv']);
  $dt_fin_pv = pg_escape_string($_POST['zs_dt_fin_pv']);
  $id_categorie_pv = pg_escape_string($_POST['zs_categorie_pv']);
  $description_pv = pg_escape_string($_POST['zs_description_pv']);
  $latitude = pg_escape_string($_POST['latitude']);
  $longitude = pg_escape_string($_POST['longitude']);
  $id_parcours = pg_escape_string($_POST['parcours']);

  $t=time();
  $td=getdate($t);
  $dt_creation_pv= '\''.$td['year'].'-'.$td['mon'].'-'.$td['mday'].'\'';

  $proj = $CF['srid'];

  $sql = "INSERT INTO point_vigilance(id_parcours_pv, num_point_pv, dt_creation_pv, dt_debut_pv, dt_fin_pv,
            id_membre_pv, id_categorie_pv, description_pv, geom_pv)
          VALUES($id_parcours, 1, $dt_creation_pv, '$dt_debut_pv', '$dt_fin_pv', $id_membre_pv,
            $id_categorie_pv, E'$description_pv', ST_GeomFromText('POINT($longitude $latitude)', $proj)
          ) returning id_vigilance_pv";

  // On essaye d'exécuter la requête
  try {
    $rs = pg_exec($idc, $sql);

    $ligne = pg_fetch_assoc($rs);
    $id_vigilance_pv_new = $ligne['id_vigilance_pv'];

    // Chemin complet de la nouvelle photo
    if ($fichier_a_charger == 1) {
      $photo_new = $id_vigilance_pv_new . "." . $fichier_ext;
    }

    // Copie photo sélectionnée sur le serveur
    if ($fichier_a_charger == 1 && !empty($photo_new)) {
      if (move_uploaded_file($fichier_temp, $fichier_dossier_dest . $photo_new)) {
        // Sauvegarde du nom de la photo du point de vigilance en base de données
        $sql = "UPDATE point_vigilance SET photo_pv = '$photo_new' WHERE id_vigilance_pv = $id_vigilance_pv_new";
        try {
          $rs = pg_exec($idc, $sql);
        }
        catch (Exception $e) {
          echo $e->getMessage();
        }
      }
    }

    echo($rs);
  }
  catch (Exception $e) {
    echo($e->getMessage());
  }
?>
