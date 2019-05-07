<?php
  session_start();
  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/config.php');
  $errorMSG = "";

  // NAME
  if (empty($_POST["nom"])) {
      $errorMSG = "Le nom est requis ";
  } else {
      $name = $_POST["name"];
  }
  // EMAIL
  if (empty($_POST["email"])) {
      $errorMSG .= "L'adresse email est requise  ";
  } else {
      $email = $_POST["email"];
  }
  // MESSAGE
  if (empty($_POST["message"])) {
      $errorMSG .= "Le message est requis ";
  } else {
      $message = $_POST["message"];
  }
  $EmailTo = $CF['admin_mail'];
  $Subject = "Plateforme Peoc : Nouveau message";
  // prepare email body text
  $Body = "";
  $Body .= "Name: ";
  $Body .= $name;
  $Body .= "\n";
  $Body .= "Email: ";
  $Body .= $email;
  $Body .= "\n";
  $Body .= "Message: ";
  $Body .= $message;
  $Body .= "\n";
  // send email
  $success = mail($EmailTo, $Subject, $Body, "From:".$email);
  // redirect to success page
  if ($success && $errorMSG == ""){
     echo "success";
  }else{
      if($errorMSG == ""){
          echo "Something went wrong :(";
      } else {
          echo $errorMSG;
      }
  }
?>
