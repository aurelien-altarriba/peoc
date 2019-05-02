<?php
session_start();

// Connexion BDD
require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
$bdd = connect();

// On récupère les valeurs
$login = htmlspecialchars($_POST['login']);
$mdp = htmlspecialchars($_POST['mdp']);

// Si le login est bon
if(!empty($login) && !empty($mdp)) {
  if((strlen($login) >= 3) && (strlen($login) <= 50)) {
    if(!preg_match('#[^a-zA-Z0-9_]#', $login)) {

      // Requête pour récupérer le mdp hashé de l'utilisateur
      $res = pg_query_params($bdd, "SELECT id_membre_ic, mdp_ic FROM info_connexion WHERE login_ic = $1", array($login));
      $mdp_bdd = pg_fetch_all($res)[0]['mdp_ic'];

      // Si un utilisateur a été trouvé avec ce login
      if(!empty($mdp_bdd)){

        // On vérifie si le mot de passe correspond
        if (password_verify($mdp, $mdp_bdd)) {

          // Si oui, on stocke l'id du membre connecté
          $_SESSION['membre']['id'] = pg_fetch_all($res)[0]['id_membre_ic'];
          echo('OK');

        } else { $erreur = 'Mot de passe incorrect'; }
      } else { $erreur = 'Le login n\'existe pas'; }
    } else { $erreur = 'Le login ne peut pas contenir de caractères spéciaux'; }
  } else { $erreur = 'Votre login doit faire entre 3 et 50 caractères'; }
} else { $erreur = 'Tous les champs doivent être complétés'; }

// Si une erreur est apparue
if(isset($erreur)) {
  echo($erreur);
}
?>
