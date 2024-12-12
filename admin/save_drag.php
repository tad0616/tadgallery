<?php
use XoopsModules\Tadtools\Utility;

/*-----------引入檔案區--------------*/
require dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';

header('HTTP/1.1 200 OK');
$xoopsLogger->activated = false;

$of_csn = (int) $_POST['of_csn'];
$csn = (int) $_POST['csn'];

$sql = 'UPDATE `' . $xoopsDB->prefix('tad_gallery_cate') . '` SET `of_csn`=? WHERE `csn`=?';
Utility::query($sql, 'ii', [$of_csn, $csn]) or die('Reset Fail! (' . date('Y-m-d H:i:s') . ')');

echo _MA_TREETABLE_MOVE_OK . ' (' . date('Y-m-d H:i:s') . ')';

//檢查目的地編號是否在其子目錄下
function chk_cate_path($csn, $to_csn)
{
    global $xoopsDB;
    //抓出子目錄的編號
    $sql = 'SELECT `csn` FROM `' . $xoopsDB->prefix('tad_gallery_cate') . '` WHERE `of_csn`=?';
    $result = Utility::query($sql, 'i', [$csn]) or Utility::web_error($sql, __FILE__, __LINE__);
    while (list($sub_csn) = $xoopsDB->fetchRow($result)) {
        if (chk_cate_path($sub_csn, $to_csn)) {
            return true;
        }

        if ($sub_csn == $to_csn) {
            return true;
        }
    }

    return false;
}
