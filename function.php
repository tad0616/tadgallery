<?php

use XoopsModules\Tadtools\Utility;

//引入TadTools的函式庫
if (!file_exists(XOOPS_ROOT_PATH . '/modules/tadtools/tad_function.php')) {
    redirect_header('http://campus-xoops.tn.edu.tw/modules/tad_modules/index.php?module_sn=1', 3, _TAD_NEED_TADTOOLS);
}
require_once XOOPS_ROOT_PATH . '/modules/tadtools/tad_function.php';

define('_TADGAL_UP_FILE_DIR', XOOPS_ROOT_PATH . '/uploads/tadgallery/');
define('_TADGAL_UP_FILE_URL', XOOPS_URL . '/uploads/tadgallery/');

$uid_dir = 0;
if (isset($xoopsUser) and is_object($xoopsUser)) {
    $uid_dir = $xoopsUser->uid();
}

define('_TADGAL_UP_IMPORT_DIR', _TADGAL_UP_FILE_DIR . "upload_pics/user_{$uid_dir}/");

Utility::mk_dir(_TADGAL_UP_FILE_DIR . 'upload_pics');
Utility::mk_dir(_TADGAL_UP_IMPORT_DIR);

define('_TADGAL_UP_MP3_DIR', _TADGAL_UP_FILE_DIR . 'mp3/');
define('_TADGAL_UP_MP3_URL', _TADGAL_UP_FILE_URL . 'mp3/');

require_once XOOPS_ROOT_PATH . '/modules/tadgallery/class/tadgallery.php';
$type_to_mime['png'] = 'image/png';
$type_to_mime['jpg'] = 'image/jpg';
$type_to_mime['peg'] = 'image/jpg';
$type_to_mime['gif'] = 'image/gif';

$cate_show_mode_array = ['normal' => _TADGAL_NORMAL, 'flickr' => _TADGAL_FLICKR, 'waterfall' => _TADGAL_WATERFALL];

//取得路徑
function get_tadgallery_cate_path($the_csn = 0, $include_self = true)
{
    global $xoopsDB;
    $the_csn = (int) $the_csn;

    $arr[0]['csn'] = '0';
    $arr[0]['title'] = "<i class='fa fa-home'></i>";
    $arr[0]['sub'] = get_tad_gallery_sub_cate(0);
    if (!empty($the_csn)) {
        $tadgallery = new tadgallery();

        $tbl = $xoopsDB->prefix('tad_gallery_cate');
        $sql = "SELECT t1.csn AS lev1, t2.csn as lev2, t3.csn as lev3, t4.csn as lev4, t5.csn as lev5, t6.csn as lev6, t7.csn as lev7
            FROM `{$tbl}` t1
            LEFT JOIN `{$tbl}` t2 ON t2.of_csn = t1.csn
            LEFT JOIN `{$tbl}` t3 ON t3.of_csn = t2.csn
            LEFT JOIN `{$tbl}` t4 ON t4.of_csn = t3.csn
            LEFT JOIN `{$tbl}` t5 ON t5.of_csn = t4.csn
            LEFT JOIN `{$tbl}` t6 ON t6.of_csn = t5.csn
            LEFT JOIN `{$tbl}` t7 ON t7.of_csn = t6.csn
            WHERE t1.of_csn = '0' order by t1.sort";
        $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            if (in_array($the_csn, $all)) {
                //$main.="-";
                foreach ($all as $csn) {
                    if (!empty($csn)) {
                        if (!$include_self and $csn == $the_csn) {
                            break;
                        }
                        $arr[$csn] = $tadgallery->get_tad_gallery_cate($csn);
                        $arr[$csn]['sub'] = get_tad_gallery_sub_cate($csn);
                        if ($csn == $the_csn) {
                            break;
                        }
                    }
                }
                //$main.="<br>";
                break;
            }
        }
    }

    return $arr;
}

function get_tad_gallery_sub_cate($csn = '0')
{
    global $xoopsDB;
    $sql = 'select csn,title from ' . $xoopsDB->prefix('tad_gallery_cate') . " where of_csn='{$csn}' order by sort";
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    $csn_arr = [];
    while (list($csn, $title) = $xoopsDB->fetchRow($result)) {
        $csn_arr[$csn] = $title;
    }

    return $csn_arr;
}

//製作EXIF語法
function mk_exif($result = [])
{
    $Longitude = getGps($result['GPS']['GPSLongitude'], $result['GPS']['GPSLongitudeRef']);
    $Latitude = getGps($result['GPS']['GPSLatitude'], $result['GPS']['GPSLatitudeRef']);

    $main = "[FILE][FileName]={$result['FILE']['FileName']}||[FILE][FileType]={$result['FILE']['FileType']}||[FILE][MimeType]={$result['FILE']['MimeType']}||[FILE][FileSize]={$result['FILE']['FileSize']}||[COMPUTED][Width]={$result['COMPUTED']['Width']}||[COMPUTED][Height]={$result['COMPUTED']['Height']}||[IFD0][Make]={$result['IFD0']['Make']}||[IFD0][Model]={$result['IFD0']['Model']}||[IFD0][DateTime]={$result['IFD0']['DateTime']}||[IFD0][Orientation]={$result['IFD0']['Orientation']}||[EXIF][ExposureTime]={$result['EXIF']['ExposureTime']}||[EXIF][ISOSpeedRatings]={$result['EXIF']['ISOSpeedRatings']}||[COMPUTED][ApertureFNumber]={$result['COMPUTED']['ApertureFNumber']}||[EXIF][Flash]={$result['EXIF']['Flash']}||[EXIF][FocalLength]={$result['EXIF']['FocalLength']}mm||[EXIF][ExposureBiasValue]={$result['EXIF']['ExposureBiasValue']}EV||[GPS][latitude]={$Latitude}||[GPS][longitude]={$Longitude}";

    return $main;
}

function getGps($exifCoord, $hemi)
{
    $degrees = count($exifCoord) > 0 ? gps2Num($exifCoord[0]) : 0;
    $minutes = count($exifCoord) > 1 ? gps2Num($exifCoord[1]) : 0;
    $seconds = count($exifCoord) > 2 ? gps2Num($exifCoord[2]) : 0;

    $flip = ('W' === $hemi or 'S' === $hemi) ? -1 : 1;

    return $flip * ($degrees + $minutes / 60 + $seconds / 3600);
}

function gps2Num($coordPart)
{
    $parts = explode('/', $coordPart);

    if (count($parts) <= 0) {
        return 0;
    }

    if (1 == count($parts)) {
        return $parts[0];
    }

    return (float) $parts[0] / (float) $parts[1];
}

//上傳者選單
function get_all_author($now_uid = '')
{
    global $xoopsDB;
    $sql = 'SELECT DISTINCT uid FROM ' . $xoopsDB->prefix('tad_gallery') . '';
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    $option = "<option value=''>" . _MD_TADGAL_ALL_AUTHOR . '</option>';
    while (list($uid) = $xoopsDB->fetchRow($result)) {
        $uid_name = \XoopsUser::getUnameFromId($uid, 1);
        $uid_name = (empty($uid_name)) ? XoopsUser::getUnameFromId($uid, 0) : $uid_name;

        $selected = ($now_uid == $uid) ? 'selected' : '';
        $option .= "<option value='{$uid}' $selected>{$uid_name}</option>";
    }

    return $option;
}

//取出所有標籤(傳回陣列)
function get_all_tag()
{
    global $xoopsDB;
    $tag_all = [];
    $sql = 'SELECT tag FROM ' . $xoopsDB->prefix('tad_gallery') . " WHERE tag!=''";
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    while (list($tag) = $xoopsDB->fetchRow($result)) {
        $tag_arr = explode(',', $tag);

        foreach ($tag_arr as $val) {
            $val = trim($val);
            $tag_all[$val]++;
        }
    }

    return $tag_all;
}

//製作標籤勾選單
function tag_select($tag = '', $id_name = '')
{
    $tag_arr = explode(',', $tag);

    $tag_all = get_all_tag();
    $menu = '';
    foreach ($tag_all as $tag => $n) {
        if (empty($tag)) {
            continue;
        }

        $checked = (in_array($tag, $tag_arr)) ? 'checked' : '';
        $js_code = (!empty($id_name)) ? " onClick=\"check_one('{$id_name}',false)\" onkeypress=\"check_one('{$id_name}',false)\"" : '';

        $menu .= "
    <label class=\"checkbox-inline\">
      <input type=\"checkbox\" name=\"tag[{$tag}]\" value=\"{$tag}\" {$checked} {$js_code}>{$tag}
    </label>
    ";
    }

    return $menu;
}

//變更精選相片狀態
function update_tad_gallery_good($sn = '', $v = '0')
{
    global $xoopsDB;
    $sql = 'update ' . $xoopsDB->prefix('tad_gallery') . " set `good`='{$v}' where sn='{$sn}'";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
}

//找出上一張或下一張
function get_pre_next($csn = '', $sn = '')
{
    global $xoopsDB;
    $sql = 'select sn from ' . $xoopsDB->prefix('tad_gallery') . " where csn='{$csn}' order by photo_sort , post_date";
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    $stop = false;
    $pre = 0;
    while (list($psn) = $xoopsDB->fetchRow($result)) {
        if ($stop) {
            $next = $psn;
            break;
        }
        if ($psn == $sn) {
            $now = $psn;
            $stop = true;
        } else {
            $pre = $psn;
        }
    }
    $main['pre'] = $pre;
    $main['next'] = $next;

    return $main;
}

//刪除tad_gallery某筆資料資料
function delete_tad_gallery($sn = '')
{
    global $xoopsDB;

    $tadgallery = new tadgallery();

    $pic = $tadgallery->get_tad_gallery($sn);

    $sql = 'delete from ' . $xoopsDB->prefix('tad_gallery') . " where sn='$sn'";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

    if (is_file(_TADGAL_UP_FILE_DIR . "small/{$pic['dir']}/{$sn}_s_{$pic['filename']}")) {
        unlink(_TADGAL_UP_FILE_DIR . "small/{$pic['dir']}/{$sn}_s_{$pic['filename']}");
    }

    if (is_file(_TADGAL_UP_FILE_DIR . "medium/{$pic['dir']}/{$sn}_m_{$pic['filename']}")) {
        unlink(_TADGAL_UP_FILE_DIR . "medium/{$pic['dir']}/{$sn}_m_{$pic['filename']}");
    }

    unlink(_TADGAL_UP_FILE_DIR . "{$pic['dir']}/{$sn}_{$pic['filename']}");

    return $pic['csn'];
}

//把檔案大小轉為文字型態
function sizef($size = '', $html = true)
{
    if ($size > 1048576) {
        $size_txt = ($html) ? round($size / 1048576, 1) . ' <font color=red>MB</font>' : round($size / 1048576, 1) . ' MB';
    } elseif ($size > 1024) {
        $size_txt = ($html) ? round($size / 1024, 1) . ' <font color=blue>KB</font>' : round($size / 1024, 1) . ' KB';
    } else {
        $size_txt = ($html) ? $size . ' <font color=gray>Bytes</font>' : $size . ' Bytes';
    }

    return $size_txt;
}

//取得分類下拉選單
function get_tad_gallery_cate_option($of_csn = 0, $level = 0, $v = '', $chk_view = 1, $chk_up = 0, $this_csn = '', $no_self = '0')
{
    global $xoopsDB, $xoopsUser, $xoopsModule, $isAdmin;

    if ($xoopsUser) {
        $module_id = $xoopsModule->getVar('mid');
        $isAdmin = $xoopsUser->isAdmin($module_id);
    } else {
        $isAdmin = false;
    }

    $tadgallery = new tadgallery();
    $show_uid = isset($_SESSION['show_uid']) ? (int) $_SESSION['show_uid'] : 0;
    if ($show_uid) {
        $tadgallery->set_show_uid($show_uid);
    }

    $cate_count = $tadgallery->get_tad_gallery_cate_count($_SESSION['gallery_list_mode']);

    //$left=$level*10;
    $level += 1;

    $syb = str_repeat('-', $level) . ' ';

    $option = ($of_csn) ? '' : "<option value='0'>" . _MD_TADGAL_CATE_SELECT . '</option>';

    $sql = 'select csn,title from ' . $xoopsDB->prefix('tad_gallery_cate') . " where of_csn='{$of_csn}' order by sort";
    $result = $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

    $ok_cat = $ok_up_cat = '';

    if ($chk_view) {
        $ok_cat = $tadgallery->chk_cate_power();
    }

    if ($chk_up) {
        $ok_up_cat = $tadgallery->chk_cate_power('upload');
    }

    while (list($csn, $title) = $xoopsDB->fetchRow($result)) {
        $csn = (int) $csn;

        if ($chk_view and is_array($ok_cat)) {
            if (!in_array($csn, $ok_cat)) {
                continue;
            }
        }

        if ($chk_up and is_array($ok_up_cat)) {
            if (!in_array($csn, $ok_up_cat)) {
                continue;
            }
        }
        if ('1' == $no_self and $this_csn == $csn) {
            continue;
        }

        $selected = ($v == $csn) ? 'selected' : '';
        $count = (empty($cate_count[$csn]['file'])) ? 0 : $cate_count[$csn]['file'];
        $option .= "<option value='{$csn}' $selected>{$syb}{$title}({$count})</option>";
        $option .= get_tad_gallery_cate_option($csn, $level, $v, $chk_view, $chk_up, $this_csn, $no_self);
        // die($option);
    }
    // die(var_export($option));
    return $option;
}

//把多重陣列變成字串
function implodeArray2D($sep = '', $array = '', $pre = '')
{
    $myts = MyTextSanitizer::getInstance();
    $array1 = ['FILE', 'COMPUTED', 'IFD0', 'EXIF', 'GPS', 'SubIFD', 'IFD1', 'GPSLongitude', 'GPSLatitude'];
    $str = '';
    foreach ($array as $key => $val) {
        if (is_array($val)) {
            if (!in_array($key, $array1)) {
                continue;
            }

            $str .= implodeArray2D($sep, $val, "[$key]");
        } else {
            $key = $myts->addSlashes($key);
            $val = $myts->addSlashes($val);
            if ('UndefinedTag' === mb_substr($key, 0, 12)) {
                continue;
            }

            if (mb_strlen($val) > 200) {
                continue;
            }

            $str .= "{$pre}[$key]={$val}{$sep}";
        }
    }

    return $str;
}

//更新tad_gallery_cate某一筆資料
function update_tad_gallery_cate($csn = '')
{
    global $xoopsDB, $xoopsUser;

    if ($xoopsUser) {
        $uid = $xoopsUser->uid();
    } else {
        redirect_header(XOOPS_URL . '/user.php', 3, _TADGAL_NO_UPLOAD_POWER);
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

    krsort($_POST['of_csn_menu']);
    //die(var_export($_POST['of_csn_menu']));
    foreach ($_POST['of_csn_menu'] as $sn) {
        if (empty($sn)) {
            continue;
        }
        $of_csn = $sn;
        break;
    }
    $myts = MyTextSanitizer::getInstance();
    $title = $myts->addSlashes($_POST['title']);
    $content = $myts->addSlashes($_POST['content']);
    $mode = $myts->addSlashes($_POST['mode']);
    $show_mode = $myts->addSlashes($_POST['show_mode']);
    $cover = $myts->addSlashes($_POST['cover']);
    $of_csn = (int) $of_csn;

    $sql = 'update ' . $xoopsDB->prefix('tad_gallery_cate') . " set
    `of_csn` = '{$of_csn}',
    `title` = '{$title}',
    `content` = '{$content}',
    `passwd` = '{$_POST['passwd']}',
    `enable_group` = '{$enable_group}',
    `enable_upload_group` = '{$enable_upload_group}' ,
    `mode` = '{$mode}',
    `show_mode` = '{$show_mode}',
    `uid`='{$uid}',
    `cover` = '{$cover}'
    where csn='$csn'";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

    return $csn;
}

//更新資料到tad_gallery中
function update_tad_gallery($sn = '')
{
    global $xoopsDB, $xoopsUser;

    $myts = MyTextSanitizer::getInstance();

    krsort($_POST['csn_menu']);
    foreach ($_POST['csn_menu'] as $cate_sn) {
        if (empty($cate_sn)) {
            continue;
        }
        $csn = $cate_sn;
        break;
    }
    if (!empty($_POST['new_csn'])) {
        $new_csn = $myts->addSlashes($new_csn);
        $sort = (int) $_POST['sort'];

        $csn = add_tad_gallery_cate($csn, $new_csn, $sort);
    }

    $uid = $xoopsUser->getVar('uid');

    if (!empty($_POST['csn'])) {
        $_SESSION['tad_gallery_csn'] = $_POST['csn'];
    }

    $title = $myts->addSlashes($_POST['title']);
    $description = $myts->addSlashes($_POST['description']);
    $new_tag = $myts->addSlashes($_POST['new_tag']);

    $all_tag = implode(',', $_POST['tag']);

    if (!empty($new_tag)) {
        $new_tags = explode(',', $new_tag);
    }

    foreach ($new_tags as $tag) {
        if (!empty($tag)) {
            $tag = trim($tag);
            $all_tag .= ",{$tag}";
        }
    }

    $is360 = (int) $_POST['is360'];

    $sql = 'update ' . $xoopsDB->prefix('tad_gallery') . " set `csn`='{$csn}',`title`='{$title}',`description`='{$description}',`tag`='{$all_tag}',`is360`='{$is360}' where sn='{$sn}'";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

    //設為封面
    if (!empty($_POST['cover'])) {
        $sql = 'update ' . $xoopsDB->prefix('tad_gallery_cate') . " set `cover`='{$_POST['cover']}' where csn='{$_POST['csn']}'";
        $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);
    }
}

//刪除tad_gallery_cate某筆資料資料
function delete_tad_gallery_cate($csn = '')
{
    global $xoopsDB;

    //先找出底下所有相片
    $sql = 'select sn from ' . $xoopsDB->prefix('tad_gallery') . " where csn='$csn'";
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    while (list($sn) = $xoopsDB->fetchRow($result)) {
        delete_tad_gallery($sn);
    }

    //找出底下分類，並將分類的所屬分類清空
    $sql = 'update ' . $xoopsDB->prefix('tad_gallery_cate') . " set  of_csn='' where of_csn='$csn'";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

    //刪除之
    $sql = 'delete from ' . $xoopsDB->prefix('tad_gallery_cate') . " where csn='$csn'";
    $xoopsDB->queryF($sql) or web_error($sql, __FILE__, __LINE__);

    //刪掉RSS
    $rss_filename = _TADGAL_UP_FILE_DIR . "photos{$csn}.rss";
    unlink($rss_filename);
}

//自動取得某分類下最大的排序
function auto_get_csn_sort($csn = '')
{
    global $xoopsDB;
    $sql = 'select max(`sort`) from ' . $xoopsDB->prefix('tad_gallery_cate') . " where of_csn='{$csn}' group by of_csn";
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    list($max_sort) = $xoopsDB->fetchRow($result);

    return ++$max_sort;
}

//新增資料到tad_gallery_cate中
function add_tad_gallery_cate($csn = 0, $new_csn = '', $sort = 0)
{
    global $xoopsDB, $xoopsUser, $isAdmin;
    if (empty($new_csn)) {
        return;
    }

    $csn = (int) $csn;

    $tadgallery = new tadgallery();
    //找出目前分類的資料
    if ($csn) {
        $cate = $tadgallery->get_tad_gallery_cate($csn);
    } else {
        $cate['enable_group'] = '';
        $cate['enable_upload_group'] = '1';
    }

    //找出目前登入者可以上傳的分類編號
    $upload_powers = $tadgallery->chk_cate_power('upload');
    if ($isAdmin) {
        $upload_powers[] = 0;
    }

    //檢查目前使用者是否在可上傳的分類中
    if (!in_array($csn, $upload_powers)) {
        redirect_header($_SERVER['PHP_SELF'], 3, _TADGAL_NO_UPLOAD_POWER);
    }

    if (empty($_POST['enable_group'])) {
        $enable_group = $cate['enable_group'];
    } else {
        $enable_group = implode(',', $_POST['enable_group']);
    }

    if (empty($_POST['enable_upload_group'])) {
        $enable_upload_group = $cate['enable_upload_group'];
    } else {
        $enable_upload_group = implode(',', $_POST['enable_upload_group']);
    }

    // $sort = (empty($sort)) ? auto_get_csn_sort() : $sort;
    $uid = $xoopsUser->uid();
    $csn = (int) $csn;
    $sort = (int) $sort;

    $sql = 'insert into ' . $xoopsDB->prefix('tad_gallery_cate') . " (
    `of_csn`, `title`, `content`, `passwd`, `enable_group`, `enable_upload_group`, `sort`, `mode`, `show_mode`, `cover`, `no_hotlink`, `uid`) values('{$csn}','{$new_csn}','','','{$enable_group}','{$enable_upload_group}','$sort','{$_POST['mode']}','normal','','','{$uid}')";
    // die($sql);
    $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    //取得最後新增資料的流水編號
    $csn = $xoopsDB->getInsertId();

    return $csn;
}

//取得tad_gallery_cate所有資料陣列
function get_tad_gallery_cate_all()
{
    global $xoopsDB;
    $sql = 'SELECT csn,title FROM ' . $xoopsDB->prefix('tad_gallery_cate');
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    while (list($csn, $title) = $xoopsDB->fetchRow($result)) {
        $data[$csn] = $title;
    }

    return $data;
}

/********************* 圖片函數 *********************/
//圖片位置及名稱
function photo_name($sn = '', $kind = '', $local = '1', $filename = '', $dir = '')
{
    global $xoopsDB;
    if (empty($filename)) {
        $sql = 'select filename,dir from ' . $xoopsDB->prefix('tad_gallery') . " where sn='{$sn}'";
        $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
        list($filename, $dir) = $xoopsDB->fetchRow($result);
    }
    $place = ($local) ? _TADGAL_UP_FILE_DIR : _TADGAL_UP_FILE_URL;

    if ('m' === $kind) {
        $key = 'm_';
        $place .= 'medium/';
    } elseif ('s' === $kind) {
        $key = 's_';
        $place .= 'small/';
    } else {
        $key = '';
    }
    Utility::mk_dir("{$place}{$dir}");

    $photo_name = "{$place}{$dir}/{$sn}_{$key}{$filename}";

    return $photo_name;
}

//做縮圖
if (!function_exists('thumbnail')) {
    function thumbnail($filename = '', $thumb_name = '', $type = 'image/jpeg', $width = '160', $angle = 0)
    {
        // set_time_limit(0);
        // ini_set('memory_limit', '100M');
        // Get new sizes
        list($old_width, $old_height) = getimagesize($filename);

        if (0 != $angle) {
            $h = $old_height;
            $w = $old_width;

            $old_width = $h;
            $old_height = $w;
        }

        // die("$old_width, $old_height");

        if ($old_width > $width) {
            $percent = ($old_width > $old_height) ? round($width / $old_width, 2) : round($width / $old_height, 2);

            $newwidth = ($old_width > $old_height) ? $width : $old_width * $percent;
            $newheight = ($old_width > $old_height) ? $old_height * $percent : $width;

            // Load
            $thumb = imagecreatetruecolor($newwidth, $newheight);

            if ('image/jpeg' === $type or 'image/jpg' === $type or 'image/pjpg' === $type or 'image/pjpeg' === $type) {
                $source = imagecreatefromjpeg($filename);

                $type = 'image/jpeg';
            } elseif ('image/png' === $type) {
                $source = imagecreatefrompng($filename);
                $type = 'image/png';
            } elseif ('image/gif' === $type) {
                $source = imagecreatefromgif($filename);
                $type = 'image/gif';
            }
            if (0 != $angle) {
                $source = imagerotate($source, $angle, 0);
            }
            // Resize
            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $old_width, $old_height);

            header("Content-type: $type");
            if ('image/jpeg' === $type) {
                imagejpeg($thumb, $thumb_name);
            } elseif ('image/png' === $type) {
                imagepng($thumb, $thumb_name);
            } elseif ('image/gif' === $type) {
                imagegif($thumb, $thumb_name);
            }

            return;
        }
        copy($filename, $thumb_name);
    }
}

/********************* 預設函數 *********************/

//製作Media RSS
function mk_rss_xml($the_csn = 0)
{
    global $xoopsDB, $xoopsModule, $xoopsConfig;
    $tadgallery = new tadgallery();
    $ok_cat = $tadgallery->chk_cate_power();
    $the_csn = (int) $the_csn;

    if (!empty($the_csn)) {
        if (in_array($the_csn, $ok_cat)) {
            $where = "and a.csn='$the_csn'";
            $cate = $tadgallery->get_tad_gallery_cate($the_csn);
            $rss_title = $cate['title'];
            $rss_link = XOOPS_URL . "/modules/tadgallery/index.php?csn={$the_csn}";
            $rss_filename = _TADGAL_UP_FILE_DIR . "photos{$the_csn}.rss";
        } else {
            return;
        }
    } else {
        $ok_str = implode("','", $ok_cat);
        $where = "and a.csn in('{$ok_str}') ";
        $rss_title = $xoopsConfig['sitename'];
        $rss_link = XOOPS_URL . '/modules/tadgallery';
        $rss_filename = _TADGAL_UP_FILE_DIR . 'photos.rss';
    }

    $sql = 'select a.sn,a.csn,a.title,a.description,a.filename,a.size,a.dir from ' . $xoopsDB->prefix('tad_gallery') . ' as a , ' . $xoopsDB->prefix('tad_gallery_cate') . " as b where a.csn=b.csn $where and b.passwd='' and b.enable_group='' order by a.post_date desc";
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);

    $main = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>
<rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
  <atom:icon>' . XOOPS_URL . "/modules/tadgallery/images/piclen_logo.png</atom:icon>
  <generator>Tad Gallery</generator>
  <title>{$rss_title}</title>
  <link>{$rss_link}</link>
  <description></description>\n";

    while (list($sn, $csn, $title, $description, $filename, $size, $dir) = $xoopsDB->fetchRow($result)) {
        $title = (empty($title)) ? $filename : $title;
        $title = htmlspecialchars($title);
        $description = htmlspecialchars($description);
        $filename = urlencode(htmlspecialchars($filename));
        $pic_url = $tadgallery->get_pic_url($dir, $sn, $filename);
        $mpic_url = $tadgallery->get_pic_url($dir, $sn, $filename, 'm');
        $spic_url = $tadgallery->get_pic_url($dir, $sn, $filename, 's');
        $main .= "    <item>
      <title>{$title}</title>
      <link>" . XOOPS_URL . "/modules/tadgallery/view.php?sn={$sn}</link>
      <guid>" . XOOPS_URL . "/modules/tadgallery/view.php?sn={$sn}#photo{$sn}</guid>
      <media:thumbnail url=\"{$spic_url}\">
      <media:content url=\"{$pic_url}\" fileSize=\"{$size}\">
      <media:title type=\"plain\">{$title}</media:title>
      <media:description type=\"plain\">{$description}</media:description>
    </item>\n";
    }
    $main .= "      </channel>
</rss>\n";

    $main = to_utf8($main);

    if (!$handle = fopen($rss_filename, 'wb')) {
        redirect_header($_SERVER['PHP_SELF'], 3, sprintf(_MD_TADPLAYER_CANT_OPEN, $rss_filename));
    }

    if (false === fwrite($handle, $main)) {
        redirect_header($_SERVER['PHP_SELF'], 3, sprintf(_MD_TADPLAYER_CANT_WRITE, $rss_filename));
    }
    fclose($handle);
}

if (!function_exists('file_put_contents')) {
    function file_put_contents($filename, $data)
    {
        $file = fopen($filename, 'wb');
        if (!$file) {
            return false;
        }
        $bytes = fwrite($file, $data);
        fclose($file);

        return $bytes;
    }
}

function tg_html5($data = '')
{
    $main = '<!DOCTYPE html>
      <html lang="zh-TW">
      <head>
        <meta charset="UTF-8">
        <title>Document</title>
      </head>
      <body>

        <link rel="stylesheet" type="text/css" media="all" title="Style sheet" href="module.css">
        <div class="container-fluid">
          <div class="row">
          ' . $data . '
          </div>
        </div>
      </body>
      </html>';

    return $main;
}

function get360_arr()
{
    global $xoopsModuleConfig;
    $xoopsModuleConfig['model360'] = trim($xoopsModuleConfig['model360']);
    if (empty($xoopsModuleConfig['model360'])) {
        $xoopsModuleConfig['model360'] = 'LG-R105;RICOH THETA S';
    }
    $model360 = explode(';', $xoopsModuleConfig['model360']);

    return $model360;
}
