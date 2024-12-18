<?php
xoops_loadLanguage('main', 'tadtools');
require_once __DIR__ . '/global.php';
define('_MD_TADGAL_UPLOAD_PAGE', '上傳照片');
define('_MD_TADGAL_MODIFY_CATE', '修改分類');

//uploads.php
define('_MD_INPUT_FORM', '單張上傳');
define('_MD_TADGAL_MUTI_INPUT_FORM', '多張上傳');
define('_MD_TADGAL_CATE_SELECT', '全部');
define('_MD_TADGAL_PHOTO', '選擇相片');
define('_MD_TADGAL_ZIP', '選擇zip格式壓縮檔');
define('_MD_TADGAL_CSN', '相片分類');
define('_MD_TADGAL_NEW_CSN', '在左邊分類下再建一個次分類');
define('_MD_TADGAL_TITLE', '相片標題');
define('_MD_TADGAL_DESCRIPTION', '相片說明');
define('_MD_TADGAL_SAVE', '上傳');
define('_MD_TADGAL_IMPORT_UPLOADS_ERROR', '上傳 %s 檔案失敗！');
define('_MD_TADGAL_IMPORT_UPLOADS_OK', '上傳 %s 檔案成功！');
define('_MD_TADGAL_ZIP_IMPORT_FORM', '壓縮上傳');
define('_MD_TADGAL_TO_PATCH_UPLOAD_PAGE', '上傳結束後，點此進行匯入動作。');

//index.php
define('_MD_TADGAL_FILENAME', '檔名');
define('_MD_TADGAL_ALL_AUTHOR', '全部');
define('_MD_TADGAL_EMPTY', '目前沒有任何相片，請從「<a href="uploads.php?csn=%s">上傳照片</a>」開始吧！');

//大量匯入
define('_MD_TADGAL_IMPORT_FILE', '欲匯入的檔案');
define('_MD_TADGAL_IMPORT_DIR', '上傳資料夾');
define('_MD_TADGAL_IMPORT_DIMENSION', '寬 x 高');
define('_MD_TADGAL_IMPORT_SIZE', '檔案大小');
define('_MD_TADGAL_PATCH_IMPORT_FORM', '批次匯入');
define('_MD_TADGAL_UP_IMPORT', '匯入');
define('_MD_TADGAL_IMPORT_STATUS', '格式或狀態');
define('_MD_TADGAL_IMPORT_EXIST', '已存在');
define('_MD_TADGAL_IMPORT_OVER_SIZE', '總大小（%s）已超出上限 %s');
define('_MD_TADGAL_IMPORT_IMPORT_ERROR', '%s 匯入到 %s 失敗！');

define('_MD_TADGAL_IMPORT_UPLOAD_TO', '請先將相片上傳至');
define('_MD_TADGAL_IMPORT_CSN', '相片分類');
define('_MD_TADGAL_IMPORT_NEW_CSN', '在左邊分類下再建一個次分類');
define('_TADGAL_FILE_COPY_S', '小縮圖路徑');
define('_TADGAL_FILE_COPY_M', '中縮圖路徑');
define('_TADGAL_FILE_COPY_B', '原圖路徑');
define('_TADGAL_DEL_PIC', '刪除此圖片');
define('_TADGAL_EDIT_PIC', '編輯圖片資訊');
define('_TADGAL_GOOD_PIC', '設為精選');
define('_TADGAL_REMOVE_GOOD_PIC', '移除精選');
define('_MD_TADGAL_AS_COVER', '設為分類封面');
define('_MD_TADGAL_TAG', '新增標籤');
define('_MD_TADGAL_TAG_TXT', '（若有多個，請用逗點「,」隔開）');
define('_MD_TADGAL_SAVE_EDIT', '儲存');
define('_MD_TADGAL_EXIF', 'EXIF 資訊');
define('_MD_TADGAL_MAKE', '製造廠商');
define('_MD_TADGAL_MODEL', '相機型號');
define('_MD_TADGAL_ORIENTATION', '方向');
define('_MD_TADGAL_XRESOLUTION', '水平解析度');
define('_MD_TADGAL_YRESOLUTION', '垂直解析度');
define('_MD_TADGAL_RESOLUTIONUNIT', '解析度');
define('_MD_TADGAL_YCBCRPOSITIONING', 'YCbCr位置控制');
define('_MD_TADGAL_EXIFOFFSET', 'Exif 偏移量');
define('_MD_TADGAL_EXPOSURETIME', '曝光時間');
define('_MD_TADGAL_FNUMBER', '有效F值');
define('_MD_TADGAL_EXPOSUREPROGRAM', '曝光軟體');
define('_MD_TADGAL_ISOSPEEDRATINGS', 'ISO感光度');
define('_MD_TADGAL_EXIFVERSION', 'Exif 版本');
define('_MD_TADGAL_DATETIMEORIGINAL', '拍攝時間');
define('_MD_TADGAL_DATETIMEDIGITIZED', '電子日期');
define('_MD_TADGAL_COMPONENTSCONFIGURATION', '份量配置');
define('_MD_TADGAL_SHUTTERSPEEDVALUE', '快門速度');
define('_MD_TADGAL_APERTUREVALUE', '光圈');
define('_MD_TADGAL_EXPOSUREBIASVALUE', '曝光補償');
define('_MD_TADGAL_MAXAPERTUREVALUE', '最大光圈值');
define('_MD_TADGAL_METERINGMODE', '測光模式');
define('_MD_TADGAL_FLASH', '閃光燈');
define('_MD_TADGAL_FOCALLENGTH', '焦距');
define('_MD_TADGAL_FLASHPIXVERSION', 'Flashpix版本');
define('_MD_TADGAL_COLORSPACE', '色彩空間');
define('_MD_TADGAL_EXIFIMAGEWIDTH', 'Exif 圖片寬度');
define('_MD_TADGAL_EXIFIMAGEHEIGHT', 'Exif 圖片高度');
define('_MD_TADGAL_EXIFINTEROPERABILITYOFFSET', 'Exif 通用偏移量');
define('_MD_TADGAL_EXPOSUREINDEX', '曝光指數');
define('_MD_TADGAL_SENSINGMETHOD', '連續彩色線性傳感器');
define('_MD_TADGAL_FILESOURCE', '檔案來源');
define('_MD_TADGAL_SCENETYPE', '場景類型');
define('_MD_TADGAL_EXPOSUREMODE', '曝光模式');
define('_MD_TADGAL_WHITEBALANCE', '白平衡');
define('_MD_TADGAL_DIGITALZOOMRATIO', '數位變焦比率');
define('_MD_TADGAL_SCENECAPTUREMODE', '場景拍攝模式');
define('_MD_TADGAL_GAINCONTROL', '獲得控制');
define('_MD_TADGAL_CONTRAST', '對比');
define('_MD_TADGAL_SATURATION', '飽和度');
define('_MD_TADGAL_SHARPNESS', '銳利度');
define('_MD_TADGAL_COMPRESSION', '壓縮');
define('_MD_TADGAL_JPEGIFOFFSET', 'JPEG縮圖偏移量');
define('_MD_TADGAL_JPEGIFBYTECOUNT', 'JPEG縮圖數據長度');

define('_MD_TADGAL_FILETYPE', '檔案類型');
define('_MD_TADGAL_MIMETYPE', '檔案格式');
define('_MD_TADGAL_FILESIZE', '檔案大小');
define('_MD_TADGAL_WIDTH', '檔案寬度');
define('_MD_TADGAL_HEIGHT', '檔案高度');
define('_MD_TADGAL_APERTUREFNUMBER', '光圈');
define('_MD_TADGAL_LONGITUDE', '經度');
define('_MD_TADGAL_LATITUDE', '緯度');
define('_MD_TADGAL_GPSMAPDATUM', '使用地理測繪數據');
define('_MD_TADGAL_GPSSATELLITES', '測量使用的衛星');
define('_MD_TADGAL_GPSSTATUS', '接受器狀態');
define('_MD_TADGAL_GPSMEASUREMODE', '測量模式');
define('_MD_TADGAL_GPSDOP', '測量精度');
define('_MD_TADGAL_GPSDATESTAMP', 'GPS日期');

define('_MD_TADGAL_COVER', '選擇封面圖');

//ajax.php
define('_MD_TADGAL_OF_CSN', '所屬分類');
define('_MD_TADGAL_PASSWD', '相簿密碼');
define('_MD_TADGAL_PASSWD_DESC', '可不設');
define('_MD_TADGAL_ENABLE_GROUP', '可「<span style="color: blue;">觀看</span>」群組');
define('_MD_TADGAL_ENABLE_UPLOAD_GROUP', '可「<span style="color: red;">上傳</span>」群組');
define('_MD_TADGAL_ALL_OK', '所有群組');
define('_MD_TADGAL_CATE_POWER_SETUP', '權限設定');
define('_MD_TADGAL_INPUT_ALBUM_PASSWD', '請輸入「%s」相簿的密碼');
define('_MD_TADGAL_ALBUM_TITLE', '相簿標題');
define('_MD_TADGAL_VIEW_PHOTO', '詳細內容');

define('_MD_TADGAL_EDIT_CATE_CONTENT', '按此編輯相簿說明內容');
define('_MD_TADGAL_NEED_CATE', '請選擇一個相簿或者新建一個相簿');
define('_MD_TADGAL_MAP', '地圖');
define('_MD_TADGAL_3D_FULL_SCREEN', '按右下角箭頭可全螢幕播放');

define('_MD_TADGAL_IS360', '360度相片？');
define('_MD_TADGAL_MULIT_PHOTO', '一次可選擇多張照片上傳');

define('_MD_TADGAL_INDEX', '所有相簿');
define('_MD_TADGAL_CATE_SHOW_MODE', '顯示模式');
