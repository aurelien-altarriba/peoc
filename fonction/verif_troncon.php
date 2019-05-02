<?php
ini_set('display_errors', 1);

session_start();

//Connexion BDD
require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
$idc = connect();

// Tableau des coordonnées
// $listeTroncons = json_decode($_POST['listeTroncons']);

$test = json_decode('{"id_108":[{"lat":43.73786614486672,"lng":0.24075061317786697},{"lat":44.03903420665632,"lng":1.5648368261944336},{"lat":44.37799275153152,"lng":3.108688385769765}],"id_132":[{"lat":43.29526688349406,"lng":0.6857754399593753},{"lat":43.7497832247868,"lng":2.7790403659316185},{"lat":43.102867411770774,"lng":2.0922736579354684},{"lat":43.76169793239583,"lng":1.7736139054252398}]}');

echo'<pre>';
print_r($test);
echo'</pre>';


/*


// récupération de l'id du membre connecté
if (isset($_SESSION['membre']['id'])){
  $id_membre = $_SESSION['membre']['id'];
}
else {
  $id_membre = 'NULL';
}

// récupération de l'id du centre équestre connecté
if (isset($_SESSION['membre']['ce']['id_centre_ce'])){
  $id_centre_p = $_SESSION['membre']['ce']['id_centre_ce'];
}
else {
  $id_centre_p = 'NULL';
}

// récupération le l'id du membre qui à créé le parcours sélectionné
$rs = pg_query($idc,$sql = "SELECT DISTINCT id_membre_p
FROM parcours
INNER JOIN troncon ON parcours.id_parcours_p = troncon.id_parcours_t
WHERE id_parcours_p = $id_parcours_p");
$id_membre_p_parcours_selection = pg_fetch_result($rs,0,0);

// récupération le l'id du centre équestre qui à créé le parcours sélectionné
$rs = pg_query($idc,$sql = "SELECT DISTINCT id_centre_p
FROM parcours
INNER JOIN troncon ON parcours.id_parcours_p = troncon.id_parcours_t
WHERE id_parcours_p = $id_parcours_p");
$id_centre_p_parcours_selection = pg_fetch_result($rs,0,0);

// vérifie si l'utilisateur est propriétaire du parcours du tronçon pour pouvoir créer un tronçon
if (isset($_POST['bt_submit_creation']) && ($id_membre==$id_membre_p_parcours_selection || $id_centre_p==$id_centre_p_parcours_selection)){
  //Insertion du tronçon par un cavalier, id_membre_p est renseigné
  $sql="INSERT INTO troncon(id_parcours_t,num_position_t,id_hierarchie_t,id_type_t,id_niveau_t,duree_estime_t,geom_t)
  VALUES($id_parcours_p,$num_position_t,1,$id_type_t,$id_niveau_nt,$duree_estime_t,ST_GeomFromText('LINESTRING(2.8876 43.2845, 2.8525 43.2748)', 3857));";
  $rs=pg_exec($idc,$sql);
}

// vérifie si l'utilisateur est propriétaire du parcours du tronçon pour pouvoir modifier un tronçon
else if (isset($_POST['bt_submit_modification']) && ($id_membre==$id_membre_p_parcours_selection || $id_centre_p==$id_centre_p_parcours_selection)){
  //Insertion du tronçon par un cavalier, id_membre_p est renseigné
  $sql="UPDATE troncon
  SET num_position_t = $num_position_t,
      id_type_t = $id_type_t,
      id_niveau_t = $id_niveau_nt,
      duree_estime_t = $duree_estime_t
      WHERE id_troncon_t = $id_troncon_t";
  $rs=pg_exec($idc,$sql);
}

// vérifie si l'utilisateur est propriétaire du parcours du tronçon pour pouvoir supprimer un tronçon
else if (isset($_POST['bt_submit_suppression']) && ($id_membre==$id_membre_p_parcours_selection || $id_centre_p==$id_centre_p_parcours_selection)){
  //Insertion du tronçon par un cavalier, id_membre_p est renseigné
  $sql="DELETE FROM troncon
        WHERE id_troncon_t = $id_troncon_t";
  $rs=pg_exec($idc,$sql);
}

else {
  echo('Vous pouvez seulement modifier vos propres parcours');
}

?>
