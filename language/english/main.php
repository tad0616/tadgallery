<?php
//global.php
include_once "global.php";
define("_MD_TADGAL_UPLOAD_PAGE","Upload photo");
define("_MD_TADGAL_COOLIRIS","3D wall");
define("_MD_TADGAL_MODIFY_CATE","Edit Album");

//uploads.php
define("_MD_INPUT_FORM","Upload single photo");
define("_MD_TADGAL_MUTI_INPUT_FORM","Upload photos");
define("_MD_TADGAL_CATE_SELECT","All");
define("_MD_TADGAL_PHOTO","Select photos:");
define("_MD_TADGAL_ZIP","Select zip file:");
define("_MD_TADGAL_CSN","Photo Category:");
define("_MD_TADGAL_NEW_CSN","Creat a sub-category from top category:");
define("_MD_TADGAL_TITLE","Photo title:");
define("_MD_TADGAL_DESCRIPTION","Photo description:");
define("_MD_SAVE","Upload");
define("_MD_TADGAL_IMPORT_UPLOADS_ERROR","Upload file \"%s\" failed!");
define("_MD_TADGAL_IMPORT_UPLOADS_OK","Upload file \"%s\" successful!");
define("_MD_TADGAL_ZIP_IMPORT_FORM","Upload Zip");
define("_MD_TADGAL_TO_PATCH_UPLOAD_PAGE","After uploading, click here to import photos.");

//index.php
define("_MD_TADGAL_FILENAME","File name");
define("_MD_TADGAL_ALL_AUTHOR","All");
define("_MD_TADGAL_EMPTY","There are currently no pictures, please <a href='uploads.php?csn={$_GET['csn']}'>upload photos</a>.");

//Batch import
define("_MD_TADGAL_IMPORT_FILE","Upload file");
define("_MD_TADGAL_IMPORT_DIR","Upload folder");
define("_MD_TADGAL_IMPORT_DIMENSION","Width x Height");
define("_MD_TADGAL_IMPORT_SIZE","File size");
define("_MD_TADGAL_PATCH_IMPORT_FORM","Batch upload photos");
define("_MD_TADGAL_UP_IMPORT","Import");
define("_MD_TADGAL_IMPORT_STATUS","Type or status");
define("_MD_TADGAL_IMPORT_EXIST","Already exist");
define("_MD_TADGAL_IMPORT_OVER_SIZE","Total upload size (%s) over %s");
define("_MD_TADGAL_IMPORT_IMPORT_ERROR","Import file from \"%s\" to \"%s\" failed!");

define("_MD_TADGAL_IMPORT_UPLOAD_TO","Please upload photos to:");
define("_MD_TADGAL_IMPORT_CSN","Photo Category:");
define("_MD_TADGAL_IMPORT_NEW_CSN","Creat a sub-category from top category:");
define("_TADGAL_FILE_COPY_S","small thumbnail");
define("_TADGAL_FILE_COPY_M","medium thumbnail");
define("_TADGAL_FILE_COPY_B","original photo");
define("_TADGAL_DEL_PIC","Delete this photo");
define("_TADGAL_EDIT_PIC","Edit photo description");
define("_TADGAL_GOOD_PIC","Add this photo to BEST PHOTOS selection");
define("_TADGAL_REMOVE_GOOD_PIC","Remove this photo from BEST PHOTOS selection");
define("_MD_TADGAL_AS_COVER","Set this photo as the Album cover");
define("_MD_TADGAL_TAG","Add new tag");
define("_MD_TADGAL_TAG_TXT","(For multi-tags, please use comma \",\" to seperate them)");
define("_MD_SAVE_EDIT","Save");
define("_MD_TADGAL_EXIF","EXIF information");
define("_MD_TADGAL_MAKE","Make");
define("_MD_TADGAL_MODEL","Model");
define("_MD_TADGAL_ORIENTATION","Orientation");
define("_MD_TADGAL_XRESOLUTION","X resolution");
define("_MD_TADGAL_YRESOLUTION","Y resolution");
define("_MD_TADGAL_RESOLUTIONUNIT","Resolution Unit");
define("_MD_TADGAL_YCBCRPOSITIONING","YCbCr positioning");
define("_MD_TADGAL_EXIFOFFSET","Exif offset");
define("_MD_TADGAL_EXPOSURETIME","Exposure Time");
define("_MD_TADGAL_FNUMBER","F number");
define("_MD_TADGAL_EXPOSUREPROGRAM","Exposure Program");
define("_MD_TADGAL_ISOSPEEDRATINGS","ISO Speed Ratings");
define("_MD_TADGAL_EXIFVERSION","Exif version");
define("_MD_TADGAL_DATETIMEORIGINAL","Date Time Original");
define("_MD_TADGAL_DATETIMEDIGITIZED","Date Time Digitized");
define("_MD_TADGAL_COMPONENTSCONFIGURATION","Components Configuration");
define("_MD_TADGAL_SHUTTERSPEEDVALUE","Shutter Speed Value");
define("_MD_TADGAL_APERTUREVALUE","Aperture Value");
define("_MD_TADGAL_EXPOSUREBIASVALUE","Exposure Bias Value");
define("_MD_TADGAL_MAXAPERTUREVALUE","Max Aperture Value");
define("_MD_TADGAL_METERINGMODE","Metering Mode");
define("_MD_TADGAL_FLASH","Flash");
define("_MD_TADGAL_FOCALLENGTH","Focal Length");
define("_MD_TADGAL_FLASHPIXVERSION","Flashpix version");
define("_MD_TADGAL_COLORSPACE","color space");
define("_MD_TADGAL_EXIFIMAGEWIDTH","Exif image width");
define("_MD_TADGAL_EXIFIMAGEHEIGHT","Exif image height");
define("_MD_TADGAL_EXIFINTEROPERABILITYOFFSET","Exif Interoperability Offset");
define("_MD_TADGAL_EXPOSUREINDEX","Exposure index");
define("_MD_TADGAL_SENSINGMETHOD","Sensing Method");
define("_MD_TADGAL_FILESOURCE","File Source");
define("_MD_TADGAL_SCENETYPE","Scene Type");
define("_MD_TADGAL_EXPOSUREMODE","Exposure Mode");
define("_MD_TADGAL_WHITEBALANCE","White Balance");
define("_MD_TADGAL_DIGITALZOOMRATIO","Digital zoom Ratio");
define("_MD_TADGAL_SCENECAPTUREMODE","Scene Capture Mode");
define("_MD_TADGAL_GAINCONTROL","Gain Control");
define("_MD_TADGAL_CONTRAST","Contrast");
define("_MD_TADGAL_SATURATION","Saturation");
define("_MD_TADGAL_SHARPNESS","Sharpness");
define("_MD_TADGAL_COMPRESSION","Compression");
define("_MD_TADGAL_JPEGIFOFFSET","Thumbnail JPEG Interchange Format Offset");
define("_MD_TADGAL_JPEGIFBYTECOUNT","Thumbnail JPEG Interchange Format Length");

define("_MD_TADGAL_FILETYPE","FileType");
define("_MD_TADGAL_MIMETYPE","MimeType");
define("_MD_TADGAL_FILESIZE","FileSize");
define("_MD_TADGAL_WIDTH","Width");
define("_MD_TADGAL_HEIGHT","Height");
define("_MD_TADGAL_APERTUREFNUMBER","ApertureFNumber");
define("_MD_TADGAL_LONGITUDE","Longitude");
define("_MD_TADGAL_LATITUDE","Latitude");
define("_MD_TADGAL_GPSMAPDATUM","GPSMapDatum");
define("_MD_TADGAL_GPSSATELLITES","GPSSatellites");
define("_MD_TADGAL_GPSSTATUS","GPSStatus");
define("_MD_TADGAL_GPSMEASUREMODE","GPSMeasureMode");
define("_MD_TADGAL_GPSDOP","GPSDOP");
define("_MD_TADGAL_GPSDATESTAMP","GPSDateStamp");

define("_MD_TADGAL_COVER","Select cover image");

//ajax.php
define("_MD_TADGAL_OF_CSN","Category");
define("_MD_TADGAL_PASSWD","Album password");
define("_MD_TADGAL_PASSWD_DESC","Optional");
define("_MD_TADGAL_ENABLE_GROUP","\"<span style='color:blue'>VIEW</span>\" enabled group");
define("_MD_TADGAL_ENABLE_UPLOAD_GROUP","\"<span style='color:red'>UPLOAD</span>\" enabled group");
define("_MD_TADGAL_ALL_OK","All Groups");
define("_MD_TADGAL_CATE_POWER_SETUP","Permission setting");
define("_MD_TADGAL_INPUT_ALBUM_PASSWD","Please key in password of \"%s\" album.");
define("_MD_TADGAL_ALBUM_TITLE","Album title");
define("_MD_TADGAL_VIEW_PHOTO","more...");

define("_MD_TADGAL_EDIT_CATE_CONTENT","Click to edit.");
define("_MD_TADGAL_NEED_CATE","Please select a album.");
define("_MD_TADGAL_MAP","Map");
define("_MD_TADGAL_3D_FULL_SCREEN","Press the bottom right arrow to full-screen playback.");
