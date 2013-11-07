<?php

//區塊主函式 (QR Code)
function tadgallery_qrcode_show($options){
  if(preg_match("/tadgallery\/index.php\?csn=/i", $_SERVER['REQUEST_URI'])){
    $url=str_replace("index.php","pda.php",$_SERVER['REQUEST_URI']);
  }elseif(preg_match("/tadgallery\/view.php\?sn=/i", $_SERVER['REQUEST_URI'])){
    $url=str_replace("view.php","pda.php",$_SERVER['REQUEST_URI']);
  }elseif(preg_match("/tadgallery\/$/i", $_SERVER['REQUEST_URI'])){
    $url=$_SERVER['REQUEST_URI']."pda.php";
  }else{
    return ;
  }

  //高亮度語法
  if(!file_exists(TADTOOLS_PATH."/qrcode.php")){
   redirect_header("index.php",3, _MA_NEED_TADTOOLS);
  }
  include_once TADTOOLS_PATH."/qrcode.php";
  $qrcode= new qrcode();
  $qrcode_code=$qrcode->render($url);


  $block="
  $qrcode_code
  ";
  return $block;
}
?>
