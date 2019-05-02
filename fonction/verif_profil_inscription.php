<?php
  session_start();
  ini_set('display_errors', 1);

  //Include
  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/config.php');
  require_once($_SERVER['DOCUMENT_ROOT'] .'/fonction/verif_upload_image.php');

  // Connexion BDD
  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
  $idc = connect();

  //Définition du chemin des photos
  $fichier_dossier_dest = '/'.$CF['image']['photo'];
  //$fichier_dossier_dest = '../image/photo/';

  //RECUPERATION VARIABLE DE SESSION
  $id_membre = '';
  if (isset($_SESSION['membre']['id'])){
    $id_membre = $_SESSION['membre']['id'];
  }
  $id_membre_new = '';


  //RECUPERATION DE L'ACTION A REALISER (selon le bouton exécuté)
  //bouton création/modification
  $action = '';
  if (isset ($_POST['bt_submit_CM'])){
    if ($_POST['bt_submit_CM']=="S'inscrire"){
      $action=1;
    }
    else if($_POST['bt_submit_CM']=='Modifier'){
      $action=2;
    }
  }
  //bouton suppression
  else if(isset ($_POST['bt_submit_S'])){
    if($_POST['bt_submit_S']=='Supprimer'){
      $action=3;
    }
  }

  //RECUPERATION DES DONNEES DU FORMULAIRE
  $statut_C=0;
  $statut_R=0;
  $statut_P=0;
  //Cavalier
  if (isset ($_POST['cc_cavalier'])){
    $statut_C=1;
  }
  //Responsable centre équestre
  if (isset ($_POST['cc_centre'])){
      $statut_R=1;
  }
  //
  if (isset ($_POST['cc_mdp'])){
      $statut_P=1;
  };

  $mail = htmlspecialchars($_POST['zs_mail_m']);
  $nom = htmlspecialchars($_POST['zs_nom_m']);
  $prenom = htmlspecialchars($_POST['zs_prenom_m']);
  $date_naissance = htmlspecialchars($_POST['zs_dt_naissance_m']);
  $adresse = htmlspecialchars($_POST['zs_adresse_m']);
  $ville = htmlspecialchars($_POST['zs_ville_m']);
  $CP = htmlspecialchars($_POST['zs_cp_m']);
  $pays = htmlspecialchars($_POST['zl_nom_pa']);
  $tel = htmlspecialchars($_POST['zs_tel_m']);

  //Récupération des variables information de connexion
  // dans le cas de la modification et de la suppression on ne s'en occupe pas car non modifiable après inscription
  if($action==1) {
    $login = htmlspecialchars($_POST['zs_login_ic']);
  }
  $mdp = htmlspecialchars($_POST['zs_mdp_ic']);
  $mdp2 = htmlspecialchars($_POST['zs_mdp_ic2']);

  //Récupération des variables d'un cavalier
  $licence = htmlspecialchars($_POST['zs_num_licence_c']);
  $expiration_licence = htmlspecialchars($_POST['zs_dt_exp_licence_c']);
  $niveau_equestre = htmlspecialchars($_POST['zl_nom_ne']);

  //Récupération des variables responsable de centre équestre
  $responsable_CE = htmlspecialchars($_POST['zl_nom_ce']);

  $erreur = '';


  //date du jour
  $t=time();
  $td=getdate($t);
  $today= '\''.$td['year'].'-'.$td['mon'].'-'.$td['mday'].'\'';


  //VERIFICATION DES CHAMPS OBLIGATOIRES
  //Cas de la modification ou suppression
  if (($action==2 || $action==3) && empty($id_membre)){
    $erreur = "Tous les champs obligatoires doivent être saisis";
    echo $erreur;
    return(0);
  }

  //Hors suppression
  If ($action!=3){
    //Cas de l'insertion d'un login
    if($action==1 && (empty($login) || empty($mdp))) {
      $erreur = 'Tous les champs obligatoires doivent être complétés';
      echo $erreur;
      return(0);
    }

    //Cas de la modification d'un mot de passe
    if($statut_P==1 && $action==2 && empty($mdp)) {
      $erreur = 'Tous les champs obligatoires doivent être complétés';
      echo $erreur;
      return(0);
    }

    //Cas de l'insertion et modification d'un membre
    if (($action==1 || $action==2) && (empty($nom) || empty($prenom) || empty($date_naissance) || empty($pays) || empty($mail))){
      $erreur = "Tous les champs obligatoires doivent être saisis";
      echo $erreur;
      return(0);
    }

    //Cas de l'insertion ou modification d'un cavalier
    if ($statut_C==1 && ($action==1 || $action==2) && (empty($licence) || empty($expiration_licence) || empty($niveau_equestre))){
      $erreur = "Tous les champs obligatoires doivent être saisis";
      echo $erreur;
      return(0);
    }

    //Cas de l'insertion ou modification d'un responsable
    if ($statut_R==1  && ($action==1 || $action==2) && empty($responsable_CE)){
      $erreur = "Tous les champs obligatoires doivent être saisis";
      echo $erreur;
      return(0);
    }
  }


  //VERIFICATION DU FORMAT DES DONNEES
  //Hors suppression
  If ($action!=3){
    //Login (seulement dans le cas de l'insertion)
    if($action==1) {
      if((strlen($login) < 3) || (strlen($login) > 50)) {
        $erreur = 'Votre login doit faire entre 3 et 50 caractères';
        echo $erreur;
        return(0);
      }

      if(preg_match('#[^a-zA-Z0-9_]#', $login)) {
        $erreur = 'Le login ne doit pas contenir de caractères spéciaux';
        echo $erreur;
        return(0);
      }

      $res = pg_query_params($idc, "SELECT count(login_ic) FROM info_connexion WHERE login_ic = $1", array($login));
      $login_existe = pg_fetch_all($res)[0]['count'];
      if($login_existe != 0) {
        $erreur = 'Le login est déjà utilisé sur un autre compte';
        echo $erreur;
        return(0);
      }
    }

    //Mot de passe
    if($action==1 || ($action==2 && $statut_P==1)) {
      if($mdp === $mdp2) {
        //$mdp_hash ='test';
        $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);
      }
      else {
        $erreur = 'Les mots de passe ne correspondent pas';
        echo $erreur;
        return(0);
      }
    }

    //Mail
    if(filter_var($mail, FILTER_VALIDATE_EMAIL)) {
      if($action==1 || $action==2) {
        if($action==1) {
          $res = pg_query_params($idc, "SELECT count(mail_m) FROM membre WHERE mail_m = $1", array($mail));
        }
        else if ($action==2) {
          $res = pg_query_params($idc, "SELECT count(mail_m) FROM membre WHERE id_membre_m != $1 and mail_m = $2", array($id_membre, $mail));
        }
        $mail_existe = pg_fetch_all($res)[0]['count'];
        if($mail_existe != 0) {
          $erreur = 'Le mail est déjà utilisé sur un autre compte';
          echo $erreur;
          return(0);
        }
      }
    }
    else {
      $erreur = 'Veuillez rentrer un mail valide';
      echo $erreur;
      return(0);
    }

    // Nom
    if(strlen($nom) > 30) {
      $erreur = 'Votre nom doit faire au maximum 30 caractères';
      echo $erreur;
      return(0);
    }

    // Prénom
    if(strlen($prenom) > 30) {
      $erreur = 'Votre prénom doit faire au maximum 30 caractères';
      echo $erreur;
      return(0);
    }

    //Date de naissance
    $td=date_parse($date_naissance);
    if(!checkdate($td['month'],$td['day'],$td['year'])) {
      $erreur = 'Erreur de format à la date de naissance';
      echo $erreur;
      return(0);
    }

    //Adresse
    if(strlen($adresse) > 100) {
      $erreur = 'L\'adresse doit faire au maximum 100 caractères';
      echo $erreur;
      return(0);
    }

    //CP
    if(strlen($CP) > 5) {
      $erreur = 'Le code postal doit faire au maximum 5 caractères';
      echo $erreur;
      return(0);
    }

    //Ville
    if(strlen($ville) > 80) {
      $erreur = 'La ville doit faire au maximum 80 caractères';
      echo $erreur;
      return(0);
    }

    //Téléphone
    if(preg_match('/[^0-9]/', $tel)) {
      $erreur = 'Votre numéro de téléphone ne doit contenir que des chiffres';
      echo $erreur;
      return(0);
    }
  }


  //GESTION DES PHOTOS
  $fichier_a_charger = 0;
  $fichier_temp = '';
  $photo_new='';

  //Récupération de la photo actuelle du membre
  $photo_old='';
  if (!empty($id_membre)){
    $sql='SELECT photo_c FROM cavalier WHERE id_membre_c = '.$id_membre.';';
    try{
      $rs=pg_exec($idc,$sql);
    }
    catch (Exception $e) {
      echo $e->getMessage(),"\n";
    };
    $ligne=pg_fetch_assoc($rs);
    if (!empty($ligne['photo_c'])){
      $photo_old=$fichier_dossier_dest.$ligne['photo_c'];
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
    //Update du centre equestre
    $sql='UPDATE centre_equestre SET id_membre_ce = null WHERE id_membre_ce = '.$id_membre;
    try{
      $rs=pg_exec($idc,$sql);
    }
    catch (Exception $e) {
      echo $e->getMessage(),"\n";
    };

    // Suppression du membre et des enregistrements associés
    $sql='DELETE FROM membre WHERE id_membre_m = '.$id_membre.';';
    try{
      $rs=pg_exec($idc,$sql);
    }
    catch (Exception $e) {
      echo $e->getMessage(),"\n";
    };

    //Suppression photo du serveur
    if (!empty($photo_old)  && file_exists($photo_old)){
      unlink($photo_old);
    }
    //echo 'OK';
    header("Location: /page/profil.php");
  }
  else {
    //Insert
    if ($action==1){
      //Insertion du membre
      $sql='INSERT INTO membre (nom_m,prenom_m,dt_naissance_m,adresse_m,cp_m,ville_m,id_pays_m,tel_m,mail_m)
            VALUES(\''.$nom.'\',\''.$prenom.'\',\''.$date_naissance.'\',\''.$adresse.'\',\''.$CP.'\',\''.$ville.'\',\''.$pays.'\',\''.$tel.'\',\''.$mail.'\') returning id_membre_m';
      try{
        $rs=pg_exec($idc,$sql);
      }
      catch (Exception $e) {
        echo $e->getMessage(),"\n";
      };

      //Récupération du nouvel id_membre affecté automatiquement au nouvel enregistrement
      $ligne=pg_fetch_assoc($rs);
      $id_membre_new=$ligne['id_membre_m'];

      //Insertion des infos de connexion
      $sql='INSERT INTO info_connexion (login_ic,mdp_ic,id_membre_ic,dt_inscription_ic)
            VALUES(\''.$login.'\',\''.$mdp_hash.'\','.$id_membre_new.','.$today.')';
      try{
        $rs=pg_exec($idc,$sql);
      }
      catch (Exception $e) {
        echo $e->getMessage(),"\n";
      };

      //Insertion du cavalier
      if ($statut_C==1){
        if (!empty($id_membre_new)){
          $sql='INSERT INTO cavalier (id_membre_c,num_licence_c,dt_exp_licence_c,id_niveau_c)
                VALUES('.$id_membre_new.',\''.$licence.'\',\''.$expiration_licence.'\','.$niveau_equestre.')';
          try{
            $rs=pg_exec($idc,$sql);
          }
          catch (Exception $e) {
            echo $e->getMessage(),"\n";
          };

          if ($fichier_a_charger==1){
            $photo_new = $id_membre_new.".".$fichier_ext;
          }

          //Copie photo sélectionnée sur le serveur
          if ($fichier_a_charger == 1 && !empty($photo_new)){
            $photo_new = $id_membre_new.".".$fichier_ext;
            if(move_uploaded_file($fichier_temp, $fichier_dossier_dest.$photo_new)){
              $sql='UPDATE cavalier SET photo_c = \''.$photo_new.'\' WHERE id_membre_c = '.$id_membre_new.';';
              try{
                $rs=pg_exec($idc,$sql);
              }
              catch (Exception $e) {
                echo $e->getMessage(),"\n";
              };
            }
          }
        }
      }

      //Mise à jour du centre équestre avec le responsable
      if ($statut_R==1){
        if (!empty($id_membre_new)){
          $sql='UPDATE centre_equestre SET id_membre_ce = '.$id_membre_new.' WHERE id_centre_ce = '.$responsable_CE;
          try{
            $rs=pg_exec($idc,$sql);
          }
          catch (Exception $e) {
            echo $e->getMessage(),"\n";
          };
        }
      }

      //echo 'OK';
      header("Location: /page/profil.php");
    }
    //update
    else if ($action==2){
      // Mise à jour du membre
      $sql='UPDATE membre
            SET nom_m = \''.$nom.'\', prenom_m = \''.$prenom.'\', dt_naissance_m = \''.$date_naissance.'\',adresse_m = \''.$adresse.'\',cp_m = \''.$CP.'\',ville_m = \''.$ville.'\',id_pays_m = \''.$pays.'\',tel_m = \''.$tel.'\',mail_m = \''.$mail.'\'
            WHERE id_membre_m = '.$id_membre;
      try{
        $rs=pg_exec($idc,$sql);
      }
      catch (Exception $e) {
        echo $e->getMessage(),"\n";
      };

      //Mise à jour du mot de passe
      if ($statut_P==1){
        if (!empty($mdp_hash) && !empty($id_membre)){
          $sql='UPDATE info_connexion SET mdp_ic = \''.$$mdp_hash.'\' WHERE id_membre_ic = '.$id_membre;
          try{
            $rs=pg_exec($idc,$sql);
          }
          catch (Exception $e) {
            echo $e->getMessage(),"\n";
          };
        }
      }

      //Mise à jour du cavalier
      if ($statut_C==1){
        $sql='SELECT count(*) AS nb FROM cavalier WHERE id_membre_c = '.$id_membre;
        try{
          $rs=pg_exec($idc,$sql);
        }
        catch (Exception $e) {
          echo $e->getMessage(),"\n";
        };
        $ligne=pg_fetch_assoc($rs);
        $nb=$ligne['nb'];

        //Déjà un enregistrement existant donc update des infos
        if ($nb == 1){
          //suppression ancienne photo du serveur
          if ($fichier_a_charger == 1){
            if (!empty($photo_old) && file_exists($photo_old)){
              unlink($photo_old);
            }
            //Nouvelle photo
            $photo_new = $id_membre.".".$fichier_ext;
          }

          $sql='UPDATE cavalier
                SET num_licence_c= \''.$licence.'\',dt_exp_licence_c = \''.$expiration_licence.'\' ,id_niveau_c = '.$niveau_equestre.', photo_c= \''.$photo_new.'\'
                WHERE id_membre_c = '.$id_membre;
          try{
            $rs=pg_exec($idc,$sql);
          }
          catch (Exception $e) {
            echo $e->getMessage(),"\n";
          };

          //Copie photo sélectionnée sur le serveur
          if ($fichier_a_charger == 1 && !empty($photo_new)){
            move_uploaded_file($fichier_temp, $fichier_dossier_dest.$photo_new);
          }
        }
        //Pas d'enregistrement déjà existant donc insert des infos
        else{
          $sql='INSERT INTO cavalier (id_membre_c,num_licence_c,dt_exp_licence_c,id_niveau_c)
          VALUES('.$id_membre.',\''.$licence.'\',\''.$expiration_licence.'\','.$niveau_equestre.')';
          try{
            $rs=pg_exec($idc,$sql);
          }
          catch (Exception $e) {
            echo $e->getMessage(),"\n";
          };

          //Copie photo sélectionnée sur le serveur
          if ($fichier_a_charger == 1 && !empty($photo_new)){
            $photo_new = $id_membre.".".$fichier_ext;
            if(move_uploaded_file($fichier_temp, $fichier_dossier_dest.$photo_new)){
              $sql='UPDATE cavalier SET photo_c = \''.$photo_new.'\' where id_membre_c = '.$id_membre.';';
              try{
                $rs=pg_exec($idc,$sql);
              }
              catch (Exception $e) {
                echo $e->getMessage(),"\n";
              };
            }
          }
        }
      }
      else{
        if (!empty($id_membre)){
          // Suppression du cavalier
          $sql='DELETE FROM cavalier WHERE id_membre_c = '.$id_membre.';';
          try{
            $rs=pg_exec($idc,$sql);
          }
          catch (Exception $e) {
            echo $e->getMessage(),"\n";
          };

          //Suppression photo du serveur
          if (!empty($photo_old) && file_exists($photo_old)){
            unlink($photo_old);
          }
        }
      }

      // Mise à jour du centre équestre avec le responsable
      if ($statut_R==1){

        //Test si tous les champs obligatoires ont bien été renseigné
        if (!empty($responsable_CE) && !empty($id_membre)){
          //Mise à null du responsable sur l'ancien centre
          $sql='update centre_equestre set id_membre_ce = null where id_membre_ce = '.$id_membre;
          try{
            $rs=pg_exec($idc,$sql);
          }
          catch (Exception $e) {
            echo $e->getMessage(),"\n";
          };
          //Affection du responsable au nouveau centre sélectionné
          $sql='update centre_equestre set id_membre_ce = '.$id_membre.' where id_centre_ce = '.$responsable_CE;
          try{
            $rs=pg_exec($idc,$sql);
          }
          catch (Exception $e) {
            echo $e->getMessage(),"\n";
          };
        }
      }
      else{
         if (!empty($id_membre)){
          //Mise à null du responsable sur l'ancien centre
          $sql='update centre_equestre set id_membre_ce = null where id_membre_ce = '.$id_membre;
          try{
            $rs=pg_exec($idc,$sql);
          }
          catch (Exception $e) {
            echo $e->getMessage(),"\n";
          };
        }
      }

      //echo 'OK';
      header("Location: /page/profil.php");
    }
    else {$erreur = "Aucune action réalisée";}
  }

  // Si une erreur est apparue
  if(isset($erreur)) {
    echo($erreur);
  }
?>
