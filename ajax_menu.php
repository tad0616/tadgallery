<?php
include_once "header.php";
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$of_csn  = system_CleanVars($_REQUEST, 'of_csn', 0, 'int');
$def_csn = system_CleanVars($_REQUEST, 'def_csn', 0, 'int');
echo get_option($of_csn, $def_csn);

function get_option($of_csn = '', $def_csn = '')
{
    global $xoopsDB;
    $option = "";
    $sql    = "select csn,title from " . $xoopsDB->prefix("tad_gallery_cate") . "
    where of_csn='$of_csn' order by sort";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    while (list($csn, $title) = $xoopsDB->fetchRow($result)) {
        $selected = $csn == $def_csn ? "selected" : "";
        $option .= "<option value='$csn' $selected>$title</option>\n";
    }
    return $option;
}
