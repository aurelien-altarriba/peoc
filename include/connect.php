<?php
	function connect(){
		
		// Informations de connexion
		$host='postgresql-peoc.alwaysdata.net';
		$port='5432';
		$user='peoc_postgres';
		$pwd='postgres';
		$dbname='peoc_bdd';

		// Connexion
		try {
			$idc=pg_connect("host=$host port=$port user=$user password=$pwd dbname=$dbname options='--client_encoding=UTF8'");
			return($idc);
		} catch(Exception $e) {
			$erreur = ("Erreur : ". $e ." <br> ". pg_error());
			return($erreur);
		}
	}
?>
