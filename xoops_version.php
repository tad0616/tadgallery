<?php
$modversion = array();

$modversion['name']        = _MI_TADGAL_NAME;
$modversion['version']     = 3.83;
$modversion['description'] = _MI_TADGAL_DESC;
$modversion['author']      = _MI_TADGAL_AUTHOR;
$modversion['credits']     = _MI_TADGAL_CREDITS;
$modversion['help']        = 'page=help';
$modversion['license']     = 'GNU GPL 2.0';
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['image']       = "images/logo_{$xoopsConfig['language']}.png";
$modversion['dirname']     = basename(__DIR__);

$modversion['release_date']        = '2019/03/27';
$modversion['module_website_url']  = 'https://tad0616.net/';
$modversion['module_website_name'] = _MI_TAD_WEB;
$modversion['module_status']       = 'release';
$modversion['author_website_url']  = 'https://tad0616.net/';
$modversion['author_website_name'] = _MI_TAD_WEB;
$modversion['min_php']             = 5.4;
$modversion['min_xoops']           = '2.5';

$modversion['paypal']                  = array();
$modversion['paypal']['business']      = 'tad0616@gmail.com';
$modversion['paypal']['item_name']     = 'Donation : ' . _MI_TAD_WEB;
$modversion['paypal']['amount']        = 0;
$modversion['paypal']['currency_code'] = 'USD';

$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][1]        = "tad_gallery";
$modversion['tables'][2]        = "tad_gallery_cate";

$modversion['system_menu'] = 1;

$modversion['hasAdmin']   = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu']  = "admin/menu.php";

$modversion['hasMain'] = 1;

$modversion['onInstall']   = "include/onInstall.php";
$modversion['onUpdate']    = "include/onUpdate.php";
$modversion['onUninstall'] = "include/onUninstall.php";

$modversion['hasComments']          = 1;
$modversion['comments']['pageName'] = 'view.php';
$modversion['comments']['itemName'] = 'sn';

$modversion['hasSearch']      = 1;
$modversion['search']['file'] = "include/search.php";
$modversion['search']['func'] = "tadgallery_search";

$i = 1;

$modversion['templates'][$i]['file']        = 'tadgallery_cooliris.tpl';
$modversion['templates'][$i]['description'] = 'tadgallery_cooliris.tpl';

$i++;
$modversion['templates'][$i]['file']        = 'tadgallery_passwd_form.tpl';
$modversion['templates'][$i]['description'] = 'tadgallery_passwd_form.tpl';

$i++;
$modversion['templates'][$i]['file']        = 'tadgallery_list_normal.tpl';
$modversion['templates'][$i]['description'] = 'tadgallery_list_normal.tpl';

$i++;
$modversion['templates'][$i]['file']        = 'tadgallery_list_flickr.tpl';
$modversion['templates'][$i]['description'] = 'tadgallery_list_flickr.tpl';

$i++;
$modversion['templates'][$i]['file']        = 'tadgallery_list_waterfall.tpl';
$modversion['templates'][$i]['description'] = 'tadgallery_list_waterfall.tpl';

$i++;
$modversion['templates'][$i]['file']        = 'tadgallery_cate_fancybox.tpl';
$modversion['templates'][$i]['description'] = 'tadgallery_cate_fancybox.tpl';

$i++;
$modversion['templates'][$i]['file']        = 'tadgallery_albums.tpl';
$modversion['templates'][$i]['description'] = 'tadgallery_albums.tpl';

$i++;
$modversion['templates'][$i]['file']        = 'tadgallery_content.tpl';
$modversion['templates'][$i]['description'] = 'tadgallery_content.tpl';

$i++;
$modversion['templates'][$i]['file']        = 'tadgallery_view.tpl';
$modversion['templates'][$i]['description'] = 'tadgallery_view.tpl';

$i++;
$modversion['templates'][$i]['file']        = 'tadgallery_upload.tpl';
$modversion['templates'][$i]['description'] = 'tadgallery_upload.tpl';

$i++;
$modversion['templates'][$i]['file']        = 'tadgallery_adm_main.tpl';
$modversion['templates'][$i]['description'] = 'tadgallery_adm_main.tpl';

$i++;
$modversion['templates'][$i]['file']        = 'tadgallery_adm_cate.tpl';
$modversion['templates'][$i]['description'] = 'tadgallery_adm_cate.tpl';

$i++;
$modversion['templates'][$i]['file']        = 'tadgallery_list_header.tpl';
$modversion['templates'][$i]['description'] = 'tadgallery_list_header.tpl';

$i                                       = 0;
$modversion['blocks'][$i]['file']        = "tadgallery_carousel.php";
$modversion['blocks'][$i]['name']        = _MI_TADGAL_BNAME1;
$modversion['blocks'][$i]['description'] = _MI_TADGAL_BDESC1;
$modversion['blocks'][$i]['show_func']   = "tadgallery_carousel_show";
$modversion['blocks'][$i]['template']    = "tadgallery_block_carousel.tpl";
$modversion['blocks'][$i]['edit_func']   = "tadgallery_carousel_edit";
$modversion['blocks'][$i]['options']     = "12||1|post_date|desc|m|0|140|105|0|1000|3|0|5000|1";

$i++;
$modversion['blocks'][$i]['file']        = "tadgallery_shuffle.php";
$modversion['blocks'][$i]['name']        = _MI_TADGAL_BNAME2;
$modversion['blocks'][$i]['description'] = _MI_TADGAL_BDESC2;
$modversion['blocks'][$i]['show_func']   = "tadgallery_shuffle_show";
$modversion['blocks'][$i]['template']    = "tadgallery_block_shuffle.tpl";
$modversion['blocks'][$i]['edit_func']   = "tadgallery_shuffle_edit";
$modversion['blocks'][$i]['options']     = "12||1|post_date|desc|m|0|200|160";

$i++;
$modversion['blocks'][$i]['file']        = "tadgallery_show.php";
$modversion['blocks'][$i]['name']        = _MI_TADGAL_BNAME3;
$modversion['blocks'][$i]['description'] = _MI_TADGAL_BDESC3;
$modversion['blocks'][$i]['show_func']   = "tadgallery_show";
$modversion['blocks'][$i]['template']    = "tadgallery_block_show.tpl";
$modversion['blocks'][$i]['edit_func']   = "tadgallery_show_edit";
$modversion['blocks'][$i]['options']     = "12||1|post_date|desc|m|0|100%|240";

$i++;
$modversion['blocks'][$i]['file']        = "tadgallery_cooliris.php";
$modversion['blocks'][$i]['name']        = _MI_TADGAL_BNAME4;
$modversion['blocks'][$i]['description'] = _MI_TADGAL_BDESC4;
$modversion['blocks'][$i]['show_func']   = "tadgallery_cooliris_show";
$modversion['blocks'][$i]['template']    = "tadgallery_block_cooliris.tpl";
$modversion['blocks'][$i]['edit_func']   = "tadgallery_cooliris_edit";
$modversion['blocks'][$i]['options']     = "|1|100%|450";

$i++;
$modversion['blocks'][$i]['file']        = "tadgallery_scroller.php";
$modversion['blocks'][$i]['name']        = _MI_TADGAL_BNAME5;
$modversion['blocks'][$i]['description'] = _MI_TADGAL_BDESC5;
$modversion['blocks'][$i]['show_func']   = "tadgallery_scroller_show";
$modversion['blocks'][$i]['template']    = "tadgallery_block_scroller.tpl";
$modversion['blocks'][$i]['edit_func']   = "tadgallery_scroller_edit";
$modversion['blocks'][$i]['options']     = "12||1|post_date|desc|m|0|100%|240|jscroller2_up|40";

$i++;
$modversion['blocks'][$i]['file']        = "tadgallery_re_block.php";
$modversion['blocks'][$i]['name']        = _MI_TADGAL_BNAME6;
$modversion['blocks'][$i]['description'] = _MI_TADGAL_BDESC6;
$modversion['blocks'][$i]['show_func']   = "tadgallery_show_re";
$modversion['blocks'][$i]['template']    = "tadgallery_block_re.tpl";
$modversion['blocks'][$i]['edit_func']   = "tadgallery_re_edit";
$modversion['blocks'][$i]['options']     = "10|1|1";

$i++;
$modversion['blocks'][$i]['file']        = "tadgallery_list.php";
$modversion['blocks'][$i]['name']        = _MI_TADGAL_BNAME8;
$modversion['blocks'][$i]['description'] = _MI_TADGAL_BDESC8;
$modversion['blocks'][$i]['show_func']   = "tadgallery_list";
$modversion['blocks'][$i]['template']    = "tadgallery_block_list.tpl";
$modversion['blocks'][$i]['edit_func']   = "tadgallery_list_edit";
$modversion['blocks'][$i]['options']     = "12||1|post_date|desc|m|0|120|120|0|0|font-size:11px;font-weight:normal;overflow:hidden;|1|cover";

$i++;
$modversion['blocks'][$i]['file']        = "tadgallery_marquee.php";
$modversion['blocks'][$i]['name']        = _MI_TADGAL_BNAME9;
$modversion['blocks'][$i]['description'] = _MI_TADGAL_BDESC9;
$modversion['blocks'][$i]['show_func']   = "tadgallery_marquee_show";
$modversion['blocks'][$i]['template']    = "tadgallery_block_marquee.tpl";
$modversion['blocks'][$i]['edit_func']   = "tadgallery_marquee_edit";
$modversion['blocks'][$i]['options']     = "12|0|1|post_date|desc|m|0|100%|150|80|1";

$i++;
$modversion['blocks'][$i]['file']        = "tadgallery_cate.php";
$modversion['blocks'][$i]['name']        = _MI_TADGAL_BNAME10;
$modversion['blocks'][$i]['description'] = _MI_TADGAL_BDESC10;
$modversion['blocks'][$i]['show_func']   = "tadgallery_cate";
$modversion['blocks'][$i]['template']    = "tadgallery_block_cate.tpl";
$modversion['blocks'][$i]['edit_func']   = "tadgallery_cate_edit";
$modversion['blocks'][$i]['options']     = "4|album|rand()||300|line-height:1.8;|0||4";

$i                                       = 0;
$modversion['config'][$i]['name']        = 'index_mode';
$modversion['config'][$i]['title']       = '_MI_TADGAL_INDEX_MODE';
$modversion['config'][$i]['description'] = '_MI_TADGAL_INDEX_MODE_DESC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = 'normal';
$modversion['config'][$i]['options']     = array(_MI_TADGAL_NORMAL => 'normal', _MI_TADGAL_FLICKR => 'flickr', _MI_TADGAL_WATERFALL => 'waterfall');

$i++;
$modversion['config'][$i]['name']        = 'thumbnail_s_width';
$modversion['config'][$i]['title']       = '_MI_TADGAL_THUMBNAIL_S_WIDTH';
$modversion['config'][$i]['description'] = '_MI_TADGAL_THUMBNAIL_S_WIDTH_DESC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = '140';

$i++;
$modversion['config'][$i]['name']        = 'thumbnail_m_width';
$modversion['config'][$i]['title']       = '_MI_TADGAL_THUMBNAIL_M_WIDTH';
$modversion['config'][$i]['description'] = '_MI_TADGAL_THUMBNAIL_M_WIDTH_DESC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = '500';

$i++;
$modversion['config'][$i]['name']        = 'thumbnail_b_width';
$modversion['config'][$i]['title']       = '_MI_TADGAL_THUMBNAIL_B_WIDTH';
$modversion['config'][$i]['description'] = '_MI_TADGAL_THUMBNAIL_B_WIDTH_DESC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = '0';

$i++;
$modversion['config'][$i]['name']        = 'thumbnail_number';
$modversion['config'][$i]['title']       = '_MI_TADGAL_THUMBNAIL_NUMBER';
$modversion['config'][$i]['description'] = '_MI_TADGAL_THUMBNAIL_NUMBER_DESC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = '30';

$i++;
$modversion['config'][$i]['name']        = 'show_copy_pic';
$modversion['config'][$i]['title']       = '_MI_TADGAL_SHOW_COPY_PIC';
$modversion['config'][$i]['description'] = '_MI_TADGAL_SHOW_COPY_PIC_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = '1';

$i++;
$modversion['config'][$i]['name']        = 'only_thumb';
$modversion['config'][$i]['title']       = '_MI_TADGAL_ONLY_THUMB';
$modversion['config'][$i]['description'] = '_MI_TADGAL_ONLY_THUMB_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = '0';

$i++;
$modversion['config'][$i]['name']        = 'pic_toolbar';
$modversion['config'][$i]['title']       = '_MI_TADGAL_USE_PIC_TOOLBAR';
$modversion['config'][$i]['description'] = '_MI_TADGAL_USE_PIC_TOOLBAR_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = '1';

$i++;
$modversion['config'][$i]['name']        = 'facebook_comments_width';
$modversion['config'][$i]['title']       = '_MI_FBCOMMENT_TITLE';
$modversion['config'][$i]['description'] = '_MI_FBCOMMENT_TITLE_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = '1';

$i++;
$modversion['config'][$i]['name']        = 'use_pda';
$modversion['config'][$i]['title']       = '_MI_USE_PDA_TITLE';
$modversion['config'][$i]['description'] = '_MI_USE_PDA_TITLE_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = '0';

$i++;
$modversion['config'][$i]['name']        = 'use_social_tools';
$modversion['config'][$i]['title']       = '_MI_SOCIALTOOLS_TITLE';
$modversion['config'][$i]['description'] = '_MI_SOCIALTOOLS_TITLE_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = '1';

$i++;
$modversion['config'][$i]['name']        = 'thumb_slider';
$modversion['config'][$i]['title']       = '_MI_TADGAL_USE_THUMB_SLIDER';
$modversion['config'][$i]['description'] = '_MI_TADGAL_USE_THUMB_SLIDER_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = '1';

$i++;
$modversion['config'][$i]['name']        = 'random_photo';
$modversion['config'][$i]['title']       = '_MI_TADGAL_RANDOM_PHOTO';
$modversion['config'][$i]['description'] = '_MI_TADGAL_RANDOM_PHOTO_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = '1';

$i++;
$modversion['config'][$i]['name']        = 'model360';
$modversion['config'][$i]['title']       = '_MI_TADGAL_MODEL360';
$modversion['config'][$i]['description'] = '_MI_TADGAL_MODEL360_DESC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = 'LG-R105;RICOH THETA S';
