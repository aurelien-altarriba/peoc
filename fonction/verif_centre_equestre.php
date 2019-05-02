<?php
  session_start();
  ini_set('display_errors', 1);

  //Include
  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/config.php');
  require_once($_SERVER['DOCUMENT_ROOT'] .'/fonction/verif_upload_image.php');

  //Connexion BDD
  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
  $idc = connect();

  //Définition du chemin des photos
  $fichier_dossier_dest = '/'.$CF['image']['logo'];
  //$fichier_dossier_dest = '../image/logo/';


  //RECUPERATION DE L'ACTION A REALISER (selon le bouton exécuté)
  //bouton création/modification
  $action = '';
  if($_POST['bt_submit_CM']=='Modifier'){
    $action=1;
  }



  //RECUPERATION DES DONNEES DU FORMULAIRE
  $id_membre = '';
  $id_centre = '';
  if (isset($_SESSION['membre']['id'])){
        $id_membre = $_SESSION['membre']['id'];
  }
  if (isset($_SESSION['membre']['ce']['id_centre_ce'])){
    $id_centre = $_SESSION['membre']['ce']['id_centre_ce'];
  }
  $tel = pg_escape_string($_POST['zs_tel_ce']);
  $mail = pg_escape_string($_POST['zs_mail_ce']);
  $nb_cheval = pg_escape_string($_POST['zs_nb_cheval_ce']);
  $url = pg_escape_string($_POST['zs_url_ce']);

  $erreur = '';


  //VERIFICATION DES CHAMPS OBLIGATOIRES
  if (empty($tel)){
    $erreur = "Tous les champs obligatoires doivent être saisis";
    echo $erreur;
    return(0);
  }


  //VERIFICATION DU FORMAT DES DONNEES
  //Url
  if(strlen($url) > 2000) {
    $erreur = 'L\'URL doit faire au maximum 2000 caractères';
    echo $erreur;
    return(0);
  }

  //Téléphone
  if(preg_match('/[^0-9]/', $tel)) {
    $erreur = 'Votre numéro de téléphone ne doit contenir que des chiffres';
    echo $erreur;
    return(0);
  }

  //Mail
  if(!empty($mail) && !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
    $erreur = 'Veuillez rentrer un mail valide';
    echo $erreur;
    return(0);
  }


  //GESTION DES PHOTOS
  $fichier_a_charger = 0;
  $fichier_temp = '';
  $logo_new='';

  //Récupération de la photo actuelle du point
  $logo_old='';
  if (!empty($id_centre)){
    $sql='SELECT logo_ce FROM centre_equestre WHERE id_centre_ce= '.$id_centre.';';
    try{
      $rs=pg_exec($idc,$sql);
    }
    catch (Exception $e) {
      echo $e->getMessage(),"\n";
    };
    $ligne=pg_fetch_assoc($rs);
    if (!empty($ligne['logo_ce'])){
      $logo_old=$fichier_dossier_dest.$ligne['logo_ce'];
    }
  }

  //Vérification du format du fichier à uploader
  if(!empty($_FILES['zs_logo_up']['name'])){
    $fichier_temp = $_FILES['zs_logo_up']['tmp_name'];
    $res_verif = verif_upload_image($fichier_temp);

    //format ok
    if ($res_verif[0]=='OK'){
      $fichier_a_charger = 1;
      // extension du fichier
      $fichier_ext = substr($_FILES['zs_logo_up']['name'],strrpos($_FILES['zs_logo_up']['name'], '.')+1);
    }
    //format ko
    else {
      $erreur = $res_verif[1];
      echo $erreur;
      return(0);
    }
  }


  // EXECUTION DE L'ACTION
  //Update
  if ($action==1) {
    //suppression ancien logo du serveur
    if ($fichier_a_charger == 1){
      if (!empty($logo_old) && file_exists($logo_old)){
        unlink($logo_old);
      }
      //Nouveau logo
      $logo_new = $id_centre.".".$fichier_ext;
    }

    $sql='UPDATE centre_equestre
          SET tel_ce = \''.$tel.'\', mail_ce = \''.$mail.'\', nb_cheval_ce = '.$nb_cheval.', url_ce = \''.$url.'\', logo_ce = \''.$logo_new.'\'
          WHERE id_centre_ce = '.$id_centre.' And id_membre_ce = '.$id_membre.';';
    try{
      $rs=pg_exec($idc,$sql);
    }
    catch (Exception $e) {
      echo $e->getMessage(),"\n";
    };
    //echo 'OK';
    header("Location: /page/centre_equestre.php");

    //Copie logo sélectionné sur le serveur
    if ($fichier_a_charger == 1 && !empty($logo_new)){
      move_uploaded_file($fichier_temp, $fichier_dossier_dest.$logonew);
    }
  }
  else { $erreur = "Aucune action réalisée"; }

  // Si une erreur est apparue
  if(isset($erreur)) {
    echo($erreur);
  }
?>
