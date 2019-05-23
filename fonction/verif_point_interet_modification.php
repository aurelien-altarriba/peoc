<?php
  session_start();
  ini_set('display_errors', 1);

  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/config.php');
  require_once($_SERVER['DOCUMENT_ROOT'] .'/fonction/verif_upload_image.php');
  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
  $idc = connect();

  $id_point = pg_escape_string($_POST['id']);
  $id_categorie = pg_escape_string($_POST['zl_nom_pic']);
  $url = pg_escape_string($_POST['zs_url_pi']);
  $description = pg_escape_string($_POST['zs_description_pi']);

  $sql = "UPDATE point_interet
          SET id_categorie_pi = $id_categorie, url_pi = '$url', description_pi = '$description'
          WHERE id_interet_pi = $id_point";

  try {
    $rs = pg_exec($idc, $sql);
    echo($rs);
  }
  catch (Exception $e) {
    echo $e->getMessage();
  };
?>
