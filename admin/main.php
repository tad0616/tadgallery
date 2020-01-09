<?php
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tadtools\Ztree;

/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = 'tadgallery_adm_main.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';

/*-----------function區--------------*/
//列出所有tad_gallery資料
function list_tad_gallery($csn = '', $show_function = 1)
{
    global $xoopsDB, $xoopsModule, $xoopsModuleConfig, $xoopsTpl, $xoTheme;

    require_once XOOPS_ROOT_PATH . '/modules/tadgallery/class/Tadgallery.php';

    $tadgallery = new Tadgallery();

    Utility::get_jquery(true);
    $xoopsTpl->assign('csn', $csn);

    $cate = '';
    if (isset($csn)) {
        $cate = Tadgallery::get_tad_gallery_cate($csn);
    }

    if (isset($_SESSION['gallery_list_mode']) and 'good' === $_SESSION['gallery_list_mode']) {
        $mode_select = '';
        $tadgallery->set_view_good(true);
        if ($csn) {
            $tadgallery->set_view_csn($csn);
        }
        $cate_options = $cate_option = $link_to_cate = '';
    } else {
        $mode_select = 'good';
        $tadgallery->set_view_good(false);
        $tadgallery->set_view_csn($csn);
        $link_to_cate = !empty($csn) ? sprintf(_MA_TADGAL_LINK_TO_CATE, $cate['title']) : '';
    }

    $tag_select = tag_select('', 'add_tag');
    $isAdmin = 1;
    $cate_option = get_tad_gallery_cate_option(0, 0);

    $xoopsTpl->assign('cate', $cate);
    $xoopsTpl->assign('cate_option', $cate_option);
    $xoopsTpl->assign('mode_select', $mode_select);
    $xoopsTpl->assign('link_to_cate', $link_to_cate);
    $xoopsTpl->assign('option', $cate_option);
    $xoopsTpl->assign('tag_select', $tag_select);

    if (\Xmf\Request::hasVar('gallery_list_mode', 'SESSION')) {
        $xoopsTpl->assign('gallery_list_mode', $_SESSION['gallery_list_mode']);
    }

    $tadgallery->set_admin_mode(true);
    $photo = $tadgallery->get_photos();
    $xoopsTpl->assign('photo', $photo);

    if ($xoTheme) {
        $xoTheme->addStylesheet('modules/tadgallery/class/jquery.thumbs/jquery.thumbs.css');
        $xoTheme->addScript('modules/tadgallery/class/jquery.thumbs/jquery.thumbs.js');
    }

    $SweetAlert = new SweetAlert();
    $SweetAlert->render('delete_tad_gallery_cate_func', 'main.php?op=delete_tad_gallery_cate&csn=', 'csn');
}

//列出所有tad_gallery_cate資料
function list_tad_gallery_cate_tree($def_csn = '')
{
    global $xoopsDB, $xoopsTpl;

    require_once XOOPS_ROOT_PATH . '/modules/tadgallery/class/Tadgallery.php';

    $tadgallery = new Tadgallery();

    if (\Xmf\Request::hasVar('gallery_list_mode', 'SESSION')) {
        $cate_count = $tadgallery->get_tad_gallery_cate_count($_SESSION['gallery_list_mode']);
    }

    // die(var_export($cate_count));
    $path = get_tadgallery_cate_path($def_csn);
    $path_arr = array_keys($path);
    $data[] = "{ id:0, pId:0, name:'All', url:'main.php', target:'_self', open:true}";

    $sql = 'SELECT csn,of_csn,title FROM ' . $xoopsDB->prefix('tad_gallery_cate') . ' ORDER BY sort';
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    while (list($csn, $of_csn, $title) = $xoopsDB->fetchRow($result)) {
        $font_style = $def_csn == $csn ? ", font:{'background-color':'yellow', 'color':'black'}" : '';
        $csn = (int) $csn;
        $of_csn = (int) $of_csn;

        $open = in_array($csn, $path_arr) ? 'true' : 'false';
        $display_counter = empty($cate_count[$csn]['file']) ? '' : " ({$cate_count[$csn]['file']})";
        $data[] = "{ id:{$csn}, pId:{$of_csn}, name:'{$title}{$display_counter}', url:'main.php?csn={$csn}', open: {$open} ,target:'_self' {$font_style}}";
    }

    $json = implode(",\n", $data);

    $ztree = new Ztree('album_tree', $json, 'save_drag.php', 'save_cate_sort.php', 'of_csn', 'csn');
    $ztree_code = $ztree->render();
    // die('ztree_code:' . $ztree);
    $xoopsTpl->assign('ztree_code', $ztree_code);

    return $data;
}

//批次搬移
function batch_move($new_csn = '')
{
    global $xoopsDB;
    if (\Xmf\Request::hasVar('pic', 'POST')) {
        $pics = implode(',', $_POST['pic']);
    }
    $sql = 'update ' . $xoopsDB->prefix('tad_gallery') . " set `csn` = '{$new_csn}' where sn in($pics)";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    return $sn;
}

//批次新增精華
function batch_add_good()
{
    global $xoopsDB;
    if (\Xmf\Request::hasVar('pic', 'POST')) {
        $pics = implode(',', $_POST['pic']);
    }
    $sql = 'update ' . $xoopsDB->prefix('tad_gallery') . " set  `good` = '1' where sn in($pics)";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    return $sn;
}

//批次新增標題
function batch_add_title()
{
    global $xoopsDB;
    if (\Xmf\Request::hasVar('pic', 'POST')) {
        $pics = implode(',', $_POST['pic']);
    }
    $sql = 'update ' . $xoopsDB->prefix('tad_gallery') . " set  `title` = '{$_POST['add_title']}' where sn in($pics)";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    return $sn;
}

//批次新增說明
function batch_add_description()
{
    global $xoopsDB;
    if (\Xmf\Request::hasVar('pic', 'POST')) {
        $pics = implode(',', $_POST['pic']);
        $sql = 'update ' . $xoopsDB->prefix('tad_gallery') . " set  `description` = '{$_POST['add_description']}' where sn in($pics)";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    }
    return $sn;
}

//批次加上標籤
function batch_add_tag()
{
    global $xoopsDB;

    $sel_tags_arr = $_POST['tag'];

    if (!empty($_POST['new_tag'])) {
        $new_tags = explode(',', $_POST['new_tag']);
        foreach ($new_tags as $t) {
            $t = trim($t);
            $sel_tags_arr[$t] = $t;
        }
    }

    foreach ($_POST['pic'] as $sn) {
        $old_tad_arr = '';
        $sql = 'select tag from ' . $xoopsDB->prefix('tad_gallery') . " where sn ='$sn'";
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        list($old_tad) = $xoopsDB->fetchRow($result);
        $old_tad_arr = explode(',', $old_tad);
        foreach ($old_tad_arr as $t) {
            $t = trim($t);
            $sel_tags_arr[$t] = $t;
        }
        $all_tags = implode(',', $sel_tags_arr);

        $sql = 'update ' . $xoopsDB->prefix('tad_gallery') . " set  `tag` = '{$all_tags}' where sn ='$sn'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    }

    return $sn;
}

//批次取消精選
function batch_del_good()
{
    global $xoopsDB;
    $pics = implode(',', $_POST['pic']);
    if (empty($pics)) {
        return;
    }

    $sql = 'update ' . $xoopsDB->prefix('tad_gallery') . " set  `good` = '0' where sn in($pics)";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    return $sn;
}

//批次清空標籤
function batch_remove_tag()
{
    global $xoopsDB;
    $pics = implode(',', $_POST['pic']);
    $sql = 'update ' . $xoopsDB->prefix('tad_gallery') . " set  `tag` = '' where sn in($pics)";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    return $sn;
}

//批次刪除
function batch_del()
{
    global $xoopsDB;
    foreach ($_POST['pic'] as $sn) {
        delete_tad_gallery($sn);
    }
}

//產生所有分類之rss
function mk_csn_rss_xml()
{
    global $xoopsDB, $xoopsModule;
    $sql = 'SELECT csn FROM ' . $xoopsDB->prefix('tad_gallery_cate') . '';
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    while (list($csn) = $xoopsDB->fetchRow($result)) {
        mk_rss_xml($csn);
    }
}

//tad_gallery_cate編輯表單
function tad_gallery_cate_form($csn = '')
{
    global $xoopsDB, $xoopsModuleConfig, $cate_show_mode_array, $xoopsTpl;
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    $xoopsTpl->assign('now_op', 'tad_gallery_cate_form');

    //抓取預設值
    if (!empty($csn)) {
        $DBV = Tadgallery::get_tad_gallery_cate($csn);
    } else {
        $DBV = [];
    }

    //預設值設定
    $csn = (!isset($DBV['csn'])) ? $csn : $DBV['csn'];
    $of_csn = (!isset($DBV['of_csn'])) ? '' : $DBV['of_csn'];
    $title = (!isset($DBV['title'])) ? '' : $DBV['title'];
    $content = (!isset($DBV['content'])) ? '' : $DBV['content'];
    $enable_group = (!isset($DBV['enable_group'])) ? '' : explode(',', $DBV['enable_group']);
    $enable_upload_group = (!isset($DBV['enable_upload_group'])) ? ['1'] : explode(',', $DBV['enable_upload_group']);
    $sort = (!isset($DBV['sort'])) ? auto_get_csn_sort() : $DBV['sort'];
    $passwd = (!isset($DBV['passwd'])) ? '' : $DBV['passwd'];
    $mode = (!isset($DBV['mode'])) ? '' : $DBV['mode'];
    $show_mode = (!isset($DBV['show_mode'])) ? $xoopsModuleConfig['index_mode'] : $DBV['show_mode'];
    $cover = (!isset($DBV['cover'])) ? '' : $DBV['cover'];

    $op = (empty($csn)) ? 'insert_tad_gallery_cate' : 'update_tad_gallery_cate';

    $xoopsTpl->assign('csn', $csn);
    $xoopsTpl->assign('of_csn', $of_csn);

    $of_csn_def = '';
    if ($of_csn) {
        $of_cate = Tadgallery::get_tad_gallery_cate($of_csn);
        $of_csn_def = $of_cate['title'];
    }
    $xoopsTpl->assign('of_csn_def', $of_csn_def);

    $xoopsTpl->assign('title', $title);
    $xoopsTpl->assign('content', $content);
    $xoopsTpl->assign('sort', $sort);
    $xoopsTpl->assign('passwd', $passwd);
    $xoopsTpl->assign('mode', $mode);
    $xoopsTpl->assign('show_mode', $show_mode);
    $xoopsTpl->assign('cover', $cover);
    $xoopsTpl->assign('op', $op);
    $xoopsTpl->assign('cate', Tadgallery::get_tad_gallery_cate($csn));

    $cover_select = get_cover($csn, $cover);

    //$xoopsTpl->assign('cate_select', $cate_select);
    $xoopsTpl->assign('cover_select', $cover_select);

    //可見群組
    $SelectGroup_name = new \XoopsFormSelectGroup('', 'enable_group', false, $enable_group, 4, true);
    $SelectGroup_name->addOption('', _MA_TADGAL_ALL_OK, false);
    $SelectGroup_name->setExtra("class='form-control'");
    $enable_group = $SelectGroup_name->render();
    $xoopsTpl->assign('enable_group', $enable_group);

    //可上傳群組
    $SelectGroup_name = new \XoopsFormSelectGroup('', 'enable_upload_group', false, $enable_upload_group, 4, true);
    //$SelectGroup_name->addOption("", _MA_TADGAL_ALL_OK, false);
    $SelectGroup_name->setExtra("class='form-control'");
    $enable_upload_group = $SelectGroup_name->render();
    $xoopsTpl->assign('enable_upload_group', $enable_upload_group);

    $cate_show_option = '';
    foreach ($cate_show_mode_array as $key => $value) {
        $selected = ($show_mode == $key) ? "selected='selected'" : '';
        $cate_show_option .= "<option value='$key' $selected>$value</option>";
    }

    $xoopsTpl->assign('cate_show_option', $cate_show_option);

    $cover_default = (!empty($cover)) ? XOOPS_URL . "/uploads/tadgallery/{$cover}" : '../images/folder_picture.png';
    $xoopsTpl->assign('cover_default', $cover_default);

    $path = get_tadgallery_cate_path($csn, false);
    $patharr = array_keys($path);
    $i = 0;
    foreach ($patharr as $k => $of_csn) {
        $j = $k + 1;
        $path_arr[$i]['of_csn'] = $of_csn;
        $path_arr[$i]['def_csn'] = $patharr[$j];
        $i++;
    }
    $xoopsTpl->assign('path_arr', $path_arr);

    //套用formValidator驗證機制
    $FormValidator = new FormValidator('#myForm', true);
    $FormValidator->render();
}

//新增資料到tad_gallery_cate中
function insert_tad_gallery_cate()
{
    global $xoopsDB, $xoopsUser;
    if (empty($_POST['title'])) {
        return;
    }

    if (empty($_POST['enable_group']) or in_array('', $_POST['enable_group'])) {
        $enable_group = '';
    } else {
        $enable_group = implode(',', $_POST['enable_group']);
    }

    if (empty($_POST['enable_upload_group'])) {
        $enable_upload_group = '1';
    } else {
        $enable_upload_group = implode(',', $_POST['enable_upload_group']);
    }

    $uid = $xoopsUser->uid();

    krsort($_POST['of_csn_menu']);
    //die(var_export($_POST['of_csn_menu']));
    foreach ($_POST['of_csn_menu'] as $sn) {
        if (empty($sn)) {
            continue;
        }
        $of_csn = $sn;
        break;
    }

    $myts = \MyTextSanitizer::getInstance();
    $title = $myts->addSlashes($_POST['title']);
    $content = $myts->addSlashes($_POST['content']);

    $of_csn = (int) $of_csn;

    $sql = 'insert into ' . $xoopsDB->prefix('tad_gallery_cate') . " (
    `of_csn`, `title`, `content`, `passwd`, `enable_group`, `enable_upload_group`, `sort`, `mode`, `show_mode`, `cover`, `no_hotlink`, `uid`) values('{$of_csn}','{$title}','{$content}','{$_POST['passwd']}','{$enable_group}','{$enable_upload_group}','{$_POST['sort']}','{$_POST['mode']}','{$_POST['show_mode']}','','',$uid)";
    $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    //取得最後新增資料的流水編號
    $csn = $xoopsDB->getInsertId();

    return $csn;
}

//重新產生縮圖，沒有 $kind 就是全部縮圖
function re_thumb($csn = '', $kind = '')
{
    global $xoopsDB, $xoopsModuleConfig, $type_to_mime;
    if (empty($csn)) {
        return 0;
    }

    //找出分類下所有相片
    $sql = 'select sn,title,filename,type,width,height,dir,post_date from ' . $xoopsDB->prefix('tad_gallery') . " where csn='{$csn}' order by photo_sort , post_date";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $n = 0;
    while (list($sn, $title, $filename, $type, $width, $height, $dir, $post_date) = $xoopsDB->fetchRow($result)) {
        $b_thumb_name = photo_name($sn, 'b', 1, $filename, $dir);
        if ('image' !== mb_substr($type, 0, 5)) {
            $file_ending = mb_substr(mb_strtolower($filename), -3); //file extension
            $type = $type_to_mime[$file_ending];
        }

        if ('m' === $kind or empty($kind)) {
            $m_thumb_name = photo_name($sn, 'm', 1, $filename, $dir);
            if ($width > $xoopsModuleConfig['thumbnail_m_width'] or $height > $xoopsModuleConfig['thumbnail_m_width']) {
                thumbnail($b_thumb_name, $m_thumb_name, $type, $xoopsModuleConfig['thumbnail_m_width']);
            }
        }

        if ('s' === $kind or empty($kind)) {
            $m_thumb_name = photo_name($sn, 'm', 1, $filename, $dir);
            $s_thumb_name = photo_name($sn, 's', 1, $filename, $dir);
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
function get_cover($csn = '', $cover = '')
{
    global $xoopsDB;
    if (empty($csn)) {
        return "<option value=''>" . _MD_TADGAL_COVER . '</option>';
    }

    $sql = 'select csn from ' . $xoopsDB->prefix('tad_gallery_cate') . " where csn='{$csn}' or of_csn='{$csn}'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    while (list($all_csn) = $xoopsDB->fetchRow($result)) {
        $csn_arr[] = $all_csn;
    }

    $csn_arr_str = implode(',', $csn_arr);

    $sql = 'select sn,dir,filename from ' . $xoopsDB->prefix('tad_gallery') . " where csn in($csn_arr_str)  order by filename";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    //$option="<option value=''>"._MD_TADGAL_COVER."</option>";
    $option = '';
    while (list($sn, $dir, $filename) = $xoopsDB->fetchRow($result)) {
        $selected = ("small/{$dir}/{$sn}_s_{$filename}" === $cover) ? 'selected' : '';
        $option .= "<option value='small/{$dir}/{$sn}_s_{$filename}' $selected>{$filename}</option>";
    }

    return $option;
}

/*-----------執行動作判斷區----------*/
require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$mode = system_CleanVars($_REQUEST, 'mode', '', 'string');
$kind = system_CleanVars($_REQUEST, 'kind', '', 'string');
$csn = system_CleanVars($_REQUEST, 'csn', 0, 'int');
$new_csn = system_CleanVars($_REQUEST, 'new_csn', 0, 'int');

switch ($op) {
    case 'del':
        batch_del();
        mk_rss_xml();
        mk_rss_xml($csn);
        header("location: {$_SERVER['PHP_SELF']}?csn={$csn}");
        exit;

    case 'move':
        batch_move($new_csn);
        mk_rss_xml();
        mk_rss_xml($csn);
        header("location: {$_SERVER['PHP_SELF']}?csn={$new_csn}");
        exit;

    case 'add_good':
        batch_add_good();
        header("location: {$_SERVER['PHP_SELF']}?csn={$csn}");
        exit;

    case 'del_good':
        batch_del_good();
        header("location: {$_SERVER['PHP_SELF']}?csn={$csn}");
        exit;

    case 'add_tag':
        batch_add_tag();
        header("location: {$_SERVER['PHP_SELF']}?csn={$csn}");
        exit;

    case 'remove_tag':
        batch_remove_tag();
        header("location: {$_SERVER['PHP_SELF']}?csn={$csn}");
        exit;

    case 'add_title':
        batch_add_title();
        header("location: {$_SERVER['PHP_SELF']}?csn={$csn}");
        exit;

    case 'add_description':
        batch_add_description();
        header("location: {$_SERVER['PHP_SELF']}?csn={$csn}");
        exit;

    //產生Media RSS
    case 'mk_rss_xml':
        mk_rss_xml();
        mk_csn_rss_xml();
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    case 'chg_mode':
        $_SESSION['gallery_list_mode'] = $mode;
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    //新增資料
    case 'insert_tad_gallery_cate':
        $csn = insert_tad_gallery_cate();
        mk_rss_xml();
        mk_rss_xml($csn);
        header("location: {$_SERVER['PHP_SELF']}?csn=$csn");
        exit;

    //刪除資料
    case 'delete_tad_gallery_cate':
        delete_tad_gallery_cate($csn);
        mk_rss_xml();
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    //更新資料
    case 'update_tad_gallery_cate':
        update_tad_gallery_cate($csn);
        mk_rss_xml();
        mk_rss_xml($csn);
        header("location: {$_SERVER['PHP_SELF']}?csn=$csn");
        exit;

    //新增資料
    case 'tad_gallery_cate_form':
        list_tad_gallery_cate_tree($csn);
        tad_gallery_cate_form($csn);
        break;

    //重新產生縮圖
    case 're_thumb':
        $n = re_thumb($csn, $kind);
        redirect_header("{$_SERVER['PHP_SELF']}?csn={$csn}", 3, "All ($n) OK!");
        break;

    //預設動作
    default:

        list_tad_gallery_cate_tree($csn);
        list_tad_gallery($csn, 1);
        break;
}

/*-----------秀出結果區--------------*/
echo "<a name='gallery_top'></a>";

require_once __DIR__ . '/footer.php';
