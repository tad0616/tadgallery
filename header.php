<?php
include_once "../../mainfile.php";
include_once "function.php";

if ($xoopsModuleConfig['use_pda'] == '1' and strpos($_SESSION['theme_kind'], 'bootstrap') === false) {
    if (file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/mobile_device_detect.php")) {
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/mobile_device_detect.php";
        mobile_device_detect(true, false, true, true, true, true, true, 'pda.php', false);
    }
}

//判斷是否對該模組有管理權限
$isAdmin = false;
if ($xoopsUser) {
    $module_id = $xoopsModule->mid();
    $isAdmin   = $xoopsUser->isAdmin($module_id);
}

$interface_menu[_TAD_TO_MOD] = "index.php";

$csn                                 = (empty($_REQUEST['csn'])) ? "" : intval($_REQUEST['csn']);
$interface_menu[_MD_TADGAL_COOLIRIS] = "cooliris.php?csn=$csn";

$upload_powers = tadgallery::chk_cate_power("upload");

if ((!empty($upload_powers) and $xoopsUser) or $isAdmin) {
    $interface_menu[_MD_TADGAL_UPLOAD_PAGE] = "uploads.php";
}

if ($csn and $isAdmin) {
    $interface_menu[_MD_TADGAL_MODIFY_CATE] = "admin/cate.php?csn={$csn}";
}
