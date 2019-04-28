<?php
/*-----------引入檔案區--------------*/
require dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';

$sql = 'update ' . $xoopsDB->prefix('tad_gallery') . " set `{$_POST['col_name']}`='{$_POST['value']}' where sn='{$_POST['sn']}'";
$xoopsDB->queryF($sql);
echo $_POST['value'];
