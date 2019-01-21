<?php
  session_start();
  ini_set('display_errors', 1);

  // Include
  include('../fonction/verif_upload_image.php');

  // Connexion BDD
  require_once('../include/connect.php');
  $idc = connect();

  // Récupération des données du formulaire
  $id_point = '';
  $parcours = '';
  if (isset($_SESSION['point_interet'])){
    $id_point = $_SESSION['point_interet'];
  }
  if (isset($_SESSION['parcours'])){
    $parcours = $_SESSION['parcours'];
  }
  $num_point = pg_escape_string($_POST['zs_num_point_pi']);
  $id_categorie = pg_escape_string($_POST['zl_nom_pic']);
  $url = pg_escape_string($_POST['zs_url_pi']);
  $photo = '';
  $description = pg_escape_string($_POST['zs_description_pi']);


  // Vérification du format des données
  // URL à tester ???
  // Testons si le fichier a bien été envoyé et s'il n'y a pas d'erreur
  if(!empty($_FILES['zs_photo_pi']['name']))
  {
      $fichier_dossier_dest = '../image/photo_pi/';
      $res_upload = verif_upload_image($_FILES['zs_photo_pi'],$fichier_dossier_dest);
      //upload ok
      if ($res_upload[0]=='OK'){
        echo $res_upload[1];
        $photo = $res_upload[2];
      }
      //upload ko
      else {
        echo $res_upload[1];
        return(0);
      }
  }
}

  $msg = '';
  $action = '';
  // Récupération de l'action à réaliser selon le bouton exécuté
  //bouton création/modification
  if (isset ($_POST['bt_submit_CM'])){
    $action = $_POST['bt_submit_CM'];
  }
  //bouton suppression
  else if(isset ($_POST['bt_submit_S'])){
    $action = $_POST['bt_submit_S'];
  }

  //Suppression
  if ($action=="Supprimer"){
    // supprimer fichier image du serveur

    $sql='delete from point_interet where id_interet_pi = '.$id_point.';';
    $rs=pg_exec($idc,$sql);
    $msg = "Suppression ok";
  }
  else{
    //insertion
    if ($action=="Créer"){
          // test si tous les champs obligatoires ont bien été renseigné
          if (!empty($parcours) & !empty($num_point) & !empty($id_categorie)) {
            $sql='insert into point_interet (id_parcours_pi,num_point_pi,id_categorie_pi,url_pi,photo_pi,description_pi) values('.$parcours.','.$num_point.','.$id_categorie.',\''.$url.'\',\''.$photo.'\',\''.$description.'\')';
            print($sql);
            $rs=pg_exec($idc,$sql);
            $msg = "Insertion ok";
          else {
            $msg = "Tous les champs obligatoires doivent être saisis";
          }
    }
    //update
    else {
      // test si tous les champs obligatoires ont bien été renseigné
      if (!empty($parcours) & !empty($num_point) & !empty($id_categorie) & !empty($id_point)){
        $sql='update point_interet set id_parcours_pi = '.$parcours.', num_point_pi = '.$num_point.', id_categorie_pi = '.$id_categorie.', url_pi = \''.$url.'\', photo_pi = \''.$photo.'\', description_pi = \''.$description.'\' where id_interet_pi = '.$id_point.';';
        print($sql);
        $rs=pg_exec($idc,$sql);
        $msg = "Modification ok";
      }
      else {
        $msg = "Tous les champs obligatoires doivent être saisis";
      }
    }
  }

  echo $msg;
?>
