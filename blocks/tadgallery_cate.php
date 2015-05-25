<?php
include_once XOOPS_ROOT_PATH."/modules/tadgallery/class/tadgallery.php";
include_once XOOPS_ROOT_PATH."/modules/tadgallery/function_block.php";

//區塊主函式 (縮圖展示)
function tadgallery_cate($options){
  global $xoopsDB,$xoTheme;

  $tadgallery=new tadgallery();
  $shownum=empty($options[0])?4:$options[0];
  $order="{$options[2]} {$options[3]}";
  $lengh=empty($options[4])?"":intval($options[4]);
  if($options[1]!="content"){
    $options[6]=0;
  }
  $albums=$tadgallery->get_albums('return', true, $shownum, $order, true ,$lengh,$options[6]);
  $block['albums']=$albums;
  $block['display_mode']=$options[1];
  $block['content_css']=$options[5];
  $block['bootstrap_version']=$_SESSION['bootstrap'];
  if($xoTheme){
    $xoTheme->addStylesheet('modules/tadgallery/module.css');
    $xoTheme->addStylesheet('modules/tadgallery/class/jquery.thumbs/jquery.thumbs.css');
    $xoTheme->addScript('modules/tadgallery/class/jquery.thumbs/jquery.thumbs.js');
  }
  return $block;
}


//區塊編輯函式
function tadgallery_cate_edit($options){

  $display_0=($options[1]=="title")?"selected":"";
  $display_1=($options[1]=="album")?"selected":"";
  $display_2=($options[1]=="content")?"selected":"";

  $sortby_0=($options[2]=="csn")?"selected":"";
  $sortby_2=($options[2]=="rand()")?"selected":"";
  $sortby_3=($options[2]=="sort")?"selected":"";

  $sort_normal=($options[3]=="")?"selected":"";
  $sort_desc=($options[3]=="desc")?"selected":"";

  $options6_1=($options[6]=="1")?"checked":"";
  $options6_0=($options[6]!="1")?"checked":"";


  $form="
  "._MB_TADGAL_BLOCK_CATE_SHOWNUM."
  <INPUT type='text' name='options[0]' value='{$options[0]}' size=2><br>

  "._MB_TADGAL_BLOCK_DISPLAY_MODE."
  <select name='options[1]'>
    <option value='title' $display_0>"._MB_TADGAL_BLOCK_DISPLAY_MODE1."</option>
    <option value='album' $display_1>"._MB_TADGAL_BLOCK_DISPLAY_MODE2."</option>
    <option value='content' $display_2>"._MB_TADGAL_BLOCK_DISPLAY_MODE3."</option>
  </select><br>

  "._MB_TADGAL_BLOCK_SORTBY."
  <select name='options[2]'>
    <option value='csn' $sortby_0>"._MB_TADGAL_BLOCK_SORTBY_MODE1."</option>
    <option value='rand()' $sortby_2>"._MB_TADGAL_BLOCK_SORTBY_MODE3."</option>
    <option value='sort' $sortby_3>"._MB_TADGAL_BLOCK_SORTBY_MODE4."</option>
  </select>

  <select name='options[3]'>
    <option value='' $sort_normal>"._MB_TADGAL_BLOCK_SORT_NORMAL."</option>
    <option value='desc' $sort_desc>"._MB_TADGAL_BLOCK_SORT_DESC."</option>
  </select><br>

  "._MB_TADGAL_BLOCK_TEXT_NUM."
  <INPUT type='text' name='options[4]' value='{$options[4]}' size=2>"._MB_TADGAL_BLOCK_TEXT_NUM_DESC."<br>

  "._MB_TADGAL_BLOCK_TEXT_CSS." <INPUT type='text' name='options[5]' value='{$options[5]}' size=50><br>
  "._MB_TADGAL_BLOCK_ONLY_HAVE_CONTENT."
  <INPUT type='radio' name='options[6]' value='1' $options6_1>"._YES."
  <INPUT type='radio' name='options[6]' value='0' $options6_0>"._NO."
  "._MB_TADGAL_BLOCK_TEXT_NUM_DESC."<br>
  ";
  return $form;
}
