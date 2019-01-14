<?php
	ini_set('display_errors',1);

	include('./../include/connect.php');
	$idc=connect();

	//get the table and fields data
	$table = $_GET['table'];
	$fields = $_GET['fields'];

	//turn fields array into formatted string
	$fieldstr = "";
	$fieldgeo = "";
  $cpt=0;
  foreach ($fields as $i => $field){
    $cpt++;
    if ($cpt == 1) {
		    $fieldstr = $fieldstr . "l.$field ";
    }
    else{
        $fieldstr = $fieldstr . ",l.$field ";
    }
	}

	//create basic sql statement
	$sql = "SELECT $fieldstr FROM $table l";

	if (!$rs=pg_exec($idc,$sql)){
		echo "A query error occured.\n";
		exit;
	}

	// echo the data back to the DOM
	while($ligne=pg_fetch_assoc($rs)){
		foreach ($ligne as $i => $attr){
			echo $attr.", ";
		}
		echo ";";
	}
?>
