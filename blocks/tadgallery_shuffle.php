<?php
include_once XOOPS_ROOT_PATH . "/modules/tadgallery/class/tadgallery.php";
include_once XOOPS_ROOT_PATH . "/modules/tadgallery/function_block.php";

//區塊主函式 (抽取圖片)
function tadgallery_shuffle_show($options)
{
    global $xoopsDB, $xoTheme;

    // $default_val="12||1|photo_sort||m|0|200|160";

    $order_array = array('post_date', 'counter', 'rand', 'photo_sort');
    $limit       = empty($options[0]) ? 12 : intval($options[0]);
    $view_csn    = empty($options[1]) ? '' : intval($options[1]);
    $include_sub = empty($options[2]) ? "0" : "1";
    $order_by    = in_array($options[3], $order_array) ? $options[3] : "post_date";
    $desc        = empty($options[4]) ? "" : "desc";
    $size        = (!empty($options[5]) and $options[5] == "s") ? "s" : "m";
    $only_good   = $options[6] != '1' ? "0" : "1";

    $options[7] = intval($options[7]);
    $width      = empty($options[7]) ? 200 : $options[7];
    $options[8] = intval($options[8]);
    $height     = empty($options[8]) ? 160 : $options[8];

    $tadgallery = new tadgallery();
    $tadgallery->set_limit($limit);
    if ($view_csn) {
        $tadgallery->set_view_csn($view_csn);
    }

    $tadgallery->set_orderby($order_by);
    $tadgallery->set_order_desc($desc);
    $tadgallery->set_view_good($only_good);
    $photos = $tadgallery->get_photos($include_sub);

    $pics = "";
    $i    = 0;
    foreach ($photos as $photo) {
        $pp      = 'photo_' . $size;
        $pic_url = $photo[$pp];

        $pics[$i]['width']       = $width;
        $pics[$i]['height']      = $height;
        $pics[$i]['pic_url']     = $pic_url;
        $pics[$i]['photo_sn']    = $photo['sn'];
        $pics[$i]['photo_title'] = $photo['title'];
        $i++;
    }

    $block['view_csn'] = $view_csn;
    $block['width']    = $width;
    $block['height']   = $height;
    $block['pics']     = $pics;

    get_jquery();
    $xoTheme->addScript('modules/tadgallery/class/jqshuffle.js');
    $xoTheme->addScript('', null, "
    (function(\$){
      \$(document).ready(function(){
        \$('.imageBox{$view_csn}').jqShuffle();
      });
    })(jQuery);
  ");

    return $block;
}

//區塊編輯函式
function tadgallery_shuffle_edit($options)
{

    //$option0~6
    $common_setup = common_setup($options);

    $options[7] = intval($options[7]);
    if (empty($options[7])) {
        $options[7] = 200;
    }

    $options[8] = intval($options[8]);
    if (empty($options[8])) {
        $options[8] = 160;
    }

    $form = "
  {$common_setup}
  <div>
    " . _MB_TADGAL_BLOCK_THUMB_WIDTH . "
    <input type='text' name='options[7]' value='{$options[7]}' size=3> x
    " . _MB_TADGAL_BLOCK_THUMB_HEIGHT . "
    <input type='text' name='options[8]' value='{$options[8]}' size=3> px
  </div>
  ";
    return $form;
}
