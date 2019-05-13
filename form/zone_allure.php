<?php
	require_once($_SERVER['DOCUMENT_ROOT'] ."/include/connect.php");
  $idc = connect();
?>

<table class="table" id="listeZoneAllure">
  <thead class="thead-dark">
    <tr>
			<th scope="col">Allure</th>
      <th scope="col">Point de début (lat,lng)</th>
      <th scope="col">Point de fin (lat,lng)</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody id="contenuZoneAllure">
		<?php
			$id_parcours_za = '2';

			// Liste des types d'allure
			$sql = 'SELECT nom_ta, id_type_ta
							FROM type_allure;';
      $rsList = pg_exec($idc,$sql);
      $ligneList = pg_fetch_all($rsList);

			// Récupération des informations des zones d'allure pour le parcours
			$sql="SELECT id_zone_za, id_parcours_za, id_type_za, nom_ta, ST_AsGeoJSON(ST_StartPoint(geom_za)) as point_deb, ST_AsGeoJSON(ST_EndPoint(geom_za)) as point_fin
					FROM zone_allure
					INNER JOIN type_allure on id_type_ta = id_type_za
					WHERE id_parcours_za = ".$id_parcours_za;
			$rs=pg_exec($idc,$sql);

			WHILE ($ligne = pg_fetch_assoc($rs)) {
				$id_zone_za = $ligne['id_zone_za'];
				$id_parcours_za = $ligne['id_parcours_za'];
				$id_type_za = $ligne['id_zone_za'];
				$nom_ta = $ligne['nom_ta'];
        $point_deb = json_decode($ligne['point_deb']);
        $point_fin = json_decode($ligne['point_fin']);
		?>
				<tr class="ligne_zoneAllure" id="<?php echo($id_zone_za); ?>">
				  <td scope="row">
				  	<select class="form-control" name="zl_id_type_za" id="zl_id_type_za" required>
				      <?php
                foreach ($ligneList as $key => $value) {
									if ($value['id_type_ta']==$id_type_za){
										print('<option value="'.$value['id_type_ta'].'" selected="selected">'.$value['nom_ta'].'</option>'."\n");
									}
									else{
										print('<option value="'.$value['id_type_ta'].'">'.$value['nom_ta'].'</option>'."\n");
									}
				      	}
							?>
				   	</select>
				  </td>
				  <td scope="row">
				    <input type="text" class="form-control" name="zs_latlng1" id="zs_latlng1" value="<?php echo($point_deb->coordinates[1].','.$point_deb->coordinates[0]); ?>"
				      placeholder="Lat & long point de début" required readonly>
				  </td>

				  <td scope="row">
				    <input type="text" class="form-control" name="zs_latlng2" id="zs_latlng2" value="<?php echo($point_fin->coordinates[1].','.$point_fin->coordinates[0]); ?>"
				      placeholder="Lat & long point de fin" required readonly>
				  </td>

				  </td>
				  <td scope="row">
						<button type="button" class="btn btn-outline-primary" onclick="capturer_position()">O</button>
            <button type="button" class="btn btn-outline-danger" onclick="creer_zone()">+</button>
				    <button type="button" class="btn btn-outline-danger" onclick="supprimer_zone(<?php echo($id_zone_za); ?>)">X</button>
				  </td>
				</tr>
		<?php } ?>
  </tbody>
</table>
