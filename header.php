<?php

use XoopsModules\Tadtools\Utility;

require_once dirname(dirname(__DIR__)) . '/mainfile.php';
require_once __DIR__ . '/function.php';

if ('1' == $xoopsModuleConfig['use_pda'] and false === mb_strpos($_SESSION['theme_kind'], 'bootstrap')) {
    Utility::mobile_device_detect(true, false, true, true, true, true, true, 'pda.php', false);
}

//判斷是否對該模組有管理權限
$isAdmin = false;
if ($xoopsUser) {
    $module_id = $xoopsModule->mid();
    $isAdmin = $xoopsUser->isAdmin($module_id);
}

$interface_menu[_TAD_TO_MOD] = 'index.php';

require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$csn = system_CleanVars($_REQUEST, 'csn', 0, 'int');

$upload_powers = Tadgallery::chk_cate_power('upload');

if ((!empty($upload_powers) and $xoopsUser) or $isAdmin) {
    $interface_menu[_MD_TADGAL_UPLOAD_PAGE] = 'uploads.php';
}

if ($csn and $isAdmin) {
    $interface_menu[_MD_TADGAL_MODIFY_CATE] = "admin/main.php?csn={$csn}";
}
