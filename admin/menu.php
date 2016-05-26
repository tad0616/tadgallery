<?php
$adminmenu = array();
$icon_dir  = substr(XOOPS_VERSION, 6, 3) == '2.6' ? "" : "images/";

$i                      = 1;
$adminmenu[$i]['title'] = _MI_TAD_ADMIN_HOME;
$adminmenu[$i]['link']  = 'admin/index.php';
$adminmenu[$i]['desc']  = _MI_TAD_ADMIN_HOME_DESC;
$adminmenu[$i]['icon']  = 'images/admin/home.png';

$i++;
$adminmenu[$i]['title'] = _MI_TADGAL_ADMENU1;
$adminmenu[$i]['link']  = "admin/main.php";
$adminmenu[$i]['desc']  = _MI_TADGAL_ADMENU1;
$adminmenu[$i]['icon']  = "images/admin/pictures.png";

// $i++;
// $adminmenu[$i]['title'] = _MI_TADGAL_ADMENU2;
// $adminmenu[$i]['link']  = "admin/cate.php";
// $adminmenu[$i]['desc']  = _MI_TADGAL_ADMENU2;
// $adminmenu[$i]['icon']  = "images/admin/category_2.png";

$i++;
$adminmenu[$i]['title'] = _MI_TADGAL_ADMENU5;
$adminmenu[$i]['link']  = "admin/main.php?op=mk_rss_xml";
$adminmenu[$i]['desc']  = _MI_TADGAL_ADMENU5;
$adminmenu[$i]['icon']  = "images/admin/rss2_3_13.png";

$i++;
$adminmenu[$i]['title'] = _MI_TAD_ADMIN_ABOUT;
$adminmenu[$i]['link']  = 'admin/about.php';
$adminmenu[$i]['desc']  = _MI_TAD_ADMIN_ABOUT_DESC;
$adminmenu[$i]['icon']  = 'images/admin/about.png';
