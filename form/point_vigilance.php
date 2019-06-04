<?php
  $idc = connect();
?>

<form enctype="multipart/form-data" id="form_pv">
  <div class="form-group">
    <label for="zs_dt_debut_pv">Date de début</label>
    <input class="form-control" type="date" name="zs_dt_debut_pv" id="zs_dt_debut_pv"/>
  </div>

  <div class="form-group">
    <label for="zs_dt_fin_pv">Date de fin</label>
    <input class="form-control" type="date" name="zs_dt_fin_pv" id="zs_dt_fin_pv"/>
  </div>

  <!-- Choix de la catégorie du point de vigilance -->
  <div class="form-group">
    <label for="zs_categorie_pv">Catégorie</label>
    <select class="form-control" name="zs_categorie_pv" id="zs_categorie_pv">
      <?php
      $sql='SELECT id_categorie_pvc, nom_pvc
            FROM categorie_pv';

      $rs=pg_exec($idc,$sql);

        while($ligne=pg_fetch_assoc($rs)){
          print('<option value="'.$ligne['id_categorie_pvc'].'">'.$ligne['nom_pvc'].'</option>');
        }
      ?>
    </select>
  </div>

  <div class="form-group" id="photo_cavalier">
    <label for="zs_photo_up">Photo</label>
    <input type="file" class="form-control-file" name="zs_photo_up_pv" id="zs_photo_up_pv">
  </div>

  <img src="<?php echo($photo_c); ?>" name="zs_photo_pv" id="zs_photo_pv" style="max-width: 300px;" />

  <div class="form-group">
    <label for="zs_categorie_pv">Description</label>
    <textarea class="form-control" name="zs_description_pv" id="zs_description_pv" rows="5"></textarea>
  </div>
</form>

<script type="text/javascript">
$(document).ready(function(){

		// Fonction de lecture d'une image importée par input
		function readURL(input) {
		  if (input.files && input.files[0]) {
		    var reader = new FileReader();

		    reader.onload = function(e) {
		      $('#zs_photo_pv').attr('src', e.target.result);
		    }

		    reader.readAsDataURL(input.files[0]);
		  }
		}

		$("#zs_photo_up_pv").change(function() {
		  readURL(this);
		});
});
</script>
