<?php
include_once XOOPS_ROOT_PATH . '/modules/tadgallery/class/tadgallery.php';

//顯示相片數：
if (!function_exists('common_setup')) {
    function common_setup($opt = '')
    {
        //die(var_export($opt));

        $opt[0] = (int) $opt[0];
        if (empty($opt[0])) {
            $opt[0] = 12;
        }

        $cate_select = get_tad_gallery_block_cate(0, 0, $opt[1]);

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
}

if (!function_exists('get_tad_gallery_block_cate')) {
    //取得分類下拉選單
    function get_tad_gallery_block_cate($of_csn = 0, $level = 0, $v = '')
    {
        global $xoopsDB, $xoopsUser;

        $moduleHandler = xoops_getHandler('module');
        $xoopsModule = $moduleHandler->getByDirname('tadgallery');

        if ($xoopsUser) {
            $module_id = $xoopsModule->getVar('mid');
            $isAdmin = $xoopsUser->isAdmin($module_id);
        } else {
            $isAdmin = false;
        }

        $sql = 'SELECT count(*),csn FROM ' . $xoopsDB->prefix('tad_gallery') . ' GROUP BY csn';
        $result = $xoopsDB->query($sql);
        while (list($count, $csn) = $xoopsDB->fetchRow($result)) {
            $cate_count[$csn] = $count;
        }

        //$left=$level*10;
        $level += 1;

        $syb = str_repeat('-', $level) . ' ';

        $option = ($of_csn) ? '' : "<option value='0'>" . _MB_TADGAL_BLOCK_ALL . '</option>';
        $sql = 'select csn,title from ' . $xoopsDB->prefix('tad_gallery_cate') . " where of_csn='{$of_csn}' and passwd='' and enable_group='' order by sort";
        $result = $xoopsDB->query($sql);

        while (list($csn, $title) = $xoopsDB->fetchRow($result)) {
            $selected = ($v == $csn) ? 'selected' : '';
            $count = (empty($cate_count[$csn])) ? 0 : $cate_count[$csn];
            $option .= "<option value='{$csn}' $selected>{$syb}{$title}({$count})</option>";
            $option .= get_tad_gallery_block_cate($csn, $level, $v);
        }

        return $option;
    }
}
