<?php
include_once XOOPS_ROOT_PATH."/modules/tadgallery/class/tadgallery.php";
include_once XOOPS_ROOT_PATH."/modules/tadgallery/function_block.php";

//區塊主函式 (縮圖展示)
function tadgallery_list($options){
  global $xoopsDB;

  $tadgallery=new tadgallery();
  if($options[1]) $tadgallery->set_view_csn($options[1]);
  $tadgallery->set_view_good($options[7]);
  $tadgallery->set_orderby($options[2]);
  $tadgallery->set_order_desc($options[3]);
  $tadgallery->set_limit($options[0]);
  $photos=$tadgallery->get_photos('return');


  $pics="";
  $i=0;
  foreach($photos as $photo){

    $txt=(empty($photo['title']))?$photo['filename']:$photo['title'];
    $pics[$i]['pic_txt']=($options[8]=='1')?"<div style='width:{$options[5]}px;{$options[9]}'>$txt</div>":"";
    $pics[$i]['description']=Only1br($photo['description']);
    $pics[$i]['options5']=$options[5];
    $pics[$i]['options6']=$options[6];
    $pics[$i]['options4']=$options[4];
    $pics[$i]['pic_url']=$photo['photo_s'];
    $pics[$i]['sn']=$photo['sn'];
    $i++;
  }

  return $pics;
}

function Only1br($string)
{
    return preg_replace("/(\r\n)+|(\n|\r)+/", "<br />", $string);
}


//區塊編輯函式
function tadgallery_list_edit($options){
  $cate_select=get_tad_gallery_block_cate_option(0,0,$options[1]);


  $sortby_0=($options[2]=="post_date")?"selected":"";
  $sortby_1=($options[2]=="counter")?"selected":"";
  $sortby_2=($options[2]=="rand")?"selected":"";
  $sortby_3=($options[2]=="photo_sort")?"selected":"";

  $sort_normal=($options[3]=="")?"selected":"";
  $sort_desc=($options[3]=="desc")?"selected":"";


  $only_good_0=($options[7]!="1")?"selected":"";
  $only_good_1=($options[7]=="1")?"selected":"";


  $show_txt_0=($options[8]=="0")?"checked":"";
  $show_txt_1=($options[8]=="1")?"checked":"";

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
  "._MB_TADGAL_BLOCK_THUMB_SPACE."
  <INPUT type='text' name='options[4]' value='{$options[4]}' size=2> px<br>
  "._MB_TADGAL_BLOCK_THUMB_WIDTH."
  <INPUT type='text' name='options[5]' value='{$options[5]}' size=3> x
  "._MB_TADGAL_BLOCK_THUMB_HEIGHT."
  <INPUT type='text' name='options[6]' value='{$options[6]}' size=3> px<br>
  "._MB_TADGAL_BLOCK_SHOW_TYPE."<select name='options[7]'>
  <option value='0' $only_good_0>"._MB_TADGAL_BLOCK_SHOW_ALL."</option>
  <option value='1' $only_good_1>"._MB_TADGAL_BLOCK_ONLY_GOOD."</option>
  </select><br>
  "._MB_TADGAL_BLOCK_SHOW_TEXT."
  <input type='radio' name='options[8]' value=1 $show_txt_1>"._MB_TADGAL_BLOCK_SHOW_TEXT_Y."
  <input type='radio' name='options[8]' value=0 $show_txt_0>"._MB_TADGAL_BLOCK_SHOW_TEXT_N."<br>
  "._MB_TADGAL_BLOCK_TEXT_CSS." <INPUT type='text' name='options[9]' value='{$options[9]}' size=50><br>
  ";
  return $form;
}

