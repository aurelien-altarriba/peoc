<?php
	function connect(){
		//database login info
		$host='10.11.159.20';
		$port='5432';
		$user='postgres';
		$pwd='postgres';
		$dbname='peoc_test';

		$idc=pg_connect('host='.$host.' port='.$port.' user='.$user.' password='.$pwd.' dbname='.$dbname);
		if(!$idc){
			echo "Not connected :".pg_error();
			exit;
		}
		return($idc);
	}
?>
