<?php
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';

require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$show_uid = system_CleanVars($_REQUEST, 'show_uid', 0, 'int');
$csn = system_CleanVars($_REQUEST, 'csn', 0, 'int');
$passwd = system_CleanVars($_REQUEST, 'passwd', '', 'string');

$tadgallery = new tadgallery();
if ($show_uid) {
    $tadgallery->set_show_uid($show_uid);
}

if (!empty($csn)) {
    $cate = $tadgallery->get_tad_gallery_cate($csn);
    if ('waterfall' === $cate['show_mode']) {
        $GLOBALS['xoopsOption']['template_main'] = 'tadgallery_list_waterfall.tpl';
    } elseif ('flickr' === $cate['show_mode']) {
        $GLOBALS['xoopsOption']['template_main'] = 'tadgallery_list_flickr.tpl';
    } elseif (isset($_REQUEST['op']) and 'passwd_form' === $_REQUEST['op']) {
        $GLOBALS['xoopsOption']['template_main'] = 'tadgallery_passwd_form.tpl';
    } else {
        $GLOBALS['xoopsOption']['template_main'] = 'tadgallery_list_normal.tpl';
    }
} else {
    if ('waterfall' === $xoopsModuleConfig['index_mode']) {
        $GLOBALS['xoopsOption']['template_main'] = 'tadgallery_list_waterfall.tpl';
    } elseif ('flickr' === $xoopsModuleConfig['index_mode']) {
        $GLOBALS['xoopsOption']['template_main'] = 'tadgallery_list_flickr.tpl';
    } else {
        $GLOBALS['xoopsOption']['template_main'] = 'tadgallery_list_normal.tpl';
    }
}

$GLOBALS['xoopsOption']['template_main'] = $xoopsOption['template_main'];

require_once XOOPS_ROOT_PATH . '/header.php';

/*-----------function區--------------*/
//列出所有照片
function list_photos($csn = '', $uid = '')
{
    global $xoopsModuleConfig, $xoopsTpl, $tadgallery, $xoopsDB;

    if (!file_exists(XOOPS_ROOT_PATH . '/modules/tadtools/fancybox.php')) {
        redirect_header('index.php', 3, _MA_NEED_TADTOOLS);
    }
    require_once XOOPS_ROOT_PATH . '/modules/tadtools/fancybox.php';

    if ($csn) {
        $tadgallery->set_orderby('photo_sort');
        $tadgallery->set_view_csn($csn);
        $tadgallery->set_only_thumb($xoopsModuleConfig['only_thumb']);
        $cate = $tadgallery->get_tad_gallery_cate($csn);
        $xoopsTpl->assign('cate', $cate);

        $upload_powers = $tadgallery->chk_cate_power('upload');
        if ($upload_powers) {
            require_once XOOPS_ROOT_PATH . '/modules/tadtools/jeditable.php';
            $file = 'save.php';
            $jeditable = new jeditable();
            $jeditable->setTextAreaCol('#content', $file, '90%', '100px', "{'csn':$csn,'op' : 'save'}", _MD_TADGAL_EDIT_CATE_CONTENT);
            $jeditable_set = $jeditable->render();
            $xoopsTpl->assign('jeditable_set', $jeditable_set);
        }
    } else {
        $nowuid = '';
        if ($xoopsUser) {
            $nowuid = $xoopsUser->uid();
        }

        $tadgallery->set_orderby('rand');
        $tadgallery->set_limit($xoopsModuleConfig['thumbnail_number']);
    }

    if (0 != $xoopsModuleConfig['random_photo'] or !empty($csn)) {
        $photo = $tadgallery->get_photos();
    }
    $xoopsTpl->assign('random_photo', $xoopsModuleConfig['random_photo']);
    // die(var_export($photo));
    $xoopsTpl->assign('photo', $photo);

    $tadgallery->get_albums();

    $cate_fancybox = new fancybox('.editbtn');
    $cate_fancybox_code = $cate_fancybox->render(false);
    $xoopsTpl->assign('cate_fancybox_code', $cate_fancybox_code);

    if (!file_exists(XOOPS_ROOT_PATH . '/modules/tadtools/colorbox.php')) {
        redirect_header('index.php', 3, _MA_NEED_TADTOOLS);
    }
    require_once XOOPS_ROOT_PATH . '/modules/tadtools/colorbox.php';
    $colorbox = new colorbox('.Photo');
    $colorbox_code = $colorbox->render(false);
    $xoopsTpl->assign('colorbox_code', $colorbox_code);
    $xoopsTpl->assign('only_thumb', $xoopsModuleConfig['only_thumb']);
    $xoopsTpl->assign('csn', $csn);
}

function passwd_form($csn, $title)
{
    global $xoopsTpl;

    $xoopsTpl->assign('title', sprintf(_MD_TADGAL_INPUT_ALBUM_PASSWD, $title));
    $xoopsTpl->assign('csn', $csn);
}
/*-----------執行動作判斷區----------*/
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$sn = system_CleanVars($_REQUEST, 'sn', 0, 'int');
$uid = system_CleanVars($_REQUEST, 'uid', 0, 'int');
$show_uid = system_CleanVars($_REQUEST, 'show_uid', 0, 'int');

if (!empty($csn) and !empty($passwd)) {
    $_SESSION['tadgallery'][$csn] = $passwd;
}

switch ($op) {
    case 'passwd_form':
        passwd_form($csn, $cate['title']);
        break;
    default:
        list_photos($csn, $show_uid);
        break;
}

/*-----------秀出結果區--------------*/

$arr = get_tadgallery_cate_path($csn);
$path = tad_breadcrumb($csn, $arr, 'index.php', 'csn', 'title');
$xoopsTpl->assign('path', $path);

$author_menu = get_all_author($show_uid);
$xoopsTpl->assign('author_option', $author_menu);

$xoopsTpl->assign('toolbar', toolbar_bootstrap($interface_menu));

if ($xoTheme) {
    $xoTheme->addStylesheet('modules/tadgallery/module.css');
    $xoTheme->addStylesheet('modules/tadgallery/class/jquery.thumbs/jquery.thumbs.css');
    $xoTheme->addScript('modules/tadgallery/class/jquery.thumbs/jquery.thumbs.js');
}
require_once XOOPS_ROOT_PATH . '/footer.php';
