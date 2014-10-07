<?php
$modversion = array();

//---模組基本資訊---//
$modversion['name'] = _MI_TADGAL_NAME;
$modversion['version'] = 3.4;
$modversion['description'] = _MI_TADGAL_DESC;
$modversion['author'] = _MI_TADGAL_AUTHOR;
$modversion['credits'] = _MI_TADGAL_CREDITS;
$modversion['help'] = 'page=help';
$modversion['license'] = 'GNU GPL 2.0';
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['image'] = "images/logo_{$xoopsConfig['language']}.png";
$modversion['dirname'] = basename(dirname(__FILE__));

//---模組狀態資訊---//
$modversion['release_date'] = '2014/10/01';
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
$modversion['templates'][$i]['file'] = 'tg_passwd_form.html';
$modversion['templates'][$i]['description'] = 'tg_passwd_form.html';
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
$modversion['templates'][$i]['file'] = 'tg_list_normal.html';
$modversion['templates'][$i]['description'] = 'tg_list_normal.html';
$i++;
$modversion['templates'][$i]['file'] = 'tg_list_flickr.html';
$modversion['templates'][$i]['description'] = 'tg_list_flickr.html';
$i++;
$modversion['templates'][$i]['file'] = 'tg_list_waterfall.html';
$modversion['templates'][$i]['description'] = 'tg_list_waterfall.html';
$i++;
$modversion['templates'][$i]['file'] = 'tg_upload_tpl.html';
$modversion['templates'][$i]['description'] = 'tg_upload_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tg_adm_main_tpl.html';
$modversion['templates'][$i]['description'] = 'tg_adm_main_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tg_adm_cate_tpl.html';
$modversion['templates'][$i]['description'] = 'tg_adm_cate_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tg_albums.html';
$modversion['templates'][$i]['description'] = 'tg_albums.html';
$i++;
$modversion['templates'][$i]['file'] = 'tg_content.html';
$modversion['templates'][$i]['description'] = 'tg_content.html';
$i++;
$modversion['templates'][$i]['file'] = 'tg_cate_fancybox.html';
$modversion['templates'][$i]['description'] = 'tg_cate_fancybox.html';

//---區塊設定---//
$i=0;
$modversion['blocks'][$i]['file'] = "tadgallery_carousel.php";
$modversion['blocks'][$i]['name'] = _MI_TADGAL_BNAME1;
$modversion['blocks'][$i]['description'] = _MI_TADGAL_BDESC1;
$modversion['blocks'][$i]['show_func'] = "tadgallery_carousel_show";
$modversion['blocks'][$i]['template'] = "tadgallery_carousel.html";
$modversion['blocks'][$i]['edit_func'] = "tadgallery_carousel_edit";
$modversion['blocks'][$i]['options'] = "10||photo_sort||s|140|105|0|0|1000|3|0|5000";

$i++;
$modversion['blocks'][$i]['file'] = "tadgallery_shuffle.php";
$modversion['blocks'][$i]['name'] = _MI_TADGAL_BNAME2;
$modversion['blocks'][$i]['description'] = _MI_TADGAL_BDESC2;
$modversion['blocks'][$i]['show_func'] = "tadgallery_shuffle_show";
$modversion['blocks'][$i]['template'] = "tadgallery_shuffle.html";
$modversion['blocks'][$i]['edit_func'] = "tadgallery_shuffle_edit";
$modversion['blocks'][$i]['options'] = "10|album|rand|desc|s|160|120|0|0|2000|5|2|3000|default";

$i++;
$modversion['blocks'][$i]['file'] = "tadgallery_show.php";
$modversion['blocks'][$i]['name'] = _MI_TADGAL_BNAME3;
$modversion['blocks'][$i]['description'] = _MI_TADGAL_BDESC3;
$modversion['blocks'][$i]['show_func'] = "tadgallery_show";
$modversion['blocks'][$i]['template'] = "tadgallery_show.html";
$modversion['blocks'][$i]['edit_func'] = "tadgallery_edit";
$modversion['blocks'][$i]['options'] = "10||rand|desc|m|100%|240|0|default";

$i++;
$modversion['blocks'][$i]['file'] = "tadgallery_cooliris.php";
$modversion['blocks'][$i]['name'] = _MI_TADGAL_BNAME4;
$modversion['blocks'][$i]['description'] = _MI_TADGAL_BDESC4;
$modversion['blocks'][$i]['show_func'] = "tadgallery_cooliris_show";
$modversion['blocks'][$i]['template'] = "tadgallery_cooliris.html";
$modversion['blocks'][$i]['edit_func'] = "tadgallery_cooliris_edit";
$modversion['blocks'][$i]['options'] = "|650|450";

$i++;
$modversion['blocks'][$i]['file'] = "tadgallery_scroller.php";
$modversion['blocks'][$i]['name'] = _MI_TADGAL_BNAME5;
$modversion['blocks'][$i]['description'] = _MI_TADGAL_BDESC5;
$modversion['blocks'][$i]['show_func'] = "tadgallery_scroller_show";
$modversion['blocks'][$i]['template'] = "tadgallery_scroller.html";
$modversion['blocks'][$i]['edit_func'] = "tadgallery_scroller_edit";
$modversion['blocks'][$i]['options'] = "10||rand|desc|s|160|240|0|jscroller2_up|40";

$i++;
$modversion['blocks'][$i]['file'] = "tadgallery_re_block.php";
$modversion['blocks'][$i]['name'] = _MI_TADGAL_BNAME6;
$modversion['blocks'][$i]['description'] = _MI_TADGAL_BDESC6;
$modversion['blocks'][$i]['show_func'] = "tadgallery_show_re";
$modversion['blocks'][$i]['template'] = "tadgallery_re.html";
$modversion['blocks'][$i]['edit_func'] = "tadgallery_re_edit";
$modversion['blocks'][$i]['options'] = "10|160|1|1";


$i++;
$modversion['blocks'][$i]['file'] = "tadgallery_list.php";
$modversion['blocks'][$i]['name'] = _MI_TADGAL_BNAME8;
$modversion['blocks'][$i]['description'] = _MI_TADGAL_BDESC8;
$modversion['blocks'][$i]['show_func'] = "tadgallery_list";
$modversion['blocks'][$i]['template'] = "tadgallery_list.html";
$modversion['blocks'][$i]['edit_func'] = "tadgallery_list_edit";
$modversion['blocks'][$i]['options'] = "12||rand|desc|6|100|100|0|0|font-size:11px;font-weight:normal;overflow:hidden;";

$i++;
$modversion['blocks'][$i]['file'] = "tadgallery_marquee.php";
$modversion['blocks'][$i]['name'] = _MI_TADGAL_BNAME9;
$modversion['blocks'][$i]['description'] = _MI_TADGAL_BDESC9;
$modversion['blocks'][$i]['show_func'] = "tadgallery_marquee_show";
$modversion['blocks'][$i]['template'] = "tadgallery_marquee.html";
$modversion['blocks'][$i]['edit_func'] = "tadgallery_marquee_edit";
$modversion['blocks'][$i]['options'] = "10||rand|desc|s|160|240|0|30";

$i++;
$modversion['blocks'][$i]['file'] = "tadgallery_qrcode.php";
$modversion['blocks'][$i]['name'] = _MI_QRCODE_BLOCKNAME;
$modversion['blocks'][$i]['description'] = _MI_QRCODE_BLOCKDESC;
$modversion['blocks'][$i]['show_func'] = "tadgallery_qrcode_show";
$modversion['blocks'][$i]['template'] = "tadgallery_qrcode.html";

$i++;
$modversion['blocks'][$i]['file'] = "tadgallery_cate.php";
$modversion['blocks'][$i]['name'] = _MI_TADGAL_BNAME10;
$modversion['blocks'][$i]['description'] = _MI_TADGAL_BDESC10;
$modversion['blocks'][$i]['show_func'] = "tadgallery_cate";
$modversion['blocks'][$i]['template'] = "tadgallery_cate.html";
$modversion['blocks'][$i]['edit_func'] = "tadgallery_cate_edit";
$modversion['blocks'][$i]['options'] = "4|album|rand()||300|line-height:1.8;|0";

//---偏好設定---//
$i=0;
$modversion['config'][$i]['name']   = 'index_mode';
$modversion['config'][$i]['title']  = '_MI_TADGAL_INDEX_MODE';
$modversion['config'][$i]['description']    = '_MI_TADGAL_INDEX_MODE_DESC';
$modversion['config'][$i]['formtype']   = 'select';
$modversion['config'][$i]['valuetype']  = 'text';
$modversion['config'][$i]['default']    = 'normal';
$modversion['config'][$i]['options']    = array(_MI_TADGAL_NORMAL=>'normal',_MI_TADGAL_FLICKR=>'flickr',_MI_TADGAL_WATERFALL=>'waterfall');

$i++;
$modversion['config'][$i]['name']	= 'thumbnail_s_width';
$modversion['config'][$i]['title']	= '_MI_TADGAL_THUMBNAIL_S_WIDTH';
$modversion['config'][$i]['description']	= '_MI_TADGAL_THUMBNAIL_S_WIDTH_DESC';
$modversion['config'][$i]['formtype']	= 'textbox';
$modversion['config'][$i]['valuetype']	= 'text';
$modversion['config'][$i]['default']	= '140';

$i++;
$modversion['config'][$i]['name']	= 'thumbnail_m_width';
$modversion['config'][$i]['title']	= '_MI_TADGAL_THUMBNAIL_M_WIDTH';
$modversion['config'][$i]['description']	= '_MI_TADGAL_THUMBNAIL_M_WIDTH_DESC';
$modversion['config'][$i]['formtype']	= 'textbox';
$modversion['config'][$i]['valuetype']	= 'text';
$modversion['config'][$i]['default']	= '500';

$i++;
$modversion['config'][$i]['name']	= 'thumbnail_b_width';
$modversion['config'][$i]['title']	= '_MI_TADGAL_THUMBNAIL_B_WIDTH';
$modversion['config'][$i]['description']	= '_MI_TADGAL_THUMBNAIL_B_WIDTH_DESC';
$modversion['config'][$i]['formtype']	= 'textbox';
$modversion['config'][$i]['valuetype']	= 'text';
$modversion['config'][$i]['default']	= '0';

$i++;
$modversion['config'][$i]['name']	= 'thumbnail_number';
$modversion['config'][$i]['title']	= '_MI_TADGAL_THUMBNAIL_NUMBER';
$modversion['config'][$i]['description']	= '_MI_TADGAL_THUMBNAIL_NUMBER_DESC';
$modversion['config'][$i]['formtype']	= 'textbox';
$modversion['config'][$i]['valuetype']	= 'text';
$modversion['config'][$i]['default']	= '30';

$i++;
$modversion['config'][$i]['name']	= 'show_copy_pic';
$modversion['config'][$i]['title']	= '_MI_TADGAL_SHOW_COPY_PIC';
$modversion['config'][$i]['description']	= '_MI_TADGAL_SHOW_COPY_PIC_DESC';
$modversion['config'][$i]['formtype']	= 'yesno';
$modversion['config'][$i]['valuetype']	= 'int';
$modversion['config'][$i]['default']	= '1';

$i++;
$modversion['config'][$i]['name']	= 'only_thumb';
$modversion['config'][$i]['title']	= '_MI_TADGAL_ONLY_THUMB';
$modversion['config'][$i]['description']	= '_MI_TADGAL_ONLY_THUMB_DESC';
$modversion['config'][$i]['formtype']	= 'yesno';
$modversion['config'][$i]['valuetype']	= 'int';
$modversion['config'][$i]['default']	= '0';

$i++;
$modversion['config'][$i]['name']	= 'pic_toolbar';
$modversion['config'][$i]['title']	= '_MI_TADGAL_USE_PIC_TOOLBAR';
$modversion['config'][$i]['description']	= '_MI_TADGAL_USE_PIC_TOOLBAR_DESC';
$modversion['config'][$i]['formtype']	= 'yesno';
$modversion['config'][$i]['valuetype']	= 'int';
$modversion['config'][$i]['default']	= '1';

$i++;
$modversion['config'][$i]['name']	= 'upload_mode';
$modversion['config'][$i]['title']	= '_MI_TADGAL_UPLOAD_MODE';
$modversion['config'][$i]['description']	= '_MI_TADGAL_UPLOAD_MODE_DESC';
$modversion['config'][$i]['formtype']	= 'select_multi';
$modversion['config'][$i]['valuetype']	= 'array';
$modversion['config'][$i]['default']	= array('one_pic','batch_pics','flash_batch_pics','java_batch_pics','zip_batch_pics');
$modversion['config'][$i]['options']	= array(_MI_INPUT_FORM=>'one_pic',_MI_TADGAL_MUTI_INPUT_FORM=>'batch_pics',"Flash"._MI_TADGAL_MUTI_INPUT_FORM=>'flash_batch_pics',_MI_TADGAL_JAVA_UPLOAD=>'java_batch_pics',_MI_MD_TADGAL_ZIP_IMPORT_FORM=>'zip_batch_pics',_MI_TADGAL_XP_IMPORT_FORM=>'upload_xp_pics');

$i++;
$modversion['config'][$i]['name'] = 'facebook_comments_width';
$modversion['config'][$i]['title'] = '_MI_FBCOMMENT_TITLE';
$modversion['config'][$i]['description'] = '_MI_FBCOMMENT_TITLE_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '1';

$i++;
$modversion['config'][$i]['name'] = 'use_pda';
$modversion['config'][$i]['title'] = '_MI_USE_PDA_TITLE';
$modversion['config'][$i]['description'] = '_MI_USE_PDA_TITLE_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '0';

$i++;
$modversion['config'][$i]['name'] = 'use_social_tools';
$modversion['config'][$i]['title'] = '_MI_SOCIALTOOLS_TITLE';
$modversion['config'][$i]['description'] = '_MI_SOCIALTOOLS_TITLE_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '1';


?>
