<?php
use XoopsModules\Tadtools\EasyResponsiveTabs;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
require_once 'header.php';
$xoopsOption['template_main'] = 'tadgallery_upload.tpl';

if ((!empty($upload_powers) and $xoopsUser) or $isAdmin) {
    require XOOPS_ROOT_PATH . '/header.php';
} else {
    redirect_header(XOOPS_URL . '/user.php', 3, _TADGAL_NO_UPLOAD_POWER);
}

/*-----------function區--------------*/

function uploads_tabs($def_csn = '')
{
    global $xoopsTpl, $xoopsModuleConfig;

    $xoopsTpl->assign('tad_gallery_form', tad_gallery_form());
    $xoopsTpl->assign('def_csn', $def_csn);

    $EasyResponsiveTabs = new EasyResponsiveTabs('#photosTab');
    $EasyResponsiveTabs->rander();
    import_form();
}

//tad_gallery編輯表單
function tad_gallery_form($sn = '')
{
    global $xoopsDB, $xoopsTpl;

    //抓取預設值
    if (!empty($sn)) {
        $DBV = Tadgallery::get_tad_gallery($sn);
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
    global $xoopsDB, $xoopsUser, $xoopsModuleConfig, $type_to_mime;
    krsort($_POST['csn_menu']);
    foreach ($_POST['csn_menu'] as $cate_sn) {
        $cate_sn = (int) $cate_sn;
        if (empty($cate_sn)) {
            continue;
        }
        $csn = $cate_sn;
        break;
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

        $orginal_file_name = mb_strtolower(basename($_FILES['image']['name'])); //get lowercase filename
        $file_ending = mb_substr(mb_strtolower($orginal_file_name), -3); //file extension

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

        $sql = 'insert into ' . $xoopsDB->prefix('tad_gallery') . " (
        `csn`, `title`, `description`, `filename`, `size`, `type`, `width`, `height`, `dir`, `uid`, `post_date`, `counter`, `exif`, `tag`, `good`, `photo_sort`,`is360`) values('{$csn}','{$_POST['title']}','{$_POST['description']}','{$_FILES['image']['name']}','{$_FILES['image']['size']}','{$_FILES['image']['type']}','{$width}','{$height}','{$dir}','{$uid}','{$now}','0','{$exif}','','0',0,'{$is360}')";

        $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
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
        }
        $csn = $cate_sn;
        break;
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

        $orginal_file_name = mb_strtolower(basename($file['name'])); //get lowercase filename
        $file_ending = mb_substr(mb_strtolower($orginal_file_name), -3); //file extension

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

        $dir = (empty($creat_date) or '2' != mb_substr($creat_date, 0, 1)) ? date('Y_m_d') : str_replace(':', '_', mb_substr($result['IFD0']['DateTime'], 0, 10));
        $exif = mk_exif($result);

        $now = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));
        $csn = (int) $csn;
        $sql = 'insert into ' . $xoopsDB->prefix('tad_gallery') . "
        (`csn`, `title`, `description`, `filename`, `size`, `type`, `width`, `height`, `dir`, `uid`, `post_date`, `counter`, `exif`, `tag`, `good`, `photo_sort`,`is360`)
        values('{$csn}','','','{$file['name']}','{$file['size']}','{$file['type']}','{$width}','{$height}','{$dir}','{$uid}','{$now}','0','{$exif}','','0', $sort, '{$is360}')";
        $sort++;
        $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
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

    require_once __DIR__ . '/class/dunzip2/dUnzip2.inc.php';
    require_once __DIR__ . '/class/dunzip2/dZip.inc.php';
    $zip = new dUnzip2($_FILES['zipfile']['tmp_name']);
    $zip->getList();
    $zip->unzipAll(_TADGAL_UP_IMPORT_DIR);
}

//新增資料到tad_gallery中
function import_tad_gallery($csn_menu = [], $new_csn = '', $all = [], $import = [])
{
    global $xoopsDB, $xoopsUser, $xoopsModuleConfig, $type_to_mime;
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
    $uid = $xoopsUser->getVar('uid');

    if (!empty($csn)) {
        $_SESSION['tad_gallery_csn'] = $csn;
    }

    //處理上傳的檔案
    $sort = 0;
    foreach ($all as $i => $source_file) {
        if ('1' != $import[$i]['upload']) {
            unlink($source_file);
            continue;
        }
        $orginal_file_name = mb_strtolower(basename($import[$i]['filename'])); //get lowercase filename
        $file_ending = mb_substr(mb_strtolower($orginal_file_name), -3); //file extension
        $csn = (int) $csn;
        $sql = 'insert into ' . $xoopsDB->prefix('tad_gallery') . " (
        `csn`, `title`, `description`, `filename`, `size`, `type`, `width`, `height`, `dir`, `uid`, `post_date`, `counter`, `exif`, `tag`, `good`, `photo_sort`) values('{$csn}','','','{$import[$i]['filename']}','{$import[$i]['size']}','{$import[$i]['type']}','{$import[$i]['width']}','{$import[$i]['height']}','{$import[$i]['dir']}','{$uid}','{$import[$i]['post_date']}','0','{$import[$i]['exif']}','','0',$sort)";
        $sort++;
        $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
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
                thumbnail($filename, $s_thumb_name, $type_to_mime[$file_ending], $xoopsModuleConfig['thumbnail_s_width'], $import[$i]['angle']);
            }
            if ($import[$i]['width'] > $xoopsModuleConfig['thumbnail_m_width'] or $import[$i]['height'] > $xoopsModuleConfig['thumbnail_m_width']) {
                thumbnail($filename, $m_thumb_name, $type_to_mime[$file_ending], $xoopsModuleConfig['thumbnail_m_width'], $import[$i]['angle']);
            }
            if (!empty($xoopsModuleConfig['thumbnail_b_width']) and ($import[$i]['width'] > $xoopsModuleConfig['thumbnail_b_width'] or $import[$i]['height'] > $xoopsModuleConfig['thumbnail_b_width'])) {
                thumbnail($filename, $filename, $type_to_mime[$file_ending], $xoopsModuleConfig['thumbnail_b_width'], $import[$i]['angle']);
            }
        } else {
            $sql = 'delete from ' . $xoopsDB->prefix('tad_gallery') . " where sn='$sn'";
            $xoopsDB->query($sql);
            redirect_header($_SERVER['PHP_SELF'], 5, sprintf(_MD_TADGAL_IMPORT_IMPORT_ERROR, $source_file, $filename));
        }
    }
    Utility::rrmdir(_TADGAL_UP_IMPORT_DIR);

    return $csn;
}

//tad_gallery編輯表單
function import_form()
{
    global $xoopsDB, $xoopsTpl;

    $myts = \MyTextSanitizer::getInstance();

    //找出要匯入的圖
    if (is_dir(_TADGAL_UP_IMPORT_DIR)) {
        $pics = read_dir_pic(_TADGAL_UP_IMPORT_DIR);
        $total_size = sizef($pics['total_size']);
    }

    $post_max_size = ini_get('post_max_size');
    //$max_input_vars=ini_get('max_input_vars');

    //預設值設定
    $main = "
    <script type='text/javascript'>

        $(document).ready(function(){
            make_option('b_csn_menu',0,0,0);
        });

        function make_option(menu_name , num , of_csn , def_csn){
            $('#'+menu_name+num).show();
            $.post('ajax_menu.php',  {'of_csn': of_csn , 'def_csn': def_csn} , function(data) {
            $('#'+menu_name+num).html(\"<option value=''>/</option>\"+data);
            });

            $('.'+menu_name).change(function(){
            var menu_id= $(this).attr('id');
            var len=menu_id.length-1;
            var next_num = Number(menu_id.charAt(len))+1
            var next_menu = menu_name + next_num;
            $.post('ajax_menu.php',  {'of_csn': $('#'+menu_id).val()} , function(data) {
                if(data==''){
                $('#'+next_menu).hide();
                }else{
                $('#'+next_menu).show();
                $('#'+next_menu).html(\"<option value=''>/</option>\"+data);
                }

            });
            });
        }
    </script>
    <div class='alert alert-info'>
        " . _MD_TADGAL_IMPORT_UPLOAD_TO . "
        <span style='color: #8C288C;'>" . _TADGAL_UP_IMPORT_DIR . "</span>
    </div>

    <form action='" . XOOPS_URL . "/modules/tadgallery/uploads.php' method='post' id='myForm' class='form-horizontal' role='form'>
        <input type='hidden' name='op' value='import_tad_gallery'>

        <div class='form-group row'>
            <label class='col-sm-2 control-label col-form-label text-sm-right'>" . _MD_TADGAL_IMPORT_CSN . "</label>
            <div class='col-sm-10 controls'>
                <select name='csn_menu[0]' id='b_csn_menu0' class='b_csn_menu'><option value=''></option></select>
                <select name='csn_menu[1]' id='b_csn_menu1' class='b_csn_menu' style='display: none;'></select>
                <select name='csn_menu[2]' id='b_csn_menu2' class='b_csn_menu' style='display: none;'></select>
                <select name='csn_menu[3]' id='b_csn_menu3' class='b_csn_menu' style='display: none;'></select>
                <select name='csn_menu[4]' id='b_csn_menu4' class='b_csn_menu' style='display: none;'></select>
                <select name='csn_menu[5]' id='b_csn_menu5' class='b_csn_menu' style='display: none;'></select>
                <select name='csn_menu[6]' id='b_csn_menu6' class='b_csn_menu' style='display: none;'></select>
                <input type='text' name='new_csn' placeholder='" . _MD_TADGAL_NEW_CSN . "' style='width: 200px;'>
            </div>
        </div>

        <table class='table table-striped'>
            <tr>
                <th></th>
                <th>" . _MD_TADGAL_IMPORT_FILE . '</th>
                <th>' . _MD_TADGAL_IMPORT_DIR . '</th>
                <th>' . _MD_TADGAL_IMPORT_DIMENSION . '</th>
                <th>' . _MD_TADGAL_IMPORT_SIZE . '</th>
                <th>' . _MD_TADGAL_IMPORT_STATUS . "</th>
            </tr>
            {$pics['pics']}
            <tr>
                <td colspan='6'>
                    <button type='submit' class='btn btn-primary'>" . _MD_TADGAL_UP_IMPORT . '</button>
                </td>
            </tr>
        </table>
    </form>';
    $xoopsTpl->assign('import_form', $main);
    return $main;
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

                $sql = 'select width,height from ' . $xoopsDB->prefix('tad_gallery') . " where filename='{$file}' and size='{$size}'";
                $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
                list($db_width, $db_height) = $xoopsDB->fetchRow($result);
                if ($db_width == $width and $db_height == $height) {
                    $checked = "disabled='disabled'";
                    $upload = '0';
                    $status = _MD_TADGAL_IMPORT_EXIST;
                    //}elseif($total_size >= $size_limit){
                    // $checked="disabled='disabled'";
                    // $upload="1";
                    // $status=sprintf(_MD_TADGAL_IMPORT_OVER_SIZE,sizef($total_size),$post_max_size);
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

/*-----------執行動作判斷區----------*/
require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$sn = system_CleanVars($_REQUEST, 'sn', 0, 'int');
$csn = system_CleanVars($_REQUEST, 'csn', 0, 'int');
$csn_menu = system_CleanVars($_REQUEST, 'csn_menu', '', 'array');
$new_csn = system_CleanVars($_REQUEST, 'new_csn', '', 'string');

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
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
require_once XOOPS_ROOT_PATH . '/footer.php';
