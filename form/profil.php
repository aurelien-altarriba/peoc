
<form name="frm"  action="/fonction/verif_profil_inscription.php" method="post" enctype="multipart/form-data">
	<div id="div_statut">
		<?php
			if ($num_licence_c!=''){
				print('<input type="checkbox" name="cc_cavalier" id="cc_cavalier" checked="checked" onclick="visible(\'c\')">Cavalier<br/>'."\n");
			}
			else {
				print('<input type="checkbox" name="cc_cavalier" id="cc_cavalier" onclick="visible(\'c\')">Cavalier<br/>'."\n");
			}
			if ($id_centre_ce!=''){
				print('<input type="checkbox" name="cc_centre" id="cc_centre" checked="checked" onclick="visible(\'r\')">Responsable d\'un centre équestre<br/>'."\n");
			}
			else {
				print('<input type="checkbox" name="cc_centre" id="cc_centre" onclick="visible(\'r\')"> Responsable d\'un centre équestre<br/>'."\n");
			}
		?>
	</div>
	<div id="div_membre">
		<?php
			// Génération du code html
			// !!! Sous firefox pb positionnement des listes de valeur sur la valeur par défaut
			print('Numéro de membre : <input type="text" name="zs_membre_m" id="zs_membre_m" value="'.$id_membre_m.'" required="required" disabled="disabled"/><br/>'."\n");
			print('Nom : <input type="text" name="zs_nom_m" value="'.$nom_m.'" required="required" /><br/>'."\n");
			print('Prénom : <input type="text" name="zs_prenom_m" value="'.$prenom_m.'" required="required" /><br/>'."\n");
			print('Date de naissance : <input type="date" name="zs_dt_naissance_m" value="'.$dt_naissance_m.'"" required="required"/><br/>'."\n");
      print('Adresse : <input type="text" name="zs_adresse_m" value="'.$adresse_m.'"/><br/>'."\n");
			print('Ville : <input type="text" name="zs_ville_m" value="'.$ville_m.'"/><br/>'."\n");
			print('Code postal : <input type="text" name="zs_cp_m" value="'.$cp_m.'"/><br/>'."\n");
			print('Pays : ');
			$sql='select id_pays_pa, nom_pa from pays order by nom_pa;';
			$rs=pg_exec($idc,$sql);
			print('<select name="zl_nom_pa" required="required">'."\n");
			print('<option value=""></option>'."\n");
			while($ligne=pg_fetch_assoc($rs)){
					if ($ligne['id_pays_pa']==$id_pays_m){
						print('<option value="'.$ligne['id_pays_pa'].'" selected="selected">'.$ligne['nom_pa'].'</option>'."\n");
					}
					else{
						print('<option value="'.$ligne['id_pays_pa'].'">'.$ligne['nom_pa'].'</option>'."\n");
					}
			}
			print('</select><br/>'."\n");
			print('Téléphone : <input type="text" name="zs_tel_m" value="'.$tel_m.'"/><br/>'."\n");
			print('Mail : <input type="email" name="zs_mail_m" value="'.$mail_m.'" required="required"/><br/>'."\n");
		?>
	</div>
	<div id="div_cavalier">
		<?php
			print('Photo : <input type="file" name="zs_photo_up"/>');
			if (!empty($photo_c)){
				$photo_c = $fichier_dossier_dest.$photo_c;
			}
			print('<img src="'.$photo_c.'" name="zs_photo_c" width="60" alt="Photo d\'identité" /><br/>'."\n");
			print('Numéro de licence : <input type="number" name="zs_num_licence_c" id="zs_num_licence_c" value="'.$num_licence_c.'"/><br/>'."\n");
			print('Date expiration licence : <input type="date" name="zs_dt_exp_licence_c" id="zs_dt_exp_licence_c" value="'.$dt_exp_licence_c.'"/><br/>'."\n");
			print('Niveau équestre : ');
			$sql='select id_niveau_ne, nom_ne from niveau_equestre order by id_niveau_ne;';
			$rs=pg_exec($idc,$sql);
			print('<select name="zl_nom_ne" id="zl_nom_ne">'."\n");
			print('<option value=""></option>'."\n");
			while($ligne=pg_fetch_assoc($rs)){
					if ($ligne['id_niveau_ne']==$id_niveau_c){
						print('<option value="'.$ligne['id_niveau_ne'].'" selected="selected">'.$ligne['nom_ne'].'</option>'."\n");
					}
					else{
						print('<option value="'.$ligne['id_niveau_ne'].'">'.$ligne['nom_ne'].'</option>'."\n");
					}
			}
			print('</select><br/>'."\n");
		?>
	</div>
	<div id="div_centre">
		<?php
			print('Centre équestre : ');
			$sql='select id_centre_ce, nom_ce from centre_equestre ';
			// Membre pas encore responsable d'un centre équestre :
			// on affiche que les centres sans responsable associé
			if ($id_centre_ce==''){
				$sql=$sql.'where id_membre_ce is null ';
			}
			// Membre déjà responsable d'un centre équestre :
			// on affiche son centre équestre ainsi que les centres sans responsable associé
			else{
			$sql=$sql.'where id_membre_ce is null or id_membre_ce='.$id_membre_m.' ';
			}
			$sql=$sql.'order by nom_ce;';
			$rs=pg_exec($idc,$sql);
			print('<select name="zl_nom_ce" id="zl_nom_ce">'."\n");
			print('<option value=""></option>'."\n");
			while($ligne=pg_fetch_assoc($rs)){
				if ($ligne['id_centre_ce']==$id_centre_ce){
					print('<option value="'.$ligne['id_centre_ce'].'" selected="selected">'.$ligne['nom_ce'].'</option>'."\n");
				}
				else{
					print('<option value="'.$ligne['id_centre_ce'].'">'.$ligne['nom_ce'].'</option>'."\n");
				}
			}
			print('</select><br/>'."\n");
		?>
	</div>
	<div id="div_connexion">
		<?php
			print('Login de connexion : <input type="text" name="zs_login_ic" id="zs_login_ic" value="'.$login_ic.'" required="required"/><br/>'."\n");
		?>
		<?php
			if ($login_ic!=''){
				print('<input type="checkbox" name="cc_mdp" id="cc_mdp" onclick="visible(\'m\')">Modifier mot de passe<br/>'."\n");
			}
			else {
				print('<input type="checkbox" name="cc_mdp" id="cc_mdp" checked="checked" onclick="visible(\'m\')">Modifier mot de passe<br/>'."\n");
			}
		?>
		<div id="div_mdp">
			<?php
				print('Mot de passe : <input type="password" name="zs_mdp_ic" value="'.$mdp_ic.'" required="required"/><br/>'."\n");
				print('Confirmation du mot de passe : <input type="password" name="zs_mdp_ic2" value="'.$mdp_ic.'" required="required"/><br/>'."\n");
				?>
			</div>
	</div>
	<div>
		<input type="submit" name="bt_submit_CM" id="bt_submit_CM" value="S'inscrire"/>
		<input type="submit" name="bt_submit_S" id="bt_submit_S" value="Supprimer"/>
	</div>
</form>
