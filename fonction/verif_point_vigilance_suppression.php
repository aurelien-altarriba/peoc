<?php
  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
  $idc = connect();

  $id_point = pg_escape_string($_POST['id']);

  // Suppression du point en base
  $sql = "DELETE FROM point_vigilance WHERE id_vigilance_pv = $id_point";

  try {
    $rs = pg_exec($idc, $sql);
    echo($rs);
  }
  catch (Exception $e) {
    echo $e->getMessage();
  }
?>
