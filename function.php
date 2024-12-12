<?php
use XoopsModules\Tadgallery\Tadgallery;
use XoopsModules\Tadgallery\Tools;
use XoopsModules\Tadtools\Utility;

xoops_loadLanguage('main', 'tadtools');
if (!isset($tad_gallery_adm)) {
    $tad_gallery_adm = isset($xoopsUser) && \is_object($xoopsUser) ? $xoopsUser->isAdmin() : false;
}

if (!isset($_SESSION['gallery_list_mode'])) {
    $_SESSION['gallery_list_mode'] = 'normal';
}
// require_once XOOPS_ROOT_PATH . '/modules/tadgallery/class/Tadgallery.php';

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

$cate_show_mode_array = ['normal' => _TADGAL_NORMAL, 'flickr' => _TADGAL_FLICKR, 'waterfall' => _TADGAL_WATERFALL];

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
    $degrees = ($exifCoord && count($exifCoord) > 0) ? gps2Num($exifCoord[0]) : 0;
    $minutes = ($exifCoord && count($exifCoord) > 1) ? gps2Num($exifCoord[1]) : 0;
    $seconds = ($exifCoord && count($exifCoord) > 2) ? gps2Num($exifCoord[2]) : 0;

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
    global $xoopsDB, $xoopsModuleConfig;
    $option = '';
    if ($xoopsModuleConfig['show_author_menu']) {
        $sql = 'SELECT DISTINCT `uid` FROM `' . $xoopsDB->prefix('tad_gallery') . '`';
        $result = Utility::query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $option = "<option value=''>" . _MD_TADGAL_ALL_AUTHOR . '</option>';
        while (list($uid) = $xoopsDB->fetchRow($result)) {
            $uid_name = \XoopsUser::getUnameFromId($uid, 1);
            $uid_name = empty($uid_name) ? \XoopsUser::getUnameFromId($uid, 0) : $uid_name;

            $selected = ($now_uid == $uid) ? 'selected' : '';
            $option .= "<option value='{$uid}' $selected>{$uid_name}</option>";
        }
    }
    return $option;
}

//取出所有標籤(傳回陣列)
function get_all_tag()
{
    global $xoopsDB;
    $tag_all = [];
    $sql = 'SELECT `tag` FROM `' . $xoopsDB->prefix('tad_gallery') . '` WHERE `tag`!=?';
    $result = Utility::query($sql, 's', ['']) or Utility::web_error($sql, __FILE__, __LINE__);

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
    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_gallery') . '` SET `good`=? WHERE `sn`=?';
    Utility::query($sql, 'si', [$v, $sn]) or Utility::web_error($sql, __FILE__, __LINE__);
}

//找出上一張或下一張
function get_pre_next($csn = '', $sn = '')
{
    global $xoopsDB;
    $sql = 'SELECT `sn` FROM `' . $xoopsDB->prefix('tad_gallery') . '` WHERE `csn`=? ORDER BY `photo_sort`, `post_date`';
    $result = Utility::query($sql, 'i', [$csn]) or Utility::web_error($sql, __FILE__, __LINE__);

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

    $pic = Tools::get_tad_gallery($sn);

    $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_gallery') . '` WHERE `sn`=?';
    Utility::query($sql, 'i', [$sn]) or Utility::web_error($sql, __FILE__, __LINE__);

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
function get_tad_gallery_cate_option($this_csn = 0, $this_of_csn = 0, $show_all = 1, $chk_view = 1, $chk_up = 0, $no_self = '0')
{
    global $xoopsDB;

    $tadgallery = new Tadgallery();
    $show_uid = isset($_SESSION['show_uid']) ? (int) $_SESSION['show_uid'] : 0;
    if ($show_uid) {
        $tadgallery->set_show_uid($show_uid);
    }

    $cate_count = $tadgallery->get_tad_gallery_cate_count($_SESSION['gallery_list_mode']);

    $sql = 'SELECT `csn`, `of_csn`, `title` FROM `' . $xoopsDB->prefix('tad_gallery_cate') . '` ORDER BY `sort`';
    $result = Utility::query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $ok_cat = $ok_up_cat = '';

    if ($chk_view) {
        $ok_cat = Tools::chk_cate_power();
    }

    if ($chk_up) {
        $ok_up_cat = Tools::chk_cate_power('upload');
    }

    $option_arr = [];
    while (list($csn, $of_csn, $title) = $xoopsDB->fetchRow($result)) {
        $option = [];
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

        $count = (empty($cate_count[$csn]['file'])) ? '' : " ({$cate_count[$csn]['file']})";

        $option['csn'] = $csn;
        $option['title'] = $title . $count;

        $option_arr[$of_csn][] = $option;
    }

    $options = generate_option($option_arr, $this_csn, $this_of_csn, $show_all);

    return $options;
}

//將陣列遞迴輸出
function generate_option($option_arr = [], $this_csn = '', $this_of_csn = '', $show_all = true, $parent_csn = 0, $level = 0)
{
    if ($level == 0 && $show_all) {
        $options = "<option value='0'>" . _MD_TADGAL_CATE_SELECT . "</option>";
    } else {
        $options = '';
    }

    $level += 1;
    $syb = str_repeat("-", $level * 4) . " ";
    foreach ($option_arr[$parent_csn] as $sub_option) {
        $csn = $sub_option['csn'];
        $selected = ($this_of_csn == $csn) ? "selected" : "";
        $options .= "<option value='{$csn}' $selected>{$syb}{$sub_option['title']}</option>";
        if (isset($option_arr[$csn])) {
            $options .= generate_option($option_arr, $this_csn, $this_of_csn, $show_all, $csn, $level);
        }

    }
    return $options;
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

    $title = (string) $_POST['title'];
    $content = (string) $_POST['content'];
    $mode = (string) $_POST['mode'];
    $show_mode = (string) $_POST['show_mode'];
    $cover = (string) $_POST['cover'];
    $of_csn = (int) $$_POST['of_csn_menu'];

    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_gallery_cate') . '` SET `of_csn` = ?, `title` = ?, `content` = ?, `passwd` = ?, `enable_group` = ?, `enable_upload_group` = ?, `mode` = ?, `show_mode` = ?, `uid` = ?, `cover` = ? WHERE `csn` = ?';
    Utility::query($sql, 'isssssssisi', [$of_csn, $title, $content, $_POST['passwd'], $enable_group, $enable_upload_group, $mode, $show_mode, $uid, $cover, $csn]) or Utility::web_error($sql, __FILE__, __LINE__);

    return $csn;
}

//更新資料到tad_gallery中
function update_tad_gallery($sn = '')
{
    global $xoopsDB;

    $csn = (string) $_POST['csn_menu'];

    if (!empty($_POST['new_csn'])) {
        $sort = (int) $_POST['sort'];

        $csn = add_tad_gallery_cate($csn, $_POST['new_csn'], $sort);
    }

    $title = (string) $_POST['title'];
    $description = (string) $_POST['description'];
    $new_tag = (string) $_POST['new_tag'];

    $all_tag = isset($_POST['tag']) && is_array($_POST['tag']) ? implode(',', $_POST['tag']) : '';

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

    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_gallery') . '` SET `csn`=?, `title`=?, `description`=?, `tag`=?, `is360`=? WHERE `sn`=?';
    Utility::query($sql, 'issssi', [$csn, $title, $description, $all_tag, $is360, $sn]) or Utility::web_error($sql, __FILE__, __LINE__);

    //設為封面
    if (!empty($_POST['cover'])) {
        $sql = 'UPDATE `' . $xoopsDB->prefix('tad_gallery_cate') . '` SET `cover`=? WHERE `csn`=?';
        Utility::query($sql, 'si', [$_POST['cover'], $_POST['csn']]) or Utility::web_error($sql, __FILE__, __LINE__);

    }
}

//刪除tad_gallery_cate某筆資料資料
function delete_tad_gallery_cate($csn = '')
{
    global $xoopsDB;

    //先找出底下所有相片
    $sql = 'SELECT `sn` FROM `' . $xoopsDB->prefix('tad_gallery') . '` WHERE `csn` = ?';
    $result = Utility::query($sql, 'i', [$csn]) or Utility::web_error($sql, __FILE__, __LINE__);
    while (list($sn) = $xoopsDB->fetchRow($result)) {
        delete_tad_gallery($sn);
    }

    //找出底下分類，並將分類的所屬分類清空
    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_gallery_cate') . '` SET `of_csn`=? WHERE `of_csn`=?';
    Utility::query($sql, 'ii', [0, $csn]) or Utility::web_error($sql, __FILE__, __LINE__);

    //刪除之
    $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_gallery_cate') . '` WHERE `csn`=?';
    Utility::query($sql, 'i', [$csn]) or Utility::web_error($sql, __FILE__, __LINE__);

    //刪掉RSS
    $rss_filename = _TADGAL_UP_FILE_DIR . "photos{$csn}.rss";
    unlink($rss_filename);
}

//自動取得某分類下最大的排序
function auto_get_csn_sort($csn = '')
{
    global $xoopsDB;
    $sql = 'SELECT MAX(`sort`) FROM `' . $xoopsDB->prefix('tad_gallery_cate') . '` WHERE `of_csn`=? GROUP BY `of_csn`';
    $result = Utility::query($sql, 'i', [$csn]) or Utility::web_error($sql, __FILE__, __LINE__);

    list($max_sort) = $xoopsDB->fetchRow($result);

    return ++$max_sort;
}

//新增資料到tad_gallery_cate中
function add_tad_gallery_cate($csn = 0, $new_csn = '', $sort = 0)
{
    global $xoopsDB, $xoopsUser, $tad_gallery_adm;
    if (empty($new_csn)) {
        return;
    }

    $csn = (int) $csn;

    //找出目前分類的資料
    if ($csn) {
        $cate = Tools::get_tad_gallery_cate($csn);
    } else {
        $cate['enable_group'] = '';
        $cate['enable_upload_group'] = '1';
    }

    //找出目前登入者可以上傳的分類編號
    $upload_powers = Tools::chk_cate_power('upload');
    if ($tad_gallery_adm) {
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

    $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_gallery_cate') . '` (`of_csn`, `title`, `content`, `passwd`, `enable_group`, `enable_upload_group`, `sort`, `mode`, `show_mode`, `cover`, `no_hotlink`, `uid`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
    Utility::query($sql, 'isssssissssi', [$csn, $new_csn, '', '', $enable_group, $enable_upload_group, $sort, (string) $_POST['mode'], 'normal', '', '', $uid]) or Utility::web_error($sql, __FILE__, __LINE__);
    //取得最後新增資料的流水編號
    $csn = $xoopsDB->getInsertId();

    return $csn;
}

//取得tad_gallery_cate所有資料陣列
function get_tad_gallery_cate_all()
{
    global $xoopsDB;
    $sql = 'SELECT `csn`, `title` FROM `' . $xoopsDB->prefix('tad_gallery_cate') . '`';
    $result = Utility::query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    while (list($csn, $title) = $xoopsDB->fetchRow($result)) {
        $data[$csn] = $title;
    }

    return $data;
}

/********************* 圖片函數 ********************
 * @param string $sn
 * @param string $kind
 * @param string $local
 * @param string $filename
 * @param string $dir
 * @return string
 */
//圖片位置及名稱
function photo_name($sn = '', $kind = '', $local = '1', $filename = '', $dir = '')
{
    global $xoopsDB;
    if (empty($filename)) {
        $sql = 'SELECT `filename`, `dir` FROM `' . $xoopsDB->prefix('tad_gallery') . '` WHERE `sn`=?';
        $result = Utility::query($sql, 'i', [$sn]) or Utility::web_error($sql, __FILE__, __LINE__);

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

/********************* 預設函數 *********************/

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

function parse_exif_string($exif_str)
{
    $photoexif = [];
    $pairs = explode('||', $exif_str);

    foreach ($pairs as $pair) {
        if (preg_match('/\[(.*?)\]\[(.*?)\]=(.*)/', $pair, $matches)) {
            $section = $matches[1];
            $key = $matches[2];
            $value = $matches[3];

            // 嘗試將數值型字串轉為數字
            if (is_numeric($value)) {
                $value = $value + 0;
            }

            $photoexif[$section][$key] = $value;
        }
    }

    return $photoexif;
}
