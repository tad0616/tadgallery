<?php
include_once XOOPS_ROOT_PATH."/modules/tadgallery/class/tadgallery.php";
include_once XOOPS_ROOT_PATH."/modules/tadgallery/function_block.php";

//區塊主函式 (Flash相片展示)
function tadgallery_slideshow($options){
  global $xoopsDB;

  $tadgallery=new tadgallery();
  $tadgallery->set_only_thumb(true);
  $tadgallery->set_view_csn($options[1]);
  $photos=$tadgallery->get_albums('return');


  foreach($photos as $photo){
    $pic_url=str_replace(XOOPS_URL."/uploads/tadgallery/medium/","",$photo['photo_m']);
    $pic_s_url=str_replace(XOOPS_URL."/uploads/tadgallery/small/","",$photo['photo_s']);
    $title=(empty($photo['title']))?$photo['album_title']:$photo['title'];
    $description=(empty($photo['description']))?"{$photo['dir']}/{$photo['filename']}":$photo['description'];

    $images.="
    <image title=\"{$photo['title']}\" date=\"{$photo['post_date']}\" thumbnail=\"{$pic_s_url}\" image=\"{$pic_url}\" link=\"".XOOPS_URL."/modules/tadgallery/view.php?sn={$photo['sn']}\">{$photo['description']}</image>";
    $at="<album title=\"{$photo['album_title']}\" description=\"{$photo['album_title']}\">";
  }

    $album="$at
    $images
    </album>";

    //製作 dl.php 內容
    //urlencode(to_utf8($options[0])) Big5可以
    //$options[0] Big5可以

    if(!empty($options[0])){
      $mp3=is_utf8(_MB_TADGAL_BLOCK_MP3)?$options[0]:urlencode(to_utf8($options[0]));
    }else{
      $mp3="";
    }

    $contents = file_get_contents(XOOPS_ROOT_PATH."/modules/tadgallery/gallery.xml");
    $contents=str_replace("[web_title]","DFGallery",$contents);
    $contents=str_replace("[xoops_url]",XOOPS_URL,$contents);
    $contents=str_replace("[mp3]",$mp3,$contents);
    $contents=str_replace("[album]",to_utf8($album),$contents);
    //寫入內容
    $handle = fopen(XOOPS_ROOT_PATH."/uploads/tadgallery/gallery_{$options[1]}.xml", 'w');
    fwrite($handle, $contents);
    fclose($handle);
  //}

  $block['sn']=$options[1];
  $block['width']=$options[2];
  $block['height']=$options[3];
  return $block;
}



//區塊編輯函式
function tadgallery_slideshow_edit($options){
  $cate_select=get_tad_gallery_block_cate_option(0,0,$options[1]);
  $is_utf8=is_utf8(_MB_TADGAL_BLOCK_MP3);
  $list="<select name='options[0]'>
  <option value=''>"._MB_TADGAL_BLOCK_NO_MP3."</option>
  ";
  if ($dh = opendir(XOOPS_ROOT_PATH."/uploads/tadgallery/mp3/")) {
    $i=0;
    while (($file = readdir($dh)) !== false) {
      if(substr($file,0,1)==".")continue;
      $selected=($options[0]==$file)?"selected":"";
      $file=($is_utf8)?to_utf8($file):$file;
      $list.="<option value='{$file}' $selected>$file</option>\n";
      $i++;
    }
    closedir($dh);
  }
  $list.="</select>";


  $form="
  "._MB_TADGAL_BLOCK_MP3."
  {$list}"._MB_TADGAL_BLOCK_MP3_txt."<br>
  "._MB_TADGAL_BLOCK_SHOWCATE."
  <select name='options[1]'>
    $cate_select
  </select>"._MB_TADGAL_BLOCK_CATE_TXT."<br>
  "._MB_TADGAL_BLOCK_WIDTH."
  <INPUT type='text' name='options[2]' value='{$options[2]}' size=3> x
  "._MB_TADGAL_BLOCK_HEIGHT."
  <INPUT type='text' name='options[3]' value='{$options[3]}' size=3> px<br>

  ";
  return $form;
}



if(!function_exists("is_utf8")){
  //判斷字串是否為utf8
  function  is_utf8($str)  {
      $i=0;
      $len  =  strlen($str);

      for($i=0;$i<$len;$i++)  {
          $sbit  =  ord(substr($str,$i,1));
          if($sbit  <  128)  {
              //本字節為英文字符，不與理會
          }elseif($sbit  >  191  &&  $sbit  <  224)  {
              //第一字節為落於192~223的utf8的中文字(表示該中文為由2個字節所組成utf8中文字)，找下一個中文字
              $i++;
          }elseif($sbit  >  223  &&  $sbit  <  240)  {
              //第一字節為落於223~239的utf8的中文字(表示該中文為由3個字節所組成的utf8中文字)，找下一個中文字
              $i+=2;
          }elseif($sbit  >  239  &&  $sbit  <  248)  {
              //第一字節為落於240~247的utf8的中文字(表示該中文為由4個字節所組成的utf8中文字)，找下一個中文字
              $i+=3;
          }else{
              //第一字節為非的utf8的中文字
              return  0;
          }
      }
      //檢查完整個字串都沒問體，代表這個字串是utf8中文字
      return  1;
  }
}


?>