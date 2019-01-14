<?php
ini_set('display_errors', 1);

// Connexion BDD
require_once('../include/connect.php');
$bdd = connect();

// Récupération des variables membre
$login = htmlspecialchars($_POST['zs_login_ic']);
$mdp = htmlspecialchars($_POST['zs_mdp_ic']);
$mdp2 = htmlspecialchars($_POST['zs_mdp_ic2']);
$mail = htmlspecialchars($_POST['zs_mail_m']);
$nom = htmlspecialchars($_POST['zs_nom_m']);
$prenom = htmlspecialchars($_POST['zs_prenom_m']);
$date_naissance = htmlspecialchars($_POST['zs_dt_naissance_m']);
$adresse = htmlspecialchars($_POST['zs_adresse_m']);
$ville = htmlspecialchars($_POST['zs_ville_m']);
$CP = htmlspecialchars($_POST['zs_cp_m']);
$departement = htmlspecialchars($_POST['zs_nom_d']);
$pays = htmlspecialchars($_POST['zs_nom_pa']);
$tel = htmlspecialchars($_POST['zs_tel_m']);

// Récupération des variables d'un cavalier
$licence = htmlspecialchars($_POST['zs_num_licence_c']);
$expiration_licence = htmlspecialchars($_POST['zs_dt_exp_licence_c']);
$niveau_equestre = htmlspecialchars($_POST['zs_nom_ne']);
$responsable_CE = htmlspecialchars($_POST['zs_nom_ce']);

// LOGIN
if(!empty($login) && !empty($mdp)) {
  if((strlen($login) > 3) && (strlen($login) < 50)) {
    if(!preg_match('#[^a-zA-Z0-9_]#', $login)) {

      // MOT DE PASSE
      if($mdp == $mdp2) {
        $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);

        // MAIL
        if(filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        
        } else { $erreur = 'Veuillez rentrer un mail valide'; }

      } else { $erreur = 'Les mots de passe ne correspondent pas'; }

    } else { $erreur = 'Le login ne doit pas contenir de caractères spéciaux'; }
  } else { $erreur = 'Votre login doit faire entre 3 et 50 caractères'; }
} else { $erreur = 'Tous les champs obligatoires doivent être complétés'; }

echo($erreur);
?>
