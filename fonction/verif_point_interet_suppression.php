<?php
  session_start();
  ini_set('display_errors', 1);

  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/config.php');
  require_once($_SERVER['DOCUMENT_ROOT'] .'/fonction/verif_upload_image.php');
  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
  $idc = connect();

  $id_point = pg_escape_string($_POST['id']);
  $latitude = pg_escape_string($_POST['latitude']);
  $longitude = pg_escape_string($_POST['longitude']);

  // Suppression du point en base
  $sql = "DELETE FROM point_interet WHERE id_interet_pi = $id_point";

  try {
    $rs = pg_exec($idc, $sql);
    echo($rs);
  }
  catch (Exception $e) {
    echo $e->getMessage();
  };
?>
