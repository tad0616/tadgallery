<{$toolbar|default:''}>

<div id="photosTab">
    <ul class="resp-tabs-list vert">
        <li><{$smarty.const._MD_TADGAL_MUTI_INPUT_FORM}></li>
        <li><{$smarty.const._MD_INPUT_FORM}></li>
        <li><{$smarty.const._MD_TADGAL_ZIP_IMPORT_FORM}></li>
        <li><{$smarty.const._MD_TADGAL_PATCH_IMPORT_FORM}></li>
    </ul>
    <div class="resp-tabs-container vert">
        <div>
            <form action="uploads.php" method="post" id="myForm_upload_pics" enctype="multipart/form-data" onsubmit="return chk_csn(this.csn.value,this.new_csn.value);" class="form-horizontal" role="form">
                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label text-sm-right text-sm-end control-label"><{$smarty.const._MD_TADGAL_CSN}></label>
                    <div class="col-sm-10 controls">
                        <select name="csn_menu" class="form-select d-inline-block" style="width: auto;"><{$tad_gallery_cate_option}></select>
                        <input type="text" name="new_csn" placeholder="<{$smarty.const._MD_TADGAL_NEW_CSN}>" class="form-control d-inline-block" style="width: 13rem;">
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label text-sm-right text-sm-end control-label"><{$smarty.const._MD_TADGAL_PHOTO}></label>
                    <div class="col-sm-10 controls">
                        <input type="file" name="upfile[]" multiple="multiple" class="multi" accept="image/*">
                        <div class="form-text text-muted help-block"><{$smarty.const._MD_TADGAL_MULIT_PHOTO}></div>
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label text-sm-right text-sm-end control-label"><{$smarty.const._MD_TADGAL_IS360}></label>
                    <div class="col-sm-10 controls">
                        <div class="form-check-inline radio-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="is360" value="1">
                                <{$smarty.const._YES}>
                            </label>
                        </div>
                        <div class="form-check-inline radio-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="is360" value="0" checked>
                                <{$smarty.const._NO}>
                            </label>
                        </div>
                    </div>

                </div>

                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label text-sm-right text-sm-end control-label"></label>
                    <div class="col-sm-10 controls">
                    <input type="hidden" name="op" value="upload_muti_file">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i>  <{$smarty.const._TAD_SAVE}></button>
                    </div>
                </div>

            </form>
        </div>
        <div>
            <form action="uploads.php" method="post" id="myForm" enctype="multipart/form-data" onsubmit="return chk_csn(this.csn.value,this.new_csn.value);" class="form-horizontal" role="form">

                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label text-sm-right text-sm-end control-label"><{$smarty.const._MD_TADGAL_CSN}></label>
                    <div class="col-sm-10 controls">
                        <select name="csn_menu" class="form-select d-inline-block" style="width: auto;"><{$tad_gallery_cate_option}></select>
                        <input type="text" name="new_csn" placeholder="<{$smarty.const._MD_TADGAL_NEW_CSN}>" class="form-control d-inline-block" style="width: 13rem;">
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label text-sm-right text-sm-end control-label"><{$smarty.const._MD_TADGAL_PHOTO}></label>
                    <div class="col-sm-10 controls">
                    <input type="file" name="image" accept="image/*">
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label text-sm-right text-sm-end control-label"><{$smarty.const._MD_TADGAL_TITLE}></label>
                    <div class="col-sm-10 controls">
                    <input type="text" name="title" class="form-control" value="<{$title|default:''}>">
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label text-sm-right text-sm-end control-label"><{$smarty.const._MD_TADGAL_IS360}></label>
                    <div class="col-sm-10 controls">
                        <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="is360" id="is3601" value="1">
                        <label class="form-check-label" for="is3601"><{$smarty.const._YES}></label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="is360" id="is3600" value="0" checked>
                        <label class="form-check-label" for="is3600"><{$smarty.const._NO}></label>
                        </div>
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label text-sm-right text-sm-end control-label"><{$smarty.const._MD_TADGAL_DESCRIPTION}></label>
                    <div class="col-sm-10 controls">
                    <textarea style="min-height: 64px;font-size: 0.75em;" name="description" class="form-control"></textarea>
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label text-sm-right text-sm-end control-label"><{$smarty.const._MD_TADGAL_TAG}></label>
                    <div class="col-sm-10 controls">
                    <input type="text" name="new_tag" class="form-control" placeholder="<{$smarty.const._MD_TADGAL_TAG_TXT}>">
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label text-sm-right text-sm-end control-label"></label>
                    <div class="col-sm-10 controls">
                    <{$tag_select|default:''}>
                    <input type="hidden" name="sn" value="<{$sn|default:''}>">
                    <input type="hidden" name="op" value="<{$op|default:''}>">
                    <button type="submit" class="btn btn-primary"><{$smarty.const._MD_TADGAL_SAVE}></button>
                    </div>
                </div>
            </form>
        </div>
        <div>
            <form action="uploads.php" method="post" id="myForm_upload_pics" enctype="multipart/form-data" class="form-horizontal" role="form">
                <div class="form-group row mb-3">
                    <label class="col-sm-3 col-form-label text-sm-right text-sm-end control-label"><{$smarty.const._MD_TADGAL_ZIP}></label>
                    <div class="col-sm-9 controls">
                    <input type="file" name="zipfile" accept="application/zip">
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label text-sm-right text-sm-end control-label"></label>
                    <div class="col-sm-10 controls">
                    <input type="hidden" name="op" value="upload_zip_file">
                    <button type="submit" class="btn btn-primary"><{$smarty.const._MD_TADGAL_SAVE}></button>
                    </div>
                </div>

            </form>
        </div>
        <div>
            <div class="alert alert-info">
                <{$smarty.const._MD_TADGAL_IMPORT_UPLOAD_TO}>
                <span style="color: #8C288C;"><{$smarty.const._TADGAL_UP_IMPORT_DIR}></span>
            </div>

            <form action="<{$xoops_url}>/modules/tadgallery/uploads.php" method="post" id="myForm" class="form-horizontal" role="form">
                <input type="hidden" name="op" value="import_tad_gallery">

                <div class="form-group row mb-3">
                    <label class="col-sm-2 control-label col-form-label text-sm-right"><{$smarty.const._MD_TADGAL_IMPORT_CSN}></label>
                    <div class="col-sm-10 controls">
                        <select name="csn_menu" class="form-select d-inline-block" style="width: auto;"><{$tad_gallery_cate_option}></select>
                        <input type="text" name="new_csn" placeholder="<{$smarty.const._MD_TADGAL_NEW_CSN}>" class="form-control d-inline-block" style="width: 13rem;">
                    </div>
                </div>

                <table class="table table-striped">
                    <tr>
                        <th></th>
                        <th><{$smarty.const._MD_TADGAL_IMPORT_FILE}></th>
                        <th><{$smarty.const._MD_TADGAL_IMPORT_DIR}></th>
                        <th><{$smarty.const._MD_TADGAL_IMPORT_DIMENSION}></th>
                        <th><{$smarty.const._MD_TADGAL_IMPORT_SIZE}></th>
                        <th><{$smarty.const._MD_TADGAL_IMPORT_STATUS}></th>
                    </tr>
                    <{$pics.pics}>
                </table>
                <div class="bar">
                    <button type="submit" class="btn btn-primary"><{$smarty.const._MD_TADGAL_UP_IMPORT}></button>
                </div>
            </form>
        </div>
    </div>
</div>
