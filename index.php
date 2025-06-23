<?php
use Xmf\Request;
use XoopsModules\Tadgallery\Tadgallery;
use XoopsModules\Tadgallery\Tools;
use XoopsModules\Tadtools\CategoryHelper;
use XoopsModules\Tadtools\ColorBox;
use XoopsModules\Tadtools\FancyBox;
use XoopsModules\Tadtools\Jeditable;
use XoopsModules\Tadtools\Utility;

/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';

$op       = Request::getString('op');
$show_uid = Request::getInt('show_uid');
$csn      = Request::getInt('csn');
$passwd   = Request::getString('passwd');

$tadgallery = new Tadgallery();
if ($show_uid) {
    $tadgallery->set_show_uid($show_uid);
}

if (!empty($csn)) {
    $cate = Tools::get_tad_gallery_cate($csn);
    if ('waterfall' === $cate['show_mode']) {
        $GLOBALS['xoopsOption']['template_main'] = 'tadgallery_list_waterfall.tpl';
    } elseif ('flickr' === $cate['show_mode']) {
        $GLOBALS['xoopsOption']['template_main'] = 'tadgallery_list_flickr.tpl';
    } elseif ('passwd_form' === $op) {
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

if ((!empty($csn) && $cate['show_mode'] == 'flickr') || (empty($csn) && $xoopsModuleConfig['index_mode'] == 'flickr')) {
    $GLOBALS['xoTheme']->addStylesheet('modules/tadgallery/class/justifiedGallery/justifiedGallery.min.css');
    $GLOBALS['xoTheme']->addScript('modules/tadgallery/class/justifiedGallery/jquery.justifiedGallery.min.js');
} elseif ((!empty($csn) && $cate['show_mode'] == 'waterfall') || (empty($csn) && $xoopsModuleConfig['index_mode'] == 'waterfall')) {
    $GLOBALS['xoTheme']->addScript('modules/tadgallery/class/jquery.masonry.min.js');
    $GLOBALS['xoTheme']->addScript('modules/tadgallery/class/jquery.corner.js');
    $GLOBALS['xoTheme']->addScript('modules/tadgallery/class/jquery.animate-shadow.js');
} else {
    $GLOBALS['xoTheme']->addScript('modules/tadgallery/class/jquery.animate-shadow.js');
}

/*-----------執行動作判斷區----------*/
$sn       = Request::getInt('sn');
$uid      = Request::getInt('uid');
$show_uid = Request::getInt('show_uid');

if (!empty($csn) and !empty($passwd)) {
    $_SESSION['tadgallery'][$csn] = $passwd;
}

switch ($op) {
    case 'passwd_form':
        passwd_form($csn, $cate['title']);
        break;

    default:
        list_photos($csn);
        break;
}

/*-----------秀出結果區--------------*/

$categoryHelper = new CategoryHelper('tad_gallery_cate', 'csn', 'of_csn', 'title');
$arr            = $categoryHelper->getCategoryPath($csn, 'tad_gallery');
$path           = Utility::tad_breadcrumb($csn, $arr, 'index.php', 'csn', 'title');
$xoopsTpl->assign('path', $path);

$author_menu = get_all_author($show_uid);
$xoopsTpl->assign('author_option', $author_menu);
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu, false, $interface_icon));

if ($GLOBALS['xoTheme']) {
    $GLOBALS['xoTheme']->addStylesheet('modules/tadgallery/css/module.css');
    $GLOBALS['xoTheme']->addStylesheet('modules/tadgallery/class/jquery.thumbs/jquery.thumbs.css');
    $GLOBALS['xoTheme']->addScript('modules/tadgallery/class/jquery.thumbs/jquery.thumbs.js');
}
require_once XOOPS_ROOT_PATH . '/footer.php';

/*-----------function區--------------*/
//列出所有照片
function list_photos($csn = '')
{
    global $xoopsModuleConfig, $xoopsTpl, $tadgallery;

    $GLOBALS['xoTheme']->addStylesheet('modules/tadgallery/class/pannellum/pannellum.css');
    $GLOBALS['xoTheme']->addScript('modules/tadgallery/class/pannellum/pannellum.js');
    if ($csn) {
        $tadgallery->set_orderby('photo_sort');
        $tadgallery->set_view_csn($csn);
        $tadgallery->set_limit($xoopsModuleConfig['thumbnail_number']);
        $tadgallery->set_only_thumb($xoopsModuleConfig['only_thumb']);
        $cate = Tools::get_tad_gallery_cate($csn);
        $xoopsTpl->assign('cate', $cate);

        $upload_powers = Tools::chk_cate_power('upload');
        if ($upload_powers) {
            $file      = 'save.php';
            $jeditable = new Jeditable();
            $jeditable->setTextAreaCol('#content', $file, '90%', '100px', "{'csn':$csn,'op' : 'save'}", _MD_TADGAL_EDIT_CATE_CONTENT);
            $jeditable->render();
        }
    } else {

        $tadgallery->set_orderby('rand');
        $tadgallery->set_limit($xoopsModuleConfig['thumbnail_number']);
    }

    if (0 != $xoopsModuleConfig['random_photo'] or !empty($csn)) {
        $photo = $tadgallery->get_photos();
    }
    $xoopsTpl->assign('random_photo', $xoopsModuleConfig['random_photo']);
    $xoopsTpl->assign('photo', $photo);

    $tadgallery->get_albums();

    $cate_fancybox = new FancyBox('.editbtn', 800);
    $cate_fancybox->set_type('iframe');
    $cate_fancybox->render(false);

    $colorbox = new ColorBox('.Photo');
    $colorbox->render(false);
    $xoopsTpl->assign('only_thumb', $xoopsModuleConfig['only_thumb']);
    $xoopsTpl->assign('csn', $csn);
}

function passwd_form($csn, $title)
{
    global $xoopsTpl;

    $xoopsTpl->assign('title', sprintf(_MD_TADGAL_INPUT_ALBUM_PASSWD, $title));
    $xoopsTpl->assign('csn', $csn);
}
