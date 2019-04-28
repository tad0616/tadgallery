<?php

use XoopsModules\Tadgallery\Update;

function xoops_module_update_tadgallery(&$module, $old_version)
{
    global $xoopsDB;

    if (!Update::chk_chk9()) {
        Update::go_update9();
    }

    Update::go_update10();
    if (!Update::chk_chk11()) {
        Update::go_update11();
    }

    if (!Update::chk_chk12()) {
        Update::go_update12();
    }

    if (Update::chk_chk13()) {
        Update::go_update13();
    }

    Update::chk_tadgallery_block();

    return true;
}
