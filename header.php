<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2008-03-23
// $Id: header.php,v 1.3 2008/05/05 03:23:04 tad Exp $
// ------------------------------------------------------------------------- //
include_once "../../mainfile.php";


if($xoopsModuleConfig['use_pda']=='1'){
  if(file_exists(XOOPS_ROOT_PATH."/modules/tadtools/mobile_device_detect.php")){
    include_once XOOPS_ROOT_PATH."/modules/tadtools/mobile_device_detect.php";
    mobile_device_detect(true,false,true,true,true,true,true,'pda.php',false);
  }
}

include_once "function.php";

//判斷是否對該模組有管理權限
$isAdmin=false;
if ($xoopsUser) {
    $module_id = $xoopsModule->getVar('mid');
    $isAdmin=$xoopsUser->isAdmin($module_id);
}


$interface_menu[_TO_INDEX_PAGE]="index.php";

$csn=(empty($_REQUEST['csn']))?"":intval($_REQUEST['csn']);
$interface_menu[_MD_TADGAL_COOLIRIS]="cooliris.php?csn=$csn";

$upload_powers=tadgallery::chk_cate_power("upload");

if(sizeof($upload_powers)>0 and $xoopsUser){
	$interface_menu[_MD_TADGAL_UPLOAD_PAGE]="uploads.php";
}

if($isAdmin){
  $and_csn=(empty($_REQUEST['csn']))?"":"?csn=".intval($_REQUEST['csn']);
  $interface_menu[_TO_ADMIN_PAGE]="admin/index.php{$and_csn}";
}
	
?>
