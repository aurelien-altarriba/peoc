<?php
  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/config.php');
  $idc = connect();

  $id_point = pg_escape_string($_POST['id']);

  // Définition du chemin des photos
  $fichier_dossier_dest = $_SERVER['DOCUMENT_ROOT'].$CF['image']['photo_pi'];

  // Suppression du point en base
  $sql = "DELETE FROM point_interet WHERE id_interet_pi = $id_point returning photo_pi";

  try {
    $rs = pg_exec($idc, $sql);

    $ligne=pg_fetch_assoc($rs);
    if (!empty($ligne['photo_pi'])) {
      $photo_old = $fichier_dossier_dest . $ligne['photo_pi'];
    }

    // Suppression photo du serveur
    if (!empty($photo_old) && file_exists($photo_old)) {
      unlink($photo_old);
    }

    echo($rs);
  }
  catch (Exception $e) {
    echo $e->getMessage();
  }
?>
