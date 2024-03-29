<?php
  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/config.php');
  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
  $idc = connect();

  // Bouton création/suppression
  $action = $_POST['action'];
  $id_parcours = $_POST['id_parcours'];

  $erreur = '';

  // Creation d'une zone allure
  if ($action == 1) {
    $id_type = $_POST['id_type'];
    $point1 = $_POST['point1'];
    $point2 = $_POST['point2'];
    $dist = $CF['dist_allure'];
    $srid = $CF['srid'];

    try {
      // Insertion du point en base
      $sql = "SELECT id_za, msg_ret
              FROM create_allure($id_parcours, $id_type, $point1[0], $point1[1], $point2[0], $point2[1], $dist, $srid)";

      try {
        $rs = pg_exec($idc, $sql);
      }
      catch (Exception $e) {
        echo $e->getMessage();
      }

      $za = pg_fetch_assoc($rs)['id_za'];

      // On renvoie l'id
      echo($za);
    }
    catch (Exception $e) {
      echo $e->getMessage();
    }
  }


  // Suppression d'une zone allure
  else if ($action == 2 && isset($id_parcours)) {
    $id_za = $_POST['id_za'];
    $sql = "DELETE FROM zone_allure WHERE id_parcours_za = $id_parcours AND id_zone_za = $id_za";

    try {
      $rs = pg_exec($idc, $sql);
    }
    catch (Exception $e) {
      echo $e->getMessage();
    }
  }

  // Si une erreur est apparue
  if (isset($erreur)) {
    echo($erreur);
  }
?>
