<?php

use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}


require_once XOOPS_ROOT_PATH . '/modules/tadgallery/function_block.php';

//區塊主函式 (圖片跑馬燈)
function tadgallery_scroller_show($options)
{
    global $xoopsDB, $xoTheme;

    // $default_val="12||1|photo_sort||m|0|100%|240|jscroller2_up|40";

    $order_array = ['post_date', 'counter', 'rand', 'photo_sort'];
    $limit = empty($options[0]) ? 12 : (int) $options[0];
    $view_csn = empty($options[1]) ? '' : (int) $options[1];
    $include_sub = empty($options[2]) ? '0' : '1';
    $order_by = in_array($options[3], $order_array) ? $options[3] : 'post_date';
    $desc = empty($options[4]) ? '' : 'desc';
    $size = (!empty($options[5]) and 's' === $options[5]) ? 's' : 'm';
    $only_good = '1' != $options[6] ? '0' : '1';

    $options[7] = (int) $options[7];
    $width = empty($options[7]) ? '100%' : $options[7];
    $options[8] = (int) $options[8];
    $height = empty($options[8]) ? 240 : $options[8];

    $direction = 'jscroller2_down' === $options[9] ? 'jscroller2_down' : 'jscroller2_up';
    $options[10] = isset($options[10]) ? (int) $options[10] : 40;
    $speed = empty($options[10]) ? 40 : $options[10];

    require_once XOOPS_ROOT_PATH . '/modules/tadgallery/class/tadgallery.php';
    $tadgallery = new tadgallery();
    $tadgallery->set_limit($limit);
    if ($view_csn) {
        $tadgallery->set_view_csn($view_csn);
    }

    $tadgallery->set_orderby($order_by);
    $tadgallery->set_order_desc($desc);
    $tadgallery->set_view_good($only_good);
    $photos = $tadgallery->get_photos($include_sub);

    $pics = [];
    $i = 0;
    foreach ($photos as $photo) {
        $pp = 'photo_' . $size;
        $pic_url = $photo[$pp];
        $title = (empty($photo['title'])) ? $photo['filename'] : $photo['title'];

        $pics[$i]['pic_url'] = $pic_url;
        $pics[$i]['photo_sn'] = $photo['sn'];
        $pics[$i]['photo_title'] = $title;
        $pics[$i]['description'] = (empty($photo['description'])) ? '' : "<div style='padding:4px;background-color:#F0FFA0;font-size:11px;text-align:left;'>{$photo['description']}</div>";
        $i++;
    }

    $block['height'] = $height;
    $block['direction'] = $direction;
    $block['speed'] = $speed;
    $block['pics'] = $pics;

    Utility::get_jquery();
    $xoTheme->addScript('modules/tadgallery/class/jscroller.js');
    $xoTheme->addScript('', null, "
    (function(\$){
      \$(document).ready(function(){
        var width=$('#scroller_container{$view_csn}_w').width();
        \$('#scroller_container{$view_csn}').css('width',width+'px');
      });
    })(jQuery);
  ");

    return $block;
}

//區塊編輯函式
function tadgallery_scroller_edit($options)
{
    //$option0~6
    $common_setup = common_setup($options);

    $options[8] = (int) $options[8];
    if (empty($options[8])) {
        $options[8] = 240;
    }

    $jscroller2_up = ('jscroller2_down' !== $options[9]) ? 'checked' : '';
    $jscroller2_down = ('jscroller2_down' === $options[9]) ? 'checked' : '';

    $options[10] = (int) $options[10];
    if (empty($options[10])) {
        $options[10] = 40;
    }

    $form = "
    <ol class='my-form'>
        {$common_setup}
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADGAL_BLOCK_THUMB_WIDTH . 'x
            ' . _MB_TADGAL_BLOCK_THUMB_HEIGHT . "</lable>
            <div class='my-content'>
                <input type='hidden' name='options[7]' class='my-input' value='100%' size=3> 100% x
                <input type='text' name='options[8]' class='my-input' value='{$options[8]}' size=3> px
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADGAL_GOOD_MOVE_DIRECTION . "</lable>
            <div class='my-content'>
                <label for='jscroller2_up'>
                    <input type='radio' $jscroller2_up name='options[9]' value='jscroller2_up' id='jscroller2_up'>
                    " . _MB_TADGAL_GOOD_MOVE_DIRECTION_OPT1 . "
                </label>
                <label for='jscroller2_down'>
                    <input type='radio' $jscroller2_down name='options[9]' value='jscroller2_down' id='jscroller2_down'>
                    " . _MB_TADGAL_GOOD_MOVE_DIRECTION_OPT2 . "
                </label>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADGAL_GOOD_MOVE_SPEED . "</lable>
            <div class='my-content'>
                <input type='text' name='options[10]' class='my-input' value='{$options[10]}' size=4>
                <span class='my-help'>(0-1000)</span>
            </div>
        </li>
    </ol>";

    return $form;
}
