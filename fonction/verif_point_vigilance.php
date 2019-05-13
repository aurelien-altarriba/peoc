<?php
session_start();

//Include
require_once($_SERVER['DOCUMENT_ROOT'] .'/include/config.php');

//Connexion BDD
require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
$idc = connect();

// On déclare l'id du membre connecté
if (isset($_SESSION['membre']['id'])) {
  $id_membre_pv = $_SESSION['membre']['id'];
}
else {
  return 0;
}

//Déclaration des variables du formulaire
$dt_debut_pv = pg_escape_string($_POST['dt_debut']);
$dt_fin_pv = pg_escape_string($_POST['dt_fin']);
$id_categorie_pv = pg_escape_string($_POST['id_categorie']);
$description_pv = pg_escape_string($_POST['description']);
$lat = pg_escape_string($_POST['lat']);
$lng = pg_escape_string($_POST['lng']);


//Déclaration d'id et du numéro du point de vigilance
$id_parcours_pv = pg_escape_string($_POST['id_parcours']);
$num_point_pv = 1;

//Déclaration de la date du jour pour la création du point de vigolance
$t=time();
$td=getdate($t);
$dt_creation_pv= '\''.$td['year'].'-'.$td['mon'].'-'.$td['mday'].'\'';

//Requête SQL pour insérer le point de vigilance

$sql = "INSERT INTO point_vigilance(id_parcours_pv, num_point_pv, dt_creation_pv, dt_debut_pv, dt_fin_pv, id_membre_pv, id_categorie_pv, description_pv, geom_pv)
        VALUES($id_parcours_pv, $num_point_pv, $dt_creation_pv, '$dt_debut_pv', '$dt_fin_pv', $id_membre_pv, $id_categorie_pv, E'$description_pv', ST_GeomFromText('POINT($lng $lat)', ".$CF['srid']."));";
print($sql);
// On essaye d'exécuter la requête
try {
  $rs = pg_exec($idc,$sql);
  echo('OK');
}
catch (Exception $e) {
  echo($e->getMessage());
}
?>
