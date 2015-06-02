<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2008-02-28
// $Id: cate.php,v 1.4 2008/05/05 03:21:31 tad Exp $
// ------------------------------------------------------------------------- //

/*-----------引入檔案區--------------*/
include "../../../include/cp_header.php";

$sql = "update " . $xoopsDB->prefix("tad_gallery") . " set `{$_POST['col_name']}`='{$_POST['value']}' where sn='{$_POST['sn']}'";
$xoopsDB->queryF($sql);
echo $_POST['value'];
