<div class="d-grid gap-2" style="margin-bottom: 30px;">
    <a href="main.php?op=tad_gallery_cate_form" class="btn btn-info btn-block"><{$smarty.const._MA_TADGAL_ADD_CATE}></a>
</div>

<h3><{$smarty.const._MA_TADGAL_THE_ACT_IS}></h3>
<div class="form-group row mb-3">
    <label class="col-sm-3 radio">
      <input type="radio" name="op" value="del" id="del">
      <{$smarty.const._TAD_DEL}>
    </label>

    <label class="col-sm-4 radio">
      <input type="radio" name="op" value="add_good" id="add_good">
      <{$smarty.const._MA_TADGAL_ADD_GOOD}>
    </label>

    <label class="col-sm-4 radio">
      <input type="radio" name="op" value="del_good" id="del_good">
      <{$smarty.const._MA_TADGAL_DEL_GOOD}>
    </label>
</div>

<div class="form-group row mb-3">
  <label class="col-sm-4 radio">
    <input type="radio" name="op" value="move" id="move">
    <{$smarty.const._MA_TADGAL_MOVE_TO}>
  </label>
  <div class="col-sm-8">
    <select name="new_csn" onChange="check_one('move',false)"  class="form-control form-select" title="select cate">
      <{$option|default:''}>
    </select>
  </div>
</div>

<div class="form-group row mb-3">
  <label class="col-sm-4 radio">
    <input type="radio" name="op" value="add_title" id="add_title">
    <{$smarty.const._MA_TADGAL_ADD_TITLE}>
  </label>

  <div class="col-sm-8">
    <input type="text" name="add_title"  class="form-control" onClick="check_one('add_title',false)" onkeypress="check_one('add_title',false)">
  </div>
</div>

<div class="form-group row mb-3">
  <label class="col-sm-4 radio">
    <input type="radio" name="op" value="add_description" id="add_description">
    <{$smarty.const._MA_TADGAL_ADD_DESCRIPTION}>
  </label>

  <div class="col-sm-8">
    <textarea name="add_description"  class="form-control" onClick="check_one('add_description',false)" onkeypress="check_one('add_description',false)"></textarea>
  </div>
</div>

<div class="form-group row mb-3">
  <label class="col-sm-4 radio">
    <input type="radio" name="op" value="remove_tag" id="remove_tag">
    <{$smarty.const._MA_TADGAL_REMOVE_TAG}>
  </label>
</div>

<div class="form-group row mb-3">
  <label class="col-sm-4 radio">
    <input type="radio" name="op" value="add_tag" id="add_tag">
    <{$smarty.const._MA_TADGAL_TAG}>
  </label>

  <div class="col-sm-8">
    <input type="text" name="new_tag" class="form-control" placeholder="<{$smarty.const._MA_TADGAL_TAG_TXT}>" onClick="check_one('add_tag',false)" onkeypress="check_one('add_tag',false)">
  </div>
</div>

<div class="form-group row mb-3">
  <{$tag_select|default:''}>
</div>

<input type="hidden" name="csn" value="<{$csn|default:''}>">
<div class="d-grid gap-2">
  <button type="submit" class="btn btn-primary btn-block"><{$smarty.const._MA_TADGAL_GO}></button>
</div>