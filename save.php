<?php
include "header.php";
$value=str_replace("<br>", '', $_POST['value']);
$sql="update ".$xoopsDB->prefix("tad_gallery_cate")." set `content`='{$value}' where csn='{$_POST['csn']}'";
$xoopsDB->queryF($sql);
echo nl2br($_POST['value']);