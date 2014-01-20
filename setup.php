<?php
include_once "header.php";
define("_TADGAL_UP_FILE_DIR",XOOPS_ROOT_PATH."/uploads/tadgallery/");
define("_TADGAL_UP_FILE_URL",XOOPS_URL."/uploads/tadgallery/");

$uid_dir=0;
if(isset($xoopsUser) and is_object($xoopsUser)){
  $uid_dir=$xoopsUser->uid();
}

define("_TADGAL_UP_IMPORT_DIR",_TADGAL_UP_FILE_DIR."upload_pics/user_{$uid_dir}/");
mk_dir(_TADGAL_UP_IMPORT_DIR);

define("_TADGAL_UP_MP3_DIR",_TADGAL_UP_FILE_DIR."mp3/");
define("_TADGAL_UP_MP3_URL",_TADGAL_UP_FILE_URL."mp3/");
?>