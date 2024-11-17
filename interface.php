<?php
use Xmf\Request;
use XoopsModules\Tadgallery\Tools;
if (!class_exists('XoopsModules\Tadgallery\Tools')) {
    require XOOPS_ROOT_PATH . '/modules/tadgallery/preloads/autoloader.php';
}

//判斷是否對該模組有管理權限
if (!isset($tad_gallery_adm)) {
    $tad_gallery_adm = isset($xoopsUser) && \is_object($xoopsUser) ? $xoopsUser->isAdmin() : false;
}

$interface_menu[_MD_TADGAL_INDEX] = 'index.php';
$interface_icon[_MD_TADGAL_INDEX] = 'fa-picture-o';

$csn = Request::getInt('csn');

$upload_powers = Tools::chk_cate_power('upload');

if ((!empty($upload_powers) and isset($xoopsUser) && \is_object($xoopsUser)) or $tad_gallery_adm) {
    $interface_menu[_MD_TADGAL_UPLOAD_PAGE] = 'uploads.php';
    $interface_icon[_MD_TADGAL_UPLOAD_PAGE] = 'fa-upload';
}

if ($csn and $tad_gallery_adm) {
    $interface_menu[_MD_TADGAL_MODIFY_CATE] = "admin/main.php?op=tad_gallery_cate_form&csn={$csn}";
    $interface_icon[_MD_TADGAL_MODIFY_CATE] = 'fa-folder-open-o';
}
