<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
$xoopsOption['template_main'] = set_bootstrap("tadgallery_cooliris.html");
include_once XOOPS_ROOT_PATH . "/header.php";

/*-----------function區--------------*/

include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$csn = system_CleanVars($_REQUEST, 'csn', 0, 'int');

$xoopsTpl->assign("csn", $csn);
$xoopsTpl->assign("up_file_url", _TADGAL_UP_FILE_URL);

$cate_option = get_tad_gallery_cate_option(0, 0, $csn);
$xoopsTpl->assign("cate_option", $cate_option);

/*-----------秀出結果區--------------*/

$xoopsTpl->assign("toolbar", toolbar_bootstrap($interface_menu));
get_jquery(true);

//路徑選單

$arr             = get_tadgallery_cate_path($csn);
$jBreadCrumbPath = breadcrumb($csn, $arr);
$xoopsTpl->assign("path", $jBreadCrumbPath);

include_once XOOPS_ROOT_PATH . '/footer.php';
