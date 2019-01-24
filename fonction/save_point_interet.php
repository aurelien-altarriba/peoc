<?php
  session_start();
  ini_set('display_errors', 1);

  //Include
  require_once('../fonction/verif_upload_image.php');

  //Connexion BDD
  require_once('../include/connect.php');
  $idc = connect();

  //Récupération des données du formulaire
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
  $description = pg_escape_string($_POST['zs_description_pi']);

  $erreur = '';

  //Vérification du format des données
  //POSITION
  if(!preg_match('#[^0-9]#', $num_point)) {

    //URL
    if(strlen($url) <= 2000) {

      //DESCRIPTION
      if(strlen($description) <= 2000) {

        //Gestion des photos
        $fichier_a_charger = 0;
        $fichier_dossier_dest = '../image/photo_pi/';
        $fichier_temp = '';
        $photo_new='';

        //Récupération de la photo actuelle du point
        $photo_old='';
        if (!empty($id_point)){
          $sql='SELECT photo_pi FROM point_interet WHERE id_interet_pi = '.$id_point.';';
          $rs=pg_exec($idc,$sql);
          $ligne=pg_fetch_assoc($rs);
          if (!empty($ligne['photo_pi'])){
            $photo_old=$fichier_dossier_dest.$ligne['photo_pi'];
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
            echo $res_verif[1];
            return(0);
          }
        }

        //Récupération de l'action à réaliser selon le bouton exécuté
        //bouton création/modification
        $action = '';

        if (isset ($_POST['bt_submit_CM'])){
          $action = $_POST['bt_submit_CM'];
        }
        //bouton suppression
        else if(isset ($_POST['bt_submit_S'])){
          $action = $_POST['bt_submit_S'];
        }


        //Delete
        if ($action=="Supprimer"){
          // Suppression du point en base
          $sql='DELETE FROM point_interet WHERE id_interet_pi = '.$id_point.';';
          $rs=pg_exec($idc,$sql);
          echo 'OK';

          //suppression photo du serveur
          if (!empty($photo_old)){
            unlink($photo_old);
          }
        }
        else{
          //Insert
          if ($action=="Créer"){
            //Test si tous les champs obligatoires ont bien été renseigné
            if (!empty($parcours) && !empty($num_point) && !empty($id_categorie)) {
              //Insertion du point en base
              $sql='INSERT INTO point_interet (id_parcours_pi,num_point_pi,id_categorie_pi,url_pi,description_pi)
                    VALUES('.$parcours.','.$num_point.','.$id_categorie.',\''.$url.'\',\''.$description.'\') returning id_interet_pi';
              $rs=pg_exec($idc,$sql);
              echo 'OK';

              if ($fichier_a_charger == 1){
                $ligne=pg_fetch_assoc($rs);
                $id_point_new=$ligne['id_interet_pi'];
                $photo_new = $id_point_new.".".$fichier_ext;
              }

              //Copie photo sélectionnée sur le serveur
              if ($fichier_a_charger = 1 && !empty($photo_new)){
                if(move_uploaded_file($fichier_temp, $fichier_dossier_dest.$photo_new)){
                  $msg=$msg." / "."Fichier uploadé";
                  $sql='UPDATE point_interet SET photo_pi = \''.$photo_new.'\' WHERE id_interet_pi = '.$id_point_new.';';
                  $rs=pg_exec($idc,$sql);
                }
              }
            }
            else {
              $erreur = "Tous les champs obligatoires doivent être saisis";
            }
          }
          //Update
          else if ($action=="Modifier") {
            //Test si tous les champs obligatoires ont bien été renseigné
            if (!empty($parcours) && !empty($num_point) && !empty($id_categorie) && !empty($id_point)){

              //suppression ancienne photo du serveur
              if ($fichier_a_charger == 1){
                if (!empty($photo_old)){
                  unlink($photo_old);
                }
                //Nouvelle photo
                $photo_new = $id_point.".".$fichier_ext;
              }

              $sql='UPDATE point_interet
                    SET id_parcours_pi = '.$parcours.', num_point_pi = '.$num_point.', id_categorie_pi = '.$id_categorie.', url_pi = \''.$url.'\', photo_pi = \''.$photo_new.'\', description_pi = \''.$description.'\'
                    WHERE id_interet_pi = '.$id_point.';';
              $rs=pg_exec($idc,$sql);
              echo 'OK';

              //Copie photo sélectionnée sur le serveur
              if ($fichier_a_charger == 1 && !empty($photo_new)){
                if(move_uploaded_file($fichier_temp, $fichier_dossier_dest.$photo_new)){
                }
                else $erreur= "Le fichier n'a pas pu être uploadé";
              }
            }
            else { $erreur = "Tous les champs obligatoires doivent être saisis";}
          }
          else { $erreur = "Aucune action réalisée"; }
        }
      }
      else { $erreur = 'La description doit faire au maximum 2000 caractères'; }
    }
    else { $erreur = 'L\'URL doit faire au maximum 2000 caractères'; }
  }
  else { $erreur = 'Le numéro de position ne doit contenir que des chiffres'; }
  echo $erreur;
?>
