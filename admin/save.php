<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
require dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';

$col_name = Request::getString('col_name');

$sql = 'UPDATE `' . $xoopsDB->prefix('tad_gallery') . '` SET `' . $col_name . '`=? WHERE `sn`=?';
Utility::query($sql, 'si', [$_POST['value'], $_POST['sn']]);

echo $_POST['value'];
