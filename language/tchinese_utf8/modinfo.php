<?php
xoops_loadLanguage('modinfo_common', 'tadtools');

define('_MI_TADGAL_NAME', '電子相簿');
define('_MI_TADGAL_AUTHOR', 'Tad');
define('_MI_TADGAL_CREDITS', 'tad');
define('_MI_TADGAL_DESC', '此模組是一個簡單易用的電子相簿');
define('_MI_TADGAL_ADMENU1', '相片管理');
define('_MI_TADGAL_ADMENU2', '分類管理');
define('_MI_TADGAL_ADMENU5', '產生 Media RSS');

define('_MI_TADGAL_BNAME1', '相片捲軸');
define('_MI_TADGAL_BDESC1', '可以上下移動圖片的的區塊');
define('_MI_TADGAL_BNAME2', '抽取相片');
define('_MI_TADGAL_BDESC2', '可以抽取切換的相片');
define('_MI_TADGAL_BNAME3', '相片投影秀');
define('_MI_TADGAL_BDESC3', '一張接著一張展示投影片');
define('_MI_TADGAL_BNAME4', '3D相片牆');
define('_MI_TADGAL_BDESC4', '3D相片牆');
define('_MI_TADGAL_BNAME5', '圖片跑馬燈');
define('_MI_TADGAL_BDESC5', '圖片會以跑馬燈的方式呈現');
define('_MI_TADGAL_BNAME6', '相片最新回應');
define('_MI_TADGAL_BDESC6', '顯示網友針對某張相片的評論');
define('_MI_TADGAL_BNAME8', '縮圖列表');
define('_MI_TADGAL_BDESC8', '最傳統的縮圖列表顯示區塊');
define('_MI_TADGAL_BNAME9', '無縫跑馬燈');
define('_MI_TADGAL_BDESC9', '無縫跑馬燈區塊');
define('_MI_TADGAL_BNAME10', '相簿一覽');
define('_MI_TADGAL_BDESC10', '相簿一覽區塊');

define('_MI_TADGAL_THUMBNAIL_S_WIDTH', '<b>小縮圖最長邊的長度</b>');
define('_MI_TADGAL_THUMBNAIL_S_WIDTH_DESC', '設定小縮圖最長的寬度或高度');
define('_MI_TADGAL_THUMBNAIL_M_WIDTH', '<b>中縮圖最長邊的長度</b>');
define('_MI_TADGAL_THUMBNAIL_M_WIDTH_DESC', '設定中縮圖最長的寬度或高度');
define('_MI_TADGAL_THUMBNAIL_B_WIDTH', '<b>強制縮小原圖到指定寬度？</b>');
define('_MI_TADGAL_THUMBNAIL_B_WIDTH_DESC', '例如「1024」代表長邊大於1024的圖，會強制將其長邊縮到1024，如此可以有效降低大圖浪費的空間。<br>若設成「0」則是維持原圖大小。');
define('_MI_TADGAL_THUMBNAIL_NUMBER', '<b>縮圖頁顯示的相片數</b>');
define('_MI_TADGAL_THUMBNAIL_NUMBER_DESC', '相片首頁以及管理頁面每一頁要顯示多少相片的數量。');

define('_MI_TADGAL_SHOW_COPY_PIC', '<b>顯示各種尺寸按鈕</b>');
define('_MI_TADGAL_SHOW_COPY_PIC_DESC', '可方便取得小型縮圖、中型縮圖、原圖的完整路徑。');

define('_MI_TADGAL_ONLY_THUMB', '<b>畫面中只秀出縮圖，不秀出分類。</b>');
define('_MI_TADGAL_ONLY_THUMB_DESC', '分類選單一樣存在，只是縮圖畫面不再顯示分類資料夾。');

define('_MI_TADGAL_USE_PIC_TOOLBAR', '<b>使用圖片工具列？</b>');
define('_MI_TADGAL_USE_PIC_TOOLBAR_DESC', '是否使用圖片工具列（或文字工具列）');

define('_MI_TADGAL_INDEX_MODE', '縮圖呈現模式');
define('_MI_TADGAL_INDEX_MODE_DESC', '選擇首頁縮圖呈現模式');
define('_MI_TADGAL_NORMAL', '一般縮圖模式');
define('_MI_TADGAL_FLICKR', 'Flickr 等高模式');
define('_MI_TADGAL_WATERFALL', '瀑布流呈現模式');

define('_MI_TADGAL_USE_THUMB_SLIDER', '<b>顯示縮圖滑動列？</b>');
define('_MI_TADGAL_USE_THUMB_SLIDER_DESC', '單張相片頁面下方是否顯示縮圖滑動列？');
define('_MI_TADGAL_RANDOM_PHOTO', '<b>是否顯示模組首頁的隨機縮圖？</b>');
define('_MI_TADGAL_RANDOM_PHOTO_DESC', '選否，模組首頁就會只出現相簿。');

define('_MI_TADGAL_MODEL360', '設定360相片的相機MODEL型號');
define('_MI_TADGAL_MODEL360_DESC', '用EXIF中的MODEL來判斷是否為360相片');
