<?php

namespace XoopsModules\Tadgallery;

use XoopsModules\Tadtools\Utility;

class Tools
{

    //區塊共同設定項目
    public static function common_setup($opt = '')
    {
        $opt[0] = (int) $opt[0];
        if (empty($opt[0])) {
            $opt[0] = 12;
        }

        $cate_select = self::get_tad_gallery_block_cate(0, 0, $opt[1]);

        $include_sub0 = ('0' == $opt[2]) ? 'checked' : '';
        $include_sub1 = ('0' != $opt[2]) ? 'checked' : '';

        $sortby_0 = ('post_date' === $opt[3]) ? 'selected' : '';
        $sortby_1 = ('counter' === $opt[3]) ? 'selected' : '';
        $sortby_2 = ('rand' === $opt[3]) ? 'selected' : '';
        $sortby_3 = ('photo_sort' === $opt[3] or empty($opt[3])) ? 'selected' : '';

        $sort_normal = ('desc' !== $opt[4]) ? 'selected' : '';
        $sort_desc = ('desc' === $opt[4]) ? 'selected' : '';

        $thumb_s = ('s' === $opt[5]) ? 'checked' : '';
        $thumb_m = ('s' !== $opt[5]) ? 'checked' : '';

        $only_good_0 = ('1' != $opt[6]) ? 'selected' : '';
        $only_good_1 = ('1' == $opt[6]) ? 'selected' : '';

        $col = "
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADGAL_BLOCK_SHOWNUM . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[0]' value='{$opt[0]}' size=3>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADGAL_BLOCK_SHOWCATE . "</lable>
            <div class='my-content'>
                <select name='options[1]' class='my-input'>
                    {$cate_select}
                </select>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADGAL_BLOCK_INCLUDE_SUB_ALBUMS . "</lable>
            <div class='my-content'>
                <label for='include_sub1'>
                    <input type='radio' name='options[2]' id='include_sub1' value='1' $include_sub1>" . _YES . "
                </label>
                <label for='include_sub0'>
                    <input type='radio' name='options[2]' id='include_sub0' value='0' $include_sub0>" . _NO . "
                </label>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADGAL_BLOCK_SORTBY . "</lable>
            <div class='my-content'>
                <select name='options[3]' class='my-input'>
                    <option value='post_date' $sortby_0>" . _MB_TADGAL_BLOCK_SORTBY_MODE1 . "</option>
                    <option value='counter' $sortby_1>" . _MB_TADGAL_BLOCK_SORTBY_MODE2 . "</option>
                    <option value='rand' $sortby_2>" . _MB_TADGAL_BLOCK_SORTBY_MODE3 . "</option>
                    <option value='photo_sort' $sortby_3>" . _MB_TADGAL_BLOCK_SORTBY_MODE4 . "</option>
                </select>
                <select name='options[4]' class='my-input'>
                    <option value='' $sort_normal>" . _MB_TADGAL_BLOCK_SORT_NORMAL . "</option>
                    <option value='desc' $sort_desc>" . _MB_TADGAL_BLOCK_SORT_DESC . "</option>
                </select>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADGAL_BLOCK_THUMB . "</lable>
            <div class='my-content'>
                <label for='thumb_s'>
                    <input type='radio' $thumb_s name='options[5]' value='s' id='thumb_s'>
                    " . _MB_TADGAL_BLOCK_THUMB_S . "
                </label>
                <label for='thumb_m'>
                    <input type='radio' $thumb_m name='options[5]' value='m' id='thumb_m'>
                    " . _MB_TADGAL_BLOCK_THUMB_M . "
                </label>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADGAL_BLOCK_SHOW_TYPE . "</lable>
            <div class='my-content'>
                <select name='options[6]' class='my-input'>
                    <option value='0' $only_good_0>" . _MB_TADGAL_BLOCK_SHOW_ALL . "</option>
                    <option value='1' $only_good_1>" . _MB_TADGAL_BLOCK_ONLY_GOOD . '</option>
                </select>
            </div>
        </li>';

        return $col;
    }

    // 取得分類下拉選單
    public static function get_tad_gallery_block_cate($of_csn = 0, $level = 0, $v = '')
    {
        global $xoopsDB;

        $sql = 'SELECT COUNT(*), `csn` FROM `' . $xoopsDB->prefix('tad_gallery') . '` GROUP BY `csn`';
        $result = Utility::query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($count, $csn) = $xoopsDB->fetchRow($result)) {
            $cate_count[$csn] = $count;
        }

        //$left=$level*10;
        $level += 1;

        $syb = str_repeat('-', $level) . ' ';

        $option = ($of_csn) ? '' : "<option value='0'>" . _MB_TADGAL_BLOCK_ALL . '</option>';

        $sql = 'SELECT `csn`, `title` FROM `' . $xoopsDB->prefix('tad_gallery_cate') . '` WHERE `of_csn` =? AND `passwd` =? AND `enable_group` =? ORDER BY `sort`';
        $result = Utility::query($sql, 'iss', [$of_csn, '', '']);

        while (list($csn, $title) = $xoopsDB->fetchRow($result)) {
            $selected = ($v == $csn) ? 'selected' : '';
            $count = (empty($cate_count[$csn])) ? 0 : $cate_count[$csn];
            $option .= "<option value='{$csn}' $selected>{$syb}{$title}({$count})</option>";
            $option .= self::get_tad_gallery_block_cate($csn, $level, $v);
        }

        return $option;
    }

    //判斷目前的登入者在哪些類別中有觀看或發表(upload)的權利 $kind=""（看），$kind="upload"（寫）
    public static function chk_cate_power($kind = '')
    {
        global $xoopsDB, $xoopsUser, $tad_gallery_adm;
        $ok_cat = [];

        if (!empty($xoopsUser)) {
            if (isset($tad_gallery_adm) && $tad_gallery_adm) {
                $ok_cat[] = 0;
            }
            $user_array = $xoopsUser->getGroups();
        } else {
            $user_array = [3];
        }

        $col = ('upload' === $kind) ? 'enable_upload_group' : 'enable_group';

        $sql = 'SELECT `csn`, `' . $col . '` FROM `' . $xoopsDB->prefix('tad_gallery_cate') . '`';
        $result = Utility::query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        $ok_cat = [];
        while (list($csn, $power) = $xoopsDB->fetchRow($result)) {
            if ((isset($tad_gallery_adm) && $tad_gallery_adm) or empty($power)) {
                $ok_cat[] = (int) $csn;
            } else {
                $power_array = explode(',', $power);
                foreach ($power_array as $gid) {
                    $gid = (int) $gid;
                    if (in_array($gid, $user_array)) {
                        $ok_cat[] = (int) $csn;
                        break;
                    }
                }
            }
        }

        return $ok_cat;
    }

    //取得圖片網址
    public static function get_pic_url($dir = '', $sn = '', $filename = '', $kind = '', $path_kind = '')
    {
        if (empty($filename)) {
            return;
        }

        $show_path = ('dir' === $path_kind) ? _TADGAL_UP_FILE_DIR : _TADGAL_UP_FILE_URL;

        if ('m' === $kind) {
            if (is_file(_TADGAL_UP_FILE_DIR . "medium/{$dir}/{$sn}_m_{$filename}")) {
                return "{$show_path}medium/{$dir}/{$sn}_m_{$filename}";
            }
        } elseif ('s' === $kind) {
            if (is_file(_TADGAL_UP_FILE_DIR . "small/{$dir}/{$sn}_s_{$filename}")) {
                return "{$show_path}small/{$dir}/{$sn}_s_{$filename}";
            } elseif (is_file(_TADGAL_UP_FILE_DIR . "medium/{$dir}/{$sn}_m_{$filename}")) {
                return "{$show_path}medium/{$dir}/{$sn}_m_{$filename}";
            }
        }

        return "{$show_path}{$dir}/{$sn}_{$filename}";
    }

    //以流水號取得某相片資料
    public static function get_tad_gallery($sn = '')
    {
        global $xoopsDB;
        if (empty($sn)) {
            return;
        }

        $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_gallery') . '` WHERE `sn`=?';
        $result = Utility::query($sql, 'i', [$sn]) or Utility::web_error($sql, __FILE__, __LINE__);
        $data = $xoopsDB->fetchArray($result);

        return $data;
    }

    //以流水號取得某相簿資料
    public static function get_tad_gallery_cate($csn = '')
    {
        global $xoopsDB, $xoopsUser, $tad_gallery_adm;
        if (empty($csn)) {
            return;
        }

        $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_gallery_cate') . '` WHERE `csn`=?';
        $result = Utility::query($sql, 'i', [$csn]) or Utility::web_error($sql, __FILE__, __LINE__);
        $data = $xoopsDB->fetchArray($result);

        $nowuid = $xoopsUser ? $xoopsUser->uid() : 0;

        $data['adm'] = ($data['uid'] == $nowuid or (isset($tad_gallery_adm) && $tad_gallery_adm)) ? true : false;

        return $data;
    }

}
