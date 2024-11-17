<?php
use Xmf\Request;
use XoopsModules\Tadgallery\Tools;
use XoopsModules\Tadtools\CategoryHelper;
use XoopsModules\Tadtools\FancyBox;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
require_once 'header.php';
$xoopsOption['template_main'] = 'tadgallery_view.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$sn = Request::getInt('sn');
$csn = Request::getInt('csn');

switch ($op) {
    case 'good':
        update_tad_gallery_good($sn, '1');
        header("location: view.php?sn={$sn}#photo{$sn}");
        exit;

    case 'good_del':
        update_tad_gallery_good($sn, '0');
        header("location: view.php?sn={$sn}#photo{$sn}");
        exit;

    case 'delete_tad_gallery':
        $csn = delete_tad_gallery($sn);
        header("location: index.php?csn=$csn");
        exit;

    default:
        view_pic($sn);
        break;
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu, false, $interface_icon));
require_once XOOPS_ROOT_PATH . '/footer.php';

/*-----------function區--------------*/

//觀看某一張照片
function view_pic($sn = 0)
{
    global $xoopsDB, $xoopsUser, $xoopsModuleConfig, $xoopsTpl, $xoTheme, $tad_gallery_adm;

    //判斷是否對該模組有管理權限，  若空白
    $nowuid = $xoopsUser ? $xoopsUser->uid() : 0;

    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_gallery') . '` WHERE `sn`=?';
    $result = Utility::query($sql, 'i', [$sn]) or Utility::web_error($sql, __FILE__, __LINE__);

    $all = $xoopsDB->fetchArray($result);
    foreach ($all as $k => $v) {
        $$k = $v;
        $xoopsTpl->assign($k, $v);
    }

    $photo_s = Tools::get_pic_url($dir, $sn, $filename, 's');
    $photo_m = Tools::get_pic_url($dir, $sn, $filename, 'm');
    $photo_l = Tools::get_pic_url($dir, $sn, $filename);

    $xoopsTpl->assign('photo_s', $photo_s);
    $xoopsTpl->assign('photo_m', $photo_m);
    $xoopsTpl->assign('photo_l', $photo_l);

    $csn = (int) $csn;

    if (!empty($csn)) {
        $ok_cat = Tools::chk_cate_power();
        $cate = Tools::get_tad_gallery_cate($csn);
        if (!in_array($csn, $ok_cat)) {
            redirect_header("index.php?csn={$csn}&op=passwd_form", 3, sprintf(_TADGAL_NO_PASSWD_CONTENT, $cate['title']));
            exit;
        }

        $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_gallery') . '` WHERE `csn`=? ORDER BY `photo_sort`, `post_date`';
        $result = Utility::query($sql, 'i', [$csn]) or Utility::web_error($sql, __FILE__, __LINE__);
        $slides1 = $slides2 = [];
        $i = 0;
        $start = false;
        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            if ($sn == $all['sn']) {
                $start = true;
                $i = 0;
            }

            if ($start) {
                $slides1[$i]['sn'] = $all['sn'];
                $slides1[$i]['photo'] = Tools::get_pic_url($all['dir'], $all['sn'], $all['filename']);
                $slides1[$i]['description'] = strip_tags($all['description']);
                $slides1[$i]['thumb'] = ($all['is360']) ? Tools::get_pic_url($all['dir'], $all['sn'], $all['filename'], 'm') : Tools::get_pic_url($all['dir'], $all['sn'], $all['filename'], 's');
            } else {
                $slides2[$i]['sn'] = $all['sn'];
                $slides2[$i]['photo'] = Tools::get_pic_url($all['dir'], $all['sn'], $all['filename']);
                $slides2[$i]['description'] = strip_tags($all['description']);
                $slides2[$i]['thumb'] = ($all['is360']) ? Tools::get_pic_url($all['dir'], $all['sn'], $all['filename'], 'm') : Tools::get_pic_url($all['dir'], $all['sn'], $all['filename'], 's');
            }
            $i++;
        }
    }

    $xoopsTpl->assign('slides1', $slides1);
    $xoopsTpl->assign('slides2', $slides2);

    //找出上一張或下一張
    $pnp = get_pre_next($csn, $sn);
    $xoopsTpl->assign('next', $pnp['next']);
    $xoopsTpl->assign('back', $pnp['pre']);

    $categoryHelper = new CategoryHelper('tad_gallery_cate', 'csn', 'of_csn', 'title');
    $arr = $categoryHelper->getCategoryPath($csn);
    // $arr = get_tadgallery_cate_path($csn);
    $path = Utility::tad_breadcrumb($csn, $arr, 'index.php', 'csn', 'title');
    $xoopsTpl->assign('path', $path);

    $fancybox = new FancyBox('.fancybox', 360);
    $fancybox->set_type('iframe');
    $fancybox->render(false);

    $title = (empty($title)) ? $filename : $title;
    $div_width = $xoopsModuleConfig['thumbnail_m_width'] + 30;

    if ($uid == $nowuid or $tad_gallery_adm) {
        $xoopsTpl->assign('show_del', 1);
        $xoopsTpl->assign('good', $good);

        $SweetAlert = new SweetAlert();
        $SweetAlert->render("delete_tad_gallery_func", "{$_SERVER['PHP_SELF']}?op=delete_tad_gallery&sn=", 'sn');

    } else {
        $del_btn = '';
    }

    $xoopsTpl->assign('del_btn', $del_btn);

    //秀出各種尺寸圖示
    if ($xoopsModuleConfig['show_copy_pic']) {
        $xoopsTpl->assign('photo_s', $photo_s);
        $xoopsTpl->assign('photo_m', $photo_m);
        $xoopsTpl->assign('photo_l', $photo_l);
        $xoopsTpl->assign('description', $description);
        $xoopsTpl->assign('sel_size', 1);
    } else {
        $xoopsTpl->assign('sel_size', 0);
    }

    //推文工具
    $push = Utility::push_url($xoopsModuleConfig['use_social_tools']);
    $xoopsTpl->assign('push', $push);
    $xoopsTpl->assign('pic_toolbar', $xoopsModuleConfig['pic_toolbar']);
    $xoopsTpl->assign('thumb_slider', $xoopsModuleConfig['thumb_slider']);

    //計數器
    add_tad_gallery_counter($sn);
    $photoexif = parse_exif_string($exif);

    $latitude = $photoexif['GPS']['latitude'];
    $longitude = $photoexif['GPS']['longitude'];
    $xoopsTpl->assign('latitude', $latitude);
    $xoopsTpl->assign('longitude', $longitude);

    $jquery_path = Utility::get_jquery(true);
    $xoopsTpl->assign('jquery', $jquery_path);

    // $categoryHelper = new CategoryHelper('tad_gallery_cate', 'csn', 'of_csn', 'title');
    // $arr = $categoryHelper->getCategoryPath($csn);
    // // $arr = get_tadgallery_cate_path($csn);
    // $path = Utility::tad_breadcrumb($csn, $arr, 'index.php', 'csn', 'title');
    // $xoopsTpl->assign('path', $path);

    $xoopsTpl->assign('div_width', $div_width);

    $fb_tag = "
      <meta property=\"og:title\" content=\"{$title}\">
      <meta property=\"og:description\" content=\"{$description}\">
      <meta property=\"og:image\" content=\"" . Tools::get_pic_url($dir, $sn, $filename, 'm') . '">
      ';
    $xoopsTpl->assign('xoops_module_header', $fb_tag);
    $xoopsTpl->assign('xoops_pagetitle', $title);
    if (is_object($xoTheme)) {
        $xoTheme->addMeta('meta', 'keywords', $title);
        $xoTheme->addMeta('meta', 'description', $description);
        $xoTheme->addStylesheet('modules/tadgallery/class/jquery.bxslider/jquery.bxslider.css');
        $xoTheme->addScript('modules/tadgallery/class/jquery.bxslider/jquery.bxslider.js');
        $xoTheme->addStylesheet('modules/tadgallery/class/leaflet.css');
        $xoTheme->addScript('modules/tadgallery/class/leaflet.js');
        if ($is360) {
            $xoTheme->addStylesheet('modules/tadgallery/class/pannellum/pannellum.css');
            $xoTheme->addScript('modules/tadgallery/class/pannellum/pannellum.js');
        }

    } else {
        $xoopsTpl->assign('xoops_meta_keywords', 'keywords', $title);
        $xoopsTpl->assign('xoops_meta_description', $description);
    }

}

//更新人氣資料到tad_gallery中
function add_tad_gallery_counter($sn = '')
{
    global $xoopsDB;
    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_gallery') . '` SET `counter`=`counter`+1 WHERE `sn`=?';
    Utility::query($sql, 'i', [$sn]) or Utility::web_error($sql, __FILE__, __LINE__);
}
