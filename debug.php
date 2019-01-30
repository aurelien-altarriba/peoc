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

      h1:first-child
      {
        margin: auto;
        margin-top: 0;
        padding-top: 0.5em;
        padding-bottom: 0.6em;
        width: 9em;
        text-align: center;
        border-radius: 0 0 1em 1em;
        box-shadow: 0px 0px 3px black;
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
        margin-bottom: 5em;
      }

      .ligne:not(.avance)
      {
        border-bottom: 1px solid black;
      }

      .ligne div:not(.pastille):not(#avance)
      {
        padding: 1em 4em 1em 3em;

        border-right: 1px solid black;
      }

      .valide, .erreur
      {
        display: flex;
        align-items: center;
        font-weight: bold;
      }

      .valide
      {
        color: green;
      }

      .erreur
      {
        color: red;
      }

      .pastille
      {
        width: 15px;
        height: 15px;
        border-radius: 100%;
        border: 2px solid grey;
        margin-right: 0.5em;
      }

      .valide .pastille {
        background-color: green;
      }

      .erreur .pastille {
        background-color: red;
      }

      #copyright
      {
        position: fixed;
        bottom: 0;
        right: 0;
        padding: 0.5em;

        font-size: 0.9em;
        font-family: Verdana, arial;
        color: #333;
        text-decoration: none;

        box-shadow: 0px 0px 3px black;
        border-top-left-radius: 1em;
        background-color: #DDD;
      }

      #lienAvance h2
      {
        color: black;
        display: flex;
        align-items: baseline;
      }

      #avance
      {
        display: none;
      }

      #avance:target
      {
        display: block;
      }

      table
      {
        text-align: left;
        box-shadow: none !important;
      }

      table td
      {
        border: 0;
      }

      .key
      {
        font-weight: bold;
        border: 0;
        text-align: left;
        background-color: #EEE;
        border-bottom: 1px solid #CCC;
      }

      .value
      {
        border-bottom: 1px dotted #CCC;
      }

      .key:last-child
      {
        border-bottom: 1px dashed black;
      }

      .vide
      {
        border-left: 1px #444 dashed;
      }
    </style>
  </head>

  <body>

  <h1>Debug web</h1>

  <!-- SESSION -->
  <h2>Session  <span class="ann">> $_SESSION</span></h2>
  <div class="ligne">
    <div>
      <h4>Données éclaircies</h4>
      <table id="tabSession">
        <?php
        foreach ($_SESSION as $key => $value) {
          echo('<tr><td class="key">[\''. $key .'\']</td>');
          if(is_array($value)) {

            foreach ($value as $key2 => $value2) {
              echo('<tr><td class="vide"></td><td class="key">[\''. $key2 .'\']</td>');
              if(is_array($value2)) {

                foreach ($value2 as $key3 => $value3) {
                  echo('<tr><td class="vide"></td><td class="vide"></td><td class="key">[\''. $key3 .'\']</td>');
                  if(is_array($value3)) {

                    foreach ($value3 as $key4 => $value4) {
                      echo('<tr><td class="vide"></td><td class="vide"></td><td class="vide"></td><td class="key">[\''. $key4 .'\']</td>');
                      if(is_array($value4)) {
                        echo('<td class="value">[TABLEAU]</td>');
                      } else {
                        echo('<td class="value">'. $value4 .'</td>');
                      }
                      echo('</tr>');
                    }

                  } else {
                    echo('<td class="value">'. $value3 .'</td>');
                  }
                  echo('</tr>');
                }

              } else {
                echo('<td class="value">'. $value2 .'</td>');
              }
              echo('</tr>');
            }

          } else {
            echo('<td class="value">'. $value .'</td>');
          }
          echo('</tr>');
        }
        ?>
      </table>
    </div>

    <div>
      <h4>Données techniques <span class="ann">> var_dump()</span></h4>
      <pre>
      <?php
        var_dump($_SESSION);
      ?>
      </pre>
    </div>
  </div>

  <!-- SERVEUR -->
  <h2>Serveur et client <span class="ann">> $_SERVER</span></h2>
  <div class="ligne">
    <div>
      <h4>SERVEUR</h4>
        <?php
        echo('Nom du serveur : <b>'. $_SERVER['SERVER_NAME'] .'</b>');
        echo('<br>Port du serveur : <b>'. $_SERVER['SERVER_PORT'] .'</b>');
        echo('<br>Type de serveur : <b>'. $_SERVER['SERVER_SOFTWARE'] .'</b>');
        echo('<br>Version de PHP : <b>'. PHP_VERSION .'</b>');
        echo('<br>Racine du serveur : <b>'. $_SERVER['DOCUMENT_ROOT'] .'</b>');
        ?>
    </div>

    <div>
      <h4>CLIENT</h4>
        <?php
        echo('Informations du client : <b>'. $_SERVER['HTTP_USER_AGENT'] .'</b>');
        echo('<br>Port du client : <b>'. $_SERVER['REMOTE_PORT'] .'</b>');
        ?>
    </div>
  </div>

  <!-- BDD -->
  <h2>Base de données PostgreSQL<span class="ann">> pg_connect()</span></h2>
  <div class="ligne">
    <div>
        <?php
        if(pg_connection_status($bdd) === PGSQL_CONNECTION_OK) {
          echo '<span class="valide"><div class="pastille"></div>Connexion active sur la base de données</span>';
        } else {
          echo '<span class="erreur"><div class="pastille"></div>Non connecté à la base de données</span>';
        }

        echo('<br>Version PostgreSQL : <b>'. pg_parameter_status($bdd, "server_version") .'</b>');
        echo('<br>Encodage du serveur : <b>'. pg_parameter_status($bdd, "server_encoding") .'</b>');
        echo('<br>Encodage du client : <b>'. pg_parameter_status($bdd, "client_encoding") .'</b>');
        ?>
    </div>
  </div>

  <a href="#reduit"></a>

  <!-- PHP -->
  <a href="#avance" id="lienAvance"><h2>Informations avancées <span class="ann">> phpinfo()</span></h2></a>
  <div class="ligne avance">
    <div id="avance">
        <?php
        phpinfo(INFO_GENERAL | INFO_CONFIGURATION | INFO_ENVIRONMENT | INFO_VARIABLES | INFO_MODULES);
        ?>
    </div>
  </div>

  <a id="copyright" href="https://aurelienaltarriba.fr" target="_blank">© 2019 Aurélien ALTARRIBA</a>
  <!-- © 2019 Aurélien ALTARRIBA -->

  <script type="text/javascript">
    document.getElementById("lienAvance").addEventListener('click', function() {
      if(window.location.hash == "#avance") {
        document.getElementById('lienAvance').href = "#reduit";
      } else {
        document.getElementById('lienAvance').href = "#avance";
      }
    });

    var tabSession = document.getElementById('tabSession');

    if(tabSession.getElementsByTagName('tr').length == 0) {
      tabSession.innerHTML = "Aucune variable de SESSION instanciée";
    }
  </script>
  </body>
</html>
