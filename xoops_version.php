<?php
$modversion = [];
global $xoopsConfig;

$modversion['name'] = _MI_TADGAL_NAME;
$modversion['version'] = (isset($_SESSION['xoops_version']) && $_SESSION['xoops_version'] >= 20511) ? '5.0.0-Stable' : '5.0';
// $modversion['version'] = 4.6;
$modversion['description'] = _MI_TADGAL_DESC;
$modversion['author'] = _MI_TADGAL_AUTHOR;
$modversion['credits'] = _MI_TADGAL_CREDITS;
$modversion['help'] = 'page=help';
$modversion['license'] = 'GNU GPL 2.0';
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['image'] = "images/logo_{$xoopsConfig['language']}.png";
$modversion['dirname'] = basename(__DIR__);

$modversion['release_date'] = '2024-12-12';
$modversion['module_website_url'] = 'https://tad0616.net/';
$modversion['module_website_name'] = _MI_TAD_WEB;
$modversion['module_status'] = 'release';
$modversion['author_website_url'] = 'https://tad0616.net/';
$modversion['author_website_name'] = _MI_TAD_WEB;
$modversion['min_php'] = 5.4;
$modversion['min_xoops'] = '2.5.10';

$modversion['paypal'] = [
    'business' => 'tad0616@gmail.com',
    'item_name' => 'Donation : ' . _MI_TAD_WEB,
    'amount' => 0,
    'currency_code' => 'USD',
];

$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'] = ['tad_gallery', 'tad_gallery_cate'];

$modversion['system_menu'] = 1;

$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

$modversion['hasMain'] = 1;

$modversion['onInstall'] = 'include/onInstall.php';
$modversion['onUpdate'] = 'include/onUpdate.php';
$modversion['onUninstall'] = 'include/onUninstall.php';

$modversion['hasComments'] = 0;

$modversion['hasSearch'] = 1;
$modversion['search']['file'] = 'include/search.php';
$modversion['search']['func'] = 'tadgallery_search';

$modversion['templates'] = [
    ['file' => 'tadgallery_passwd_form.tpl', 'description' => 'tadgallery_passwd_form.tpl'],
    ['file' => 'tadgallery_list_normal.tpl', 'description' => 'tadgallery_list_normal.tpl'],
    ['file' => 'tadgallery_list_flickr.tpl', 'description' => 'tadgallery_list_flickr.tpl'],
    ['file' => 'tadgallery_list_waterfall.tpl', 'description' => 'tadgallery_list_waterfall.tpl'],
    ['file' => 'tadgallery_cate_fancybox.tpl', 'description' => 'tadgallery_cate_fancybox.tpl'],
    ['file' => 'tadgallery_albums.tpl', 'description' => 'tadgallery_albums.tpl'],
    ['file' => 'tadgallery_content.tpl', 'description' => 'tadgallery_content.tpl'],
    ['file' => 'tadgallery_view.tpl', 'description' => 'tadgallery_view.tpl'],
    ['file' => 'tadgallery_upload.tpl', 'description' => 'tadgallery_upload.tpl'],
    ['file' => 'tadgallery_admin.tpl', 'description' => 'tadgallery_admin.tpl'],
    ['file' => 'tadgallery_adm_cate.tpl', 'description' => 'tadgallery_adm_cate.tpl'],
    ['file' => 'tadgallery_list_header.tpl', 'description' => 'tadgallery_list_header.tpl'],
];

//---區塊設定 (索引為固定值，若欲刪除區塊記得補上索引，避免區塊重複)---//
$modversion['blocks'] = [
    0 => ['file' => 'tadgallery_carousel.php', 'name' => _MI_TADGAL_BNAME1, 'description' => _MI_TADGAL_BDESC1, 'show_func' => 'tadgallery_carousel_show', 'template' => 'tadgallery_block_carousel.tpl', 'edit_func' => 'tadgallery_carousel_edit', 'options' => '12||1|post_date|desc|m|0|140|105|0|1000|3|0|5000|1'],
    1 => ['file' => 'tadgallery_shuffle.php', 'name' => _MI_TADGAL_BNAME2, 'description' => _MI_TADGAL_BDESC2, 'show_func' => 'tadgallery_shuffle_show', 'template' => 'tadgallery_block_shuffle.tpl', 'edit_func' => 'tadgallery_shuffle_edit', 'options' => '12||1|post_date|desc|m|0|200|160'],
    2 => ['file' => 'tadgallery_show.php', 'name' => _MI_TADGAL_BNAME3, 'description' => _MI_TADGAL_BDESC3, 'show_func' => 'tadgallery_show', 'template' => 'tadgallery_block_show.tpl', 'edit_func' => 'tadgallery_show_edit', 'options' => '12||1|post_date|desc|m|0|100%|240'],
    3 => ['file' => 'tadgallery_scroller.php', 'name' => _MI_TADGAL_BNAME5, 'description' => _MI_TADGAL_BDESC5, 'show_func' => 'tadgallery_scroller_show', 'template' => 'tadgallery_block_scroller.tpl', 'edit_func' => 'tadgallery_scroller_edit', 'options' => '12||1|post_date|desc|m|0|100%|240|jscroller2_up|40'],
    5 => ['file' => 'tadgallery_list.php', 'name' => _MI_TADGAL_BNAME8, 'description' => _MI_TADGAL_BDESC8, 'show_func' => 'tadgallery_list', 'template' => 'tadgallery_block_list.tpl', 'edit_func' => 'tadgallery_list_edit', 'options' => '12||1|post_date|desc|m|0|120|120|0|0|font-size: 0.8em;font-weight:normal;overflow:hidden;|1|cover'],
    6 => ['file' => 'tadgallery_marquee.php', 'name' => _MI_TADGAL_BNAME9, 'description' => _MI_TADGAL_BDESC9, 'show_func' => 'tadgallery_marquee_show', 'template' => 'tadgallery_block_marquee.tpl', 'edit_func' => 'tadgallery_marquee_edit', 'options' => '12|0|1|post_date|desc|m|0|100%|150|80|1'],
    7 => ['file' => 'tadgallery_cate.php', 'name' => _MI_TADGAL_BNAME10, 'description' => _MI_TADGAL_BDESC10, 'show_func' => 'tadgallery_cate', 'template' => 'tadgallery_block_cate.tpl', 'edit_func' => 'tadgallery_cate_edit', 'options' => '4|album|rand()||300|line-height:1.8;|0||4'],
];

$modversion['config'] = [
    ['name' => 'index_mode', 'title' => '_MI_TADGAL_INDEX_MODE', 'description' => '_MI_TADGAL_INDEX_MODE_DESC', 'formtype' => 'select', 'valuetype' => 'text', 'default' => 'normal', 'options' => [_MI_TADGAL_NORMAL => 'normal', _MI_TADGAL_FLICKR => 'flickr', _MI_TADGAL_WATERFALL => 'waterfall']],
    ['name' => 'thumbnail_s_width', 'title' => '_MI_TADGAL_THUMBNAIL_S_WIDTH', 'description' => '_MI_TADGAL_THUMBNAIL_S_WIDTH_DESC', 'formtype' => 'textbox', 'valuetype' => 'text', 'default' => '240'],
    ['name' => 'thumbnail_m_width', 'title' => '_MI_TADGAL_THUMBNAIL_M_WIDTH', 'description' => '_MI_TADGAL_THUMBNAIL_M_WIDTH_DESC', 'formtype' => 'textbox', 'valuetype' => 'text', 'default' => '640'],
    ['name' => 'thumbnail_b_width', 'title' => '_MI_TADGAL_THUMBNAIL_B_WIDTH', 'description' => '_MI_TADGAL_THUMBNAIL_B_WIDTH_DESC', 'formtype' => 'textbox', 'valuetype' => 'text', 'default' => '0'],
    ['name' => 'thumbnail_number', 'title' => '_MI_TADGAL_THUMBNAIL_NUMBER', 'description' => '_MI_TADGAL_THUMBNAIL_NUMBER_DESC', 'formtype' => 'textbox', 'valuetype' => 'text', 'default' => '30'],
    ['name' => 'show_copy_pic', 'title' => '_MI_TADGAL_SHOW_COPY_PIC', 'description' => '_MI_TADGAL_SHOW_COPY_PIC_DESC', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => '1'],
    ['name' => 'only_thumb', 'title' => '_MI_TADGAL_ONLY_THUMB', 'description' => '_MI_TADGAL_ONLY_THUMB_DESC', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => '0'],
    ['name' => 'pic_toolbar', 'title' => '_MI_TADGAL_USE_PIC_TOOLBAR', 'description' => '_MI_TADGAL_USE_PIC_TOOLBAR_DESC', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => '1'],
    ['name' => 'use_social_tools', 'title' => '_MI_SOCIALTOOLS_TITLE', 'description' => '_MI_SOCIALTOOLS_TITLE_DESC', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => '1'],
    ['name' => 'thumb_slider', 'title' => '_MI_TADGAL_USE_THUMB_SLIDER', 'description' => '_MI_TADGAL_USE_THUMB_SLIDER_DESC', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => '1'],
    ['name' => 'random_photo', 'title' => '_MI_TADGAL_RANDOM_PHOTO', 'description' => '_MI_TADGAL_RANDOM_PHOTO_DESC', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => '1'],
    ['name' => 'model360', 'title' => '_MI_TADGAL_MODEL360', 'description' => '_MI_TADGAL_MODEL360_DESC', 'formtype' => 'textbox', 'valuetype' => 'text', 'default' => 'LG-R105;RICOH THETA S'],
    ['name' => 'show_author_menu', 'title' => '_MI_TADGAL_SHOW_AUTHOR_MENU', 'description' => '_MI_TADGAL_SHOW_AUTHOR_MENU_DESC', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => '0'],
];
