<?php
include_once XOOPS_ROOT_PATH . "/modules/tadgallery/class/tadgallery.php";
include_once XOOPS_ROOT_PATH . "/modules/tadgallery/function_block.php";

//區塊主函式 (跑馬燈圖片)
function tadgallery_scroller_show($options)
{
    global $xoopsDB, $xoTheme;

    // $default_val="12||1|photo_sort||m|0|100%|240|jscroller2_up|40";

    $order_array = array('post_date', 'counter', 'rand', 'photo_sort');
    $limit       = empty($options[0]) ? 12 : (int)$options[0];
    $view_csn    = empty($options[1]) ? '' : (int)$options[1];
    $include_sub = empty($options[2]) ? "0" : "1";
    $order_by    = in_array($options[3], $order_array) ? $options[3] : "post_date";
    $desc        = empty($options[4]) ? "" : "desc";
    $size        = (!empty($options[5]) and $options[5] == "s") ? "s" : "m";
    $only_good   = $options[6] != '1' ? "0" : "1";

    $options[7] = (int)$options[7];
    $width      = empty($options[7]) ? '100%' : $options[7];
    $options[8] = (int)$options[8];
    $height     = empty($options[8]) ? 240 : $options[8];

    $direction   = $options[9] == 'jscroller2_down' ? "jscroller2_down" : "jscroller2_up";
    $options[10] = isset($options[10]) ? (int)$options[10] : 40;
    $speed       = empty($options[10]) ? 40 : $options[10];

    $tadgallery = new tadgallery();
    $tadgallery->set_limit($limit);
    if ($view_csn) {
        $tadgallery->set_view_csn($view_csn);
    }

    $tadgallery->set_orderby($order_by);
    $tadgallery->set_order_desc($desc);
    $tadgallery->set_view_good($only_good);
    $photos = $tadgallery->get_photos($include_sub);

    $pics = array();
    $i    = 0;
    foreach ($photos as $photo) {
        $pp      = 'photo_' . $size;
        $pic_url = $photo[$pp];
        $title   = (empty($photo['title'])) ? $photo['filename'] : $photo['title'];

        $pics[$i]['pic_url']     = $pic_url;
        $pics[$i]['photo_sn']    = $photo['sn'];
        $pics[$i]['photo_title'] = $title;
        $pics[$i]['description'] = (empty($photo['description'])) ? "" : "<div style='padding:4px;background-color:#F0FFA0;font-size:11px;text-align:left;'>{$photo['description']}</div>";
        $i++;
    }

    $block['height']    = $height;
    $block['direction'] = $direction;
    $block['speed']     = $speed;
    $block['pics']      = $pics;

    get_jquery();
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

    $options[8] = (int)$options[8];
    if (empty($options[8])) {
        $options[8] = 240;
    }

    $jscroller2_up   = ($options[9] != "jscroller2_down") ? "checked" : "";
    $jscroller2_down = ($options[9] == "jscroller2_down") ? "checked" : "";

    $options[10] = (int)$options[10];
    if (empty($options[10])) {
        $options[10] = 40;
    }

    $form = "
  {$common_setup}
  <div>
    " . _MB_TADGAL_BLOCK_THUMB_WIDTH . "
    <input type='hidden' name='options[7]' value='100%' size=3> 100% x
    " . _MB_TADGAL_BLOCK_THUMB_HEIGHT . "
    <input type='text' name='options[8]' value='{$options[8]}' size=3> px
  </div>
  <div>
    " . _MB_TADGAL_GOOD_MOVE_DIRECTION . "
    <label for='jscroller2_up'>
      <input type='radio' $jscroller2_up name='options[9]' value='jscroller2_up' id='jscroller2_up'>
      " . _MB_TADGAL_GOOD_MOVE_DIRECTION_OPT1 . "
    </label>
    <label for='jscroller2_down'>
      <input type='radio' $jscroller2_down name='options[9]' value='jscroller2_down' id='jscroller2_down'>
      " . _MB_TADGAL_GOOD_MOVE_DIRECTION_OPT2 . "
    </label>
  </div>
  <div>
    " . _MB_TADGAL_GOOD_MOVE_SPEED . "
    <input type='text' name='options[10]' value='{$options[10]}' size=4> (0-1000)
  </div>
  ";
    return $form;
}
