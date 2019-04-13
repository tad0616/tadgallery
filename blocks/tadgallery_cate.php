<?php
include_once XOOPS_ROOT_PATH . '/modules/tadgallery/class/tadgallery.php';
include_once XOOPS_ROOT_PATH . '/modules/tadgallery/function_block.php';

//區塊主函式 (相簿一覽)
function tadgallery_cate($options)
{
    global $xoopsDB, $xoTheme;

    // $default_val="4|album|rand()||300|line-height:1.8;|0";

    $options[0] = (int) $options[0];
    $shownum = empty($options[0]) ? '5' : $options[0];

    $display_arr = ['title', 'album', 'content'];
    $display_mode = in_array($options[1], $display_arr, true) ? $options[1] : 'album';

    $sortby_arr = ['csn', 'rand()', 'sort'];
    $sortby = in_array($options[2], $sortby_arr, true) ? $options[2] : 'rand()';

    $sort_desc = ('desc' == $options[3]) ? 'desc' : '';

    $options[4] = (int) $options[4];
    $lengh = empty($options[4]) ? 300 : $options[4];

    $content_css = (empty($options[5]) or false === mb_strrpos(';', $options[5])) ? 'line-height:1.8;' : $options[5];

    $only_have_desc = ('1' == $options[6]) ? '1' : '0';
    if ('content' != $display_mode) {
        $only_have_desc = 0;
    }

    $view_csn = empty($options[7]) ? '' : (int) $options[7];
    $all = empty($options[9]) ? false : true;

    $tadgallery = new tadgallery();
    $order = "{$sortby} {$sort_desc}";
    if ($view_csn) {
        $tadgallery->set_view_csn($view_csn);
    }
    $albums = $tadgallery->get_albums('return', $all, $shownum, $order, true, $lengh, $only_have_desc);

    $block['albums'] = $albums;
    $block['display_mode'] = $display_mode;
    $block['content_css'] = $content_css;
    $block['count'] = count($albums);
    $block['col'] = empty($options[8]) ? 4 : $options[8];

    if ($xoTheme) {
        $xoTheme->addStylesheet('modules/tadgallery/module.css');
        $xoTheme->addStylesheet('modules/tadgallery/class/jquery.thumbs/jquery.thumbs.css');
        $xoTheme->addScript('modules/tadgallery/class/jquery.thumbs/jquery.thumbs.js');
    }

    return $block;
}

//區塊編輯函式
function tadgallery_cate_edit($options)
{
    $options[0] = (int) $options[0];
    $options[0] = empty($options[0]) ? 5 : $options[0];

    $display_0 = ('title' == $options[1]) ? 'selected' : '';
    $display_1 = ('title' != $options[1] and 'content' != $options[1]) ? 'selected' : '';
    $display_2 = ('content' == $options[1]) ? 'selected' : '';

    $sortby_0 = ('csn' == $options[2]) ? 'selected' : '';
    $sortby_2 = ('csn' != $options[2] and 'sort' != $options[2]) ? 'selected' : '';
    $sortby_3 = ('sort' == $options[2]) ? 'selected' : '';

    $sort_normal = ('desc' != $options[3]) ? 'selected' : '';
    $sort_desc = ('desc' == $options[3]) ? 'selected' : '';

    $options[4] = (int) $options[4];
    $options[4] = empty($options[4]) ? 300 : $options[4];

    $options[5] = (empty($options[5]) or false === mb_strrpos(';', $options[5])) ? 'line-height:1.8;' : $options[5];

    $options6_1 = ('1' == $options[6]) ? 'checked' : '';
    $options6_0 = ('1' != $options[6]) ? 'checked' : '';
    $opt9_1 = ('1' == $options[9]) ? 'checked' : '';
    $opt9_0 = ('1' != $options[9]) ? 'checked' : '';
    $cate_select = get_tad_gallery_block_cate(0, 0, $options[7]);

    if (4 == $_SESSION['bootstrap']) {
        $opt8 = '';
        for ($i = 1; $i <= 12; $i++) {
            $selected = $options[8] == $i ? 'selected' : '';
            $opt8 .= "<option value='$i' $selected >$i</option>";
        }
    } else {
        $s12 = ('12' == $options[8]) ? 'selected' : '';
        $s6 = ('6' == $options[8]) ? 'selected' : '';
        $s4 = ('4' == $options[8]) ? 'selected' : '';
        $s3 = ('3' == $options[8]) ? 'selected' : '';
        $s2 = ('2' == $options[8]) ? 'selected' : '';
        $s1 = ('1' == $options[8]) ? 'selected' : '';
        $sno = ('0' == $options[8]) ? 'selected' : '';

        $opt8 = "<option value='12' $s12>1</option>
        <option value='6' $s6>2</option>
        <option value='4' $s4>3</option>
        <option value='3' $s3>4</option>
        <option value='2' $s2>6</option>
        <option value='1' $s1>12</option>";
    }

    $form = "
    <ol class='my-form'>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADGAL_BLOCK_CATE_SHOWNUM . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[0]' value='{$options[0]}' size=6>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADGAL_BLOCK_DISPLAY_MODE . "</lable>
            <div class='my-content'>
                <select name='options[1]' class='my-input'>
                    <option value='title' $display_0>" . _MB_TADGAL_BLOCK_DISPLAY_MODE1 . "</option>
                    <option value='album' $display_1>" . _MB_TADGAL_BLOCK_DISPLAY_MODE2 . "</option>
                    <option value='content' $display_2>" . _MB_TADGAL_BLOCK_DISPLAY_MODE3 . "</option>
                </select>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADGAL_BLOCK_SORTBY . "</lable>
            <div class='my-content'>
                <select name='options[2]' class='my-input'>
                    <option value='csn' $sortby_0>" . _MB_TADGAL_BLOCK_SORTBY_MODE1 . "</option>
                    <option value='rand()' $sortby_2>" . _MB_TADGAL_BLOCK_SORTBY_MODE3 . "</option>
                    <option value='sort' $sortby_3>" . _MB_TADGAL_BLOCK_SORTBY_MODE4 . "</option>
                </select>
                <select name='options[3]' class='my-input'>
                    <option value='' $sort_normal>" . _MB_TADGAL_BLOCK_SORT_NORMAL . "</option>
                    <option value='desc' $sort_desc>" . _MB_TADGAL_BLOCK_SORT_DESC . "</option>
                </select>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADGAL_BLOCK_TEXT_NUM . "</lable>
            <div class='my-content'>
                <input type='text' name='options[4]' class='my-input' value='{$options[4]}' size=2>
                <span class='my-help'>" . _MB_TADGAL_BLOCK_TEXT_NUM_DESC . "</span>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADGAL_BLOCK_TEXT_CSS . "</lable>
            <div class='my-content'>
                <input type='text' name='options[5]' class='my-input' value='{$options[5]}' size=50>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADGAL_BLOCK_ONLY_HAVE_CONTENT . "</lable>
            <div class='my-content'>
                <label for='options6_1'>
                <input type='radio' name='options[6]' value='1' $options6_1 id='options6_1'>
                " . _YES . "
                </label>
                <label for='options6_0'>
                <input type='radio' name='options[6]' value='0' $options6_0 id='options6_0'>
                " . _NO . "
                </label>
                <span class='my-help'>" . _MB_TADGAL_BLOCK_TEXT_NUM_DESC . "</span>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADGAL_BLOCK_SHOWCATE . "</lable>
            <div class='my-content'>
                <select name='options[7]' class='my-input'>
                    {$cate_select}
                </select>
                <label for='include_sub'>

                </label>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADGAL_BLOCK_BOOTSTRAP_COL . "</lable>
            <div class='my-content'>
                <select name='options[8]' class='my-input' value='{$options[8]}'>
                    $opt8
                </select>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADGAL_BLOCK_INCLUDE_SUB_ALBUMS . "</lable>
            <div class='my-content'>
            <input type='radio' name='options[9]' id='opt9_1' value='1' $opt9_1>
            " . _YES . "
            <input type='radio' name='options[9]' id='opt9_0' value='0' $opt9_0>
            " . _NO . '
            </div>
        </li>
    </ol>';

    return $form;
}
