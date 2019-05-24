<?php
  session_start();
  ini_set('display_errors', 1);

  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/config.php');
  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
  $idc = connect();

  $id_point = pg_escape_string($_POST['id']);
  $latitude = pg_escape_string($_POST['lat']);
  $longitude = pg_escape_string($_POST['lng']);

  $proj = $CF['srid'];

  $sql = "UPDATE point_vigilance
          SET geom_pv = ST_GeomFromText('POINT($longitude $latitude)', $proj)
          WHERE id_vigilance_pv = $id_point";

  try {
    $rs = pg_exec($idc, $sql);
    echo($rs);
  }
  catch (Exception $e) {
    echo $e->getMessage();
  };
?>
