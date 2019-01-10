<?php

	function jour_fr_vl($numj){
		if($numj==0){
			$nomj='Dimanche1';
		}
		else{
			if ($numj==1) {
				$nomj='Lundi1';	
			}
			else {
				if ($numj==2) {
					$nomj='Mardi1';
				} 
				else {
					if ($numj==3) {
						$nomj='Mercredi1';
					}
					else {
						if ($numj==4) {
							$nomj='Jeudi1';
						} 
						else {
							if ($numj==5) {
								$nomj='Vendredi1';
							} 
							else {
								$nomj='Samedi1';
							}
						}
					}
				}
			}
		}
		return($nomj);
	}
	
	
	function jour_fr_v2($numj){
		switch($numj) {
			case 0:
				$nomj='Dimanche2'; break;
			case 1:
				$nomj='Lundi2'; break;
			case 2:
				$nomj='Mardi2'; break;
			case 3:
				$nomj='Mercredi2'; break;
			case 4:
				$nomj='Jeudi2'; break;
			case 5:
				$nomj='Vendredi2'; break;
			default :
				$nomj='Erreur'; break;
		}
		return($nomj);
	}
	
	
	function jour_fr_v3($numj){
		$tab=array('Dimanche3','Lundi3','Mardi3','Mercedi3','Jeudi3','Vendredi3','Samedi3');
		$nomj=$tab[$numj];
		return($nomj);	
	}

	function mois_fr_v1($numm){
		$tab=array('janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre');
		$nomj=$tab[$numm];
		return($nomj);	
	}

	function mois_fr_v2($numm){
		$tab=array(1=>'janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre');
		$nomj=$tab[$numm];
		return($nomj);	
	}
	
	// date au format chinois 2017-12-21 doit ressortir une date au format français 21/12/2017
	function date_ch2fr($dt_ch){
		$t=explode('-',$dt_ch);
		$dt_fr = $t[2].'/'.$t[1].'/'.$t[0];
		return($dt_fr);
	}
	
?>
