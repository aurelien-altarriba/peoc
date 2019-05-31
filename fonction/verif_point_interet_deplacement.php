<?php
  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/config.php');
  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
  $idc = connect();

  $id_point = pg_escape_string($_POST['id']);
  $latitude = pg_escape_string($_POST['lat']);
  $longitude = pg_escape_string($_POST['lng']);

  $proj = $CF['srid'];

  $sql = "UPDATE point_interet
          SET geom_pi = ST_GeomFromText('POINT($longitude $latitude)', $proj)
          WHERE id_interet_pi = $id_point";

  try {
    $rs = pg_exec($idc, $sql);
    echo($rs);
  }
  catch (Exception $e) {
    echo $e->getMessage();
  }
?>
