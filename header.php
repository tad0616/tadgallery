<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;

require_once dirname(dirname(__DIR__)) . '/mainfile.php';
require_once __DIR__ . '/function.php';

//判斷是否對該模組有管理權限
$isAdmin = false;
if ($xoopsUser) {
    $module_id = $xoopsModule->mid();
    $isAdmin = $xoopsUser->isAdmin($module_id);
}

$interface_menu[_TAD_TO_MOD] = 'index.php';

$csn = Request::getInt('csn');

$upload_powers = Tadgallery::chk_cate_power('upload');

if ((!empty($upload_powers) and $xoopsUser) or $isAdmin) {
    $interface_menu[_MD_TADGAL_UPLOAD_PAGE] = 'uploads.php';
}

if ($csn and $isAdmin) {
    $interface_menu[_MD_TADGAL_MODIFY_CATE] = "admin/main.php?op=tad_gallery_cate_form&csn={$csn}";
}
