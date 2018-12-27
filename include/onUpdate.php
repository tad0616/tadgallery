<?php

function xoops_module_update_tadgallery(&$module, $old_version)
{
    global $xoopsDB;

    if (!chk_chk9()) {
        go_update9();
    }

    go_update10();
    if (!chk_chk11()) {
        go_update11();
    }

    if (!chk_chk12()) {
        go_update12();
    }

    if (chk_chk13()) {
        go_update13();
    }

    chk_tadgallery_block();

    return true;
}

//新增排序欄位
function chk_chk9()
{
    global $xoopsDB;
    $sql    = "SELECT count(`photo_sort`) FROM " . $xoopsDB->prefix("tad_gallery");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function go_update9()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_gallery") . " ADD `photo_sort` SMALLINT(5) UNSIGNED NOT NULL";
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin", 30, $xoopsDB->error());
}

function go_update10()
{
    global $xoopsDB;
    $sql = "update " . $xoopsDB->prefix("tad_gallery_cate") . " set show_mode='normal' where show_mode='thubm' or show_mode='slideshow' or show_mode='3d'";
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin", 30, $xoopsDB->error());
}

//新增說明欄位
function chk_chk11()
{
    global $xoopsDB;
    $sql    = "SELECT count(`content`) FROM " . $xoopsDB->prefix("tad_gallery_cate");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function go_update11()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_gallery_cate") . " ADD `content` TEXT NOT NULL AFTER `title`";
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin", 30, $xoopsDB->error());
}

//新增是否啟用欄位
function chk_chk12()
{
    global $xoopsDB;
    $sql    = "SELECT count(`enable`) FROM " . $xoopsDB->prefix("tad_gallery_cate");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function go_update12()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_gallery_cate") . " ADD `enable` ENUM('1','0') NOT NULL DEFAULT '1'";
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin", 30, $xoopsDB->error());
}

//刪除錯誤的重複欄位及樣板檔
function chk_tadgallery_block()
{
    global $xoopsDB;
    //die(var_export($xoopsConfig));
    include XOOPS_ROOT_PATH . '/modules/tadgallery/xoops_version.php';

    //先找出該有的區塊以及對應樣板
    foreach ($modversion['blocks'] as $i => $block) {
        $show_func                = $block['show_func'];
        $tpl_file_arr[$show_func] = $block['template'];
        $tpl_desc_arr[$show_func] = $block['description'];
    }

    //找出目前所有的樣板檔
    $sql    = "SELECT bid,name,visible,show_func,template FROM `" . $xoopsDB->prefix("newblocks") . "` WHERE `dirname` = 'tadgallery' ORDER BY `func_num`";
    $result = $xoopsDB->query($sql);
    while (list($bid, $name, $visible, $show_func, $template) = $xoopsDB->fetchRow($result)) {
        //假如現有的區塊和樣板對不上就刪掉
        if ($template != $tpl_file_arr[$show_func]) {
            $sql = "delete from " . $xoopsDB->prefix("newblocks") . " where bid='{$bid}'";
            $xoopsDB->queryF($sql);

            //連同樣板以及樣板實體檔案也要刪掉
            $sql = "delete from " . $xoopsDB->prefix("tplfile") . " as a left join " . $xoopsDB->prefix("tplsource") . "  as b on a.tpl_id=b.tpl_id where a.tpl_refid='$bid' and a.tpl_module='tadgallery' and a.tpl_type='block'";
            $xoopsDB->queryF($sql);
        } else {
            $sql = "update " . $xoopsDB->prefix("tplfile") . " set tpl_file='{$template}' , tpl_desc='{$tpl_desc_arr[$show_func]}' where tpl_refid='{$bid}'";
            $xoopsDB->queryF($sql);
        }
    }

}

//新增360欄位
function chk_chk13()
{
    global $xoopsDB;
    $sql    = "SELECT count(`is360`) FROM " . $xoopsDB->prefix("tad_gallery");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function go_update13()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_gallery") . " ADD `is360` ENUM('0','1') NOT NULL DEFAULT '0'";
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin", 30, $xoopsDB->error());
}

//建立目錄
if (!function_exists('mk_dir')) {
    function mk_dir($dir = "")
    {
        //若無目錄名稱秀出警告訊息
        if (empty($dir)) {
            return;
        }

        //若目錄不存在的話建立目錄
        if (!is_dir($dir)) {
            umask(000);
            //若建立失敗秀出警告訊息
            mkdir($dir, 0777);
        }
    }
}

//拷貝目錄
if (!function_exists('full_copy')) {
    function full_copy($source = "", $target = "")
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
                    full_copy($Entry, $target . '/' . $entry);
                    continue;
                }
                copy($Entry, $target . '/' . $entry);
            }
            $d->close();
        } else {
            copy($source, $target);
        }
    }
}

if (!function_exists('rename_win')) {
    function rename_win($oldfile, $newfile)
    {
        if (!rename($oldfile, $newfile)) {
            if (copy($oldfile, $newfile)) {
                unlink($oldfile);
                return true;
            }
            return false;
        }
        return true;
    }
}

if (!function_exists('delete_directory')) {
    function delete_directory($dirname)
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
                    delete_directory($dirname . '/' . $file);
                }
            }
        }
        closedir($dir_handle);
        rmdir($dirname);
        return true;
    }
}
