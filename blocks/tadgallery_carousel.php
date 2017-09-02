<?php
include_once XOOPS_ROOT_PATH . "/modules/tadgallery/class/tadgallery.php";
include_once XOOPS_ROOT_PATH . "/modules/tadgallery/function_block.php";

//區塊主函式 (相片捲軸)
function tadgallery_carousel_show($options)
{
    global $xoopsDB, $xoTheme;

    $order_array = array('post_date', 'counter', 'rand', 'photo_sort');
    $limit       = empty($options[0]) ? 12 : (int)$options[0];
    $view_csn    = empty($options[1]) ? '' : (int)$options[1];
    $include_sub = empty($options[2]) ? "0" : "1";
    $order_by    = in_array($options[3], $order_array) ? $options[3] : "post_date";
    $desc        = empty($options[4]) ? "" : "desc";
    $size        = (!empty($options[5]) and $options[5] == "s") ? "s" : "m";
    $only_good   = $options[6] != '1' ? "0" : "1";

    $options[7] = (int)$options[7];
    $width      = empty($options[7]) ? 140 : $options[7];
    $options[8] = (int)$options[8];
    $height     = empty($options[8]) ? 105 : $options[8];

    $direction   = empty($options[9]) ? "0" : "1";
    $options[10] = (int)$options[10];
    $speed       = (empty($options[10]) or $options[10] < 10) ? 1000 : $options[10];
    $options[11] = (int)$options[11];
    $scroll      = (empty($options[11]) or $options[11] > 20) ? 3 : $options[11];
    $move        = (empty($options[12]) or $options[12] > 20) ? 0 : (int)$options[12];
    $options[13] = (int)$options[13];
    $staytime    = empty($options[13]) ? 5000 : $options[13];

    $tadgallery = new tadgallery();
    $tadgallery->set_limit($limit);
    if ($view_csn) {
        $tadgallery->set_view_csn($view_csn);
    }

    if ($options[14]) {
        $tadgallery->set_display2fancybox('tad_gallery_colorbox_' . $view_csn);
    }

    $tadgallery->set_orderby($order_by);
    $tadgallery->set_order_desc($desc);
    $tadgallery->set_view_good($only_good);
    $photos = $tadgallery->get_photos($include_sub);

    $pics = array();
    $i    = 0;
    foreach ($photos as $photo) {
        $pp                      = 'photo_' . $size;
        $pic_url                 = $photo[$pp];
        $pics[$i]['width']       = $width;
        $pics[$i]['height']      = $height;
        $pics[$i]['direction']   = $direction;
        $pics[$i]['pic_url']     = $pic_url;
        $pics[$i]['photo_sn']    = $photo['sn'];
        $pics[$i]['photo_title'] = $photo['title'];
        $pics[$i]['pic_txt']     = (empty($photo['title'])) ? $photo['filename'] : $photo['title'];
        $pics[$i]['fancy_class'] = $photo['fancy_class'];
        $pics[$i]['link']        = ($options[14]) ? $photo['photo_l'] : XOOPS_URL . '/modules/tadgallery/view.php?sn=' . $photo['sn'];
        $i++;
    }

    if ($direction == '1') {
        $vertical_height = $height * $scroll + 50;
        $css_txt         = "width:{$width}px;";
        $vertical        = "direction : 'up',";
    } else {
        $vertical_height = "'auto'";
        $css_txt         = "height:{$height}px;";
        $vertical        = "";
    }

    //引入TadTools的jquery
    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/jquery.php")) {
        redirect_header("http://campus-xoops.tn.edu.tw/modules/tad_modules/index.php?module_sn=1", 3, _TAD_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/jquery.php";

    $block['view_csn']        = $view_csn;
    $block['vertical']        = $vertical;
    $block['vertical_height'] = $vertical_height;
    $block['scroll']          = (int)$scroll == 0 ? "" : "scroll: {$scroll},";
    $block['pics']            = $pics;

    get_jquery();
    $xoTheme->addScript('modules/tadgallery/class/carouFredSel/jquery.carouFredSel-6.2.1-packed.js');
    $xoTheme->addScript('modules/tadgallery/class/carouFredSel/helper-plugins/jquery.mousewheel.min.js');
    $xoTheme->addScript('modules/tadgallery/class/carouFredSel/helper-plugins/jquery.touchSwipe.min.js');
    $xoTheme->addScript('modules/tadgallery/class/carouFredSel/helper-plugins/jquery.transit.min.js');
    $xoTheme->addScript('modules/tadgallery/class/carouFredSel/helper-plugins/jquery.ba-throttle-debounce.min.js');
    return $block;
}

//區塊編輯函式
function tadgallery_carousel_edit($options)
{
    //$option0~6
    $common_setup = common_setup($options);

    $options[7] = (int)$options[7];
    if (empty($options[7])) {
        $options[7] = 140;
    }

    $options[8] = (int)$options[8];
    if (empty($options[8])) {
        $options[8] = 105;
    }

    $vertical_1 = ($options[9] != "1") ? "checked" : "";
    $vertical_0 = ($options[9] == "1") ? "checked" : "";

    $options[10] = (int)$options[10];
    if (empty($options[10])) {
        $options[10] = 1000;
    }

    $options[11] = (int)$options[11];
    if (empty($options[11])) {
        $options[11] = 3;
    }

    $options[12] = (int)$options[12];

    $options[13] = (int)$options[13];
    if (empty($options[13])) {
        $options[13] = 5000;
    }

    $form = "
  {$common_setup}

  <div>
    " . _MB_TADGAL_BLOCK_THUMB_WIDTH . "
    <input type='text' name='options[7]' value='{$options[7]}' size=3> x
    " . _MB_TADGAL_BLOCK_THUMB_HEIGHT . "
    <input type='text' name='options[8]' value='{$options[8]}' size=3> px
  </div>

  <div>
    " . _MB_TADGAL_GOOD_MOVE_DIRECTION . "
    <input type='radio' name='options[9]' value='1' $vertical_1>" . _MB_TADGAL_GOOD_MOVE_DIRECTION_OPT3 . "
    <input type='radio' name='options[9]' value='0' $vertical_0>" . _MB_TADGAL_GOOD_MOVE_DIRECTION_OPT4 . "
  </div>

  <div>
    " . _MB_TADGAL_GOOD_MOVE_SPEED . "
    <input type='text' name='options[10]' value='{$options[10]}' size=5>
    " . _MB_TADGAL_MS . "
  </div>

  <div>
    " . _MB_TADGAL_BLOCK_COLS . "
    <input type='text' name='options[11]' value='{$options[11]}' size=1>
    " . _MB_TADGAL_BLOCK_COLS_DESC . "
  </div>

  <div>
    " . _MB_TADGAL_MOVE_NUM . "
    <input type='text' name='options[12]' value='{$options[12]}' size=1>
    " . _MB_TADGAL_MOVE_NUM_DESC . "
  </div>

  <div>
    " . _MB_TADGAL_SHOW_TIME . "
    <input type='text' name='options[13]' value='{$options[13]}' size=5>
    " . _MB_TADGAL_MS . "
  </div>
  ";

    $show_fancybox_1 = ($options[14] != "1") ? "checked" : "";
    $show_fancybox_0 = ($options[14] == "1") ? "checked" : "";
    $form .= "
  <div>
      " . _MB_TADGAL_BLOCK_SHOW_FANCYBOX . "
    <label for='show_txt_1'>
      <input type='radio' name='options[14]' value=1 $show_fancybox_1 id='show_fancybox_1'>
      " . _YES . "
    </label>
    <label for='show_txt_0'>
      <input type='radio' name='options[14]' value=0 $show_fancybox_0 id='show_fancybox_0'>
      " . _NO . "
    </label>
  </div>
  ";
    return $form;
}
