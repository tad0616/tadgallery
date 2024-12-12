<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: Origin, Methods, Content-Type");

use Xmf\Request;
use XoopsModules\Tadgallery\Tools;
use XoopsModules\Tadtools\Utility;

require_once __DIR__ . '/header.php';
header('HTTP/1.1 200 OK');
$xoopsLogger->activated = false;

$op = Request::getString('op');
$of_csn = Request::getInt('of_csn');
$def_csn = Request::getInt('def_csn');
$chk_view = Request::getInt('chk_view', 1);
$chk_up = Request::getInt('chk_up', 1);

echo get_option($of_csn, $def_csn, $chk_view, $chk_up);

function get_option($of_csn = '', $def_csn = '', $chk_view = 1, $chk_up = 1)
{
    global $xoopsDB;

    $ok_cat = $ok_up_cat = '';

    if ($chk_view) {
        $ok_cat = Tools::chk_cate_power();
    }

    if ($chk_up) {
        $ok_up_cat = Tools::chk_cate_power('upload');
    }
    $option = '';
    $sql = 'SELECT `csn`, `title` FROM `' . $xoopsDB->prefix('tad_gallery_cate') . '` WHERE `of_csn` = ? ORDER BY `sort`';
    $result = Utility::query($sql, 'i', [$of_csn]) or Utility::web_error($sql, __FILE__, __LINE__);
    while (list($csn, $title) = $xoopsDB->fetchRow($result)) {
        $csn = (int) $csn;

        if ($chk_view and is_array($ok_cat)) {

            if (!in_array($csn, $ok_cat)) {
                continue;
            }
        }

        if ($chk_up and is_array($ok_up_cat)) {
            if (!in_array($csn, $ok_up_cat)) {
                continue;
            }
        }
        $selected = $csn == $def_csn ? 'selected' : '';
        $option .= "<option value='$csn' $selected>/$title</option>\n";
    }

    return $option;
}
