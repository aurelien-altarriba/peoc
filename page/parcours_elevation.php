<!DOCTYPE html>
<html lang="en">
<head>
  <title>PÉ-OC</title>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="/css/lib/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="/css/header.css">
  <link rel="stylesheet" type="text/css" href="/css/footer.css">


  <script type="text/javascript" src="/js/lib/jquery.min.js"></script>
</head>
<body>
  <!-- HEADER -->
  <?php
  require_once($_SERVER['DOCUMENT_ROOT'] ."/include/header.php");
  $idc=connect();


    // Calcul de l'emprise totale
    $xmin=0;
    $xmax=800;
    $ymin=0;
    $ymax=500;
    $width= abs($xmax-$xmin);
    $height= abs($ymax-$ymin);
    //$viewBox=$xmin.' '.(-1*$ymax).' '.$width.' '.$height;


    print('<div style="border-width:1px;border-style:dotted;border-color:black;">'."\n");
    //print('<svg id="id_svg" width="800" height="600" viewBox="'.$viewBox.'">'."\n");
    print('<svg width="800" height="500" id="svg">');



    //Grille de positionnement
    $xgrid = $xmin;
    $ygrid =- $ymin;
    $axeW=$width/10;
    $axeH=$height/10;
    for($i=0; $i<10;$i++){
      //$ygrid = -1*($ymin+($height*$axeH/100));
      print('<line  x1='.$xgrid.' y1='.(1*$ymin).' x2='.$xgrid.' y2='.(1*$ymax).' fill="none" stroke="black" stroke-width="0.025%"/>'."\n");
      print('<line  x1='.$xmin.' y1='.$ygrid.' x2='.$xmax.' y2='.$ygrid.' fill="none" stroke="black" stroke-width="0.025%"/>'."\n");
      $xgrid = $xgrid+$axeW;
      $ygrid = $ygrid-$axeH;
    }


    print('</svg>');
    print('<div>');

  //FOOTER
  require_once($_SERVER['DOCUMENT_ROOT'] ."/include/footer.php");
  ?>
  <script type="text/javascript">

 </script>
</body>
</html>
