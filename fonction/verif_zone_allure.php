<?php
  session_start();
  ini_set('display_errors', 1);

  //Include
  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/config.php');

  //Connexion BDD
  require_once($_SERVER['DOCUMENT_ROOT'] .'/include/connect.php');
  $idc = connect();


  //bouton chargement/création/suppression
  $action = $_POST['action'];

  $erreur = '';


  // chargement des zones allures
  if ($action==0){
    $id_parcours = $_POST['id_parcours'];

    try{
      $res = pg_query_params($idc,
        "SELECT id_zone_za_t, id_parcours_za,id_type_za,
          ST_AsGeoJSON(ST_Transform(geom_za,".$CF['srid']."))
         FROM zone_allure
         WHERE id_parcours_za = $1",
       array($id_parcours));

      $liste_za = pg_fetch_all($res);

    	// On renvoie le résultat dans un tableau encodé en JSON
      echo(json_encode($liste_za));
    }
    catch (Exception $e) {
      echo $e->getMessage(),"\n";
    };
  }


  // creation d'une zone allure
  elseif ($action==1){

    $id_parcours = $_POST['id_parcours'];
    $id_type = $_POST['id_type'];
    $point1 = explode(",",$_POST['point1']);
    $point2 = explode(",",$_POST['point2']);
    $dist = $CF['dist_allure'];

    try{
      $res = pg_query_params($idc,'SELECT id_ret, za_ret, msg_ret FROM create_allure('.$id_parcours.','.$id_type.','.$point1[0].','.$point1[1].','.$point2[0].','.$point2[1].','.$dist.')';
      $za = pg_fetch_all($res);

      // On renvoie le résultat dans un tableau encodé en JSON
      echo(json_encode($za));
    }
    catch (Exception $e) {
      echo $e->getMessage(),"\n";
    };
  }


  // suppression d'une zone allure
  elseif ($action==2 && isset ($_POST['id'])){
    $id=$_POST['id'];
    $sql='DELETE FROM zone_allure WHERE id_zone_za = '.$id.';';

    try{
      $rs=pg_exec($idc,$sql);
    }
    catch (Exception $e) {
      echo $e->getMessage(),"\n";
    };
  }




  // Si une erreur est apparue
  if(isset($erreur)) {
    echo($erreur);
  }
?>
