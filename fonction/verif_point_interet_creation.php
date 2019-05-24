<?php
  session_start();
  ini_set('display_errors', 1);

  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/config.php');
  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
  $idc = connect();

  // Récupération des données
  $id_categorie = pg_escape_string($_POST['zl_nom_pic']);
  $url = pg_escape_string($_POST['zs_url_pi']);
  $description = pg_escape_string($_POST['zs_description_pi']);
  $latitude = pg_escape_string($_POST['latitude']);
  $longitude = pg_escape_string($_POST['longitude']);
  $id_parcours = pg_escape_string($_POST['parcours']);

  // URL
  if(strlen($url) > 2000) {
    $erreur = 'L\'URL doit faire au maximum 2000 caractères';
    echo $erreur;
    return(0);
  }

  // Description
  if(strlen($description) > 2000) {
    $erreur = 'La description doit faire au maximum 2000 caractères';
    echo $erreur;
    return(0);
  }

  $proj = $CF['srid'];

  //Insertion du point en base
  $sql = "INSERT INTO point_interet (id_parcours_pi, num_point_pi, id_categorie_pi, url_pi, description_pi, geom_pi)
          VALUES($id_parcours, 1, $id_categorie, E'$url', E'$description',
            ST_GeomFromText('POINT($longitude $latitude)', $proj)
          ) returning id_interet_pi";

  try {
    $rs = pg_exec($idc, $sql);
    echo($rs);
  }
  catch (Exception $e) {
    echo $e->getMessage();
  };























  /*
  // EXECUTION DE L'ACTION
  //Delete
  if ($action==3){
    // Suppression du point en base
    $sql='DELETE FROM point_interet WHERE id_interet_pi = '.$id_point.';';
    try{
      $rs=pg_exec($idc,$sql);
    }
    catch (Exception $e) {
      echo $e->getMessage(),"\n";
    };
    //echo 'OK';
    header("Location: /page/point_interet.php");

    //suppression photo du serveur
    if (!empty($photo_old) && file_exists($photo_old)){
      unlink($photo_old);
    }
  }
  else{
    //Insert
    if ($action==1){
      //Insertion du point en base
      $sql='INSERT INTO point_interet (id_parcours_pi,num_point_pi,id_categorie_pi,url_pi,description_pi)
            VALUES('.$parcours.','.$num_point.','.$id_categorie.',\''.$url.'\',\''.$description.'\') returning id_interet_pi';
      try{
        $rs=pg_exec($idc,$sql);
      }
      catch (Exception $e) {
        echo $e->getMessage(),"\n";
      };
      //echo 'OK';
      header("Location: /page/point_interet.php");

      if ($fichier_a_charger==1){
        $ligne=pg_fetch_assoc($rs);
        $id_point_new=$ligne['id_interet_pi'];
        $photo_new = $id_point_new.".".$fichier_ext;
      }

      //Copie photo sélectionnée sur le serveur
      if ($fichier_a_charger = 1 && !empty($photo_new)){
        if(move_uploaded_file($fichier_temp, $fichier_dossier_dest.$photo_new)){
          $sql='UPDATE point_interet SET photo_pi = \''.$photo_new.'\' WHERE id_interet_pi = '.$id_point_new.';';
          try{
            $rs=pg_exec($idc,$sql);
          }
          catch (Exception $e) {
            echo $e->getMessage(),"\n";
          };
        }
      }
    }
    //Update
    else if ($action==2) {
      //suppression ancienne photo du serveur
      if ($fichier_a_charger == 1){
        if (!empty($photo_old) && file_exists($photo_old)){
          unlink($photo_old);
        }
        //Nouvelle photo
        $photo_new = $id_point.".".$fichier_ext;
      }

      $sql='UPDATE point_interet
            SET id_parcours_pi = '.$parcours.', num_point_pi = '.$num_point.', id_categorie_pi = '.$id_categorie.', url_pi = \''.$url.'\', photo_pi = \''.$photo_new.'\', description_pi = \''.$description.'\'
            WHERE id_interet_pi = '.$id_point.';';
      try{
        $rs=pg_exec($idc,$sql);
      }
      catch (Exception $e) {
        echo $e->getMessage(),"\n";
      };
      //echo 'OK';
      header("Location: /page/point_interet.php");

      //Copie photo sélectionnée sur le serveur
      if ($fichier_a_charger == 1 && !empty($photo_new)){
        move_uploaded_file($fichier_temp, $fichier_dossier_dest.$photo_new);
      }
    }
    else { $erreur = "Aucune action réalisée"; }
  }

  // Si une erreur est apparue
  if(isset($erreur)) {
    echo($erreur);
  }
?>
