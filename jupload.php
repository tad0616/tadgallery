<?php
include_once "header.php";
include_once XOOPS_ROOT_PATH."/modules/tadtools/jupload/jupload.php";
if(empty($upload_powers) or !$xoopsUser){
  die(_TADGAL_NO_UPLOAD_POWER);
}
/*-----------function區--------------*/


$appletParameters = array(
  'maxFileSize' => '2G',
  'postURL' => XOOPS_URL.'/modules/tadgallery/jupload.php',
  'archive' => XOOPS_URL.'/modules/tadtools/jupload/wjhk.jupload.jar',
  'afterUploadURL' => XOOPS_URL.'/modules/tadgallery/uploads.php?op=to_batch_upload',
  'allowedFileExtensions' => 'jpg/png/gif',
  'sendMD5Sum' => 'true',
  'showLogWindow' => 'false',
  'debugLevel' => 99
);

$classParameters = array(
  'demo_mode' => false,
  'allow_subdirs' => true,
  'destdir' => _TADGAL_UP_IMPORT_DIR
);
$juploadPhpSupportClass = new JUpload($appletParameters, $classParameters);

?>

<!--JUPLOAD_APPLET-->