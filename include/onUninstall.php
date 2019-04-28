<?php

function xoops_module_uninstall_tadgallery(&$module)
{
    global $xoopsDB;
    $date = date('Ymd');

    rename(XOOPS_ROOT_PATH . '/uploads/tadgallery', XOOPS_ROOT_PATH . "/uploads/tadgallery_bak_{$date}");

    return true;
}
