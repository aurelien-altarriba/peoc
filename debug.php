<?php
ini_set('display_errors', 1);
session_start();
include('include/connect.php');
$bdd = connect();
?>
<!DOCTYPE html>
  <html>
  <head>
    <title>DEBUG</title>
    <meta charset="utf-8">

    <style type="text/css">
      body
      {
        margin: 0;
      }

      h2
      {
        margin-left: 1em;
        margin-bottom: 0;
        padding-bottom: 0.5em;
        padding-top: 0.3em;
        padding-left: 1em;

        border-left: 5px solid #444;
        border-bottom: 1px solid black;
        font-size: 1.6em;
      }

      h4
      {
        border-bottom: 1px solid black;
        padding-bottom: 0.5em;
        font-size: 1.2em;
      }

      .ann
      {
        font-size: 0.7em;
        color: #666;
      }

      .ligne
      {
        display: flex;
        background-color: #FAFAFA;
        border-bottom: 1px solid black;
        margin-bottom: 5em;
      }

      .ligne div
      {
        padding: 1em 4em 1em 3em;

        border-right: 1px solid black;
      }
    </style>
  </head>

  <body>

  <!-- SESSION -->
  <h2>SESSION  <span class="ann">> $_SESSION</span></h2>
  <div class="ligne">
    <div>
      <h4>Types et données</h4>
      <pre>
        <?php
        var_dump($_SESSION);
        ?>
      </pre>
    </div>
    <div>
      <h4>Données affichées proprement</h4>
      <pre>
        <?php
        print_r($_SESSION);
        ?>
      </pre>
    </div>
  </div>

  <h2>Base de données <span class="ann">> connect()</span></h2>
  <div class="ligne">
    <div>
      <h4>Types et données</h4>
        <?php
        if(pg_connection_status($bdd) === PGSQL_CONNECTION_OK) {
          echo 'Connexion active sur la base de données';
        } else {
          echo 'Non connecté à la base de données';
        }

        echo('<br>Encodage : <b>'. pg_parameter_status($bdd, "server_encoding") .'</b>');
        ?>
    </div>
  </div>
  </body>
</html>
