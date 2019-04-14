<?php
include_once XOOPS_ROOT_PATH . '/modules/tadgallery/class/tadgallery.php';
include_once XOOPS_ROOT_PATH . '/modules/tadgallery/function_block.php';

//區塊主函式 (無縫跑馬燈)
function tadgallery_marquee_show($options)
{
    global $xoopsDB;

    // $default_val="12|0|1|post_date||m|0|100%|150|80";

    $order_array = ['post_date', 'counter', 'rand', 'photo_sort'];
    $limit = empty($options[0]) ? 12 : (int) $options[0];
    $view_csn = empty($options[1]) ? '' : (int) $options[1];
    $include_sub = empty($options[2]) ? '0' : '1';
    $order_by = in_array($options[3], $order_array, true) ? $options[3] : 'post_date';
    $desc = empty($options[4]) ? '' : 'desc';
    $size = (!empty($options[5]) and 's' === $options[5]) ? 's' : 'm';
    $only_good = '1' != $options[6] ? '0' : '1';

    $options[7] = (int) $options[7];
    $width = empty($options[7]) ? '100%' : $options[7];
    $options[8] = (int) $options[8];
    $height = (empty($options[8]) or $options[8] <= 30) ? 240 : $options[8];

    $options[9] = (int) $options[9];
    $speed = empty($options[9]) ? 30 : $options[9];

    $tadgallery = new tadgallery();
    $tadgallery->set_limit($limit);
    if ($view_csn) {
        $tadgallery->set_view_csn($view_csn);
    }

    if ($options[10]) {
        $tadgallery->set_display2fancybox('tad_gallery_colorbox_' . $view_csn);
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
        //$pics[$i]['description']=(empty($photo['description']))?"":"<div style='padding:4px;background-color:#F0FFA0;font-size:11px;text-align:left;'>{$photo['description']}</div>";
        $pics[$i]['fancy_class'] = $photo['fancy_class'];
        $pics[$i]['link'] = ($options[10]) ? $photo['photo_l'] : XOOPS_URL . '/modules/tadgallery/view.php?sn=' . $photo['sn'];
        $i++;
    }

    $block['width'] = $width;
    $block['height'] = $height;
    $block['speed'] = $speed;
    $block['pics'] = $pics;
    $block['view_csn'] = $view_csn;

    get_jquery();

    return $block;
}

//區塊編輯函式
function tadgallery_marquee_edit($options)
{
    //die(implode('|',$options));
    //$option0~6
    $common_setup = common_setup($options);

    $options[8] = (int) $options[8];
    if (empty($options[8]) or $options[8] <= 30) {
        $options[8] = 240;
    }

    $options[9] = (int) $options[9];
    if (empty($options[9])) {
        $options[9] = 30;
    }

    $show_fancybox_1 = ('1' == $options[10]) ? 'checked' : '';
    $show_fancybox_0 = ('1' != $options[10]) ? 'checked' : '';

    $form = "
    <ol class='my-form'>
        {$common_setup}
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADGAL_BLOCK_THUMB_WIDTH . ' x
            ' . _MB_TADGAL_BLOCK_THUMB_HEIGHT . "</lable>
            <div class='my-content'>
                <input type='hidden' name='options[7]' class='my-input' value='100%' size=3> 100% x
                <input type='text' name='options[8]' class='my-input' value='{$options[8]}' size=3> px
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADGAL_GOOD_MOVE_SPEED . "</lable>
            <div class='my-content'>
            <input type='text' name='options[9]' class='my-input' value='{$options[9]}' size=4>
                <span class='my-help'>(0-1000)</span>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADGAL_BLOCK_SHOW_FANCYBOX . "</lable>
            <div class='my-content'>
                <label for='show_fancybox_1'>
                    <input type='radio' name='options[10]' value=1 $show_fancybox_1 id='show_fancybox_1'>
                    " . _YES . "
                </label>
                <label for='show_fancybox_0'>
                    <input type='radio' name='options[10]' value=0 $show_fancybox_0 id='show_fancybox_0'>
                    " . _NO . '
                </label>
            </div>
        </li>
    </ol> ';

    return $form;
}
