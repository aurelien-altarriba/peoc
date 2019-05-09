<?php
	require_once($_SERVER['DOCUMENT_ROOT'] ."/include/connect.php");

  $idc = connect();

  if (isset($_POST['id']) && isset($_POST['pos'])) {
    $id = htmlspecialchars($_POST['id']);
    $pos = htmlspecialchars($_POST['pos']);
  }
?>
<tr class="ligne_troncon" <?php if (isset($_POST['id'])) { echo('id="ligne_' . $id . '"'); }?>>
  <td scope="row">
    <input type="number" class="form-control" name="zs_num_position_t" id="zs_num_position_t"
      value="<?php echo($pos); ?>" required>
  </td>

  <td scope="row">
    <input type="number" class="form-control" name="zs_duree_estime_t" id="zs_duree_estime_t"
      placeholder="Temps en minutes" required>
  </td>

  <td scope="row">
    <select class="form-control" name="zl_id_type_t" id="zl_id_type_t" required>
      <?php
      $sql = 'SELECT nom_tt, id_type_tt
              FROM type_terrain;';

      $rs = pg_exec($idc,$sql);

      while ($ligne = pg_fetch_assoc($rs)) {
        print('<option value="'.$ligne['id_type_tt'].'">'.$ligne['nom_tt'].'</option>');
      } ?>
    </select>
  </td>

  <td scope="row">
    <select class="form-control" name="zl_id_niveau_nt" id="zl_id_niveau_nt" required>
    <?php
    $sql = 'SELECT nom_nt, id_niveau_nt
            FROM niveau_terrain;';

    $rs = pg_exec($idc,$sql);

    while ($ligne = pg_fetch_assoc($rs)) {
      print('<option value="'.$ligne['id_niveau_nt'].'">'.$ligne['nom_nt'].'</option>');
    } ?>
    </select>
  </td>
  <td scope="row">
    <button type="button" class="btn btn-outline-danger" onclick="supprimer_troncon(<?php echo($id); ?>)">X</button>
  </td>
</tr>
