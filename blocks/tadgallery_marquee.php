<?php
include_once XOOPS_ROOT_PATH."/modules/tadgallery/class/tadgallery.php";
include_once XOOPS_ROOT_PATH."/modules/tadgallery/function_block.php";

//區塊主函式 (無縫跑馬燈)
function tadgallery_marquee_show($options){
  global $xoopsDB;

  $modhandler = &xoops_gethandler('module');
  $xoopsModule = &$modhandler->getByDirname("tadgallery");
  $config_handler =& xoops_gethandler('config');
  $xoopsModuleConfig =& $config_handler->getConfigsByCat(0, $xoopsModule->getVar('mid'));

  $tadgallery=new tadgallery();
  if($options[1]) $tadgallery->set_view_csn($options[1]);
  $tadgallery->set_view_good($options[7]);
  $tadgallery->set_orderby($options[2]);
  $tadgallery->set_order_desc($options[3]);
  $tadgallery->set_limit($options[0]);
  $photos=$tadgallery->get_photos('return');
  if(empty($options[4]))$options[4]="s";
  if($options[4]=="m"){
    $subdir="medium/";
    $pic_width=$xoopsModuleConfig['thumbnail_m_width'];
  }elseif($options[4]=="s"){
    $subdir="small/";
    $pic_width=$xoopsModuleConfig['thumbnail_s_width'];
  }else{
    $subdir=$pic_width="";
  }

  $pics="";
  $i=0;
  foreach($photos as $photo){
    $pp='photo_'.$options[4];
    $pic_url=$photo[$pp];
    $title=(empty($photo['title']))?$photo['filename']:$photo['title'];
    $pics[$i]['description']=(empty($photo['description']))?"":"<div style='padding:4px;background-color:#F0FFA0'>{$photo['description']}</div>";
    $pics[$i]['pic_url']=$pic_url;
    $pics[$i]['sn']=$photo['sn'];
    $pics[$i]['title']=$photo['title'];
    $i++;
  }

  $block['options1']=$options[1];
  //$block['options5']=$options[5];
  $block['options6']=$options[6];
  $block['options8']=$options[8];
  $block['pic_width']=$pic_width;
  $block['pics']=$pics;
  $block['jquery']=get_jquery();
  return $block;
}



//區塊編輯函式
function tadgallery_marquee_edit($options){
  $cate_select=get_tad_gallery_block_cate_option(0,0,$options[1]);

  $sortby_0=($options[2]=="post_date")?"selected":"";
  $sortby_1=($options[2]=="counter")?"selected":"";
  $sortby_2=($options[2]=="rand")?"selected":"";
  $sortby_3=($options[2]=="photo_sort")?"selected":"";

  $sort_normal=($options[3]=="")?"selected":"";
  $sort_desc=($options[3]=="desc")?"selected":"";

  $thumb_s=($options[4]=="s")?"checked":"";
  $thumb_m=($options[4]=="m")?"checked":"";

  $only_good_0=($options[7]!="1")?"selected":"";
  $only_good_1=($options[7]=="1")?"selected":"";


  $form="
  "._MB_TADGAL_BLOCK_SHOWNUM."
  <INPUT type='text' name='options[0]' value='{$options[0]}' size=2><br>
  "._MB_TADGAL_BLOCK_SHOWCATE."
  <select name='options[1]'>
    $cate_select
  </select><br>
  "._MB_TADGAL_BLOCK_SORTBY."
  <select name='options[2]'>
  <option value='post_date' $sortby_0>"._MB_TADGAL_BLOCK_SORTBY_MODE1."</option>
  <option value='counter' $sortby_1>"._MB_TADGAL_BLOCK_SORTBY_MODE2."</option>
  <option value='rand' $sortby_2>"._MB_TADGAL_BLOCK_SORTBY_MODE3."</option>
  <option value='photo_sort' $sortby_3>"._MB_TADGAL_BLOCK_SORTBY_MODE4."</option>
  </select><select name='options[3]'>
  <option value='' $sort_normal>"._MB_TADGAL_BLOCK_SORT_NORMAL."</option>
  <option value='desc' $sort_desc>"._MB_TADGAL_BLOCK_SORT_DESC."</option>
  </select><br>
  "._MB_TADGAL_BLOCK_THUMB."
  <INPUT type='radio' $thumb_s name='options[4]' value='s'>"._MB_TADGAL_BLOCK_THUMB_S."
  <INPUT type='radio' $thumb_m name='options[4]' value='m'>"._MB_TADGAL_BLOCK_THUMB_M."<br>
  <INPUT type='hidden' name='options[5]' value='{$options[5]}' size=3>
  "._MB_TADGAL_BLOCK_HEIGHT."
  <INPUT type='text' name='options[6]' value='{$options[6]}' size=3> px<br>
  "._MB_TADGAL_BLOCK_SHOW_TYPE."<select name='options[7]'>
  <option value='0' $only_good_0>"._MB_TADGAL_BLOCK_SHOW_ALL."</option>
  <option value='1' $only_good_1>"._MB_TADGAL_BLOCK_ONLY_GOOD."</option>
  </select><br>
  "._MB_TADGAL_GOOD_MOVE_SPEED."
  <INPUT type='text' name='options[8]' value='{$options[8]}' size=4> (0-1000)<br>

  ";
  return $form;
}

