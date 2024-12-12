<?php
use Xmf\Request;
use XoopsModules\Tadgallery\Tools;
use XoopsModules\Tadtools\EasyResponsiveTabs;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
require_once 'header.php';
$xoopsOption['template_main'] = 'tadgallery_upload.tpl';

if ((!empty($upload_powers) and isset($xoopsUser) && \is_object($xoopsUser)) or $tad_gallery_adm) {
    require XOOPS_ROOT_PATH . '/header.php';
} else {
    redirect_header(XOOPS_URL . '/user.php', 3, _TADGAL_NO_UPLOAD_POWER);
}

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$sn = Request::getInt('sn');
$csn = Request::getInt('csn');
$csn_menu = Request::getArray('csn_menu');
$new_csn = Request::getString('new_csn');

switch ($op) {
    case 'insert_tad_gallery':
        $sn = insert_tad_gallery();
        redirect_header("view.php?sn=$sn", 1, sprintf(_MD_TADGAL_IMPORT_UPLOADS_OK, $filename));
        break;

    case 'upload_muti_file':
        $csn = upload_muti_file();
        redirect_header("index.php?csn=$csn", 1, sprintf(_MD_TADGAL_IMPORT_UPLOADS_OK, $filename));
        break;
    case 'upload_zip_file':
        upload_zip_file();
        header('location: uploads.php#photosTab4');
        exit;

    case 'import_tad_gallery':
        $csn = import_tad_gallery($csn_menu, $new_csn, $_POST['all'], $_POST['import']);
        header("location: index.php?csn=$csn");
        exit;
    default:
        uploads_tabs($csn);
        break;
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu, false, $interface_icon));
$xoTheme->addStylesheet('modules/tadtools/css/my-input.css');
require_once XOOPS_ROOT_PATH . '/footer.php';

/*-----------function區--------------*/

function uploads_tabs($def_csn = '')
{
    global $xoopsTpl;

    $xoopsTpl->assign('tad_gallery_form', tad_gallery_form());
    $xoopsTpl->assign('def_csn', $def_csn);

    $EasyResponsiveTabs = new EasyResponsiveTabs('#photosTab');
    $EasyResponsiveTabs->render();

    $tad_gallery_cate_option = get_tad_gallery_cate_option($def_csn);
    $xoopsTpl->assign('tad_gallery_cate_option', $tad_gallery_cate_option);

    //找出要匯入的圖
    $pics = is_dir(_TADGAL_UP_IMPORT_DIR) ? read_dir_pic(_TADGAL_UP_IMPORT_DIR) : '';

    //預設值設定
    $xoopsTpl->assign('pics', $pics);
}

//tad_gallery編輯表單
function tad_gallery_form($sn = '')
{
    global $xoopsTpl;

    //抓取預設值
    if (!empty($sn)) {
        $DBV = Tools::get_tad_gallery($sn);
    } else {
        $DBV = [];
    }

    //預設值設定

    $sn = (!isset($DBV['sn'])) ? '' : $DBV['sn'];
    $title = (!isset($DBV['title'])) ? '' : $DBV['title'];
    $tag = (!isset($DBV['tag'])) ? '' : $DBV['tag'];

    $op = (empty($sn)) ? 'insert_tad_gallery' : 'update_tad_gallery';

    $xoopsTpl->assign('title', $title);
    $xoopsTpl->assign('op', $op);
    $xoopsTpl->assign('sn', $sn);

    $tag_select = tag_select($tag);
    $xoopsTpl->assign('tag_select', $tag_select);
}

//新增資料到tad_gallery中
function insert_tad_gallery()
{
    global $xoopsDB, $xoopsUser, $xoopsModuleConfig;

    $csn = (int) $_POST['csn_menu'];
    if (!empty($_POST['new_csn'])) {
        $csn = add_tad_gallery_cate($csn, (string) $_POST['new_csn'], (int) $_POST['sort']);
    }

    $uid = $xoopsUser->uid();

    //處理上傳的檔案
    if (!empty($_FILES['image']['name'])) {
        //若需要轉方向的話
        $angle = 0;

        $orginal_file_name = mb_strtolower(basename($_FILES['image']['name']));

        $pic = getimagesize($_FILES['image']['tmp_name']);
        $width = $pic[0];
        $height = $pic[1];
        $is360 = (int) $_POST['is360'];

        //讀取exif資訊
        if (function_exists('exif_read_data')) {
            $result = exif_read_data($_FILES['image']['tmp_name'], 0, true);
            // die(var_export($result));
            $creat_date = $result['IFD0']['DateTime'];
            $Model360 = get360_arr();
            if (in_array($result['IFD0']['Model'], $Model360)) {
                $is360 = 1;
            }

            //直拍照片
            if (6 == $result['IFD0']['Orientation']) {
                $angle = 270;
            } elseif (8 == $result['IFD0']['Orientation']) {
                $angle = 90;
            }
        } else {
            $creat_date = date('Y-m-d');
        }
        $dir = (empty($creat_date) or '2' != mb_substr($creat_date, 0, 1)) ? date('Y_m_d') : str_replace(':', '_', mb_substr($result['IFD0']['DateTime'], 0, 10));
        $exif = mk_exif($result);

        $now = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));
        $csn = (int) $csn;

        $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_gallery') . '` (
            `csn`, `title`, `description`, `filename`, `size`, `type`, `width`, `height`, `dir`, `uid`, `post_date`, `counter`, `exif`, `tag`, `good`, `photo_sort`, `is360`
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, 0, ?)';

        Utility::query($sql, 'isssisiisisisss', [$csn, $_POST['title'], $_POST['description'], $_FILES['image']['name'], $_FILES['image']['size'], $_FILES['image']['type'], $width, $height, $dir, $uid, $now, 0, $exif, '', $is360]) or Utility::web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $sn = $xoopsDB->getInsertId();

        Utility::mk_dir(_TADGAL_UP_FILE_DIR);
        Utility::mk_dir(_TADGAL_UP_FILE_DIR . $dir);
        Utility::mk_dir(_TADGAL_UP_FILE_DIR . 'small/' . $dir);
        Utility::mk_dir(_TADGAL_UP_FILE_DIR . 'medium/' . $dir);

        $filename = photo_name($sn, 'source', 1);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $filename)) {
            $m_thumb_name = photo_name($sn, 'm', 1);
            $s_thumb_name = photo_name($sn, 's', 1);

            if ($width > $xoopsModuleConfig['thumbnail_s_width'] or $height > $xoopsModuleConfig['thumbnail_s_width']) {
                Utility::generateThumbnail($filename, $s_thumb_name, $xoopsModuleConfig['thumbnail_s_width'], $angle);
            }

            if ($width > $xoopsModuleConfig['thumbnail_m_width'] or $height > $xoopsModuleConfig['thumbnail_m_width']) {
                Utility::generateThumbnail($filename, $m_thumb_name, $xoopsModuleConfig['thumbnail_m_width'], $angle);
            }

            if (!$is360) {
                if (!empty($xoopsModuleConfig['thumbnail_b_width']) and ($width > $xoopsModuleConfig['thumbnail_b_width'] or $height > $xoopsModuleConfig['thumbnail_b_width'])) {
                    Utility::generateThumbnail($filename, $filename, $xoopsModuleConfig['thumbnail_b_width'], $angle);
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
    global $xoopsDB, $xoopsUser, $xoopsModuleConfig;

    if (!empty($_POST['csn'])) {
        $csn = (int) $_POST['csn'];
    }

    if (!empty($_POST['new_csn'])) {
        $csn = add_tad_gallery_cate($csn, (string) $_POST['new_csn'], (int) $_POST['sort']);
    }

    $uid = $xoopsUser->uid();

    //取消上傳時間限制
    set_time_limit(0);

    //設置上傳大小
    ini_set('memory_limit', '100M');

    $files = [];
    foreach ($_FILES['upfile'] as $k => $l) {
        foreach ($l as $i => $v) {
            if (empty($v)) {
                continue;
            }

            if (!array_key_exists($i, $files)) {
                $files[$i] = [];
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

        $orginal_file_name = mb_strtolower(basename($file['name']));

        $pic = getimagesize($file['tmp_name']);
        $width = $pic[0];
        $height = $pic[1];
        $is360 = (int) $_POST['is360'];

        //讀取exif資訊
        if (function_exists('exif_read_data')) {
            $result = exif_read_data($file['tmp_name'], 0, true);
            $creat_date = $result['IFD0']['DateTime'];
            if (in_array($result['IFD0']['Model'], $Model360)) {
                $is360 = 1;
            }

            //直拍照片
            if (6 == $result['IFD0']['Orientation']) {
                $angle = 270;
            } elseif (8 == $result['IFD0']['Orientation']) {
                $angle = 90;
            }
        } else {
            $creat_date = date('Y-m-d');
        }

        $dir = (empty($creat_date) or '2' != mb_substr($creat_date, 0, 1)) ? date('Y_m_d') : str_replace([':', '-', '/'], '_', mb_substr($result['IFD0']['DateTime'], 0, 10));
        $exif = mk_exif($result);

        $now = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));
        $csn = (int) $csn;
        $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_gallery') . '` (`csn`, `title`, `description`, `filename`, `size`, `type`, `width`, `height`, `dir`, `uid`, `post_date`, `counter`, `exif`, `tag`, `good`, `photo_sort`, `is360`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        Utility::query($sql, 'isssisiisisisssis', [$csn, '', '', $file['name'], $file['size'], $file['type'], $width, $height, $dir, $uid, $now, 0, $exif, '', '0', $sort, $is360]) or Utility::web_error($sql, __FILE__, __LINE__);
        $sort++;
        //取得最後新增資料的流水編號
        $sn = $xoopsDB->getInsertId();

        Utility::mk_dir(_TADGAL_UP_FILE_DIR . $dir);
        Utility::mk_dir(_TADGAL_UP_FILE_DIR . 'small/' . $dir);
        Utility::mk_dir(_TADGAL_UP_FILE_DIR . 'medium/' . $dir);

        $filename = photo_name($sn, 'source', 1);

        if (move_uploaded_file($file['tmp_name'], $filename)) {
            $m_thumb_name = photo_name($sn, 'm', 1);
            $s_thumb_name = photo_name($sn, 's', 1);

            if ($width > $xoopsModuleConfig['thumbnail_s_width'] or $height > $xoopsModuleConfig['thumbnail_s_width']) {
                Utility::generateThumbnail($filename, $s_thumb_name, $xoopsModuleConfig['thumbnail_s_width'], $angle);
            }

            if ($width > $xoopsModuleConfig['thumbnail_m_width'] or $height > $xoopsModuleConfig['thumbnail_m_width']) {
                Utility::generateThumbnail($filename, $m_thumb_name, $xoopsModuleConfig['thumbnail_m_width'], $angle);
            }

            if (!$is360) {
                if (!empty($xoopsModuleConfig['thumbnail_b_width']) and ($width > $xoopsModuleConfig['thumbnail_b_width'] or $height > $xoopsModuleConfig['thumbnail_b_width'])) {
                    Utility::generateThumbnail($filename, $filename, $xoopsModuleConfig['thumbnail_b_width'], $angle);
                }
            }
        }
    }

    return $csn;
}

//上傳壓縮圖檔
function upload_zip_file()
{

    //取消上傳時間限制
    set_time_limit(0);

    //設置上傳大小
    ini_set('memory_limit', '100M');

    require_once __DIR__ . '/class/dunzip2/dUnzip2.inc.php';
    require_once __DIR__ . '/class/dunzip2/dZip.inc.php';
    $zip = new dUnzip2($_FILES['zipfile']['tmp_name']);
    $zip->getList();
    $zip->unzipAll(_TADGAL_UP_IMPORT_DIR);
}

//新增資料到tad_gallery中
function import_tad_gallery($csn_menu = [], $new_csn = '', $all = [], $import = [])
{
    global $xoopsDB, $xoopsUser, $xoopsModuleConfig;
    krsort($csn_menu);
    foreach ($csn_menu as $cate_sn) {
        if (empty($cate_sn)) {
            continue;
        }
        $csn = $cate_sn;
        break;
    }
    if (!empty($new_csn)) {
        $csn = add_tad_gallery_cate($csn, $new_csn);
    }
    $uid = $xoopsUser->uid();

    //處理上傳的檔案
    $sort = 0;
    foreach ($all as $i => $source_file) {
        if ('1' != $import[$i]['upload']) {
            unlink($source_file);
            continue;
        }
        $orginal_file_name = mb_strtolower(basename($import[$i]['filename'])); //get lowercase filename

        $csn = (int) $csn;
        $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_gallery') . '` ( `csn`, `title`, `description`, `filename`, `size`, `type`, `width`, `height`, `dir`, `uid`, `post_date`, `counter`, `exif`, `tag`, `good`, `photo_sort`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        Utility::query($sql, 'isssisiisisisssi', [$csn, '', '', $import[$i]['filename'], $import[$i]['size'], $import[$i]['type'], $import[$i]['width'], $import[$i]['height'], $import[$i]['dir'], $uid, $import[$i]['post_date'], '0', $import[$i]['exif'], '', '0', $sort]) or Utility::web_error($sql, __FILE__, __LINE__);
        $sort++;
        //取得最後新增資料的流水編號
        $sn = $xoopsDB->getInsertId();

        set_time_limit(0);

        Utility::mk_dir(_TADGAL_UP_FILE_DIR . $import[$i]['dir']);
        Utility::mk_dir(_TADGAL_UP_FILE_DIR . 'small/' . $import[$i]['dir']);
        Utility::mk_dir(_TADGAL_UP_FILE_DIR . 'medium/' . $import[$i]['dir']);

        $filename = photo_name($sn, 'source', 1);
        if (rename($source_file, $filename)) {
            $m_thumb_name = photo_name($sn, 'm', 1);
            $s_thumb_name = photo_name($sn, 's', 1);

            if ($import[$i]['width'] > $xoopsModuleConfig['thumbnail_s_width'] or $import[$i]['height'] > $xoopsModuleConfig['thumbnail_s_width']) {
                Utility::generateThumbnail($filename, $s_thumb_name, $xoopsModuleConfig['thumbnail_s_width'], $import[$i]['angle']);
            }
            if ($import[$i]['width'] > $xoopsModuleConfig['thumbnail_m_width'] or $import[$i]['height'] > $xoopsModuleConfig['thumbnail_m_width']) {
                Utility::generateThumbnail($filename, $m_thumb_name, $xoopsModuleConfig['thumbnail_m_width'], $import[$i]['angle']);
            }
            if (!empty($xoopsModuleConfig['thumbnail_b_width']) and ($import[$i]['width'] > $xoopsModuleConfig['thumbnail_b_width'] or $import[$i]['height'] > $xoopsModuleConfig['thumbnail_b_width'])) {
                Utility::generateThumbnail($filename, $filename, $xoopsModuleConfig['thumbnail_b_width'], $import[$i]['angle']);
            }
        } else {
            $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_gallery') . '` WHERE `sn`=?';
            Utility::query($sql, 'i', [$sn]);
            redirect_header($_SERVER['PHP_SELF'], 5, sprintf(_MD_TADGAL_IMPORT_IMPORT_ERROR, $source_file, $filename));
        }
    }
    Utility::rrmdir(_TADGAL_UP_IMPORT_DIR);

    return $csn;
}

//讀取目錄下圖片
function read_dir_pic($main_dir = '')
{
    global $xoopsDB, $Model360;
    $pics = '';
    $post_max_size = ini_get('post_max_size');
    //$size_limit=intval($post_max_size) * 0.5  * 1024 * 1024;

    if ('/' !== mb_substr($main_dir, -1)) {
        $main_dir = $main_dir . '/';
    }

    if ($dh = opendir($main_dir)) {
        $total_size = 0;
        $i = 1;
        while (false !== ($file = readdir($dh))) {
            if ('.' === mb_substr($file, 0, 1)) {
                continue;
            }

            if (is_dir($main_dir . $file)) {
                $pic = read_dir_pic($main_dir . $file);
                $pics .= $pic['pics'];
                $total_size += $pic['total_size'];
            } else {
                //若需要轉方向的話
                $angle = 0;

                //讀取exif資訊
                $result = exif_read_data($main_dir . $file, 0, true);
                $creat_date = $result['IFD0']['DateTime'];
                $dir = (empty($creat_date) or '2' != mb_substr($creat_date, 0, 1)) ? date('Y_m_d') : str_replace(':', '_', mb_substr($result['IFD0']['DateTime'], 0, 10));
                if (in_array($result['IFD0']['Model'], $Model360)) {
                    $is360 = 1;
                }

                //直拍照片
                if (6 == $result['IFD0']['Orientation']) {
                    $angle = 270;
                } elseif (8 == $result['IFD0']['Orientation']) {
                    $angle = 90;
                }
                $exif = mk_exif($result);

                $size = filesize($main_dir . $file);

                $total_size += (int) $size;

                $size_txt = sizef($size);
                $pic = getimagesize($main_dir . $file);
                $width = $pic[0];
                $height = $pic[1];

                $subname = mb_strtolower(mb_substr($file, -3));
                if ('jpg' === $subname or 'peg' === $subname) {
                    $type = 'image/jpeg';
                } elseif ('png' === $subname) {
                    $type = 'image/png';
                } elseif ('gif' === $subname) {
                    $type = 'image/gif';
                } else {
                    $type = $subname;
                    continue;
                }

                $sql = 'SELECT `width`,`height` FROM `' . $xoopsDB->prefix('tad_gallery') . '` WHERE `filename`=? AND `size`=?';
                $result = Utility::query($sql, 'ss', [$file, $size]) or Utility::web_error($sql, __FILE__, __LINE__);
                list($db_width, $db_height) = $xoopsDB->fetchRow($result);
                if ($db_width == $width and $db_height == $height) {
                    $checked = "disabled='disabled'";
                    $upload = '0';
                    $status = _MD_TADGAL_IMPORT_EXIST;
                } else {
                    $checked = 'checked';
                    $upload = '1';
                    $status = $type;
                }

                if (_CHARSET === 'UTF-8') {
                    $file = Utility::to_utf8($file);
                }

                $pics .= "
                <tr>
                    <td style='font-size: 0.8em'>$i</td>
                    <td style='font-size: 0.8em'>
                        <input type='hidden' name='all[$i]' value='" . $main_dir . $file . "'>
                        <input type='checkbox' name='import[$i][upload]' value='1' $checked>
                        {$file}
                        <input type='hidden' name='import[$i][filename]' value='{$file}'></td>
                    <td style='font-size: 0.8em'>$dir<input type='hidden' name='import[$i][dir]' value='{$dir}'></td>
                    <td style='font-size: 0.8em'>$width x $height
                        <input type='hidden' name='import[$i][post_date]' value='{$creat_date}'>
                        <input type='hidden' name='import[$i][width]' value='{$width}'>
                        <input type='hidden' name='import[$i][height]' value='{$height}'>
                        <input type='hidden' name='import[$i][angle]' value='{$angle}'>
                    </td>
                    <td style='font-size: 0.8em'>$size_txt<input type='hidden' name='import[$i][size]' value='{$size}'></td>
                    <td style='font-size: 0.8em'>{$status}
                        <input type='hidden' name='import[$i][exif]' value='{$exif}'>
                        <input type='hidden' name='import[$i][type]' value='{$type}'>
                    </td>
                </tr>";
                $i++;
            }
        }
        closedir($dh);
    }
    $main['pics'] = $pics;
    $main['total_size'] = $total_size;

    return $main;
}
