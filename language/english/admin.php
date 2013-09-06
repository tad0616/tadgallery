<?php
//  ------------------------------------------------------------------------ //
// This module was written by Tad
// Release date: 2008-03-23
// $Id: admin.php,v 1.3 2008/05/05 03:22:37 tad Exp $
// ------------------------------------------------------------------------- //

include_once "../../tadtools/language/{$xoopsConfig['language']}/admin_common.php";

define("_BACK_MODULES_PAGE","Back to module homepage");
define("_BP_DEL_CHK","Are you sure to delete this data?");
define("_BP_FUNCTION","Function");
define("_BP_EDIT","Edit");
define("_BP_DEL","Delete");
define("_BP_ADD","Add New Data");
define("_TAD_NEED_TADTOOLS","Need modules/tadtools. You can download tadtools from <a href='http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50' target='_blank'>Tad's web</a>.");

define("_MA_INPUT_CATE_FORM","Photo Album management");
define("_MA_SAVE","Save");

//cate.php
define("_MA_TADGAL_SN","Serial number");
define("_MA_TADGAL_CSN","Photo album");
define("_MA_TADGAL_CTITLE","Album title");
define("_MA_TADGAL_DESCRIPTION","Photo description");
define("_MA_TADGAL_FILENAME","File name");
define("_MA_TADGAL_SIZE","Size");
define("_MA_TADGAL_TYPE","Type");
define("_MA_TADGAL_UID","Provider");
define("_MA_TADGAL_POST_DATE","Post date");
define("_MA_TADGAL_COUNTER","View Count");
define("_MA_TADGAL_PASSWD","Album password");
define("_MA_TADGAL_PASSWD_DESC","(Optional)");
define("_MA_TADGAL_CATE_ADVANCE_SETUP","Show advanced settings");
define("_MA_TADGAL_CATE_HIDE_ADVANCE_SETUP","Hide advanced settings");
define("_MA_TADGAL_CATE_SHL_SETUP","Show anti-hotlink setting");
define("_MA_TADGAL_CATE_HIDE_SHL_SETUP","Hide anti-hotlink setting");
define("_MA_TADGAL_CATE_POWER_SETUP","Permission setting");
define("_MA_TADGAL_CATE_SHOW_MODE","Display mode");
define("_MA_TADGAL_CATE_SHOW_MODE_1","Thumbnails mode(default)");
define("_MA_TADGAL_CATE_SHOW_MODE_2","3D Gallery mode");
define("_MA_TADGAL_CATE_SHOW_MODE_3","Slideshow mode");
define("_MA_TADGAL_COVER","Cover image");
define("_MD_TADGAL_COVER","Select cover image");

define("_MA_TADGAL_TITLE","Title");
define("_MA_TADGAL_CREATOR","Provider");
define("_MA_TADGAL_LOCATION","Image location");
define("_MA_TADGAL_IMAGE","Thumbnails location");
define("_MA_TADGAL_INFO","Download to");
define("_MA_TADGAL_LSN","Serial number");
define("_MA_TADGAL_OF_LSN","Item");
define("_MA_TADGAL_ENABLE_GROUP","\"<font color=blue>VIEW</font>\" enabled group");
define("_MA_TADGAL_ENABLE_UPLOAD_GROUP","\"<font color=red>UPLOAD</font>\"enabled group");
define("_MA_TADGAL_SORT","Sort");
define("_MA_TADGAL_ALL_OK","All Groups");
define("_MA_TADGAL_LIST_CATE","Category List");
define("_MA_TADGAL_CANT_OPEN","Can not creat \"%s\" ");
define("_MA_TADGAL_CANT_WRITE","Can not write \"%s\" ");
define("_MA_TADGAL_SHOW_DATE","(Post date: %s)");
define("_MA_TADGAL_CATE_SELECT","Not classified");
define("_MA_TADGAL_XML_OK","\"%s\" playlist completed!");
define("_MA_TADGAL_NO_DIRNAME","No directory name");
define("_MA_TADGAL_MKDIR_ERROR","Can not creat directory \"%s\" . Please manually create it and change the permission to writable by all (777)");
define("_MA_TADGAL_LIST_ALL","List all photos");

define("_MA_MKDIR_NO_DIRNAME","Folder name was not assigned!");
define("_MA_MKDIR_ERROR","%s folder creation failed!");

define("_MA_TADGAL_SHOW_MODE","Thumbnails border");
define("_MA_TADGAL_SHOW_MODE_1","No border");
define("_MA_TADGAL_SHOW_MODE_2","Rectangular border with drop-shadow");
define("_MA_TADGAL_SHOW_MODE_3","Round corner border");
define("_MA_TADGAL_SHOW_MODE_4","Picture frame with drop-shadow");
define("_MA_TADGAL_SHOW_MODE_5","Edge color fading");
define("_MA_TADGAL_SHOW_MODE_6","Slide frame");

define("_MA_TREETABLE_MOVE_PIC","Drag to the destination category.");
define("_MA_TREETABLE_SORT_PIC","Drag up or down to set the sort of categories");
define("_MA_TREETABLE_MOVE_ERROR1","Move fail! Source and destination category can not be the same.");
define("_MA_TREETABLE_MOVE_ERROR2","Move fail! Destination category can not be under the source category.");

//update
define("_MA_TADGAL_AUTOUPDATE","Module update");
define("_MA_TADGAL_AUTOUPDATE_VER","Version");
define("_MA_TADGAL_AUTOUPDATE_DESC","Release note");
define("_MA_TADGAL_AUTOUPDATE_STATUS","Update status");
define("_MA_TADGAL_AUTOUPDATE_GO","Update now");
define("_MA_GAL_AUTOUPDATE1","Add display mode column in category list");
define("_MA_GAL_AUTOUPDATE2","Move thumbnails to new directory");
define("_MA_GAL_AUTOUPDATE3","Add album (category) default display mode setting column: show_mode");
define("_MA_GAL_AUTOUPDATE4","Add album (category) cover image column: cover");
define("_MA_GAL_AUTOUPDATE5","Add anti-hotlink, anti-download setting column: no_hotlink");
define("_MA_GAL_AUTOUPDATE6","Add category creator record column: uid");


define("_MA_TADGAL_SELECT_ALL","Select all");
define("_MA_TADGAL_LIST_GOOD","BEST PHOTOS Mode");
define("_MA_TADGAL_LIST_NORMAL","Normal Mode");
define("_MA_TADGAL_CLICK_EDIT_TITLE","<<Click to edit title>>");
define("_MA_TADGAL_CLICK_EDIT_DESC","Click to edit description");
define("_MA_TADGAL_THE_ACT_IS","Action on selected pictures:");
define("_MA_TADGAL_ADD_GOOD","Add to BEST PHOTOS selection");
define("_MA_TADGAL_DEL_GOOD","Remove from BEST PHOTOS selection");
define("_MA_TADGAL_MOVE_TO","Move to");
define("_MA_TADGAL_GO","Go");
define("_MA_TADGAL_TAG","Add new tag");
define("_MA_TADGAL_REMOVE_TAG","Remove tags");
define("_MA_TADGAL_TAG_TXT","(For multi-tags, please use coma \",\" to seperate them)");
define("_MA_TADGAL_LINK_TO_CATE","Link to \"%s\"");
define("_MA_TADGAL_ADD_TITLE","Add Title");
define("_MA_TADGAL_ADD_DESCRIPTION","Add description");
define("_MA_TADGAL_RE_CREATE_THUMBNAILS_ALL","All");
define("_MA_TADGAL_RE_CREATE_THUMBNAILS_M","Medium");
define("_MA_TADGAL_RE_CREATE_THUMBNAILS_S","Small");
define("_MA_TADGAL_RE_CREATE_THUMBNAILS_FB","for facebook");
?>