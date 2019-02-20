<?php
  session_start();
  ini_set('display_errors', 1);

  //Include
  require_once('../include/config.php');

  //Connexion BDD
  require_once('../include/connect.php');
  $idc = connect();


  //RECUPERATION DE L'ACTION A REALISER (selon le bouton exécuté)
  //bouton insertion/suppression
  $action = '';
  if (isset ($_POST['bt_submit_CM'])){
    if ($_POST['bt_submit_CM']=='Créer'){
      $action=1;
    }
    else if($_POST['bt_submit_CM']=='Supprimer'){
      $action=2;
    }
  }


  //RECUPERATION DES DONNEES DU FORMULAIRE
  $id_parcours_e = '';
  $id_membre_e = '';
  if (isset($_SESSION['parcours'])){
    $id_parcours_e = $_SESSION['parcours'];
  }
  if (isset($_SESSION['membre']['id'])){
    $id_membre_e = $_SESSION['membre']['id'];
  }
  $date_jour = htmlspecialchars($_POST['zs_dt_jour_e ']);
  $note_e = htmlspecialchars($_POST['zs_note_e']);
  $duree_reel_e = htmlspecialchars($_POST['zs_duree_reel_e']);
  $commentaire_e = pg_escape_string($_POST['zs_commentaire_e']);

  $erreur = '';


  //VERIFICATION DES CHAMPS OBLIGATOIRES
  //cas de l'insertion
  if ($action==1 && (empty($id_parcours_e) || empty($id_membre_e) || empty($date_jour) || empty($note_e) || empty($duree_reel_e) || empty($commentaire_e))){
    $erreur = "Tous les champs obligatoires doivent être saisis";
    echo $erreur;
    return(0);
  }

  //cas de la suppression
  if ($action==2 && (empty($id_parcours_e) || empty($id_membre_e) || empty($date_jour))) {
      $erreur = "Tous les champs obligatoires doivent être saisis";
      echo $erreur;
      return(0);
    }
  }


  //VERIFICATION DU FORMAT DES DONNEES
  if ($action==1){
    //Note
    if(preg_match('#[^0-9]#', $note_e)) {
      $erreur = 'Le numéro de position ne doit contenir que des chiffres';
      echo $erreur;
      return(0);
    }

    //Date d'éffectuation du parcours
    $td=date_parse($date_jour);
    if(!checkdate($td['month'],$td['day'],$td['year'])) {
      $erreur = 'Erreur de format de la date à laquelle la parcours a été effectué';
      echo $erreur;
      return(0);
    }

    //Description
    if(strlen($commentaire_e) > 2000) {
      $erreur = 'Le commentaire doit faire au maximum 2000 caractères';
      echo $erreur;
      return(0);
    }
  }


  // EXECUTION DE L'ACTION
  //Delete
  if ($action==2){
    // Suppression du point en base
    $sql='DELETE FROM effectue WHERE id_membre_e = '.$id_membre_e.' AND id_parcours_e = '.$id_parcours_e.';';
    try{
      $rs=pg_exec($idc,$sql);
    }
    catch (Exception $e) {
      echo $e->getMessage(),"\n";
    };
    //echo 'OK';
    header("Location: ./../page/parcours.php");
  }
  //Insert
  else if ($action==1){
    //Insertion du point en base
    $sql='INSERT INTO effectue (id_membre_e,id_parcours_e,dt_jour_e,note_e,duree_reel_e,commentaire_e)
          VALUES ('.$id_membre_e.','.$id_parcours_e.',\''.$date_jour.'\','.$note_e.','.$duree_reel_e.',\''.$commentaire_e.'\')';
    try{
      $rs=pg_exec($idc,$sql);
    }
    catch (Exception $e) {
      echo $e->getMessage(),"\n";
    };
    //echo 'OK';
    header("Location: ./../page/parcours.php");
    }
  else { $erreur = "Aucune action réalisée"; }

  // Si une erreur est apparue
  if(isset($erreur)) {
    echo($erreur);
  }
?>
