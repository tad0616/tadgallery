<?php
/*-----------引入檔案區--------------*/
require dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';

require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$updateRecordsArray = system_CleanVars($_REQUEST, 'recordsArray', '', 'array');

$sort = 1;
foreach ($updateRecordsArray as $recordIDValue) {
    $sql = 'update ' . $xoopsDB->prefix('tad_gallery') . " set `photo_sort`='{$sort}' where sn='{$recordIDValue}'";
    $xoopsDB->queryF($sql) or die('Save Sort Fail! (' . date('Y-m-d H:i:s') . ')');
    $sort++;
}

echo 'Save Sort OK! (' . date('Y-m-d H:i:s') . ') ';
