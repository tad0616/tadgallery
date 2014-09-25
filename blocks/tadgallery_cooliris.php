<?php
include_once XOOPS_ROOT_PATH."/modules/tadgallery/class/tadgallery.php";
include_once XOOPS_ROOT_PATH."/modules/tadgallery/function_block.php";

//區塊主函式 (cooliris)
function tadgallery_cooliris_show($options){
  global $xoopsDB;

  $block=block_cooliris($options[0],$options[1],$options[2]);
  return $block;
}



//區塊編輯函式
function tadgallery_cooliris_edit($options){
  $cate_select=get_tad_gallery_block_cate_option(0,0,$options[0]);

  $form="
  "._MB_TADGAL_BLOCK_SHOWCATE."
  <select name='options[0]'>
    $cate_select
  </select><br>
  "._MB_TADGAL_BLOCK_WIDTH."
  <INPUT type='text' name='options[1]' value='{$options[1]}' size=3> x
  "._MB_TADGAL_BLOCK_HEIGHT."
  <INPUT type='text' name='options[2]' value='{$options[2]}' size=3> px<br>
  ";
  return $form;
}


function block_cooliris($csn="",$width=650,$height=450){
  if(empty($csn))$csn="";
  $main = "<div align='center'>
  <object id='block_cooliris' classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000'
    width='{$width}' height='{$height}'>
  <param name='movie' value='http://apps.cooliris.com/embed/cooliris.swf' />
  <param name='allowFullScreen' value='true' />
  <param name='allowScriptAccess' value='always' />
  <param name='flashvars' value='feed=".XOOPS_URL."/uploads/tadgallery/photos{$csn}.rss' />
  <embed type='application/x-shockwave-flash'
    src='http://apps.cooliris.com/embed/cooliris.swf'
    flashvars='feed=".XOOPS_URL."/uploads/tadgallery/photos{$csn}.rss'
    width='{$width}'
    height='{$height}'
    allowFullScreen='true'
    allowScriptAccess='always'>cooliris</embed>
  </object>
  </div>";
  return $main;
}
?>