<?php
include_once 'header.php';
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$of_csn = system_CleanVars($_REQUEST, 'of_csn', 0, 'int');
$def_csn = system_CleanVars($_REQUEST, 'def_csn', 0, 'int');
$chk_view = system_CleanVars($_REQUEST, 'chk_view', 1, 'int');
$chk_up = system_CleanVars($_REQUEST, 'chk_up', 1, 'int');
echo get_option($of_csn, $def_csn, $chk_view, $chk_up);

function get_option($of_csn = '', $def_csn = '', $chk_view = 1, $chk_up = 1)
{
    global $xoopsDB, $xoopsUser, $xoopsModule, $isAdmin;

    $tadgallery = new tadgallery();
    $ok_cat = $ok_up_cat = '';

    if ($chk_view) {
        $ok_cat = $tadgallery->chk_cate_power();
    }

    if ($chk_up) {
        $ok_up_cat = $tadgallery->chk_cate_power('upload');
    }
    $option = '';
    $sql = 'select csn,title from ' . $xoopsDB->prefix('tad_gallery_cate') . "
    where of_csn='$of_csn' order by sort";
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    while (list($csn, $title) = $xoopsDB->fetchRow($result)) {
        $csn = (int) $csn;

        if ($chk_view and is_array($ok_cat)) {

            if (!in_array($csn, $ok_cat, true)) {
                continue;
            }
        }

        if ($chk_up and is_array($ok_up_cat)) {
            if (!in_array($csn, $ok_up_cat, true)) {
                continue;
            }
        }
        $selected = $csn == $def_csn ? 'selected' : '';
        $option .= "<option value='$csn' $selected>$title</option>\n";
    }

    return $option;
}
