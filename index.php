<?php
/*-----------引入檔案區--------------*/
include_once "header.php";

include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$show_uid = system_CleanVars($_SESSION, 'show_uid', 0, 'int');
$csn      = system_CleanVars($_REQUEST, 'csn', 0, 'int');
$passwd   = system_CleanVars($_POST, 'passwd', '', 'string');

$tadgallery = new tadgallery();
if ($show_uid) {
    $tadgallery->set_show_uid($show_uid);
}

if (!empty($csn)) {
    $cate = $tadgallery->get_tad_gallery_cate($csn);
    if ($cate['show_mode'] == "waterfall") {
        $xoopsOption['template_main'] = "tadgallery_list_waterfall.html";
    } elseif ($cate['show_mode'] == "flickr") {
        $xoopsOption['template_main'] = "tadgallery_list_flickr.html";
    } elseif (isset($_REQUEST['op']) and $_REQUEST['op'] == "passwd_form") {
        $xoopsOption['template_main'] = "tadgallery_passwd_form.html";
    } else {
        $xoopsOption['template_main'] = "tadgallery_list_normal.html";
    }
} else {
    if ($xoopsModuleConfig['index_mode'] == "waterfall") {
        $xoopsOption['template_main'] = "tadgallery_list_waterfall.html";
    } elseif ($xoopsModuleConfig['index_mode'] == "flickr") {
        $xoopsOption['template_main'] = "tadgallery_list_flickr.html";
    } else {
        $xoopsOption['template_main'] = "tadgallery_list_normal.html";
    }
}

$xoopsOption['template_main'] = set_bootstrap($xoopsOption['template_main']);

include_once XOOPS_ROOT_PATH . "/header.php";

/*-----------function區--------------*/
//列出所有照片
function list_photos($csn = "", $uid = "")
{
    global $xoopsModuleConfig, $xoopsTpl, $tadgallery;

    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/fancybox.php")) {
        redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/fancybox.php";

    if ($csn) {
        $tadgallery->set_orderby("photo_sort");
        $tadgallery->set_view_csn($csn);
        $tadgallery->set_only_thumb($xoopsModuleConfig['only_thumb']);
        $cate = $tadgallery->get_tad_gallery_cate($csn);
        $xoopsTpl->assign("cate", $cate);

        $upload_powers = $tadgallery->chk_cate_power("upload");
        if ($upload_powers) {
            include_once XOOPS_ROOT_PATH . "/modules/tadtools/jeditable.php";
            $file      = "save.php";
            $jeditable = new jeditable();
            $jeditable->setTextAreaCol("#content", $file, '90%', '100px', "{'csn':$csn,'op' : 'save'}", _MD_TADGAL_EDIT_CATE_CONTENT);
            $jeditable_set = $jeditable->render();
            $xoopsTpl->assign("jeditable_set", $jeditable_set);
        }

    } else {
        $tadgallery->set_orderby("rand");
        $tadgallery->set_limit($xoopsModuleConfig['thumbnail_number']);
    }
    $tadgallery->get_photos();
    $tadgallery->get_albums();

    $cate_fancybox      = new fancybox('.editbtn');
    $cate_fancybox_code = $cate_fancybox->render(false);
    $xoopsTpl->assign('cate_fancybox_code', $cate_fancybox_code);

    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/colorbox.php")) {
        redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/colorbox.php";
    $colorbox      = new colorbox('.Photo');
    $colorbox_code = $colorbox->render(false);
    $xoopsTpl->assign('colorbox_code', $colorbox_code);
    $xoopsTpl->assign('only_thumb', $xoopsModuleConfig['only_thumb']);

}

function passwd_form($csn, $title)
{
    global $xoopsTpl;

    $xoopsTpl->assign("title", sprintf(_MD_TADGAL_INPUT_ALBUM_PASSWD, $title));
    $xoopsTpl->assign("csn", $csn);
}
/*-----------執行動作判斷區----------*/
$op       = system_CleanVars($_REQUEST, 'op', '', 'string');
$sn       = system_CleanVars($_REQUEST, 'sn', 0, 'int');
$uid      = system_CleanVars($_REQUEST, 'uid', 0, 'int');
$show_uid = system_CleanVars($_REQUEST, 'show_uid', 0, 'int');

if (!empty($csn) and !empty($passwd)) {
    $_SESSION['tadgallery'][$csn] = $passwd;
}

switch ($op) {

    case "passwd_form":
        passwd_form($csn, $cate['title']);
        break;

    default:
        list_photos($csn, $show_uid);
        break;
}

/*-----------秀出結果區--------------*/

$arr             = get_tadgallery_cate_path($csn);
$jBreadCrumbPath = breadcrumb($csn, $arr);
$xoopsTpl->assign("path", $jBreadCrumbPath);

$author_menu = get_all_author($show_uid);
$xoopsTpl->assign("author_option", $author_menu);

$xoopsTpl->assign("toolbar", toolbar_bootstrap($interface_menu));

if ($xoTheme) {
    $xoTheme->addStylesheet('modules/tadgallery/module.css');
    $xoTheme->addStylesheet('modules/tadgallery/class/jquery.thumbs/jquery.thumbs.css');
    $xoTheme->addScript('modules/tadgallery/class/jquery.thumbs/jquery.thumbs.js');
}
include_once XOOPS_ROOT_PATH . '/footer.php';
