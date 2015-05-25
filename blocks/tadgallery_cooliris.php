<?php
include_once XOOPS_ROOT_PATH."/modules/tadgallery/class/tadgallery.php";
include_once XOOPS_ROOT_PATH."/modules/tadgallery/function_block.php";

function tadgallery_cooliris_show($options){
  global $xoopsDB;

  $default_val="|100%|450|1";
  $options=get_block_default($options,$default_val);

  $block=block_cooliris($options[0],$options[1],$options[2]);
  return $block;
}



function tadgallery_cooliris_edit($options){
  $cate_select=get_tad_gallery_block_cate_option(0,0,$options[0]);

  $include_sub=($options[3]=="1")?"checked":"";
  $form="
  "._MB_TADGAL_BLOCK_SHOWCATE."
  <select name='options[0]'>
    $cate_select
  </select>
  <INPUT type='checkbox' name='options[3]' value='1' $include_sub>"._MB_TADGAL_BLOCK_INCLUDE_SUB_ALBUMS."
  <br>
  "._MB_TADGAL_BLOCK_WIDTH."
  <INPUT type='hidden' name='options[1]' value='100%' size=3> 100% x
  "._MB_TADGAL_BLOCK_HEIGHT."
  <INPUT type='text' name='options[2]' value='{$options[2]}' size=3> px<br>
  ";
  return $form;
}


function block_cooliris($csn="",$width=650,$height=450){
  if(empty($csn))$csn="";
  $main = "
  <div align='center'>
    <object id='block_cooliris' classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000'
      width='100%' height='{$height}'>
      <param name='movie' value='".XOOPS_URL."/modules/tadgallery/class/cooliris.swf'/>
      <param name='allowFullScreen' value='true'/>
      <param name='allowScriptAccess' value='never'/>
      <param name='flashvars' value='feed=".XOOPS_URL."/uploads/tadgallery/photos{$csn}.rss' />
      <param name='wmode' value='opaque'/>
      <embed type='application/x-shockwave-flash'
        src='".XOOPS_URL."/modules/tadgallery/class/cooliris.swf'
        width='100%'
        height='{$height}'
        allowfullscreen='true'
        allowscriptaccess='never'
        flashvars='feed=".XOOPS_URL."/uploads/tadgallery/photos{$csn}.rss'
        wmode='opaque'>
      </embed>
      <div style='color:transparent;'>cooliris</div>
    </object>
  </div>";
  return $main;
}