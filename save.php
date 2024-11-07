<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;
require __DIR__ . '/header.php';
$xoopsLogger->activated = false;

$csn = Request::getInt('csn');
$value = Request::getString('value');

$value = str_replace('<br>', '', $value);
$sql = 'UPDATE `' . $xoopsDB->prefix('tad_gallery_cate') . '` SET `content`=? WHERE `csn`=?';
Utility::query($sql, 'si', [$value, $csn]);
echo $value;
