<?php
include_once 'header.php';

include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$csn_menu = system_CleanVars($_REQUEST, 'csn_menu', '', 'array');
$csn = system_CleanVars($_REQUEST, 'csn', 0, 'int');
$new_csn = system_CleanVars($_REQUEST, 'new_csn', '', 'string');

switch ($op) {
    case 'import_tad_gallery':
        //die('bbb');
        //$_POST['all'][$i]=_TADGAL_UP_IMPORT_DIR.$filename;
        //$import[$i]['upload']=1
        //$import[$i][filename]=filename
        //$import[$i][dir]=dir
        //$import[$i][post_date]
        //$import[$i][width]
        //$import[$i][height]
        //$import[$i][size]
        //$import[$i][exif]
        //$import[$i][type]
        $csn = import_tad_gallery($csn_menu, $new_csn, $_POST['all'], $_POST['import']);
        mk_rss_xml();
        mk_rss_xml($csn);
        header("location: index.php?csn=$csn");
        exit;

    default:
        echo import_form();
        break;
}

//tad_gallery編輯表單
function import_form()
{
    global $xoopsDB;

    $myts = MyTextSanitizer::getInstance();

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

    <form action='" . XOOPS_URL . "/modules/tadgallery/import.php' method='post' id='myForm' class='form-horizontal' role='form'>
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
                if (in_array($result['IFD0']['Model'], $Model360, true)) {
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
                $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
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
                    $checked = "checked='checked'";
                    $upload = '1';
                    $status = $type;
                }

                if (_CHARSET === 'UTF-8') {
                    $file = to_utf8($file);
                }

                $pics .= "
                <tr>
                    <td style='font-size:11px'>$i</td>
                    <td style='font-size:11px'>
                        <input type='hidden' name='all[$i]' value='" . $main_dir . $file . "'>
                        <input type='checkbox' name='import[$i][upload]' value='1' $checked>
                        {$file}
                        <input type='hidden' name='import[$i][filename]' value='{$file}'></td>
                    <td style='font-size:11px'>$dir<input type='hidden' name='import[$i][dir]' value='{$dir}'></td>
                    <td style='font-size:11px'>$width x $height
                        <input type='hidden' name='import[$i][post_date]' value='{$creat_date}'>
                        <input type='hidden' name='import[$i][width]' value='{$width}'>
                        <input type='hidden' name='import[$i][height]' value='{$height}'>
                        <input type='hidden' name='import[$i][angle]' value='{$angle}'>
                    </td>
                    <td style='font-size:11px'>$size_txt<input type='hidden' name='import[$i][size]' value='{$size}'></td>
                    <td style='font-size:11px'>{$status}
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
        $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
        //取得最後新增資料的流水編號
        $sn = $xoopsDB->getInsertId();

        set_time_limit(0);

        mk_dir(_TADGAL_UP_FILE_DIR . $import[$i]['dir']);
        mk_dir(_TADGAL_UP_FILE_DIR . 'small/' . $import[$i]['dir']);
        mk_dir(_TADGAL_UP_FILE_DIR . 'medium/' . $import[$i]['dir']);

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
    rrmdir(_TADGAL_UP_IMPORT_DIR);

    return $csn;
}
