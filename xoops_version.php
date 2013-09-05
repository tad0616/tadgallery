<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2008-03-23
// $Id: xoops_version.php,v 1.5 2008/05/14 01:23:14 tad Exp $
// ------------------------------------------------------------------------- //
$modversion = array();

//---模組基本資訊---//
$modversion['name'] = _MI_TADGAL_NAME;
$modversion['version'] = 3.0;
$modversion['description'] = _MI_TADGAL_DESC;
$modversion['author'] = _MI_TADGAL_AUTHOR;
$modversion['credits'] = _MI_TADGAL_CREDITS;
$modversion['help'] = 'page=help';
$modversion['license'] = 'GNU GPL 2.0';
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['image'] = "images/logo_{$xoopsConfig['language']}.png";
$modversion['dirname'] = basename(dirname(__FILE__));

//---模組狀態資訊---//
$modversion['release_date'] = '2013/06/08';
$modversion['module_website_url'] = 'http://tad0616.net/';
$modversion['module_website_name'] = _MI_TAD_WEB;
$modversion['module_status'] = 'release';
$modversion['author_website_url'] = 'http://tad0616.net/';
$modversion['author_website_name'] = _MI_TAD_WEB;
$modversion['min_php']=5.2;
$modversion['min_xoops']='2.5';

//---paypal資訊---//
$modversion ['paypal'] = array();
$modversion ['paypal']['business'] = 'tad0616@gmail.com';
$modversion ['paypal']['item_name'] = 'Donation : ' . _MI_TAD_WEB;
$modversion ['paypal']['amount'] = 0;
$modversion ['paypal']['currency_code'] = 'USD';


//---資料表架構---//
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][1] = "tad_gallery";
$modversion['tables'][2] = "tad_gallery_cate";


//---啟動後台管理界面選單---//
$modversion['system_menu'] = 1;

//---管理介面設定---//
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

//---使用者主選單設定---//
$modversion['hasMain'] = 1;


//---模組自動功能---//
$modversion['onInstall'] = "include/onInstall.php";
$modversion['onUpdate'] = "include/onUpdate.php";
$modversion['onUninstall'] = "include/onUninstall.php";

//---評論設定---//
$modversion['hasComments'] = 1;
$modversion['comments']['pageName'] = 'view.php';
$modversion['comments']['itemName'] = 'sn';

//---搜尋設定---//
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.php";
$modversion['search']['func'] = "tadgallery_search";

//---樣板設定---//
$i=1;
$modversion['templates'][$i]['file'] = 'tg_show_tpl.html';
$modversion['templates'][$i]['description'] = 'tg_show_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tg_view_tpl.html';
$modversion['templates'][$i]['description'] = 'tg_view_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tg_slideshow.html';
$modversion['templates'][$i]['description'] = 'tg_slideshow.html';
$i++;
$modversion['templates'][$i]['file'] = 'tg_author_tpl.html';
$modversion['templates'][$i]['description'] = 'tg_author_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tg_responsive_view_tpl.html';
$modversion['templates'][$i]['description'] = 'tg_responsive_view_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tg_list_tpl.html';
$modversion['templates'][$i]['description'] = 'tg_list_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tg_upload_tpl.html';
$modversion['templates'][$i]['description'] = 'tg_upload_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tg_adm_main_tpl.html';
$modversion['templates'][$i]['description'] = 'tg_adm_main_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tg_adm_cate_tpl.html';
$modversion['templates'][$i]['description'] = 'tg_adm_cate_tpl.html';



//---區塊設定---//

$modversion['blocks'][1]['file'] = "tadgallery_carousel.php";
$modversion['blocks'][1]['name'] = _MI_TADGAL_BNAME1;
$modversion['blocks'][1]['description'] = _MI_TADGAL_BDESC1;
$modversion['blocks'][1]['show_func'] = "tadgallery_carousel_show";
$modversion['blocks'][1]['template'] = "tadgallery_carousel.html";
$modversion['blocks'][1]['edit_func'] = "tadgallery_carousel_edit";
$modversion['blocks'][1]['options'] = "10||photo_sort||s|140|105|0|0|1000|3|0|5000";

$modversion['blocks'][2]['file'] = "tadgallery_shuffle.php";
$modversion['blocks'][2]['name'] = _MI_TADGAL_BNAME2;
$modversion['blocks'][2]['description'] = _MI_TADGAL_BDESC2;
$modversion['blocks'][2]['show_func'] = "tadgallery_shuffle_show";
$modversion['blocks'][2]['template'] = "tadgallery_shuffle.html";
$modversion['blocks'][2]['edit_func'] = "tadgallery_shuffle_edit";
$modversion['blocks'][2]['options'] = "10||rand|desc|s|160|120|0|0|2000|5|2|3000|default";

$modversion['blocks'][3]['file'] = "tadgallery_show.php";
$modversion['blocks'][3]['name'] = _MI_TADGAL_BNAME3;
$modversion['blocks'][3]['description'] = _MI_TADGAL_BDESC3;
$modversion['blocks'][3]['show_func'] = "tadgallery_show";
$modversion['blocks'][3]['template'] = "tadgallery_show.html";
$modversion['blocks'][3]['edit_func'] = "tadgallery_edit";
$modversion['blocks'][3]['options'] = "10||rand|desc|s|160|120|0|default";

$modversion['blocks'][4]['file'] = "tadgallery_cooliris.php";
$modversion['blocks'][4]['name'] = _MI_TADGAL_BNAME4;
$modversion['blocks'][4]['description'] = _MI_TADGAL_BDESC4;
$modversion['blocks'][4]['show_func'] = "tadgallery_cooliris_show";
$modversion['blocks'][4]['template'] = "tadgallery_cooliris.html";
$modversion['blocks'][4]['edit_func'] = "tadgallery_cooliris_edit";
$modversion['blocks'][4]['options'] = "|650|450";

$modversion['blocks'][5]['file'] = "tadgallery_scroller.php";
$modversion['blocks'][5]['name'] = _MI_TADGAL_BNAME5;
$modversion['blocks'][5]['description'] = _MI_TADGAL_BDESC5;
$modversion['blocks'][5]['show_func'] = "tadgallery_scroller_show";
$modversion['blocks'][5]['template'] = "tadgallery_scroller.html";
$modversion['blocks'][5]['edit_func'] = "tadgallery_scroller_edit";
$modversion['blocks'][5]['options'] = "10||rand|desc|s|160|240|0|jscroller2_up|40";

$modversion['blocks'][6]['file'] = "tadgallery_re_block.php";
$modversion['blocks'][6]['name'] = _MI_TADGAL_BNAME6;
$modversion['blocks'][6]['description'] = _MI_TADGAL_BDESC6;
$modversion['blocks'][6]['show_func'] = "tadgallery_show_re";
$modversion['blocks'][6]['template'] = "tadgallery_re.html";
$modversion['blocks'][6]['edit_func'] = "tadgallery_re_edit";
$modversion['blocks'][6]['options'] = "10|160|1|1";

$modversion['blocks'][7]['file'] = "tadgallery_slideshow.php";
$modversion['blocks'][7]['name'] = _MI_TADGAL_BNAME7;
$modversion['blocks'][7]['description'] = _MI_TADGAL_BDESC7;
$modversion['blocks'][7]['show_func'] = "tadgallery_slideshow";
$modversion['blocks'][7]['template'] = "tadgallery_slideshow.html";
$modversion['blocks'][7]['edit_func'] = "tadgallery_slideshow_edit";
$modversion['blocks'][7]['options'] = "||510|400|false";

$modversion['blocks'][8]['file'] = "tadgallery_list.php";
$modversion['blocks'][8]['name'] = _MI_TADGAL_BNAME8;
$modversion['blocks'][8]['description'] = _MI_TADGAL_BDESC8;
$modversion['blocks'][8]['show_func'] = "tadgallery_list";
$modversion['blocks'][8]['template'] = "tadgallery_list.html";
$modversion['blocks'][8]['edit_func'] = "tadgallery_list_edit";
$modversion['blocks'][8]['options'] = "12||rand|desc|6|100|100|0|0|font-size:11px;font-weight:normal;overflow:hidden;";


$modversion['blocks'][9]['file'] = "tadgallery_marquee.php";
$modversion['blocks'][9]['name'] = _MI_TADGAL_BNAME9;
$modversion['blocks'][9]['description'] = _MI_TADGAL_BDESC9;
$modversion['blocks'][9]['show_func'] = "tadgallery_marquee_show";
$modversion['blocks'][9]['template'] = "tadgallery_marquee.html";
$modversion['blocks'][9]['edit_func'] = "tadgallery_marquee_edit";
$modversion['blocks'][9]['options'] = "10||rand|desc|s|160|240|0|30";


$modversion['blocks'][10]['file'] = "tadgallery_qrcode.php";
$modversion['blocks'][10]['name'] = _MI_QRCODE_BLOCKNAME;
$modversion['blocks'][10]['description'] = _MI_QRCODE_BLOCKDESC;
$modversion['blocks'][10]['show_func'] = "tadgallery_qrcode_show";
$modversion['blocks'][10]['template'] = "tadgallery_qrcode.html";

//---偏好設定---//
$modversion['config'][0]['name']	= 'thumbnail_s_width';
$modversion['config'][0]['title']	= '_MI_TADGAL_THUMBNAIL_S_WIDTH';
$modversion['config'][0]['description']	= '_MI_TADGAL_THUMBNAIL_S_WIDTH_DESC';
$modversion['config'][0]['formtype']	= 'textbox';
$modversion['config'][0]['valuetype']	= 'text';
$modversion['config'][0]['default']	= '140';

$modversion['config'][1]['name']	= 'thumbnail_m_width';
$modversion['config'][1]['title']	= '_MI_TADGAL_THUMBNAIL_M_WIDTH';
$modversion['config'][1]['description']	= '_MI_TADGAL_THUMBNAIL_M_WIDTH_DESC';
$modversion['config'][1]['formtype']	= 'textbox';
$modversion['config'][1]['valuetype']	= 'text';
$modversion['config'][1]['default']	= '500';

$modversion['config'][2]['name']	= 'thumbnail_b_width';
$modversion['config'][2]['title']	= '_MI_TADGAL_THUMBNAIL_B_WIDTH';
$modversion['config'][2]['description']	= '_MI_TADGAL_THUMBNAIL_B_WIDTH_DESC';
$modversion['config'][2]['formtype']	= 'textbox';
$modversion['config'][2]['valuetype']	= 'text';
$modversion['config'][2]['default']	= '0';


$modversion['config'][4]['name']	= 'thumbnail_number';
$modversion['config'][4]['title']	= '_MI_TADGAL_THUMBNAIL_NUMBER';
$modversion['config'][4]['description']	= '_MI_TADGAL_THUMBNAIL_NUMBER_DESC';
$modversion['config'][4]['formtype']	= 'textbox';
$modversion['config'][4]['valuetype']	= 'text';
$modversion['config'][4]['default']	= '30';

$modversion['config'][7]['name']	= 'show_copy_pic';
$modversion['config'][7]['title']	= '_MI_TADGAL_SHOW_COPY_PIC';
$modversion['config'][7]['description']	= '_MI_TADGAL_SHOW_COPY_PIC_DESC';
$modversion['config'][7]['formtype']	= 'yesno';
$modversion['config'][7]['valuetype']	= 'int';
$modversion['config'][7]['default']	= '1';

$modversion['config'][8]['name']	= 'only_thumb';
$modversion['config'][8]['title']	= '_MI_TADGAL_ONLY_THUMB';
$modversion['config'][8]['description']	= '_MI_TADGAL_ONLY_THUMB_DESC';
$modversion['config'][8]['formtype']	= 'yesno';
$modversion['config'][8]['valuetype']	= 'int';
$modversion['config'][8]['default']	= '0';

$modversion['config'][9]['name']	= 'pic_toolbar';
$modversion['config'][9]['title']	= '_MI_TADGAL_USE_PIC_TOOLBAR';
$modversion['config'][9]['description']	= '_MI_TADGAL_USE_PIC_TOOLBAR_DESC';
$modversion['config'][9]['formtype']	= 'yesno';
$modversion['config'][9]['valuetype']	= 'int';
$modversion['config'][9]['default']	= '1';

$modversion['config'][10]['name']	= 'upload_mode';
$modversion['config'][10]['title']	= '_MI_TADGAL_UPLOAD_MODE';
$modversion['config'][10]['description']	= '_MI_TADGAL_UPLOAD_MODE_DESC';
$modversion['config'][10]['formtype']	= 'select_multi';
$modversion['config'][10]['valuetype']	= 'array';
$modversion['config'][10]['default']	= array('one_pic','flash_batch_pics','java_batch_pics','zip_batch_pics');
$modversion['config'][10]['options']	= array(_MI_INPUT_FORM=>'one_pic',_MI_TADGAL_MUTI_INPUT_FORM=>'batch_pics',"Flash"._MI_TADGAL_MUTI_INPUT_FORM=>'flash_batch_pics',_MI_TADGAL_JAVA_UPLOAD=>'java_batch_pics',_MI_TADGAL_ZIP_IMPORT_FORM=>'zip_batch_pics',_MI_TADGAL_XP_IMPORT_FORM=>'upload_xp_pics');

$modversion['config'][11]['name'] = 'facebook_comments_width';
$modversion['config'][11]['title'] = '_MI_FBCOMMENT_TITLE';
$modversion['config'][11]['description'] = '_MI_FBCOMMENT_TITLE_DESC';
$modversion['config'][11]['formtype'] = 'yesno';
$modversion['config'][11]['valuetype'] = 'int';
$modversion['config'][11]['default'] = '1';

$modversion['config'][12]['name'] = 'use_pda';
$modversion['config'][12]['title'] = '_MI_USE_PDA_TITLE';
$modversion['config'][12]['description'] = '_MI_USE_PDA_TITLE_DESC';
$modversion['config'][12]['formtype'] = 'yesno';
$modversion['config'][12]['valuetype'] = 'int';
$modversion['config'][12]['default'] = '1';

$modversion['config'][13]['name'] = 'use_social_tools';
$modversion['config'][13]['title'] = '_MI_SOCIALTOOLS_TITLE';
$modversion['config'][13]['description'] = '_MI_SOCIALTOOLS_TITLE_DESC';
$modversion['config'][13]['formtype'] = 'yesno';
$modversion['config'][13]['valuetype'] = 'int';
$modversion['config'][13]['default'] = '1';
?>
