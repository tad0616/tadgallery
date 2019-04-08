<?php
include "header.php";

include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$value = system_CleanVars($_POST, 'value', '', 'string');
$csn   = system_CleanVars($_POST, 'csn', '', 'int');

$value = str_replace("<br>", '', $value);
$sql   = "update " . $xoopsDB->prefix("tad_gallery_cate") . " set `content`='{$value}' where csn='{$csn}'";
$xoopsDB->queryF($sql);
echo $value;
