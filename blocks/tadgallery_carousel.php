<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2008-03-23
// $Id: tadgallery_carousel.php,v 1.8 2008/05/14 01:23:14 tad Exp $
// ------------------------------------------------------------------------- //

include_once XOOPS_ROOT_PATH."/modules/tadgallery/class/tadgallery.php";
include_once XOOPS_ROOT_PATH."/modules/tadgallery/function_block.php";

//區塊主函式 (最新上傳的相片)
function tadgallery_carousel_show($options){
	global $xoopsDB;

  $tadgallery=new tadgallery();
  $tadgallery->set_only_thumb(true);  
  $tadgallery->set_view_csn($options[1]);
  $tadgallery->set_view_good($options[7]); 
  $tadgallery->set_orderby($options[2]); 
  $tadgallery->set_order_desc($options[3]); 
  $tadgallery->set_limit($options[0]); 
  $photos=$tadgallery->get_albums('return');
	if(empty($options[4]))$options[4]="s";

	$pics="";
  $i=0;
  foreach($photos as $photo){
    $pp='photo_'.$options[4];
    $pic_url=$photo[$pp];
    $pics[$i]['options5']=$options[5];
    $pics[$i]['options6']=$options[6];
    $pics[$i]['options11']=$options[11];
    $pics[$i]['pic_url']=$pic_url;
    $pics[$i]['photo_sn']=$photo['sn'];
    $pics[$i]['photo_title']=$photo['title'];
    $i++;
  }


  if($options[8]=='1'){
    $vertical_height=$options[6]*$options[10]+50;
    $css_txt="width:{$options[5]}px;";
    $vertical="direction : 'up',";
    $button1="<a href='#' class='next'><div style='width:{$options[5]}px;height:40px;background-image:url(".XOOPS_URL."/modules/tadgallery/images/up_32.png);  background-position: center center;  background-repeat: no-repeat;'></div></a>";
    $button2="";
    $button3="<a href='#' class='prev'><div style='width:{$options[5]}px;height:40px;background-image:url(".XOOPS_URL."/modules/tadgallery/images/down_32.png);  background-position: center center;  background-repeat: no-repeat;'></div></a>";
  }else{
    $vertical_height="'auto'";
    $css_txt="height:{$options[6]}px;";
    $vertical="";
    $button1="<a href='#' class='next'><div style='float:right;width:40px;height:{$options[6]}px;background-image:url(".XOOPS_URL."/modules/tadgallery/images/right_32.png);  background-position: center center;  background-repeat: no-repeat;'></div></a>";
    $button2="<a href='#' class='prev'><div style='float:left;width:40px;height:{$options[6]}px;background-image:url(".XOOPS_URL."/modules/tadgallery/images/left_32.png);  background-position: center center;  background-repeat: no-repeat;'></div></a>";
    $button3="";
  }
  

	//引入TadTools的jquery
  if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/jquery.php")){
   redirect_header("http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50",3, _TAD_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/jquery.php";

 
  $block['vertical_height']=$vertical_height;
  $block['button1']=$button1;
  $block['button2']=$button2;
  $block['button3']=$button3;
  $block['pics']=$pics;
  $block['options1']=$options[1];
  $block['options10']=$options[10];
  $block['scroll']=intval($options[11])==0?"":"scroll: {$options[11]},";
  $block['options9']=$options[9];
  $block['options12']=$options[12];
  $block['vertical']=$vertical;
  $block['jquery_path']=get_jquery();
	return $block;
}



//區塊編輯函式
function tadgallery_carousel_edit($options){
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
	

	$vertical_1=($options[8]=="1")?"checked":"";
	$vertical_0=($options[8]=="0")?"checked":"";

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
	</select>
  
  <select name='options[3]'>
	<option value='' $sort_normal>"._MB_TADGAL_BLOCK_SORT_NORMAL."</option>
	<option value='desc' $sort_desc>"._MB_TADGAL_BLOCK_SORT_DESC."</option>
	</select><br>
	"._MB_TADGAL_BLOCK_THUMB."
	<INPUT type='radio' $thumb_s name='options[4]' value='s'>"._MB_TADGAL_BLOCK_THUMB_S."
	<INPUT type='radio' $thumb_m name='options[4]' value='m'>"._MB_TADGAL_BLOCK_THUMB_M."<br>
	"._MB_TADGAL_BLOCK_THUMB_WIDTH."
	<INPUT type='text' name='options[5]' value='{$options[5]}' size=3> x
	"._MB_TADGAL_BLOCK_THUMB_HEIGHT."
	<INPUT type='text' name='options[6]' value='{$options[6]}' size=3> px<br>
	"._MB_TADGAL_BLOCK_SHOW_TYPE."<select name='options[7]'>
	<option value='0' $only_good_0>"._MB_TADGAL_BLOCK_SHOW_ALL."</option>
	<option value='1' $only_good_1>"._MB_TADGAL_BLOCK_ONLY_GOOD."</option>
	</select><br>
	"._MB_TADGAL_GOOD_MOVE_DIRECTION."
  <INPUT type='radio' name='options[8]' value='1' $vertical_1>"._MB_TADGAL_GOOD_MOVE_DIRECTION_OPT3."
  <INPUT type='radio' name='options[8]' value='0' $vertical_0>"._MB_TADGAL_GOOD_MOVE_DIRECTION_OPT4."<br>
  "._MB_TADGAL_GOOD_MOVE_SPEED."<INPUT type='text' name='options[9]' value='{$options[9]}' size=5>"._MB_TADGAL_MS."<br>
  "._MB_TADGAL_BLOCK_COLS."<INPUT type='text' name='options[10]' value='{$options[10]}' size=1>"._MB_TADGAL_BLOCK_COLS_DESC."<br>
  "._MB_TADGAL_MOVE_NUM."<INPUT type='text' name='options[11]' value='{$options[11]}' size=1>"._MB_TADGAL_MOVE_NUM_DESC."<br>
  "._MB_TADGAL_SHOW_TIME."<INPUT type='text' name='options[12]' value='{$options[12]}' size=5>"._MB_TADGAL_MS."
	";
	return $form;
}



?>
