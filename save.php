<?php
use Xmf\Request;
require __DIR__ . '/header.php';

$csn = Request::getInt('csn');
$value = Request::getString('value');

$value = str_replace('<br>', '', $value);
$sql = 'update ' . $xoopsDB->prefix('tad_gallery_cate') . " set `content`='{$value}' where csn='{$csn}'";
$xoopsDB->queryF($sql);
echo $value;
