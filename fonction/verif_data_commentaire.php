<?php
session_start();

//Connexion BDD
require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
$idc = connect();

// On récupère l'id du membre connecté
if (isset($_SESSION['membre']['id'])) {
  $id_membre = $_SESSION['membre']['id'];
}
else {
  return 0;
}

//Déclaration des variables $note_e, $duree_reel_e, $commentaire_e
$id_parcours = pg_escape_string($_POST['id_parcours']);
$note_e = pg_escape_string($_POST['note']);
$duree_reel_e = pg_escape_string($_POST['duree']);
$commentaire_e = pg_escape_string($_POST['commentaire']);

//Déclaration de la date du jour
$t=time();
$td=getdate($t);
$today= '\''.$td['year'].'-'.$td['mon'].'-'.$td['mday'].'\'';

//Requête SQL pour insérer les commentaires
$sql = "INSERT INTO effectue(id_membre_e, id_parcours_e, dt_jour_e, note_e, duree_reel_e, commentaire_e)
        VALUES($id_membre, $id_parcours, $today, $note_e, $duree_reel_e, E'$commentaire_e');";

// On essaye d'exécuter la requête
try {
  $rs = pg_exec($idc,$sql);
  echo('OK');
}
catch (Exception $e) {
  echo($e->getMessage());
}
?>
