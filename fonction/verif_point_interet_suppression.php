<?php
  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
  $idc = connect();

  $id_point = pg_escape_string($_POST['id']);

  // Suppression du point en base
  $sql = "DELETE FROM point_interet WHERE id_interet_pi = $id_point";

  try {
    $rs = pg_exec($idc, $sql);
    echo($rs);
  }
  catch (Exception $e) {
    echo $e->getMessage();
  }
?>
