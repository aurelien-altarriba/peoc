<?php
  session_start();
  ini_set('display_errors', 1);

  //Include
  require_once('../fonction/verif_upload_image.php');

  //Connexion BDD
  require_once('../include/connect.php');
  $idc = connect();

  //Définition du chemin des photos
  $fichier_dossier_dest = '../image/photo_pv/';


  //RECUPERATION DE L'ACTION A REALISER (selon le bouton exécuté)
  //bouton création/modification
  $action = '';
  if (isset ($_POST['bt_submit_CM'])){
    if ($_POST['bt_submit_CM']=='Créer'){
      $action=1;
    }
    else if($_POST['bt_submit_CM']=='Modifier'){
      $action=2;
    }
  }
  //bouton suppression
  else if(isset ($_POST['bt_submit_S'])){
    if($_POST['bt_submit_S']=="Supprimer"){
      $action=3;
    }
  }


  //RECUPERATION DES DONNEES DU FORMULAIRE
  $id_point = '';
  $parcours = '';
  $id_membre = '';
  if (isset($_SESSION['point_vigilance'])){
    $id_point = $_SESSION['point_vigilance'];
  }
  if (isset($_SESSION['parcours'])){
    $parcours = $_SESSION['parcours'];
  }
  if (isset($_SESSION['membre'])){
    $id_membre = $_SESSION['membre'];
  }
  $num_point = pg_escape_string($_POST['zs_num_point_pv']);
  $id_categorie = pg_escape_string($_POST['zl_nom_pvc']);
  $date_creation = htmlspecialchars($_POST['zs_dt_creation_pv']);
  $date_debut = htmlspecialchars($_POST['zs_dt_debut_pv']);
  $date_fin = htmlspecialchars($_POST['zs_dt_fin_pv']);
  $description = pg_escape_string($_POST['zs_description_pv']);

  $erreur = '';


  //VERIFICATION DES CHAMPS OBLIGATOIRES
  //cas de la modification et suppression
  if (($action==2 || $action==3) && empty($id_point)){
    $erreur = "Tous les champs obligatoires doivent être saisis";
    echo $erreur;
    return(0);
  }

  //Hors supression
  if ($action!=3){
    //Cas de l'insertion et la modification d'un point
    if (($action==1 || $action==2) && (empty($parcours) || empty($num_point) || empty($id_categorie) || empty($id_membre) || empty($date_creation) || empty($date_debut))) {
      $erreur = "Tous les champs obligatoires doivent être saisis";
      echo $erreur;
      return(0);
    }
  }


  //VERIFICATION DU FORMAT DES DONNEES
  if ($action!=3){
    //Position
    if(preg_match('#[^0-9]#', $num_point)) {
      $erreur = 'Le numéro de position ne doit contenir que des chiffres';
      echo $erreur;
      return(0);
    }

    //Date de création
    $td=date_parse($date_creation);
    if(!checkdate($td['month'],$td['day'],$td['year'])) {
      $erreur = 'Erreur de format à la date de création';
      echo $erreur;
      return(0);
    }

    //Date de début
    $td=date_parse($date_debut);
    if(!checkdate($td['month'],$td['day'],$td['year'])) {
      $erreur = 'Erreur de format à la date de début';
      echo $erreur;
      return(0);
    }

    //Date de fin
    $td=date_parse($date_fin);
    if(!checkdate($td['month'],$td['day'],$td['year'])) {
      $erreur = 'Erreur de format à la date de fin';
      echo $erreur;
      return(0);
    }

    if (!empty($date_fin) && $date_debut > $date_fin) {
      $erreur = 'La date de fin ne peut pas être inférieure à la date de début';
      echo $erreur;
      return(0);
    }

    //Description
    if(strlen($description) > 2000) {
      $erreur = 'La description doit faire au maximum 2000 caractères';
      echo $erreur;
      return(0);
    }
  }


  //GESTION DES PHOTOS
  $fichier_a_charger = 0;
  $fichier_temp = '';
  $photo_new='';

  //Récupération de la photo actuelle du point
  $photo_old='';
  if (!empty($id_point)){
    $sql='SELECT photo_pv FROM point_vigilance WHERE id_vigilance_pv = '.$id_point.';';
    try{
      $rs=pg_exec($idc,$sql);
    }
    catch (Exception $e) {
      echo $e->getMessage(),"\n";
    };
    $ligne=pg_fetch_assoc($rs);
    if (!empty($ligne['photo_pv'])){
      $photo_old=$fichier_dossier_dest.$ligne['photo_pv'];
    }
  }

  //Vérification du format du fichier à uploader
  if(!empty($_FILES['zs_photo_up']['name'])){
    $fichier_temp = $_FILES['zs_photo_up']['tmp_name'];
    $res_verif = verif_upload_image($fichier_temp);

    //format ok
    if ($res_verif[0]=='OK'){
      $fichier_a_charger = 1;
      // extension du fichier
      $fichier_ext = substr($_FILES['zs_photo_up']['name'],strrpos($_FILES['zs_photo_up']['name'], '.')+1);
    }
    //format ko
    else {
      $erreur = $res_verif[1];
      echo $erreur;
      return(0);
    }
  }


  // EXECUTION DE L'ACTION
  //Delete
  if ($action==3){
    // Suppression du point en base
    $sql='DELETE FROM point_vigilance WHERE id_vigilance_pv = '.$id_point.';';
    try{
      $rs=pg_exec($idc,$sql);
    }
    catch (Exception $e) {
      echo $e->getMessage(),"\n";
    };
    echo 'OK';

    //suppression photo du serveur
    if (!empty($photo_old) && file_exists($photo_old)){
      unlink($photo_old);
    }
  }
  else{
    //Insert
    if ($action==1){
      //Insertion du point en base
      $sql='INSERT INTO point_vigilance (id_parcours_pv,num_point_pv,id_categorie_pv,id_membre_pv,dt_creation_pv,dt_debut_pv,dt_fin_pv,description_pv)
            VALUES('.$parcours.','.$num_point.','.$id_categorie.','.$id_membre.',\''.$date_creation.'\',\''.$date_debut.'\',\''.$date_fin.'\',\''.$description.'\') returning id_vigilance_pv';
      try{
        $rs=pg_exec($idc,$sql);
      }
      catch (Exception $e) {
        echo $e->getMessage(),"\n";
      };
      echo 'OK';

      if ($fichier_a_charger==1){
        $ligne=pg_fetch_assoc($rs);
        $id_point_new=$ligne['id_vigilance_pv'];
        $photo_new = $id_point_new.".".$fichier_ext;
      }

      //Copie photo sélectionnée sur le serveur
      if ($fichier_a_charger = 1 && !empty($photo_new)){
        if(move_uploaded_file($fichier_temp, $fichier_dossier_dest.$photo_new)){
          $sql='UPDATE point_vigilance SET photo_pv = \''.$photo_new.'\' WHERE id_vigilance_pv = '.$id_point_new.';';
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

      $sql='UPDATE point_vigilance
            SET id_parcours_pv = '.$parcours.', num_point_pv = '.$num_point.', id_categorie_pv = '.$id_categorie.',  id_membre_pv = '.$id_membre.',  dt_creation_pv = \''.$date_creation.'\',  dt_debut_pv = \''.$date_debut.'\',  dt_fin_pv = \''.$date_fin.'\', photo_pv = \''.$photo_new.'\', description_pv = \''.$description.'\'
            WHERE id_vigilance_pv = '.$id_point.';';
      try{
        $rs=pg_exec($idc,$sql);
      }
      catch (Exception $e) {
        echo $e->getMessage(),"\n";
      };
      echo 'OK';

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
