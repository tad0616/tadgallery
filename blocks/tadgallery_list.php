<?php
include_once XOOPS_ROOT_PATH . '/modules/tadgallery/class/tadgallery.php';
include_once XOOPS_ROOT_PATH . '/modules/tadgallery/function_block.php';

//區塊主函式 (縮圖列表)
function tadgallery_list($options)
{
    global $xoopsDB;

    $order_array = ['post_date', 'counter', 'rand', 'photo_sort'];
    $limit = empty($options[0]) ? 12 : (int) $options[0];
    $view_csn = empty($options[1]) ? '' : (int) $options[1];
    $include_sub = empty($options[2]) ? '0' : '1';
    $order_by = in_array($options[3], $order_array, true) ? $options[3] : 'post_date';
    $desc = empty($options[4]) ? '' : 'desc';
    $size = (!empty($options[5]) and 's' == $options[5]) ? 's' : 'm';
    $only_good = '1' != $options[6] ? '0' : '1';

    $options[7] = (int) $options[7];
    $width = empty($options[7]) ? 120 : $options[7];
    $options[8] = (int) $options[8];
    $height = empty($options[8]) ? 120 : $options[8];

    $options[9] = (int) $options[9];
    $margin = empty($options[9]) ? 0 : $options[9];
    $bgsize = empty($options[13]) ? 'cover' : $options[13];

    $show_txt = ('1' == $options[10]) ? '1' : '0';

    $style = (empty($options[11]) or false === mb_strrpos(';', $options[11])) ? 'font-size:11px;font-weight:normal;overflow:hidden;' : $options[11];

    $tadgallery = new tadgallery();
    $tadgallery->set_limit($limit);
    if ($view_csn) {
        $tadgallery->set_view_csn($view_csn);
    }

    if ($options[12]) {
        $tadgallery->set_display2fancybox('tad_gallery_colorbox_' . $view_csn);
    }

    $tadgallery->set_orderby($order_by);
    $tadgallery->set_order_desc($desc);
    $tadgallery->set_view_good($only_good);
    $photos = $tadgallery->get_photos($include_sub);

    $pics = [];
    $i = 0;
    foreach ($photos as $photo) {
        // die(var_dump($photo));
        $pp = 'photo_' . $size;
        $pic_url = $photo[$pp];
        $pics[$i]['pic_url'] = $pic_url;
        $pics[$i]['photo_sn'] = $photo['sn'];
        if (!empty($photo['title'])) {
            $pics[$i]['pic_txt'] = $photo['title'];
        } elseif (!empty($photo['description'])) {
            $pics[$i]['pic_txt'] = $photo['description'];
        } else {
            $pics[$i]['pic_txt'] = $photo['filename'];
        }
        $pics[$i]['fancy_class'] = $photo['fancy_class'];
        // die($photo['photo_l_url']);
        $pics[$i]['link'] = ($options[12]) ? $photo['photo_l'] : XOOPS_URL . '/modules/tadgallery/view.php?sn=' . $photo['sn'];
        $i++;
    }
    //die(var_export($pics));
    $block['view_csn'] = $view_csn;
    $block['width'] = $width;
    $block['height'] = $height;
    $block['margin'] = $margin;
    $block['style'] = $style;
    $block['pics'] = $pics;
    $block['show_txt'] = $show_txt;
    $block['bgsize'] = $bgsize;

    return $block;
}

//區塊編輯函式
function tadgallery_list_edit($options)
{
    //die(var_export($options));
    //$option0~6
    $common_setup = common_setup($options);

    $options[7] = (int) $options[7];
    if (empty($options[7])) {
        $options[7] = 100;
    }

    $options[8] = (int) $options[8];
    if (empty($options[8])) {
        $options[8] = 100;
    }

    $options[9] = (int) $options[9];
    if (empty($options[9])) {
        $options[9] = 0;
    }

    $show_txt_0 = ('1' != $options[10]) ? 'checked' : '';
    $show_txt_1 = ('1' == $options[10]) ? 'checked' : '';

    if (empty($options[11])) {
        $options[11] = 'font-size:11px;font-weight:normal;overflow:hidden;';
    }
    $show_fancybox_1 = ('1' == $options[12]) ? 'checked' : '';
    $show_fancybox_0 = ('1' != $options[12]) ? 'checked' : '';

    $bgsize_1 = ('contain' == $options[13]) ? 'checked' : '';
    $bgsize_0 = ('contain' != $options[13]) ? 'checked' : '';
    //$opt0_show_photo_num=opt0_show_photo_num($options[0]);
    $form = "
    <ol class='my-form'>
        {$common_setup}
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADGAL_BLOCK_THUMB_WIDTH . ' x ' . _MB_TADGAL_BLOCK_THUMB_HEIGHT . "</lable>
            <div class='my-content'>
                <input type='text' name='options[7]' class='my-input' value='{$options[7]}' size=3> x
                <input type='text' name='options[8]' class='my-input' value='{$options[8]}' size=3> px
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADGAL_BLOCK_THUMB_SPACE . "</lable>
            <div class='my-content'>
                <input type='text' name='options[9]' class='my-input' value='{$options[9]}' size=2> px
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADGAL_BLOCK_SHOW_TEXT . "</lable>
            <div class='my-content'>
                <label for='show_txt_1'>
                    <input type='radio' name='options[10]' value=1 $show_txt_1 id='show_txt_1'>
                    " . _MB_TADGAL_BLOCK_SHOW_TEXT_Y . "
                </label>
                <label for='show_txt_0'>
                    <input type='radio' name='options[10]' value=0 $show_txt_0 id='show_txt_0'>
                    " . _MB_TADGAL_BLOCK_SHOW_TEXT_N . "
                </label>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADGAL_BLOCK_TEXT_CSS . "</lable>
            <div class='my-content'>
                <input type='text' name='options[11]' class='my-input' value='{$options[11]}' size=100>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADGAL_BLOCK_SHOW_FANCYBOX . "</lable>
            <div class='my-content'>
                <label for='show_fancybox_1'>
                    <input type='radio' name='options[12]' value=1 $show_fancybox_1 id='show_fancybox_1'>
                    " . _YES . "
                </label>
                <label for='show_fancybox_0'>
                    <input type='radio' name='options[12]' value=0 $show_fancybox_0 id='show_fancybox_0'>
                    " . _NO . "
                </label>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADGAL_BLOCK_BGSIZE . "</lable>
            <div class='my-content'>
                <label for='bgsize_1'>
                    <input type='radio' name='options[13]' value='contain' $bgsize_1 id='bgsize_1'>
                    " . _MB_TADGAL_BLOCK_BGSIZE_CONTAIN . "
                </label>
                <label for='bgsize_0'>
                    <input type='radio' name='options[13]' value='cover' $bgsize_0 id='bgsize_0'>
                    " . _MB_TADGAL_BLOCK_BGSIZE_COVER . '
                </label>
            </div>
        </li>
    </ol>';

    return $form;
}
