<?php
  session_start();
  ini_set('display_errors', 1);

  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/config.php');
  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
  $idc = connect();

  // On déclare l'id du membre connecté
  if (isset($_SESSION['membre']['id'])) {
    $id_membre_pv = $_SESSION['membre']['id'];
  }
  else {
    return 0;
  }

  // Récupération des données
  $dt_debut_pv = pg_escape_string($_POST['zs_dt_debut_pv']);
  $dt_fin_pv = pg_escape_string($_POST['zs_dt_fin_pv']);
  $id_categorie_pv = pg_escape_string($_POST['zs_categorie_pv']);
  $description_pv = pg_escape_string($_POST['zs_description_pv']);
  $latitude = pg_escape_string($_POST['latitude']);
  $longitude = pg_escape_string($_POST['longitude']);
  $id_parcours = pg_escape_string($_POST['parcours']);

  $t=time();
  $td=getdate($t);
  $dt_creation_pv= '\''.$td['year'].'-'.$td['mon'].'-'.$td['mday'].'\'';

  $proj = $CF['srid'];

  $sql = "INSERT INTO point_vigilance(id_parcours_pv, num_point_pv, dt_creation_pv, dt_debut_pv, dt_fin_pv,
            id_membre_pv, id_categorie_pv, description_pv, geom_pv)
          VALUES($id_parcours, 1, $dt_creation_pv, '$dt_debut_pv', '$dt_fin_pv', $id_membre_pv,
            $id_categorie_pv, E'$description_pv', ST_GeomFromText('POINT($longitude $latitude)', $proj)
          ) returning id_vigilance_pv";

  // On essaye d'exécuter la requête
  try {
    $rs = pg_exec($idc, $sql);
    echo($rs);
  }
  catch (Exception $e) {
    echo($e->getMessage());
  }
?>
