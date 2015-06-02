<?php
/*-----------引入檔案區--------------*/
include "../../../include/cp_header.php";

$csn  = intval($_POST['csn']);
$sort = intval($_POST['sort']);
$sql  = "update " . $xoopsDB->prefix("tad_gallery_cate") . " set `sort`='{$sort}' where csn='{$csn}'";
$xoopsDB->queryF($sql) or die("Save Sort Fail! (" . date("Y-m-d H:i:s") . ")");

echo "Save Sort OK! (" . date("Y-m-d H:i:s") . ") ";
