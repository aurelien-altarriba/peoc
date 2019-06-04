<?php
  $idc = connect();
?>

<form name="frm"  action="/fonction/verif_point_interet.php" enctype="multipart/form-data" id="form_pi">
  <div id="div_point">

      <!-- Choix de la catégorie du point d'intérêt -->
      <div class="form-group">
        <label for="zl_nom_pic">Catégorie</label>
        <select class="form-control" name="zl_nom_pic" id="zl_nom_pic">
          <?php
          $sql = 'SELECT id_categorie_pic, nom_pic
                  FROM categorie_pi';

          $rs = pg_exec($idc,$sql);

          while ($ligne = pg_fetch_assoc($rs)){
            if ($ligne['id_categorie_pic'] == $id_categorie_pi){
              print('<option value="'.$ligne['id_categorie_pic'].'" selected="selected">'.$ligne['nom_pic'].'</option>');
            }
            else{
              print('<option value="'.$ligne['id_categorie_pic'].'">'.$ligne['nom_pic'].'</option>');
            }
          }
          ?>
        </select>
      </div>

      <div class="form-group">
        <label for="zs_url_pi">URL</label>
        <input type="text" class="form-control" name="zs_url_pi" id="zs_url_pi">
      </div>

      <div class="form-group" id="photo_cavalier">
  	    <label for="zs_photo_up">Photo</label>
  	    <input type="file" class="form-control-file" name="zs_photo_up_pi" id="zs_photo_up_pi">
  	  </div>

  		<img src="<?php echo($photo_c); ?>" name="zs_photo_pi" id="zs_photo_pi" style="max-width: 300px;" />

      <div class="form-group">
        <label for="zs_description_pi">Description</label>
        <input type="text" class="form-control" name="zs_description_pi" id="zs_description_pi">
      </div>
  </div>
</form>

<script type="text/javascript">
$(document).ready(function(){

		// Fonction de lecture d'une image importée par input
		function readURL(input) {
		  if (input.files && input.files[0]) {
		    var reader = new FileReader();

		    reader.onload = function(e) {
		      $('#zs_photo_pi').attr('src', e.target.result);
		    }

		    reader.readAsDataURL(input.files[0]);
		  }
		}

		$("#zs_photo_up_pi").change(function() {
		  readURL(this);
		});
});
</script>
