<?php
use XoopsModules\Tadgallery\Tadgallery;
use XoopsModules\Tadgallery\Tools;
if (!class_exists('XoopsModules\Tadgallery\Tadgallery')) {
    require XOOPS_ROOT_PATH . '/modules/tadgallery/preloads/autoloader.php';
}
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}
//區塊主函式 (相片投影秀)
function tadgallery_show($options)
{
    global $xoopsDB, $xoTheme;

    // $default_val="12||1|photo_sort||m|0|100%|240";

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
    $block_height = empty($options[8]) ? 240 : $options[8];

    // require_once XOOPS_ROOT_PATH . '/modules/tadgallery/class/Tadgallery.php';
    $tadgallery = new Tadgallery();
    $tadgallery->set_limit($limit);
    if ($view_csn) {
        $tadgallery->set_view_csn($view_csn);
    }

    $tadgallery->set_orderby($order_by);
    $tadgallery->set_order_desc($desc);
    $tadgallery->set_view_good($only_good);
    $photos = $tadgallery->get_photos('return', $include_sub);

    $pics = [];
    $i = 0;
    foreach ($photos as $photo) {
        $pp = 'photo_' . $size;
        $pic_url = $photo[$pp];

        $pics[$i]['width'] = $width;
        // $pics[$i]['height']      = $height;
        $pics[$i]['pic_url'] = $pic_url;
        $pics[$i]['photo_sn'] = $photo['sn'];
        $pics[$i]['photo_title'] = $photo['title'];
        $i++;
    }

    $block['view_csn'] = $view_csn;
    $block['pics'] = $pics;

    Utility::get_jquery();
    $xoTheme->addScript('modules/tadgallery/class/jquery.animated.innerfade/js/jquery.animated.innerfade.js');
    $xoTheme->addScript('', null, "
    (function(\$){
      \$(document).ready(function(){
        \$('ul#animated-portfolio-block{$view_csn}').animatedinnerfade({
          speed: 1000,
          timeout: 5000,
          type: 'random',
          containerwidth: '100%',
          containerheight: '{$block_height}px',
          animationSpeed: 5000,
          animationtype: 'fade',
          bgFrame: 'none',
          controlBox: 'auto',
          controlBoxClass: 'none',
          controlButtonsPath: '" . XOOPS_URL . "/modules/tadgallery/class/jquery.animated.innerfade/img',
          displayTitle: 'none'
        });
      });
    })(jQuery);
  ");

    return $block;
}

//區塊編輯函式
function tadgallery_show_edit($options)
{
    //$option0~6
    $common_setup = Tools::common_setup($options);

    $options[7] = (int) $options[7];
    if (empty($options[7])) {
        $options[7] = '100%';
    }

    $options[8] = (int) $options[8];
    if (empty($options[8])) {
        $options[8] = '240';
    }

    $form = "
    <ol class='my-form'>
        {$common_setup}
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADGAL_BLOCK_THUMB_WIDTH . 'x
            ' . _MB_TADGAL_BLOCK_THUMB_HEIGHT . "</lable>
            <div class='my-content'>
                <input type='hidden' name='options[7]' class='my-input' value='100%' size=3> 100%
                <input type='text' name='options[8]' class='my-input' value='{$options[8]}' size=3> px
            </div>
        </li>
    </ol>";

    return $form;
}
