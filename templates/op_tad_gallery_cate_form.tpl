<div class="form-group row mb-3">
    <label class="col-sm-2 col-form-label control-label text-sm-right text-sm-end">
        <{$smarty.const._MA_TADGAL_OF_CSN}>
    </label>
    <div class="col-sm-10 controls">
        <select name="of_csn_menu" class="form-control form-select" style="width: auto;"><{$tad_gallery_cate_option|default:''}></select>
    </div>
</div>


<div class="form-group row mb-3">
    <label class="col-sm-2 col-form-label control-label text-sm-right text-sm-end">
        <{$smarty.const._MA_TADGAL_TITLE}>
    </label>
    <div class="col-sm-4">
        <input type="text" name="title" class="validate[required] form-control" value="<{$title|default:''}>" placeholder="<{$smarty.const._MA_TADGAL_TITLE}>">
    </div>

    <label class="col-sm-2 col-form-label control-label text-sm-right text-sm-end">
        <{$smarty.const._MA_TADGAL_PASSWD}>
    </label>
    <div class="col-sm-4">
        <input type="text" name="passwd" class="form-control" value="<{$passwd|default:''}>" placeholder="<{$smarty.const._MA_TADGAL_PASSWD_DESC}>">
    </div>
</div>

<div class="form-group row mb-3">
    <label class="col-sm-2 col-form-label control-label text-sm-right text-sm-end">
        <{$smarty.const._MA_TADGAL_EDIT_CATE_CONTENT}>
    </label>
    <div class="col-sm-10">
        <textarea name="content" class="form-control"><{$content|default:''}></textarea>
    </div>
</div>

<div class="form-group row mb-3">
    <label class="col-sm-2 col-form-label control-label text-sm-right text-sm-end">
        <{$smarty.const._MA_TADGAL_ENABLE_GROUP}>
    </label>
    <div class="col-sm-4">
        <{$enable_group|default:''}>
    </div>

    <label class="col-sm-2 col-form-label control-label text-sm-right text-sm-end">
        <{$smarty.const._MA_TADGAL_ENABLE_UPLOAD_GROUP}>
    </label>
    <div class="col-sm-4">
        <{$enable_upload_group|default:''}>
    </div>
</div>


<div class="form-group row mb-3">
    <label class="col-sm-2 col-form-label control-label text-sm-right text-sm-end">
        <{$smarty.const._MA_TADGAL_CATE_SHOW_MODE}>
    </label>
    <div class="col-sm-4">
        <select name="show_mode" class="form-control form-select" size=6>
        <{$cate_show_option|default:''}>
        </select>
    </div>


    <{if $csn|default:false}>
        <label class="col-sm-2 col-form-label control-label text-sm-right text-sm-end">
        <{$smarty.const._MA_TADGAL_COVER}>
        </label>

        <div class="col-sm-2">
        <img src="<{$cover_default|default:''}>" id="pic" class="rounded img-fluid" alt="<{$smarty.const._MA_TADGAL_COVER}>" style="margin-top: 20px;">
        </div>

        <div class="col-sm-2">
        <select class="form-control form-select" name="cover" size=6 onChange="document.getElementById('pic').src='<{$smarty.const.XOOPS_URL}>/uploads/tadgallery/'+ this.value" title="select cover">
            <{$cover_select|default:''}>
        </select>
        </div>
    <{/if}>
</div>

<div class="bar">
    <input type="hidden" name="sort" value="<{$sort|default:''}>">
    <input type="hidden" name="csn" value="<{$csn|default:''}>">
    <input type="hidden" name="op" value="<{$op|default:''}>">
    <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-disk" aria-hidden="true"></i>  <{$smarty.const._TAD_SAVE}></button>
</div>