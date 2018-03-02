<?php
/*-----------引入檔案區--------------*/
include "header.php";

$exif_item['Make']  = _MD_TADGAL_MAKE;
$exif_item['Model'] = _MD_TADGAL_MODEL;
//$exif_item['Orientation']=_MD_TADGAL_ORIENTATION;
$exif_item['xResolution']                = _MD_TADGAL_XRESOLUTION;
$exif_item['yResolution']                = _MD_TADGAL_YRESOLUTION;
$exif_item['ResolutionUnit']             = _MD_TADGAL_RESOLUTIONUNIT;
$exif_item['YCbCrPositioning']           = _MD_TADGAL_YCBCRPOSITIONING;
$exif_item['ExifOffset']                 = _MD_TADGAL_EXIFOFFSET;
$exif_item['ExposureTime']               = _MD_TADGAL_EXPOSURETIME;
$exif_item['FNumber']                    = _MD_TADGAL_FNUMBER;
$exif_item['ExposureProgram']            = _MD_TADGAL_EXPOSUREPROGRAM;
$exif_item['ISOSpeedRatings']            = _MD_TADGAL_ISOSPEEDRATINGS;
$exif_item['ExifVersion']                = _MD_TADGAL_EXIFVERSION;
$exif_item['DateTimeOriginal']           = _MD_TADGAL_DATETIMEORIGINAL;
$exif_item['DateTimedigitized']          = _MD_TADGAL_DATETIMEDIGITIZED;
$exif_item['ComponentsConfiguration']    = _MD_TADGAL_COMPONENTSCONFIGURATION;
$exif_item['ShutterSpeedValue']          = _MD_TADGAL_SHUTTERSPEEDVALUE;
$exif_item['ApertureValue']              = _MD_TADGAL_APERTUREVALUE;
$exif_item['ExposureBiasValue']          = _MD_TADGAL_EXPOSUREBIASVALUE;
$exif_item['MaxApertureValue']           = _MD_TADGAL_MAXAPERTUREVALUE;
$exif_item['MeteringMode']               = _MD_TADGAL_METERINGMODE;
$exif_item['Flash']                      = _MD_TADGAL_FLASH;
$exif_item['FocalLength']                = _MD_TADGAL_FOCALLENGTH;
$exif_item['FlashPixVersion']            = _MD_TADGAL_FLASHPIXVERSION;
$exif_item['ColorSpace']                 = _MD_TADGAL_COLORSPACE;
$exif_item['ExifImageWidth']             = _MD_TADGAL_EXIFIMAGEWIDTH;
$exif_item['ExifImageHeight']            = _MD_TADGAL_EXIFIMAGEHEIGHT;
$exif_item['ExifInteroperabilityOffset'] = _MD_TADGAL_EXIFINTEROPERABILITYOFFSET;
$exif_item['ExposureIndex']              = _MD_TADGAL_EXPOSUREINDEX;
$exif_item['SensingMethod']              = _MD_TADGAL_SENSINGMETHOD;
$exif_item['FileSource']                 = _MD_TADGAL_FILESOURCE;
$exif_item['SceneType']                  = _MD_TADGAL_SCENETYPE;
$exif_item['ExposureMode']               = _MD_TADGAL_EXPOSUREMODE;
$exif_item['WhiteBalance']               = _MD_TADGAL_WHITEBALANCE;
$exif_item['DigitalZoomRatio']           = _MD_TADGAL_DIGITALZOOMRATIO;
$exif_item['SceneCaptureMode']           = _MD_TADGAL_SCENECAPTUREMODE;
$exif_item['GainControl']                = _MD_TADGAL_GAINCONTROL;
$exif_item['Contrast']                   = _MD_TADGAL_CONTRAST;
$exif_item['Saturation']                 = _MD_TADGAL_SATURATION;
$exif_item['Sharpness']                  = _MD_TADGAL_SHARPNESS;
$exif_item['Compression']                = _MD_TADGAL_COMPRESSION;
//$exif_item['Orientation']=_MD_TADGAL_ORIENTATION;
$exif_item['xResolution']     = _MD_TADGAL_XRESOLUTION;
$exif_item['yResolution']     = _MD_TADGAL_YRESOLUTION;
$exif_item['ResolutionUnit']  = _MD_TADGAL_RESOLUTIONUNIT;
$exif_item['JpegIFOffset']    = _MD_TADGAL_JPEGIFOFFSET;
$exif_item['JpegIFByteCount'] = _MD_TADGAL_JPEGIFBYTECOUNT;
$exif_item['FileName']        = _MD_TADGAL_FILENAME;
//$exif_item['FileType']=_MD_TADGAL_FILETYPE;
$exif_item['MimeType']        = _MD_TADGAL_MIMETYPE;
$exif_item['FileSize']        = _MD_TADGAL_FILESIZE;
$exif_item['Width']           = _MD_TADGAL_WIDTH;
$exif_item['Height']          = _MD_TADGAL_HEIGHT;
$exif_item['ApertureFNumber'] = _MD_TADGAL_APERTUREFNUMBER;
$exif_item['Longitude']       = _MD_TADGAL_LONGITUDE;
$exif_item['Latitude']        = _MD_TADGAL_LATITUDE;
$exif_item['GPSMapDatum']     = _MD_TADGAL_GPSMAPDATUM;
$exif_item['GPSSatellites']   = _MD_TADGAL_GPSSATELLITES;
$exif_item['GPSStatus']       = _MD_TADGAL_GPSSTATUS;
$exif_item['GPSMeasureMode']  = _MD_TADGAL_GPSMEASUREMODE;
$exif_item['GPSDOP']          = _MD_TADGAL_GPSDOP;
$exif_item['GPSDateStamp']    = _MD_TADGAL_GPSDATESTAMP;

/*-----------function區--------------*/

function get_exif_info($item = "", $v = "")
{
    global $exif_item;
    $v = trim($v);
    if (empty($v)) {
        return;
    }

    $main = "";
    if (!empty($exif_item[$item])) {
        $main = "<tr><td>{$exif_item[$item]} ($item) </td><td>{$v}</td></tr>";
    }
    return $main;
}

//觀看某一張照片
function view_pic_exif($sn = "")
{
    global $xoopsDB, $xoopsModule, $xoopsModuleConfig;

    $sql        = "select exif from " . $xoopsDB->prefix("tad_gallery") . " where sn='{$sn}'";
    $result     = $xoopsDB->query($sql) or web_error($sql);
    list($exif) = $xoopsDB->fetchRow($result);

    $info = explode("||", $exif);

    foreach ($info as $v) {
        $exif_arr    = explode("=", $v);
        $exif_arr[1] = str_replace("&#65533;", "", $exif_arr[1]);
        $bb          = "\$aa{$exif_arr[0]}=\"{$exif_arr[1]}\";";
        if (empty($exif_arr[0])) {
            continue;
        }

        @eval($bb);
    }

    $exif_all = $exif_data = "";
    foreach ($aa as $k => $v) {

        $exif_data = "";
        foreach ($v as $kk => $vv) {

            $exif_data .= get_exif_info($kk, $vv);
        }
        if (!empty($exif_data)) {
            $exif_all .= $exif_data;
        }
    }

    return "<table style='width:auto;' class='line'>$exif_all</table>";
}

/*-----------執行動作判斷區----------*/
$sn = (!empty($_REQUEST['sn'])) ? (int)$_REQUEST['sn'] : 0;

$main = view_pic_exif($sn);

/*-----------秀出結果區--------------*/
echo '<!DOCTYPE html>
<html lang="zh-TW">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>EXIF</title>
  <link rel="stylesheet" type="text/css" media="screen" href="' . XOOPS_URL . '/modules/tadgallery/module.css">
</head>
<body>
<h3 class="sr-only">EXIF</h3>
' . $main . '
</body>
</html>';
