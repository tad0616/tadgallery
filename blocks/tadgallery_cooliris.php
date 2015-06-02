<?php
include_once XOOPS_ROOT_PATH . "/modules/tadgallery/class/tadgallery.php";
include_once XOOPS_ROOT_PATH . "/modules/tadgallery/function_block.php";

function tadgallery_cooliris_show($options)
{
    global $xoopsDB;

    // $default_val="|1|100%|450";

    $view_csn    = empty($options[0]) ? '' : intval($options[0]);
    $include_sub = empty($options[1]) ? "0" : "1";

    $options[2] = intval($options[2]);
    $width      = empty($options[2]) ? '100%' : $options[2];
    $options[3] = intval($options[3]);
    $height     = empty($options[3]) ? 450 : $options[3];

    $block = block_cooliris($view_csn, $include_sub, $height);
    return $block;
}

function tadgallery_cooliris_edit($options)
{
    $cate_select = get_tad_gallery_block_cate(0, 0, $options[0]);
    $include_sub = ($options[1] == "1") ? "checked" : "";

    $options[2] = intval($options[2]);
    if (empty($options[2])) {
        $options[2] = '100%';
    }

    $options[3] = intval($options[3]);
    if (empty($options[3])) {
        $options[3] = '450';
    }

    $form = "
    <div>" . _MB_TADGAL_BLOCK_SHOWCATE . "
      <select name='options[0]'>
        {$cate_select}
      </select>
      <label for='include_sub'>
        <input type='checkbox' name='options[1]' id='include_sub' value='1' $include_sub>
        " . _MB_TADGAL_BLOCK_INCLUDE_SUB_ALBUMS . "
      </label>
    </div>
  <div>
    " . _MB_TADGAL_BLOCK_WIDTH . "
    <input type='hidden' name='options[2]' value='100%' size=3> 100% x
    " . _MB_TADGAL_BLOCK_HEIGHT . "
    <input type='text' name='options[3]' value='{$options[3]}' size=3> px
  </div>
  ";
    return $form;
}

function block_cooliris($csn = "", $include_sub = "", $height = 450)
{
    if (empty($csn)) {
        $csn = "";
    }

    $main = "
  <div align='center'>
    <object id='block_cooliris' classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000'
      width='100%' height='{$height}'>
      <param name='movie' value='" . XOOPS_URL . "/modules/tadgallery/class/cooliris.swf'/>
      <param name='allowFullScreen' value='true'/>
      <param name='allowScriptAccess' value='never'/>
      <param name='flashvars' value='feed=" . XOOPS_URL . "/uploads/tadgallery/photos{$csn}.rss' />
      <param name='wmode' value='opaque'/>
      <embed type='application/x-shockwave-flash'
        src='" . XOOPS_URL . "/modules/tadgallery/class/cooliris.swf'
        width='100%'
        height='{$height}'
        allowfullscreen='true'
        allowscriptaccess='never'
        flashvars='feed=" . XOOPS_URL . "/uploads/tadgallery/photos{$csn}.rss'
        wmode='opaque'>
      </embed>
      <div style='color:transparent;'>cooliris</div>
    </object>
  </div>";
    return $main;
}
