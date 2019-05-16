<form onsubmit="return(false);" id="bloc_commentaire">
  <div class="form-group">
    <h4>Note</h4>
    <div class="note_bloc_flex">
      <div class="note_comm"></div>
      <div class="note_comm_texte">3 / 5</div>
    </div>
  </div>

  <div class="form-group">
    <label for="zs_note_e">Durée réelle en minutes</label>
    <input type="text" name="zs_duree_reel_e" id="zs_duree_reel_e"/>
  </div>

  <div class="form-group">
    <label for="zs_note_e">Commentaire</label>
    <textarea class="form-control" name="zs_commentaire_e" id="zs_commentaire_e" rows="5"></textarea>
  </div>

  <button class="btn btn-primary btn-block" type="submit" name="bt_submit_comm" id="bt_submit_comm"/>Envoyer le commentaire</button>
</form>

<script type="text/javascript">
var options_comm = {
  max_value: 5,
  step_size: 1,
  initial_value: 3,
  selected_symbol_type: 'cheval',
  symbols: {
    cheval: {
      base: '<span class="note_base">🐎</span>',
      hover: '<span class="note_hover">🐎</span>',
      selected: '<span class="note_selected">🐎</span>',
    }
  },
  cursor: 'pointer',
  readonly: false,
}

$('.note_comm').rate(options_comm);

$(".note_comm").on("afterChange", function(ev, data){
    console.log(data.from, data.to);
    $('.note_comm_texte').html(data.to + " / 5");
});
</script>
