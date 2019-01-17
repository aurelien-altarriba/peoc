<?php
// Destruction de la session
session_start();
session_destroy();

// Redirection
header('Location: /');
?>
