<?php
  function verif_upload_image($fichier_temp){
    $msg='';
    $res='KO';

    // on défini les dimensions et le type du fichier
    list($fichier_larg, $fichier_haut, $fichier_type, $fichier_attr)=getimagesize($fichier_temp);

    // infos de contrôle du fichier
    $fichier_poids_max = 500000;
    $fichier_h_max = 2448;
    $fichier_l_max = 3264;

    // on vérifie s'il y a bien un fichier à uploader
    if (!empty($fichier_temp) && is_uploaded_file($fichier_temp))
    {
      // on vérifie le poids du fichier
      if (filesize($fichier_temp)<$fichier_poids_max)
      {
        // types de fichiers autorises 1=gif / 2=jpg / 3=png
        if (($fichier_type===1) || ($fichier_type===2) || ($fichier_type===3))
        {
          // on vérifie si l'image n'est pas trop grande
          if (($fichier_larg<=$fichier_l_max) && ($fichier_haut<=$fichier_h_max))
          {
            $msg='';
            $res='OK';
          }
          else $msg="Le fichier est trop grand<br />";
        }
        else $msg="Le fichier n'a pas le bon format<br />";
      }
      else $msg="Le fichier est trop lourd<br />";
    }
    else $msg="Pas de fichier à uploader<br />";

    return array($res,$msg);
  }
?>
