<?php
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}


function xoops_module_install_tadgallery(&$module)
{
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tadgallery');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tadgallery/upload_pics');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tadgallery/medium');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tadgallery/small');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tadgallery/mp3');

    return true;
}
