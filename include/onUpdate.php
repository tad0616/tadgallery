<?php

use XoopsModules\Tadgallery\Utility;

function xoops_module_update_tadgallery(&$module, $old_version)
{
    global $xoopsDB;

    if (!Utility::chk_chk9()) {
        Utility::go_update9();
    }

    Utility::go_update10();
    if (!Utility::chk_chk11()) {
        Utility::go_update11();
    }

    if (!Utility::chk_chk12()) {
        Utility::go_update12();
    }

    if (Utility::chk_chk13()) {
        Utility::go_update13();
    }

    Utility::chk_tadgallery_block();

    return true;
}
