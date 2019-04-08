<{$toolbar}>
<{includeq file="$xoops_rootpath/modules/$xoops_dirname/templates/sub_upload_js.tpl"}>

<div id="jquery_tabs_tg_<{$now}>">
    <ul>
        <li><a href="#upload_pics"><span><{$smarty.const._MD_TADGAL_MUTI_INPUT_FORM}></span></a></li>
        <li><a href="#upload_zip_pics"><span><{$smarty.const._MD_TADGAL_ZIP_IMPORT_FORM}></span></a></li>
        <li><a href="import.php#import" id="import"><span><{$smarty.const._MD_TADGAL_PATCH_IMPORT_FORM}></span></a></li>
    </ul>


    <div id="upload_pics">

        <form action="uploads.php" method="post" id="myForm_upload_pics" enctype="multipart/form-data" onsubmit="return chk_csn(this.csn.value,this.new_csn.value);" class="form-horizontal" role="form">

        <div class="form-group">
            <label class="col-sm-2 control-label"><{$smarty.const._MD_TADGAL_CSN}></label>
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

        <div class="form-group">
            <label class="col-sm-2 control-label"><{$smarty.const._MD_TADGAL_PHOTO}></label>
            <div class="col-sm-10 controls">
            <input type="file" name="upfile[]" multiple="multiple" class="multi">          
            <div class="help-block"><{$smarty.const._MD_TADGAL_MULIT_PHOTO}></div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label"><{$smarty.const._MD_TADGAL_IS360}></label>
            <div class="col-sm-10 controls">
            <label class="radio-inline">
                <input type="radio" name="is360" value="1" <{if $is360=='1'}>checked<{/if}>><{$smarty.const._YES}>
            </label>
            <label class="radio-inline">
                <input type="radio" name="is360" value="0" <{if $is360!='1'}>checked<{/if}>><{$smarty.const._NO}>
            </label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label"></label>
            <div class="col-sm-10 controls">
            <input type="hidden" name="op" value="upload_muti_file">
            <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
            </div>
        </div>

        </form>
    </div>



    <div id="upload_zip_pics">
        <form action="uploads.php" method="post" id="myForm_upload_pics" enctype="multipart/form-data" class="form-horizontal" role="form">
        <div class="form-group">
            <label class="col-sm-3 control-label"><{$smarty.const._MD_TADGAL_ZIP}></label>
            <div class="col-sm-9 controls">
            <input type="file" name="zipfile">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label"></label>
            <div class="col-sm-10 controls">
            <input type="hidden" name="op" value="upload_zip_file">
            <button type="submit" class="btn btn-primary"><{$smarty.const._MD_SAVE}></button>
            </div>
        </div>

        </form>
    </div>
</div>