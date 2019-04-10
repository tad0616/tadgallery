<?php
function xoops_module_uninstall_tadgallery(&$module)
{
    global $xoopsDB;
    $date = date("Ymd");

    rename(XOOPS_ROOT_PATH . "/uploads/tadgallery", XOOPS_ROOT_PATH . "/uploads/tadgallery_bak_{$date}");

    //tadgallery_full_copy(XOOPS_ROOT_PATH."/uploads/tadgallery",XOOPS_ROOT_PATH."/uploads/tadgallery_bak_{$date}");
    //tadgallery_delete_directory(XOOPS_ROOT_PATH."/uploads/tadgallery");

    return true;
}

function tadgallery_delete_directory($dirname)
{
    if (is_dir($dirname)) {
        $dir_handle = opendir($dirname);
    }

    if (!$dir_handle) {
        return false;
    }

    while ($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($dirname . "/" . $file)) {
                unlink($dirname . "/" . $file);
            } else {
                tadgallery_delete_directory($dirname . '/' . $file);
            }

        }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
}

function tadgallery_full_copy($source = "", $target = "")
{
    if (is_dir($source)) {
        @mkdir($target);
        $d = dir($source);
        while (false !== ($entry = $d->read())) {
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            $Entry = $source . '/' . $entry;
            if (is_dir($Entry)) {
                tadgallery_full_copy($Entry, $target . '/' . $entry);
                continue;
            }
            copy($Entry, $target . '/' . $entry);
        }
        $d->close();
    } else {
        copy($source, $target);
    }
}
