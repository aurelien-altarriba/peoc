<?php
ini_set('display_errors', 1);

//Include
require_once('../fonction/verif_upload_image.php');

// Connexion BDD
require_once('../include/connect.php');
$idc = connect();

//Récupération des variables membre depuis le formulaire
$mail = htmlspecialchars($_POST['zs_mail_m']);
$nom = htmlspecialchars($_POST['zs_nom_m']);
$prenom = htmlspecialchars($_POST['zs_prenom_m']);
$date_naissance = htmlspecialchars($_POST['zs_dt_naissance_m']);
$adresse = htmlspecialchars($_POST['zs_adresse_m']);
$ville = htmlspecialchars($_POST['zs_ville_m']);
$CP = htmlspecialchars($_POST['zs_cp_m']);
$departement = htmlspecialchars($_POST['zl_nom_d']);
$pays = htmlspecialchars($_POST['zl_nom_pa']);
$tel = htmlspecialchars($_POST['zs_tel_m']);

//Récupération des variables information de connexion
$login = htmlspecialchars($_POST['zs_login_ic']);
$mdp = htmlspecialchars($_POST['zs_mdp_ic']);
$mdp2 = htmlspecialchars($_POST['zs_mdp_ic2']);

//Récupération des variables d'un cavalier
$licence = htmlspecialchars($_POST['zs_num_licence_c']);
$expiration_licence = htmlspecialchars($_POST['zs_dt_exp_licence_c']);
$niveau_equestre = htmlspecialchars($_POST['zl_nom_ne']);

//Récupération des variables responsable de centre équestre
$responsable_CE = htmlspecialchars($_POST['zl_nom_ce']);

$erreur = '';

// LOGIN
if(!empty($login) && !empty($mdp)) {
  if((strlen($login) >= 3) && (strlen($login) <= 50)) {
    if(!preg_match('#[^a-zA-Z0-9_]#', $login)) {

      // MOT DE PASSE
      if($mdp === $mdp2) {
        $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);

        // MAIL
        if(filter_var($mail, FILTER_VALIDATE_EMAIL)) {
          $res = pg_query_params($idc, "SELECT count(mail_m) FROM membre WHERE mail_m = $1", array($mail));
          $mail_existe = pg_fetch_all($res)[0]['count'];
          if($mail_existe == 0) {

            // NOM
            if(strlen($nom) <= 30) {

              // PRÉNOM
              if(strlen($prenom) <= 30) {

                // DATE DE NAISSANCE
                $td=date_parse($date_naissance);
                if(checkdate($td['month'],$td['day'],$td['year'])) {

                  // ADRESSE
                  if(strlen($adresse) <= 100) {
                    if(strlen($ville) <= 80) {

                      // TÉLÉPHONE
                      if(!preg_match('/[^0-9]/', $tel)) {

                        // id_membre à récupérer par session
                        $id_membre ='';
                        if (isset($_SESSION['id_membre'])){
                          $id_membre = $_SESSION['id_membre'];
                        }
                        $id_membre_new = '';

                        //date du jour
                        $t=time();
                        $td=getdate($t);
                        $today= '\''.$td['year'].'-'.$td['mon'].'-'.$td['mday'].'\'';


                        //Gestion des photos
                        $fichier_a_charger = 0;
                        $fichier_dossier_dest = '../image/photo/';
                        $fichier_temp = '';
                        $photo_new='';

                        //Récupération de la photo actuelle du point
                        $photo_old='';
                        if (!empty($id_membre)){
                          $sql='SELECT photo_c FROM cavalier WHERE id_membre_c = '.$id_membre.';';
                          $rs=pg_exec($idc,$sql);
                          $ligne=pg_fetch_assoc($rs);
                          if (!empty($ligne['photo_c'])){
                            $photo_old=$fichier_dossier_dest.$ligne['photo_c'];
                          }
                        }

                        //Vérification du format du fichier à uploader
                        if(!empty($_FILES['zs_photo_up']['name'])){
                          $fichier_temp = $_FILES['zs_photo_up']['tmp_name'];
                          $res_verif = verif_upload_image($fichier_temp);

                          //format ok
                          if ($res_verif[0]=='OK'){
                            $fichier_a_charger = 1;
                            // extension du fichier
                            $fichier_ext = substr($_FILES['zs_photo_up']['name'],strrpos($_FILES['zs_photo_up']['name'], '.')+1);
                          }
                          //format ko
                          else {
                            echo $res_verif[1];
                            return(0);
                          }
                        }

                        //Récupération de l'action à réaliser selon le bouton exécuté
                        //bouton création/modification
                        $action = '';
                        if (isset($_POST['bt_submit_CM'])){
                          $action = $_POST['bt_submit_CM'];
                        }
                        //bouton suppression
                        else if(isset($_POST['bt_submit_S'])){
                          $action = $_POST['bt_submit_S'];
                        }

                        //Delete
                        if ($action=="Supprimer"){
                          // Suppression du membre et des enregistrements associés
                          $sql='DELETE FROM membre WHERE id_membre_m = '.$id_membre.';';
                          $rs=pg_exec($idc,$sql);

                          //Update du centre equestre
                          $sql='UPDATE centre_equestre SET id_membre_ce = null WHERE id_membre_ce = '.$id_membre;
                          $rs=pg_exec($idc,$sql);

                          //Suppression photo du serveur
                          if (!empty($photo_old)){
                            unlink($photo_old);
                          }
                        }
                        else{
                          //Insert
                          if ($action=="S'inscrire"){
                            $statut = 1;
                            //Test si tous les champs obligatoires ont bien été renseigné
                            if (!empty($nom) && !empty($prenom) && !empty($date_naissance) && !empty($pays) && !empty($mai) && !empty($login) && !empty($mdp)){
                              if (isset ($_POST['cc_cavalier'])){
                                if (!empty($licence) && !empty($expiration_licence) && !empty($niveau_equestre)){
                                }
                                else{
                                  $erreur = 'Tous les champs obligatoires doivent être complétés';
                                  $statut = 0;
                                }
                              }
                              if (isset ($_POST['cc_centre'])){
                                if (!empty($responsable_CE)){
                                }
                                else{
                                  $erreur = 'Tous les champs obligatoires doivent être complétés';
                                  $statut = 0;
                                }
                              }
                            }
                            else{
                              $erreur = 'Tous les champs obligatoires doivent être complétés';
                              $statut = 0;
                            }

                            if ($statut == 1){
                              //Insertion du membre
                              $sql='INSERT INTO membre (nom_m,prenom_m,dt_naissance_m,adresse_m,cp_m,ville_m,id_departement_m,id_pays_m,tel_m,mail_m)
                                    VALUES(\''.$nom.'\',\''.$prenom.'\',\''.$date_naissance.'\',\''.$adresse.'\',\''.$CP.'\',\''.$ville.'\',\''.$departement.'\',\''.$pays.'\',\''.$tel.'\',\''.$mail.'\')
                                    returning id_membre_m';
                              echo $sql;
                              $rs=pg_exec($idc,$sql);

                              //Récupération de l'id_membre affecté automatiquement au nouvel enregistrement
                              $ligne=pg_fetch_assoc($rs);
                              $id_membre_new=$ligne['id_membre_m'];

                              //Insertion des infos de connexion
                              $sql='INSERT INTO info_connexion (login_ic,mdp_ic,id_membre_ic,dt_inscription_ic)
                                    VALUES(\''.$login.'\',\''.$mdp.'\',\''.$id_membre_new.'\',\''.$today.'\'))';
                              $rs=pg_exec($idc,$sql);

                              //Insertion du cavalier
                              if (isset ($_POST['cc_cavalier'])){
                                if (!empty($id_membre_new)){
                                  $sql='INSERT INTO cavalier (id_membre_c,num_licence_c,dt_exp_licence_c,id_niveau_c,photo_c)
                                        VALUES('.$id_membre_new.',\''.$licence.'\',\''.$expiration_licence.'\','.$niveau_equestre.',\''.$photo.'\')';
                                  $rs=pg_exec($idc,$sql);

                                  //Copie photo sélectionnée sur le serveur
                                  if ($fichier_a_charger == 1 && !empty($photo_new)){
                                    $photo_new = $id_membre_new.".".$fichier_ext;
                                    if(move_uploaded_file($fichier_temp, $fichier_dossier_dest.$photo_new)){
                                      $sql='UPDATE cavalier SET photo_pc = \''.$photo_new.'\' WHERE id_membre_c = '.$id_membre_new.';';
                                      $rs=pg_exec($idc,$sql);
                                    }
                                  }
                                }
                              }

                              //Mise à jour du centre équestre avec le responsable
                              if (isset ($_POST['cc_centre'])){
                                if (!empty($id_membre_new)){
                                  $sql='UPDATE centre_equestre SET id_membre_ce = '.$id_membre_new.' WHERE id_centre_ce = '.$responsable_CE;
                                  $rs=pg_exec($idc,$sql);
                                }
                              }

                              echo 'OK';
                            }
                          }
                          //update
                          else if ($action=="Modifier"){
                            //Test si tous les champs obligatoires ont bien été renseigné
                            if (!empty($id_membre) && !empty($nom) && !empty($prenom) && !empty($date_naissance) && !empty($pays) && !empty($mai)){
                              // Mise à jour du membre
                              $sql="UPDATE membre
                                    SET nom_m = \''.$nom.'\', prenom_m = \''.$prenom.'\', dt_naissance_m = \''.$date_naissance.'\',adresse_m = \''.$adresse.'\',cp_m = \''.$CP.'\',ville_m = \''.$ville.'\',id_departement_m = \''.$departement.'\',id_pays_m = \''.$pays.'\',tel_m = \''.$tel.'\',mail_m = \''.$mail.'\'
                                    WHERE id_membre_m = '.$id_membre.'\'";
                              $rs=pg_exec($idc,$sql);
                            }

                            //Mise à jour du mot de passe
                            if (isset ($_POST['cc_mdp'])){
                                $cc_mdp = $_POST['cc_mdp'];
                                echo $cc_mdp;
                                if (!empty($mdp) && !empty($id_membre)){
                                  $sql='UPDATE info_connexion SET mdp_ic = \''.$mdp.'\' WHERE id_membre_ic = '.$id_membre;
                                }
                            }

                            //Mise à jour du cavalier
                            if (isset ($_POST['cc_cavalier'])){
                              $cc_cavalier = $_POST['cc_cavalier'];
                              //Test si tous les champs obligatoires ont bien été renseigné
                              if (!empty($id_membre) && !empty($licence) && !empty($expiration_licence) && !empty($niveau_equestre)){
                                $sql='SELECT count(*) AS nb FROM cavalier WHERE id_membre_c = '.$id_membre;
                                $rs=pg_exec($idc,$sql);
                                $ligne=pg_fetch_assoc($rs);
                                $nb=$ligne['nb'];

                                //Déjà un enregistrement existant donc update des infos
                                if ($nb == 1){
                                  //suppression ancienne photo du serveur
                                  if ($fichier_a_charger == 1){
                                    if (!empty($photo_old)){
                                      unlink($photo_old);
                                    }
                                    //Nouvelle photo
                                    $photo_new = $id_membre.".".$fichier_ext;
                                  }

                                  $sql='UPDATE cavalier
                                        SET num_licence_c= \''.$licence.'\',dt_exp_licence_c = \''.$expiration_licence.'\' ,id_niveau_c = '.$niveau_equestre.', photo_c= \''.$photo_new.'\'
                                        WHERE id_membre_c = '.$id_membre;
                                  $rs=pg_exec($idc,$sql);

                                  //Copie photo sélectionnée sur le serveur
                                  if ($fichier_a_charger == 1 && !empty($photo_new)){
                                    if(move_uploaded_file($fichier_temp, $fichier_dossier_dest.$photo_new)){
                                    }
                                  }
                                }

                                //Pas d'enregistrement déjà existant donc insert des infos
                                else{
                                  $sql='INSERT INTO cavalier (id_membre_c,num_licence_c,dt_exp_licence_c,id_niveau_c,photo_c)
                                  VALUES('.$id_membre.',\''.$licence.'\',\''.$expiration_licence.'\','.$niveau_equestre.',\''.$photo.'\')';
                                  $rs=pg_exec($idc,$sql);

                                  //Copie photo sélectionnée sur le serveur
                                  if ($fichier_a_charger == 1 && !empty($photo_new)){
                                    $photo_new = $id_membre.".".$fichier_ext;
                                    if(move_uploaded_file($fichier_temp, $fichier_dossier_dest.$photo_new)){
                                      $sql='UPDATE cavalier SET photo_pc = \''.$photo_new.'\' where id_membre_c = '.$id_membre.';';
                                      $rs=pg_exec($idc,$sql);
                                    }
                                  }
                                }
                              }
                            }

                            // Mise à jour du centre équestre avec le responsable
                            if (isset ($_POST['cc_centre'])){
                              $cc_centre = $_POST['centre'];
                              //Test si tous les champs obligatoires ont bien été renseigné
                              if (!empty($responsable_CE) && !empty($id_membre)){
                                //Mise à null du responsable sur l'ancien centre
                                $sql='update centre_equestre set id_membre_ce = null where id_membre_ce = '.$id_membre;
                                $rs=pg_exec($idc,$sql);
                                //Affection du responsable au nouveau centre sélectionné
                                $sql='update centre_equestre set id_membre_ce = '.$id_membre.' where id_centre_ce = '.$responsable_CE;
                                $rs=pg_exec($idc,$sql);
                              }
                            }

                            echo 'OK';
                          }
                          else {
                            $erreur = "Aucune action réalisée";
                          }
                        }

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
