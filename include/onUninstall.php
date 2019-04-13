<?php

function xoops_module_uninstall_tadgallery(&$module)
{
    global $xoopsDB;
    $date = date('Ymd');

    rename(XOOPS_ROOT_PATH . '/uploads/tadgallery', XOOPS_ROOT_PATH . "/uploads/tadgallery_bak_{$date}");

    //tadgallery_full_copy(XOOPS_ROOT_PATH."/uploads/tadgallery",XOOPS_ROOT_PATH."/uploads/tadgallery_bak_{$date}");
    //tadgallery_delete_directory(XOOPS_ROOT_PATH."/uploads/tadgallery");

    return true;
}
