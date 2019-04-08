<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
$xoopsOption['template_main'] = "tadgallery_upload.tpl";

if ((!empty($upload_powers) and $xoopsUser) or $isAdmin) {
    include XOOPS_ROOT_PATH . "/header.php";
} else {
    redirect_header(XOOPS_URL . "/user.php", 3, _TADGAL_NO_UPLOAD_POWER);
}

/*-----------function區--------------*/

function uploads_tabs($def_csn = "")
{
    global $xoopsTpl, $xoopsModuleConfig;

    get_jquery(true);
    $now = time();

    $to_batch_upload = "";
    if (isset($_REQUEST['op']) and $_REQUEST['op'] == 'to_batch_upload') {
        $to_batch_upload = '{ active: 3 }';
    }

    $jquery_ui = '
    <script type="text/javascript">
        $(document).ready(function() {
            $("#jquery_tabs_tg_' . $now . '").tabs(' . $to_batch_upload . ');
        });
    </script>';

    $csn = isset($_SESSION['tad_gallery_csn']) ? (int) $_SESSION['tad_gallery_csn'] : "";

    $xoopsTpl->assign("xoops_module_header", $jquery_ui);
    $xoopsTpl->assign('now', $now);
    $xoopsTpl->assign('tad_gallery_form', tad_gallery_form());
    $xoopsTpl->assign('def_csn', $def_csn);

}

//tad_gallery編輯表單
function tad_gallery_form($sn = "")
{
    global $xoopsDB, $xoopsTpl;

    //抓取預設值
    if (!empty($sn)) {
        $DBV = tadgallery::get_tad_gallery($sn);
    } else {
        $DBV = array();
    }

    //預設值設定

    $sn    = (!isset($DBV['sn'])) ? "" : $DBV['sn'];
    $title = (!isset($DBV['title'])) ? "" : $DBV['title'];
    $tag   = (!isset($DBV['tag'])) ? "" : $DBV['tag'];

    $op = (empty($sn)) ? "insert_tad_gallery" : "update_tad_gallery";

    $xoopsTpl->assign('title', $title);
    $xoopsTpl->assign('op', $op);
    $xoopsTpl->assign('sn', $sn);

    $tag_select = tag_select($tag);
    $xoopsTpl->assign('tag_select', $tag_select);

}

//新增資料到tad_gallery中
function insert_tad_gallery()
{
    global $xoopsDB, $xoopsUser, $xoopsModuleConfig, $type_to_mime;
    krsort($_POST['csn_menu']);
    foreach ($_POST['csn_menu'] as $cate_sn) {
        $cate_sn = (int) $cate_sn;
        if (empty($cate_sn)) {
            continue;
        } else {
            $csn = $cate_sn;
            break;
        }
    }
    if (!empty($_POST['new_csn'])) {
        $csn = add_tad_gallery_cate($csn, (int) $_POST['new_csn'], (int) $_POST['sort']);
    }

    $uid = $xoopsUser->uid();

    if (!empty($_POST['csn'])) {
        $_SESSION['tad_gallery_csn'] = (int) $_POST['csn'];
    }

    //處理上傳的檔案
    if (!empty($_FILES['image']['name'])) {

        //若需要轉方向的話
        $angle = 0;

        $orginal_file_name = strtolower(basename($_FILES['image']["name"])); //get lowercase filename
        $file_ending       = substr(strtolower($orginal_file_name), -3); //file extension

        $pic    = getimagesize($_FILES['image']['tmp_name']);
        $width  = $pic[0];
        $height = $pic[1];
        $is360  = (int) $_POST['is360'];

        //讀取exif資訊
        if (function_exists('exif_read_data')) {
            $result = exif_read_data($_FILES['image']['tmp_name'], 0, true);
            // die(var_export($result));
            $creat_date = $result['IFD0']['DateTime'];
            $Model360   = get360_arr();
            if (in_array($result['IFD0']['Model'], $Model360)) {
                $is360 = 1;
            }

            //直拍照片
            if ($result['IFD0']['Orientation'] == 6) {
                $angle = 270;
            } elseif ($result['IFD0']['Orientation'] == 8) {
                $angle = 90;
            }
        } else {
            $creat_date = date("Y-m-d");
        }
        $dir  = (empty($creat_date) or substr($creat_date, 0, 1) != "2") ? date("Y_m_d") : str_replace(":", "_", substr($result['IFD0']['DateTime'], 0, 10));
        $exif = mk_exif($result);

        $now = date("Y-m-d H:i:s", xoops_getUserTimestamp(time()));
        $csn = (int) $csn;

        $sql = "insert into " . $xoopsDB->prefix("tad_gallery") . " (
        `csn`, `title`, `description`, `filename`, `size`, `type`, `width`, `height`, `dir`, `uid`, `post_date`, `counter`, `exif`, `tag`, `good`, `photo_sort`,`is360`) values('{$csn}','{$_POST['title']}','{$_POST['description']}','{$_FILES['image']['name']}','{$_FILES['image']['size']}','{$_FILES['image']['type']}','{$width}','{$height}','{$dir}','{$uid}','{$now}','0','{$exif}','','0',0,'{$is360}')";

        $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
        //取得最後新增資料的流水編號
        $sn = $xoopsDB->getInsertId();

        mk_dir(_TADGAL_UP_FILE_DIR);
        mk_dir(_TADGAL_UP_FILE_DIR . $dir);
        mk_dir(_TADGAL_UP_FILE_DIR . "small/" . $dir);
        mk_dir(_TADGAL_UP_FILE_DIR . "medium/" . $dir);

        $filename = photo_name($sn, "source", 1);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $filename)) {

            $m_thumb_name = photo_name($sn, "m", 1);
            $s_thumb_name = photo_name($sn, "s", 1);

            if ($width > $xoopsModuleConfig['thumbnail_s_width'] or $height > $xoopsModuleConfig['thumbnail_s_width']) {
                thumbnail($filename, $s_thumb_name, $type_to_mime[$file_ending], $xoopsModuleConfig['thumbnail_s_width'], $angle);
            }

            if ($width > $xoopsModuleConfig['thumbnail_m_width'] or $height > $xoopsModuleConfig['thumbnail_m_width']) {
                thumbnail($filename, $m_thumb_name, $type_to_mime[$file_ending], $xoopsModuleConfig['thumbnail_m_width'], $angle);
            }

            if (!$is360) {
                if (!empty($xoopsModuleConfig['thumbnail_b_width']) and ($width > $xoopsModuleConfig['thumbnail_b_width'] or $height > $xoopsModuleConfig['thumbnail_b_width'])) {
                    thumbnail($filename, $filename, $type_to_mime[$file_ending], $xoopsModuleConfig['thumbnail_b_width'], $angle);
                }
            }

        } else {
            redirect_header($_SERVER['PHP_SELF'], 5, sprintf(_MD_TADGAL_IMPORT_UPLOADS_ERROR, $filename));
        }
    }

    return $sn;
}

//上傳圖檔
function upload_muti_file()
{
    global $xoopsDB, $xoopsUser, $xoopsModule, $xoopsModuleConfig, $type_to_mime;

    krsort($_POST['csn_menu']);
    foreach ($_POST['csn_menu'] as $cate_sn) {
        if (empty($cate_sn)) {
            continue;
        } else {
            $csn = $cate_sn;
            break;
        }
    }
    if (!empty($_POST['new_csn'])) {
        $csn = add_tad_gallery_cate($csn, $_POST['new_csn'], $_POST['sort']);
    }

    $uid = $xoopsUser->uid();

    if (!empty($_POST['csn'])) {
        $_SESSION['tad_gallery_csn'] = $_POST['csn'];
    }

    //取消上傳時間限制
    set_time_limit(0);

    //設置上傳大小
    ini_set('memory_limit', '100M');

    $files = array();
    foreach ($_FILES['upfile'] as $k => $l) {

        foreach ($l as $i => $v) {
            if (empty($v)) {
                continue;
            }

            if (!array_key_exists($i, $files)) {
                $files[$i] = array();
            }
            $files[$i][$k] = $v;
        }
    }

    $sort = 0;

    $Model360 = get360_arr();
    foreach ($files as $i => $file) {

        if (empty($file['tmp_name'])) {
            continue;
        }

        //若需要轉方向的話
        $angle = 0;

        $orginal_file_name = strtolower(basename($file["name"])); //get lowercase filename
        $file_ending       = substr(strtolower($orginal_file_name), -3); //file extension

        $pic    = getimagesize($file['tmp_name']);
        $width  = $pic[0];
        $height = $pic[1];
        $is360  = (int) $_POST['is360'];

        //讀取exif資訊
        if (function_exists('exif_read_data')) {
            $result     = exif_read_data($file['tmp_name'], 0, true);
            $creat_date = $result['IFD0']['DateTime'];
            if (in_array($result['IFD0']['Model'], $Model360)) {
                $is360 = 1;
            }

            //直拍照片
            if ($result['IFD0']['Orientation'] == 6) {
                $angle = 270;
            } elseif ($result['IFD0']['Orientation'] == 8) {
                $angle = 90;
            }
        } else {
            $creat_date = date("Y-m-d");
        }

        $dir  = (empty($creat_date) or substr($creat_date, 0, 1) != "2") ? date("Y_m_d") : str_replace(":", "_", substr($result['IFD0']['DateTime'], 0, 10));
        $exif = mk_exif($result);

        $now = date("Y-m-d H:i:s", xoops_getUserTimestamp(time()));
        $csn = (int) $csn;
        $sql = "insert into " . $xoopsDB->prefix("tad_gallery") . "
        (`csn`, `title`, `description`, `filename`, `size`, `type`, `width`, `height`, `dir`, `uid`, `post_date`, `counter`, `exif`, `tag`, `good`, `photo_sort`,`is360`)
        values('{$csn}','','','{$file['name']}','{$file['size']}','{$file['type']}','{$width}','{$height}','{$dir}','{$uid}','{$now}','0','{$exif}','','0', $sort, '{$is360}')";
        $sort++;
        $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
        //取得最後新增資料的流水編號
        $sn = $xoopsDB->getInsertId();

        mk_dir(_TADGAL_UP_FILE_DIR . $dir);
        mk_dir(_TADGAL_UP_FILE_DIR . "small/" . $dir);
        mk_dir(_TADGAL_UP_FILE_DIR . "medium/" . $dir);

        $filename = photo_name($sn, "source", 1);

        if (move_uploaded_file($file['tmp_name'], $filename)) {

            $m_thumb_name = photo_name($sn, "m", 1);
            $s_thumb_name = photo_name($sn, "s", 1);

            if ($width > $xoopsModuleConfig['thumbnail_s_width'] or $height > $xoopsModuleConfig['thumbnail_s_width']) {
                thumbnail($filename, $s_thumb_name, $type_to_mime[$file_ending], $xoopsModuleConfig['thumbnail_s_width'], $angle);
            }

            if ($width > $xoopsModuleConfig['thumbnail_m_width'] or $height > $xoopsModuleConfig['thumbnail_m_width']) {
                thumbnail($filename, $m_thumb_name, $type_to_mime[$file_ending], $xoopsModuleConfig['thumbnail_m_width'], $angle);
            }

            if (!$is360) {
                if (!empty($xoopsModuleConfig['thumbnail_b_width']) and ($width > $xoopsModuleConfig['thumbnail_b_width'] or $height > $xoopsModuleConfig['thumbnail_b_width'])) {
                    thumbnail($filename, $filename, $type_to_mime[$file_ending], $xoopsModuleConfig['thumbnail_b_width'], $angle);
                }
            }

        }
    }
    return $csn;
}

//上傳壓縮圖檔
function upload_zip_file()
{
    global $xoopsDB, $xoopsUser, $xoopsModule, $xoopsModuleConfig, $type_to_mime;

    //取消上傳時間限制
    set_time_limit(0);

    //設置上傳大小
    ini_set('memory_limit', '100M');

    require_once "class/dunzip2/dUnzip2.inc.php";
    require_once "class/dunzip2/dZip.inc.php";
    $zip = new dUnzip2($_FILES['zipfile']['tmp_name']);
    $zip->getList();
    $zip->unzipAll(_TADGAL_UP_IMPORT_DIR);

}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op  = system_CleanVars($_REQUEST, 'op', '', 'string');
$sn  = system_CleanVars($_REQUEST, 'sn', 0, 'int');
$csn = system_CleanVars($_REQUEST, 'csn', 0, 'int');

switch ($op) {
    case "insert_tad_gallery":
        $sn = insert_tad_gallery();
        mk_rss_xml();
        mk_rss_xml($csn);
        redirect_header("view.php?sn=$sn", 1, sprintf(_MD_TADGAL_IMPORT_UPLOADS_OK, $filename));
        break;

    case "upload_muti_file":
        $csn = upload_muti_file();
        mk_rss_xml();
        mk_rss_xml($csn);
        redirect_header("index.php?csn=$csn", 1, sprintf(_MD_TADGAL_IMPORT_UPLOADS_OK, $filename));
        break;

    case "upload_zip_file":
        upload_zip_file();
        header("location: uploads.php?op=to_batch_upload");
        exit;

    default:
        uploads_tabs($csn);
        break;
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign("toolbar", toolbar_bootstrap($interface_menu));
include_once XOOPS_ROOT_PATH . '/footer.php';
