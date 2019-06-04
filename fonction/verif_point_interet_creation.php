<?php
  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/config.php');
  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
  require_once($_SERVER['DOCUMENT_ROOT'] .'/fonction/verif_upload_image.php');
  $idc = connect();

  // Définition du chemin des photos
  $fichier_dossier_dest = $_SERVER['DOCUMENT_ROOT'].$CF['image']['photo_pi'];

  // Récupération des données
  $id_categorie = pg_escape_string($_POST['zl_nom_pic']);
  $url = pg_escape_string($_POST['zs_url_pi']);
  $description = pg_escape_string($_POST['zs_description_pi']);
  $latitude = pg_escape_string($_POST['latitude']);
  $longitude = pg_escape_string($_POST['longitude']);
  $id_parcours = pg_escape_string($_POST['parcours']);


  // Gestion de la photo
  $fichier_a_charger = 0;
  if (!empty($_FILES['zs_photo_up_pi']['name'])) {
    $fichier_temp = $_FILES['zs_photo_up_pi']['tmp_name'];
    $res_verif = verif_upload_image($fichier_temp);

    // Format OK
    if ($res_verif[0] == 'OK') {
      $fichier_a_charger = 1;

      // extension du fichier
      $fichier_ext = substr($_FILES['zs_photo_up_pi']['name'], strrpos($_FILES['zs_photo_up_pi']['name'], '.') + 1);
    }
    // Format KO
    else {
      $erreur = $res_verif[1];
      echo $erreur;
      return(0);
    }
  }


  // URL
  if(strlen($url) > 2000) {
    $erreur = 'L\'URL doit faire au maximum 2000 caractères';
    echo $erreur;
    return(0);
  }

  // Description
  if(strlen($description) > 2000) {
    $erreur = 'La description doit faire au maximum 2000 caractères';
    echo $erreur;
    return(0);
  }

  $proj = $CF['srid'];

  //Insertion du point en base
  $sql = "INSERT INTO point_interet (id_parcours_pi, num_point_pi, id_categorie_pi, url_pi, description_pi, geom_pi)
          VALUES($id_parcours, 1, $id_categorie, E'$url', E'$description',
            ST_GeomFromText('POINT($longitude $latitude)', $proj)
          ) returning id_interet_pi";

  try {
    $rs = pg_exec($idc, $sql);

    $ligne = pg_fetch_assoc($rs);
    $id_interet_pi_new = $ligne['id_interet_pi'];

    // Chemin complet de la nouvelle photo
    if ($fichier_a_charger == 1) {
      $photo_new = $id_interet_pi_new . "." . $fichier_ext;
    }

    // Copie photo sélectionnée sur le serveur
    if ($fichier_a_charger == 1 && !empty($photo_new)) {
      if (move_uploaded_file($fichier_temp, $fichier_dossier_dest . $photo_new)) {
        // Sauvegarde du nom de la photo du point d'intéret en base de données
        $sql = "UPDATE point_interet SET photo_pi = '$photo_new' WHERE id_interet_pi = $id_interet_pi_new";
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
    echo $e->getMessage();
  }
?>
