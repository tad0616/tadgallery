<?php
include "header.php";

$sql="update ".$xoopsDB->prefix("tad_gallery_cate")." set `content`='{$_POST['value']}' where csn='{$_POST['csn']}'";
$xoopsDB->queryF($sql);
echo nl2br($_POST['value']);
?>