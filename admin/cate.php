<?php
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = "tadgallery_adm_cate.html";
include_once "header.php";
include_once "../function.php";

/*-----------function區--------------*/
//tad_gallery_cate編輯表單
function tad_gallery_cate_form($csn = "")
{
    global $xoopsDB, $xoopsModuleConfig, $cate_show_mode_array, $xoopsTpl;
    include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
    $xoopsTpl->assign('now_op', 'tad_gallery_cate_form');

    //抓取預設值
    if (!empty($csn)) {
        $DBV = tadgallery::get_tad_gallery_cate($csn);
    } else {
        $DBV = array();
    }

    $row  = ($_SESSION['bootstrap'] == '3') ? 'row' : 'row-fluid';
    $span = ($_SESSION['bootstrap'] == '3') ? 'col-md-' : 'span';

    //預設值設定
    $csn                 = (!isset($DBV['csn'])) ? $csn : $DBV['csn'];
    $of_csn              = (!isset($DBV['of_csn'])) ? "" : $DBV['of_csn'];
    $title               = (!isset($DBV['title'])) ? "" : $DBV['title'];
    $enable_group        = (!isset($DBV['enable_group'])) ? "" : explode(",", $DBV['enable_group']);
    $enable_upload_group = (!isset($DBV['enable_upload_group'])) ? array('1') : explode(",", $DBV['enable_upload_group']);
    $sort                = (!isset($DBV['sort'])) ? auto_get_csn_sort() : $DBV['sort'];
    $passwd              = (!isset($DBV['passwd'])) ? "" : $DBV['passwd'];
    $mode                = (!isset($DBV['mode'])) ? "" : $DBV['mode'];
    $show_mode           = (!isset($DBV['show_mode'])) ? $xoopsModuleConfig['index_mode'] : $DBV['show_mode'];
    $cover               = (!isset($DBV['cover'])) ? "" : $DBV['cover'];

    $op = (empty($csn)) ? "insert_tad_gallery_cate" : "update_tad_gallery_cate";

    $xoopsTpl->assign('csn', $csn);
    $xoopsTpl->assign('of_csn', $of_csn);

    $of_csn_def = "";
    if ($of_csn) {
        $of_cate    = tadgallery::get_tad_gallery_cate($of_csn);
        $of_csn_def = $of_cate['title'];
    }
    $xoopsTpl->assign('of_csn_def', $of_csn_def);

    $xoopsTpl->assign('title', $title);
    $xoopsTpl->assign('enable_group', $enable_group);
    $xoopsTpl->assign('enable_upload_group', $enable_upload_group);
    $xoopsTpl->assign('sort', $sort);
    $xoopsTpl->assign('passwd', $passwd);
    $xoopsTpl->assign('mode', $mode);
    $xoopsTpl->assign('show_mode', $show_mode);
    $xoopsTpl->assign('cover', $cover);
    $xoopsTpl->assign('op', $op);

    $cover_select = get_cover($csn, $cover);

    //$xoopsTpl->assign('cate_select', $cate_select);
    $xoopsTpl->assign('cover_select', $cover_select);

    //可見群組
    $SelectGroup_name = new XoopsFormSelectGroup("", "enable_group", false, $enable_group, 4, true);
    $SelectGroup_name->addOption("", _MA_TADGAL_ALL_OK, false);
    $SelectGroup_name->setExtra("class='{$span}12'");
    $enable_group = $SelectGroup_name->render();
    $xoopsTpl->assign('enable_group', $enable_group);

    //可上傳群組
    $SelectGroup_name = new XoopsFormSelectGroup("", "enable_upload_group", false, $enable_upload_group, 4, true);
    //$SelectGroup_name->addOption("", _MA_TADGAL_ALL_OK, false);
    $SelectGroup_name->setExtra("class='{$span}12'");
    $enable_upload_group = $SelectGroup_name->render();
    $xoopsTpl->assign('enable_upload_group', $enable_upload_group);

    $cate_show_option = "";
    foreach ($cate_show_mode_array as $key => $value) {
        $selected = ($show_mode == $key) ? "selected='selected'" : "";
        $cate_show_option .= "<option value='$key' $selected>$value</option>";
    }

    $xoopsTpl->assign('cate_show_option', $cate_show_option);

    $cover_default = (!empty($cover)) ? XOOPS_URL . "/uploads/tadgallery/{$cover}" : "../images/folder_picture.png";
    $xoopsTpl->assign('cover_default', $cover_default);

    // $sub_cate   = get_tad_gallery_sub_cate($csn);
    // $no_checked = array_keys($sub_cate);
    // $ztree_code = get_tad_gallery_cate_tree($of_csn, $csn, $no_checked);
    // $xoopsTpl->assign('ztree_cate_code', $ztree_code);

    $path    = get_tadgallery_cate_path($csn, false);
    $patharr = array_keys($path);
    $i       = 0;
    foreach ($patharr as $k => $of_csn) {
        $j                       = $k + 1;
        $path_arr[$i]['of_csn']  = $of_csn;
        $path_arr[$i]['def_csn'] = $patharr[$j];
        $i++;
    }
    $xoopsTpl->assign('path_arr', $path_arr);
}

//新增資料到tad_gallery_cate中
function insert_tad_gallery_cate()
{
    global $xoopsDB, $xoopsUser;
    if (empty($_POST['title'])) {
        return;
    }

    if (empty($_POST['enable_group']) or in_array("", $_POST['enable_group'])) {
        $enable_group = "";
    } else {
        $enable_group = implode(",", $_POST['enable_group']);
    }

    if (empty($_POST['enable_upload_group'])) {
        $enable_upload_group = "1";
    } else {
        $enable_upload_group = implode(",", $_POST['enable_upload_group']);
    }

    $uid = $xoopsUser->uid();

    krsort($_POST['of_csn_menu']);
    //die(var_export($_POST['of_csn_menu']));
    foreach ($_POST['of_csn_menu'] as $sn) {
        if (empty($sn)) {
            continue;
        } else {
            $of_csn = $sn;
            break;
        }
    }
    //die('$of_csn:' . $of_csn);
    $sql = "insert into " . $xoopsDB->prefix("tad_gallery_cate") . " (
    `of_csn`, `title`, `content`, `passwd`, `enable_group`, `enable_upload_group`, `sort`, `mode`, `show_mode`, `cover`, `no_hotlink`, `uid`) values('{$of_csn}','{$_POST['title']}','','{$_POST['passwd']}','{$enable_group}','{$enable_upload_group}','{$_POST['sort']}','{$_POST['mode']}','{$_POST['show_mode']}','','',$uid)";
    $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    //取得最後新增資料的流水編號
    $csn = $xoopsDB->getInsertId();
    return $csn;
}

//列出所有tad_gallery_cate資料
function list_tad_gallery_cate_tree($def_csn = "")
{
    global $xoopsDB, $xoopsTpl;

    $tadgallery = new tadgallery();
    $cate_count = $tadgallery->get_tad_gallery_cate_count();
    $path       = get_tadgallery_cate_path($def_csn);
    $path_arr   = array_keys($path);

    $sql    = "select csn,of_csn,title from " . $xoopsDB->prefix("tad_gallery_cate") . " order by sort";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    while (list($csn, $of_csn, $title) = $xoopsDB->fetchRow($result)) {

        $font_style      = $def_csn == $csn ? ", font:{'background-color':'yellow', 'color':'black'}" : '';
        $open            = in_array($csn, $path_arr) ? 'true' : 'false';
        $display_counter = empty($cate_count[$csn]['file']) ? "" : " ({$cate_count[$csn]['file']})";
        $data[]          = "{ id:{$csn}, pId:{$of_csn}, name:'{$title}{$display_counter}', url:'cate.php?csn={$csn}', open:{$open} {$font_style}}";
    }

    $json = implode(',', $data);

    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/ztree.php")) {
        redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/ztree.php";
    $ztree      = new ztree("album_tree", $json, "save_drag.php", "save_cate_sort.php", "of_csn", "csn");
    $ztree_code = $ztree->render();
    $xoopsTpl->assign('ztree_code', $ztree_code);

    return $data;
}

//重新產生縮圖，沒有 $kind 就是全部縮圖
function re_thumb($csn = "", $kind = "")
{
    global $xoopsDB, $xoopsModuleConfig, $type_to_mime;
    if (empty($csn)) {
        return 0;
    }

    //找出分類下所有相片
    $sql    = "select sn,title,filename,type,width,height,dir,post_date from " . $xoopsDB->prefix("tad_gallery") . " where csn='{$csn}' order by photo_sort , post_date";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    $n      = 0;
    while (list($sn, $title, $filename, $type, $width, $height, $dir, $post_date) = $xoopsDB->fetchRow($result)) {

        $b_thumb_name = photo_name($sn, "b", 1, $filename, $dir);
        if (substr($type, 0, 5) !== "image") {
            $file_ending = substr(strtolower($filename), -3); //file extension
            $type        = $type_to_mime[$file_ending];
        }

        if ($kind == "m" or empty($kind)) {
            $m_thumb_name = photo_name($sn, "m", 1, $filename, $dir);
            if ($width > $xoopsModuleConfig['thumbnail_m_width'] or $height > $xoopsModuleConfig['thumbnail_m_width']) {
                thumbnail($b_thumb_name, $m_thumb_name, $type, $xoopsModuleConfig['thumbnail_m_width']);
            }
        }

        if ($kind == "s" or empty($kind)) {
            $m_thumb_name = photo_name($sn, "m", 1, $filename, $dir);
            $s_thumb_name = photo_name($sn, "s", 1, $filename, $dir);
            if ($width > $xoopsModuleConfig['thumbnail_s_width'] or $height > $xoopsModuleConfig['thumbnail_s_width']) {
                thumbnail($m_thumb_name, $s_thumb_name, $type, $xoopsModuleConfig['thumbnail_s_width']);
            }
        }

        $n++;
    }
    //exit;
    return $n;
}

//封面圖選單
function get_cover($csn = "", $cover = "")
{
    global $xoopsDB;
    if (empty($csn)) {
        return "<option value=''>" . _MD_TADGAL_COVER . "</option>";
    }

    $sql    = "select csn from " . $xoopsDB->prefix("tad_gallery_cate") . " where csn='{$csn}' or of_csn='{$csn}'";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error() . "<br>$sql");
    while (list($all_csn) = $xoopsDB->fetchRow($result)) {
        $csn_arr[] = $all_csn;
    }

    $csn_arr_str = implode(",", $csn_arr);

    $sql    = "select sn,dir,filename from " . $xoopsDB->prefix("tad_gallery") . " where csn in($csn_arr_str)  order by filename";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error() . "<br>$sql");
    //$option="<option value=''>"._MD_TADGAL_COVER."</option>";
    $option = "";
    while (list($sn, $dir, $filename) = $xoopsDB->fetchRow($result)) {
        $selected = ($cover == "small/{$dir}/{$sn}_s_{$filename}") ? "selected" : "";
        $option .= "<option value='small/{$dir}/{$sn}_s_{$filename}' $selected>{$filename}</option>";
    }
    return $option;
}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op  = system_CleanVars($_REQUEST, 'op', '', 'string');
$csn = system_CleanVars($_REQUEST, 'csn', 0, 'int');

switch ($op) {

    //新增資料
    case "insert_tad_gallery_cate":
        $csn = insert_tad_gallery_cate();
        mk_rss_xml();
        mk_rss_xml($csn);
        header("location: {$_SERVER['PHP_SELF']}?csn=$csn");
        break;

    //刪除資料
    case "delete_tad_gallery_cate";
        delete_tad_gallery_cate($csn);
        mk_rss_xml();
        header("location: {$_SERVER['PHP_SELF']}");
        break;

    //更新資料
    case "update_tad_gallery_cate";
        update_tad_gallery_cate($csn);
        mk_rss_xml();
        mk_rss_xml($csn);
        header("location: {$_SERVER['PHP_SELF']}?csn=$csn");
        break;

    //新增資料
    case "tad_gallery_cate_form";
        list_tad_gallery_cate_tree($csn);
        tad_gallery_cate_form();
        break;

    //重新產生縮圖
    case "re_thumb":
        $n = re_thumb($csn, $_REQUEST['kind']);
        redirect_header("{$_SERVER['PHP_SELF']}?csn={$_REQUEST['csn']}", 3, "All ($n) OK!");
        break;

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
