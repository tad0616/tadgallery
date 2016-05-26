<?php
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = "tadgallery_adm_cate.html";
include_once "header.php";
include_once "../function.php";

/*-----------function區--------------*/

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op  = system_CleanVars($_REQUEST, 'op', '', 'string');
$csn = system_CleanVars($_REQUEST, 'csn', 0, 'int');

switch ($op) {

    //預設動作
    default:
        list_tad_gallery_cate_tree($csn);
        if (!empty($csn)) {
            tad_gallery_cate_form($csn);
        }
        break;

}

/*-----------秀出結果區--------------*/

$xoopsTpl->assign('main', $main);
include_once 'footer.php';
