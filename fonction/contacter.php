<?php
  session_start();
  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/config.php');
  $errorMSG = "";

  // NAME
  if (empty($_POST["nom"])) {
      $errorMSG = "Le nom est requis ";
  } else {
      $nom = $_POST["nom"];
  }
  // EMAIL
  if (empty($_POST["mail"])) {
      $errorMSG .= "L'adresse email est requise  ";
  } else {
      $mail = $_POST["mail"];
  }
  // MESSAGE
  if (empty($_POST["message"])) {
      $errorMSG .= "Le message est requis ";
  } else {
      $message = $_POST["message"];
  }
  $mailTo = $CF['mail_admin'];
  $sujet = "Plateforme Peoc : Nouveau message";

  if (empty($errorMSG)) {
    if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
    {
    	$passage_ligne = "\r\n";
    }
    else
    {
    	$passage_ligne = "\n";
    }
    // Déclaration des messages au format texte et au format HTML.
    $message_txt = $message;
    $message_html = "<html><head></head><body>".$message."</body></html>";

    // Création de la boundary
    $boundary = "-----=".md5(rand());

    // Création du header de l'e-mail.
    $header = "From: \"Membre PEOC\"<".$mail.">".$passage_ligne;
    $header.= "Reply-to: \"Membre PEOC\" <".$mail.">".$passage_ligne;
    $header.= "MIME-Version: 1.0".$passage_ligne;
    $header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;

    // Création du message.
    $message = $passage_ligne."--".$boundary.$passage_ligne;

    // Ajout du message au format texte.
    $message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
    $message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
    $message.= $passage_ligne.$message_txt.$passage_ligne;
    $message.= $passage_ligne."--".$boundary.$passage_ligne;

    // Ajout du message au format HTML
    $message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
    $message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
    $message.= $passage_ligne.$message_html.$passage_ligne;
    $message.= $passage_ligne."--".$boundary."--".$passage_ligne;
    $message.= $passage_ligne."--".$boundary."--".$passage_ligne;

    // Envoi de l'e-mail.
    if (@mail($mailTo,$sujet,$message,$header)) {
      echo "success";
    } else {
      echo "fail";
    }
  }
  else {
    echo $errorMSG;
  }
?>
