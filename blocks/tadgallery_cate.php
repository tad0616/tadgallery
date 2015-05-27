<?php
include_once XOOPS_ROOT_PATH."/modules/tadgallery/class/tadgallery.php";
include_once XOOPS_ROOT_PATH."/modules/tadgallery/function_block.php";

//區塊主函式 (縮圖展示)
function tadgallery_cate($options){
  global $xoopsDB,$xoTheme;

  // $default_val="4|album|rand()||300|line-height:1.8;|0";
  // $options=get_block_default($options,$default_val);

  $options[0]=intval($options[0]);
  $shownum=empty($options[0])?'5':$options[0];

  $display_arr=array('title','album','content');
  $display_mode=in_array($options[1],$display_arr)?$options[1]:"album";

  $sortby_arr=array('csn','rand()','sort');
  $sortby=in_array($options[2],$sortby_arr)?$options[2]:"rand()";

  $sort_desc=($options[3]=="desc")?"desc":"";

  $options[4]=intval($options[4]);
  $lengh=empty($options[4])?300:$options[4];

  $content_css=(empty($options[5]) or strrpos(';', $options[5])===false)?'line-height:1.8;':$options[5];

  $only_have_desc=($options[6]=="1")?"1":"0";
  if($display_mode!="content"){
    $only_have_desc=0;
  }

  $tadgallery=new tadgallery();
  $order="{$sortby} {$sort_desc}";
  $albums=$tadgallery->get_albums('return', true, $shownum, $order, true ,$lengh,$only_have_desc);
  $block['albums']=$albums;
  $block['display_mode']=$display_mode;
  $block['content_css']=$content_css;
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

  $options[0]=intval($options[0]);
  $options[0]=empty($options[0])?5:$options[0];

  $display_0=($options[1]=="title")?"selected":"";
  $display_1=($options[1]!="title" and $options[1]!="content")?"selected":"";
  $display_2=($options[1]=="content")?"selected":"";

  $sortby_0=($options[2]=="csn")?"selected":"";
  $sortby_2=($options[2]!="csn" and $options[2]!="sort")?"selected":"";
  $sortby_3=($options[2]=="sort")?"selected":"";

  $sort_normal=($options[3]!="desc")?"selected":"";
  $sort_desc=($options[3]=="desc")?"selected":"";

  $options[4]=intval($options[4]);
  $options[4]=empty($options[4])?300:$options[4];

  $options[5]=(empty($options[5]) or strrpos(';', $options[5])===false)?'line-height:1.8;':$options[5];

  $options6_1=($options[6]=="1")?"checked":"";
  $options6_0=($options[6]!="1")?"checked":"";


  $form="
  <div>
    "._MB_TADGAL_BLOCK_CATE_SHOWNUM."
    <input type='text' name='options[0]' value='{$options[0]}' size=2>
  </div>

  <div>
    "._MB_TADGAL_BLOCK_DISPLAY_MODE."
    <select name='options[1]'>
      <option value='title' $display_0>"._MB_TADGAL_BLOCK_DISPLAY_MODE1."</option>
      <option value='album' $display_1>"._MB_TADGAL_BLOCK_DISPLAY_MODE2."</option>
      <option value='content' $display_2>"._MB_TADGAL_BLOCK_DISPLAY_MODE3."</option>
    </select>
  </div>


  <div>
    "._MB_TADGAL_BLOCK_SORTBY."
    <select name='options[2]'>
      <option value='csn' $sortby_0>"._MB_TADGAL_BLOCK_SORTBY_MODE1."</option>
      <option value='rand()' $sortby_2>"._MB_TADGAL_BLOCK_SORTBY_MODE3."</option>
      <option value='sort' $sortby_3>"._MB_TADGAL_BLOCK_SORTBY_MODE4."</option>
    </select>

    <select name='options[3]'>
      <option value='' $sort_normal>"._MB_TADGAL_BLOCK_SORT_NORMAL."</option>
      <option value='desc' $sort_desc>"._MB_TADGAL_BLOCK_SORT_DESC."</option>
    </select>
  </div>


  <div>
    "._MB_TADGAL_BLOCK_TEXT_NUM."
    <input type='text' name='options[4]' value='{$options[4]}' size=2>
    "._MB_TADGAL_BLOCK_TEXT_NUM_DESC."
  </div>


  <div>
    "._MB_TADGAL_BLOCK_TEXT_CSS."
    <input type='text' name='options[5]' value='{$options[5]}' size=50>
  </div>

  <div>
    "._MB_TADGAL_BLOCK_ONLY_HAVE_CONTENT."
    <label for='options6_1'>
      <input type='radio' name='options[6]' value='1' $options6_1 id='options6_1'>
      "._YES."
    </label>
    <label for='options6_0'>
      <input type='radio' name='options[6]' value='0' $options6_0 id='options6_0'>
      "._NO."
    </label>
    "._MB_TADGAL_BLOCK_TEXT_NUM_DESC."
  </div>

  ";
  return $form;
}
