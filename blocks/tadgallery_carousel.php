<?php
include_once XOOPS_ROOT_PATH."/modules/tadgallery/class/tadgallery.php";
include_once XOOPS_ROOT_PATH."/modules/tadgallery/function_block.php";

//區塊主函式 (相片捲軸)
function tadgallery_carousel_show($options){
  global $xoopsDB,$xoTheme;

  $order_array=array('post_date','counter','rand','photo_sort');
  $limit=empty($options[0])?12:intval($options[0]);
  $view_csn=empty($options[1])?'':intval($options[1]);
  $include_sub=empty($options[2])?"0":"1";
  $order_by=in_array($options[3],$order_array)?$options[3]:"post_date";
  $desc=empty($options[4])?"":"desc";
  $size=(!empty($options[5]) and $options[5]=="s")?"s":"m";
  $only_good=$options[6]!='1'?"0":"1";

  $options[7]=intval($options[7]);
  $width=empty($options[7])?140:$options[7];
  $options[8]=intval($options[8]);
  $height=empty($options[8])?105:$options[8];

  $direction=empty($options[9])?"0":"1";
  $options[10]=intval($options[10]);
  $speed=empty($options[10])?1000:$options[10];
  $options[11]=intval($options[11]);
  $scroll=empty($options[11])?3:$options[11];
  $move=empty($options[12])?0:intval($options[12]);
  $options[13]=intval($options[13]);
  $staytime=empty($options[13])?5000:$options[13];

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
    $pics[$i]['width']=$width;
    $pics[$i]['height']=$height;
    $pics[$i]['direction']=$direction;
    $pics[$i]['pic_url']=$pic_url;
    $pics[$i]['photo_sn']=$photo['sn'];
    $pics[$i]['photo_title']=$photo['title'];
    $i++;
  }


  if($direction=='1'){
    $vertical_height=$height * $scroll + 50;
    $css_txt="width:{$width}px;";
    $vertical="direction : 'up',";
  }else{
    $vertical_height="'auto'";
    $css_txt="height:{$height}px;";
    $vertical="";
  }

  //引入TadTools的jquery
  if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/jquery.php")){
   redirect_header("http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50",3, _TAD_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/jquery.php";

  $block['view_csn']=$view_csn;
  $block['vertical']=$vertical;
  $block['vertical_height']=$vertical_height;
  $block['scroll']=intval($scroll)==0?"":"scroll: {$scroll},";
  $block['pics']=$pics;

  get_jquery();
  $xoTheme->addScript('modules/tadgallery/class/carouFredSel/jquery.carouFredSel-6.2.1-packed.js');
  $xoTheme->addScript('modules/tadgallery/class/carouFredSel/helper-plugins/jquery.mousewheel.min.js');
  $xoTheme->addScript('modules/tadgallery/class/carouFredSel/helper-plugins/jquery.touchSwipe.min.js');
  $xoTheme->addScript('modules/tadgallery/class/carouFredSel/helper-plugins/jquery.transit.min.js');
  $xoTheme->addScript('modules/tadgallery/class/carouFredSel/helper-plugins/jquery.ba-throttle-debounce.min.js');
  return $block;
}



//區塊編輯函式
function tadgallery_carousel_edit($options){
  //$option0~6
  $common_setup=common_setup($options);

  $options[7]=intval($options[7]);
  if(empty($options[7]))$options[7]=140;

  $options[8]=intval($options[8]);
  if(empty($options[8]))$options[8]=105;

  $vertical_1=($options[9]=="1")?"checked":"";
  $vertical_0=($options[9]=="0")?"checked":"";

  $options[10]=intval($options[10]);
  if(empty($options[10]))$options[10]=1000;

  $options[11]=intval($options[11]);
  if(empty($options[11]))$options[11]=3;

  $options[12]=intval($options[12]);

  $options[13]=intval($options[13]);
  if(empty($options[13]))$options[13]=5000;

  $form="
  {$common_setup}

  <div>
    "._MB_TADGAL_BLOCK_THUMB_WIDTH."
    <input type='text' name='options[7]' value='{$options[7]}' size=3> x
    "._MB_TADGAL_BLOCK_THUMB_HEIGHT."
    <input type='text' name='options[8]' value='{$options[8]}' size=3> px
  </div>

  <div>
    "._MB_TADGAL_GOOD_MOVE_DIRECTION."
    <input type='radio' name='options[9]' value='1' $vertical_1>"._MB_TADGAL_GOOD_MOVE_DIRECTION_OPT3."
    <input type='radio' name='options[9]' value='0' $vertical_0>"._MB_TADGAL_GOOD_MOVE_DIRECTION_OPT4."
  </div>

  <div>
    "._MB_TADGAL_GOOD_MOVE_SPEED."
    <input type='text' name='options[10]' value='{$options[10]}' size=5>
    "._MB_TADGAL_MS."
  </div>

  <div>
    "._MB_TADGAL_BLOCK_COLS."
    <input type='text' name='options[11]' value='{$options[11]}' size=1>
    "._MB_TADGAL_BLOCK_COLS_DESC."
  </div>

  <div>
    "._MB_TADGAL_MOVE_NUM."
    <input type='text' name='options[12]' value='{$options[12]}' size=1>
    "._MB_TADGAL_MOVE_NUM_DESC."
  </div>

  <div>
    "._MB_TADGAL_SHOW_TIME."
    <input type='text' name='options[13]' value='{$options[13]}' size=5>
    "._MB_TADGAL_MS."
  </div>
  ";
  return $form;
}
