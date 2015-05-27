<?php
include_once XOOPS_ROOT_PATH."/modules/tadgallery/class/tadgallery.php";
include_once XOOPS_ROOT_PATH."/modules/tadgallery/function_block.php";

//區塊主函式 (無縫跑馬燈)
function tadgallery_marquee_show($options){
  global $xoopsDB;

  // $default_val="12|0|1|post_date||m|0|100%|150|80";
  // $options=get_block_default($options,$default_val);

  $order_array=array('post_date','counter','rand','photo_sort');
  $limit=empty($options[0])?12:intval($options[0]);
  $view_csn=empty($options[1])?'':intval($options[1]);
  $include_sub=empty($options[2])?"0":"1";
  $order_by=in_array($options[3],$order_array)?$options[3]:"post_date";
  $desc=empty($options[4])?"":"desc";
  $size=(!empty($options[5]) and $options[5]=="s")?"s":"m";
  $only_good=$options[6]!='1'?"0":"1";

  $options[7]=intval($options[7]);
  $width=empty($options[7])?'100%':$options[7];
  $options[8]=intval($options[8]);
  $height=(empty($options[8]) or $options[8] <= 30)?240:$options[8];

  $options[9]=intval($options[9]);
  $speed=empty($options[9])?30:$options[9];

  $tadgallery=new tadgallery();
  $tadgallery->set_limit($limit);
  if($view_csn) $tadgallery->set_view_csn($view_csn);
  $tadgallery->set_orderby($order_by);
  $tadgallery->set_order_desc($desc);
  $tadgallery->set_view_good($only_good);
  $photos=$tadgallery->get_photos('return',$include_sub);



  $pics="";
  $i=0;
  foreach($photos as $photo){
    $pp='photo_'.$size;
    $pic_url=$photo[$pp];
    $title=(empty($photo['title']))?$photo['filename']:$photo['title'];

    $pics[$i]['pic_url']=$pic_url;
    $pics[$i]['photo_sn']=$photo['sn'];
    $pics[$i]['photo_title']=$title;
    //$pics[$i]['description']=(empty($photo['description']))?"":"<div style='padding:4px;background-color:#F0FFA0;font-size:11px;text-align:left;'>{$photo['description']}</div>";
    $i++;
  }

  $block['width']=$width;
  $block['height']=$height;
  $block['speed']=$speed;
  $block['pics']=$pics;

  get_jquery();
  return $block;
}



//區塊編輯函式
function tadgallery_marquee_edit($options){
  //die(implode('|',$options));
  //$option0~6
  $common_setup=common_setup($options);

  $options[8]=intval($options[8]);
  if(empty($options[8]) or $options[8]<=30)$options[8]=240;

  $options[9]=intval($options[9]);
  if(empty($options[9]))$options[9]=30;


  $form="
  {$common_setup}
  <div>
    "._MB_TADGAL_BLOCK_THUMB_WIDTH."
    <input type='hidden' name='options[7]' value='100%' size=3> 100% x
    "._MB_TADGAL_BLOCK_THUMB_HEIGHT."
    <input type='text' name='options[8]' value='{$options[8]}' size=3> px
  </div>
  <div>
    "._MB_TADGAL_GOOD_MOVE_SPEED."
    <input type='text' name='options[9]' value='{$options[9]}' size=4> (0-1000)
  </div>
  ";
  return $form;
}

