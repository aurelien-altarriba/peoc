<?php
  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
  $idc = connect();

  // Récupération des données
  $id_point = pg_escape_string($_POST['id']);
  $dt_debut_pv = pg_escape_string($_POST['zs_dt_debut_pv']);
  $dt_fin_pv = pg_escape_string($_POST['zs_dt_fin_pv']);
  $id_categorie_pv = pg_escape_string($_POST['zs_categorie_pv']);
  $description_pv = pg_escape_string($_POST['zs_description_pv']);

  $sql = "UPDATE point_vigilance
          SET id_categorie_pv = $id_categorie_pv, dt_debut_pv = '$dt_debut_pv', dt_fin_pv = '$dt_fin_pv',
            description_pv = '$description_pv'
          WHERE id_vigilance_pv = $id_point";

  try {
    $rs = pg_exec($idc, $sql);
    echo($rs);
  }
  catch (Exception $e) {
    echo $e->getMessage();
  }
?>
