<?php
	function connect(){
		//database login info
		$host='10.11.159.20';
		$port='5432';
		$user='postgres';
		$pwd='postgres';
		$dbname='peoc';

		try {
			$idc=pg_connect("host=$host port=$port user=$user password=$pwd dbname=$dbname options='--client_encoding=UTF8'");
			return($idc);
		} catch(Exception $e) {
			$erreur = ("Erreur : ". $e ." <br> ". pg_error());
			return($erreur);
		}
	}
?>
