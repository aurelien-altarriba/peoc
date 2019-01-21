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
  if((strlen($login) >= 3) && (strlen($login) <= 50)) {
    if(!preg_match('#[^a-zA-Z0-9_]#', $login)) {

      // MOT DE PASSE
      if($mdp === $mdp2) {
        $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);

        // MAIL
        if(filter_var($mail, FILTER_VALIDATE_EMAIL)) {
          $res = pg_query_params($bdd, "SELECT count(mail_m) FROM membre WHERE mail_m = $1", array($mail));
          $mail_existe = pg_fetch_all($res)[0]['count'];
          if($mail_existe == 0) {

            // NOM
            if(strlen($nom) <= 30) {

              // PRÉNOM
              if(strlen($prenom) <= 30) {

                // DATE DE NAISSANCE
                if(checkdate($date_naissance)) {

                  // ADRESSE
                  if(strlen($adresse) <= 100) {
                    if(strlen($ville) <= 80) {

                      // TÉLÉPHONE
                      if(preg_match('/[^0-9]/', $tel)) {

// A compléter ici avec insert update delete

                      } else { $erreur = 'Votre numéro de téléphone ne doit contenir que des chiffres'; }
                    } else { $erreur = 'La ville doit faire au maximum 80 caractères'; }
                  } else { $erreur = 'L\'adresse doit faire au maximum 100 caractères'; }
                } else { $erreur = 'Erreur de format à la date de naissance'; }
              } else { $erreur = 'Votre prénom doit faire au maximum 30 caractères'; }
            } else { $erreur = 'Votre nom doit faire au maximum 30 caractères'; }
          } else { $erreur = 'Le mail est déjà utilisé sur un autre compte'; }
        } else { $erreur = 'Veuillez rentrer un mail valide'; }
      } else { $erreur = 'Les mots de passe ne correspondent pas'; }
    } else { $erreur = 'Le login ne doit pas contenir de caractères spéciaux'; }
  } else { $erreur = 'Votre login doit faire entre 3 et 50 caractères'; }
} else { $erreur = 'Tous les champs obligatoires doivent être complétés'; }

// Si une erreur est apparue
if(isset($erreur)) {
  echo($erreur);
}
?>
