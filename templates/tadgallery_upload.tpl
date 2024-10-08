<{$toolbar|default:''}>
<{include file="$xoops_rootpath/modules/$xoops_dirname/templates/sub_upload_js.tpl"}>
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
                    <label class="col-sm-2 col-form-label text-sm-right control-label"><{$smarty.const._MD_TADGAL_CSN}></label>
                    <div class="col-sm-10 controls">
                        <select name="csn_menu[0]" id="m_csn_menu0" class="m_csn_menu"><option value=''></option></select>
                        <select name="csn_menu[1]" id="m_csn_menu1" class="m_csn_menu" style="display: none;"></select>
                        <select name="csn_menu[2]" id="m_csn_menu2" class="m_csn_menu" style="display: none;"></select>
                        <select name="csn_menu[3]" id="m_csn_menu3" class="m_csn_menu" style="display: none;"></select>
                        <select name="csn_menu[4]" id="m_csn_menu4" class="m_csn_menu" style="display: none;"></select>
                        <select name="csn_menu[5]" id="m_csn_menu5" class="m_csn_menu" style="display: none;"></select>
                        <select name="csn_menu[6]" id="m_csn_menu6" class="m_csn_menu" style="display: none;"></select>
                        <input type="text" name="new_csn" placeholder="<{$smarty.const._MD_TADGAL_NEW_CSN}>" style="width: 200px;">
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label text-sm-right control-label"><{$smarty.const._MD_TADGAL_PHOTO}></label>
                    <div class="col-sm-10 controls">
                        <input type="file" name="upfile[]" multiple="multiple" class="multi" accept="image/*">
                        <div class="form-text text-muted help-block"><{$smarty.const._MD_TADGAL_MULIT_PHOTO}></div>
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label text-sm-right control-label"><{$smarty.const._MD_TADGAL_IS360}></label>
                    <div class="col-sm-10 controls">
                        <div class="form-check-inline radio-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="is360" value="1" <{if $is360=='1'}>checked<{/if}>>
                                <{$smarty.const._YES}>
                            </label>
                        </div>
                        <div class="form-check-inline radio-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="is360" value="0" <{if $is360!='1'}>checked<{/if}>>
                                <{$smarty.const._NO}>
                            </label>
                        </div>
                    </div>

                </div>

                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label text-sm-right control-label"></label>
                    <div class="col-sm-10 controls">
                    <input type="hidden" name="op" value="upload_muti_file">
                    <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
                    </div>
                </div>

            </form>
        </div>
        <div>
            <form action="uploads.php" method="post" id="myForm" enctype="multipart/form-data" onsubmit="return chk_csn(this.csn.value,this.new_csn.value);" class="form-horizontal" role="form">

                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label text-sm-right control-label"><{$smarty.const._MD_TADGAL_CSN}></label>
                    <div class="col-sm-10 controls">
                        <select name="csn_menu[0]" id="csn_menu0" class="csn_menu"><option value=''></option></select>
                        <select name="csn_menu[1]" id="csn_menu1" class="csn_menu" style="display: none;"></select>
                        <select name="csn_menu[2]" id="csn_menu2" class="csn_menu" style="display: none;"></select>
                        <select name="csn_menu[3]" id="csn_menu3" class="csn_menu" style="display: none;"></select>
                        <select name="csn_menu[4]" id="csn_menu4" class="csn_menu" style="display: none;"></select>
                        <select name="csn_menu[5]" id="csn_menu5" class="csn_menu" style="display: none;"></select>
                        <select name="csn_menu[6]" id="csn_menu6" class="csn_menu" style="display: none;"></select>
                        <input type="text" name="new_csn" placeholder="<{$smarty.const._MD_TADGAL_NEW_CSN}>" style="width: 200px;">
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label text-sm-right control-label"><{$smarty.const._MD_TADGAL_PHOTO}></label>
                    <div class="col-sm-10 controls">
                    <input type="file" name="image" accept="image/*">
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label text-sm-right control-label"><{$smarty.const._MD_TADGAL_TITLE}></label>
                    <div class="col-sm-10 controls">
                    <input type="text" name="title" class="form-control" value="<{$title|default:''}>">
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label text-sm-right control-label"><{$smarty.const._MD_TADGAL_IS360}></label>
                    <div class="col-sm-10 controls">
                        <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="is360" id="is3601" value="1" <{if $is360=='1'}>checked<{/if}>>
                        <label class="form-check-label" for="is3601"><{$smarty.const._YES}></label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="is360" id="is3600" value="0" <{if $is360!='1'}>checked<{/if}>>
                        <label class="form-check-label" for="is3600"><{$smarty.const._NO}></label>
                        </div>
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label text-sm-right control-label"><{$smarty.const._MD_TADGAL_DESCRIPTION}></label>
                    <div class="col-sm-10 controls">
                    <textarea style="min-height: 64px;font-size: 0.75em;" name="description" class="form-control"></textarea>
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label text-sm-right control-label"><{$smarty.const._MD_TADGAL_TAG}></label>
                    <div class="col-sm-10 controls">
                    <input type="text" name="new_tag" class="form-control" placeholder="<{$smarty.const._MD_TADGAL_TAG_TXT}>">
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label text-sm-right control-label"></label>
                    <div class="col-sm-10 controls">
                    <{$tag_select|default:''}>
                    <input type="hidden" name="sn" value="<{$sn|default:''}>">
                    <input type="hidden" name="op" value="<{$op|default:''}>">
                    <button type="submit" class="btn btn-primary"><{$smarty.const._MD_SAVE}></button>
                    </div>
                </div>
            </form>
        </div>
        <div>
            <form action="uploads.php" method="post" id="myForm_upload_pics" enctype="multipart/form-data" class="form-horizontal" role="form">
                <div class="form-group row mb-3">
                    <label class="col-sm-3 col-form-label text-sm-right control-label"><{$smarty.const._MD_TADGAL_ZIP}></label>
                    <div class="col-sm-9 controls">
                    <input type="file" name="zipfile" accept="application/zip">
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label class="col-sm-2 col-form-label text-sm-right control-label"></label>
                    <div class="col-sm-10 controls">
                    <input type="hidden" name="op" value="upload_zip_file">
                    <button type="submit" class="btn btn-primary"><{$smarty.const._MD_SAVE}></button>
                    </div>
                </div>

            </form>
        </div>
        <div>
            <{$import_form|default:''}>
        </div>
    </div>
</div>
