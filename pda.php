<?php
/*-----------引入檔案區--------------*/
require_once dirname(dirname(__DIR__)) . '/mainfile.php';
require_once __DIR__ . '/function.php';

/*-----------function區--------------*/
function show_album($csn)
{
    global $xoopsDB;

    $tadgallery = new tadgallery();
    $tadgallery->set_view_csn($csn);
    $album_arr = $tadgallery->get_albums('return');
    $data = '';
    foreach ($album_arr as $key => $value) {
        if ($value['album_lock']) {
            $data .= "<a href='#' class='album{$value['csn']} password-modal{$csn}' data-csn='{$value['csn']}'><img src='{$value['cover_pic']}' alt='{$value['title']}'></a>\n";
        } else {
            $data .= "<a href='pda.php?csn={$value['csn']}' class='album{$value['csn']}' data-csn='{$value['csn']}'><img src='{$value['cover_pic']}' alt='{$value['title']}'></a>\n";
        }
    }

    return $data;
}

function show_photo($csn, $passwd)
{
    global $xoopsDB;

    $csn = (int) $csn;

    //以流水號取得某筆tad_gallery_cate資料
    $cate = tadgallery::get_tad_gallery_cate($csn);

    //可觀看相簿
    $ok_cat = tadgallery::chk_cate_power();

    //密碼檢查
    if (!empty($csn)) {
        if (empty($passwd) and !empty($_SESSION['tadgallery'][$csn])) {
            $passwd = $_SESSION['tadgallery'][$csn];
        }

        $sql = 'select csn,passwd from ' . $xoopsDB->prefix('tad_gallery_cate') . " where csn='{$csn}'";
        $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
        list($ok_csn, $ok_passwd) = $xoopsDB->fetchRow($result);
        if (!empty($ok_csn) and $ok_passwd != $passwd) {
            header("location: {$_SERVER['PHP_SELF']}");
            exit;
        }

        if (!empty($ok_passwd) and empty($_SESSION['tadgallery'][$csn])) {
            $_SESSION['tadgallery'][$csn] = $passwd;
        }

        //檢查相簿觀看權限
        if (!in_array($csn, $ok_cat)) {
            header("location: {$_SERVER['PHP_SELF']}");
            exit;
        }
    }

    $num = empty($_POST['n']) ? 10 : (int) $_POST['n'];
    $p = empty($_POST['p']) ? 0 : (int) $_POST['p'];
    $start = $p * $num;

    $sql = 'select * from ' . $xoopsDB->prefix('tad_gallery') . " where `csn`='{$csn}' order by `photo_sort` , `post_date` limit {$start},{$num}";
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);

    $data = '';
    while (false !== (list($sn, $db_csn, $title, $description, $filename, $size, $type, $width, $height, $dir, $uid, $post_date, $counter, $exif, $tag, $good, $photo_sort, $is360) = $xoopsDB->fetchRow($result))) {
        if ($is360) {
            $data .= "<a href='javascript:;' data-src='360.php?sn={$sn}&file=" . tadgallery::get_pic_url($dir, $sn, $filename, 'l') . "' class='gallery360'><img src='" . tadgallery::get_pic_url($dir, $sn, $filename, 's') . "' alt='{$title}'></a>\n";
        } else {
            $data .= "<a href='" . tadgallery::get_pic_url($dir, $sn, $filename, 'm') . "' data-fancybox='gallery'><img src='" . tadgallery::get_pic_url($dir, $sn, $filename, 's') . "' alt='{$title}'></a>\n";
        }
    }

    return $data;
}

function passwd_check_json($csn, $passwd)
{
    global $xoopsDB;

    $csn = (int) $csn;

    //以流水號取得某筆tad_gallery_cate資料
    $cate = tadgallery::get_tad_gallery_cate($csn);

    //可觀看相簿
    $ok_cat = tadgallery::chk_cate_power();

    //密碼檢查
    if (empty($passwd) and !empty($_SESSION['tadgallery'][$csn])) {
        $passwd = $_SESSION['tadgallery'][$csn];
    }

    $sql = 'select csn,passwd from ' . $xoopsDB->prefix('tad_gallery_cate') . " where csn='{$csn}'";
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);

    list($ok_csn, $ok_passwd) = $xoopsDB->fetchRow($result);
    if (!empty($ok_csn) and $ok_passwd != $passwd) {
        $output = json_encode(['type' => 'error', 'text' => sprintf(_TADGAL_NO_PASSWD_CONTENT, $cate['title'])]);
        die($output);
    }

    //檢查相簿觀看權限
    if (!in_array($csn, $ok_cat)) {
        $output = json_encode(['type' => 'error', 'text' => _TADGAL_NO_POWER_TITLE, sprintf(_TADGAL_NO_POWER_CONTENT, $cate['title'], $select)]);
        die($output);
    }

    if (!empty($ok_csn) and $ok_passwd == $passwd) {
        $_SESSION['tadgallery'][$csn] = $passwd;
        $output = json_encode(['type' => 'success', 'text' => 'success']);
        die($output);
    }
}

/*-----------執行動作判斷區----------*/
require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$sn = system_CleanVars($_REQUEST, 'sn', 0, 'int');
$csn = system_CleanVars($_REQUEST, 'csn', 0, 'int');
$passwd = system_CleanVars($_REQUEST, 'passwd', '', 'string');
$module_name = $xoopsModule->getVar('name');

switch ($op) {
    case 'load_more':
        $main = show_photo($csn, $passwd);
        echo $main;
        exit;

    case 'check':
        $main = passwd_check_json($csn, $passwd);
        echo $main;
        exit;

    default:
        if (0 == $csn) {
            $album = show_album($csn);
            $main = "
            <div class='navbar layout-dark theme-white'>
                <div class='navbar-inner' data-page='index'>
                    <div class='left'></div>
                    <div class='center sliding'>{$module_name}</div>
                    <div class='right'></div>
                </div>
            </div>
            <div class='pages navbar-through'>
                <div data-page='index' class='page'>
                    <div class='page-content'>
                        <div id='album{$csn}' style='margin-top:3px;'>{$album}</div>
                    </div>
                </div>
            </div>
            ";
        } else {
            $get_cate = tadgallery::get_tad_gallery_cate($csn);
            $title = $get_cate['title'];
            $album = show_album($csn);
            $photo = show_photo($csn, $passwd);
            $album_wrap = (empty($album)) ? '' : "<div id='album{$csn}' style='margin-top:3px;'>{$album}</div>";
            $photo_wrap = (empty($photo)) ? '' : "<div id='photo{$csn}' style='margin-top:3px;'>{$photo}</div>";
            $main = "
            <div class='navbar layout-dark theme-white'>
                <div class='navbar-inner' data-page='cate'>
                    <div class='left'>
                        <a href='pda.php' class='back link icon-only'>
                            <i class='icon icon-back'></i>
                        </a>
                    </div>
                    <div class='center sliding'>{$title}</div>
                    <div class='right'></div>
                </div>
            </div>
            <div class='pages navbar-through'>
                <div data-page='cate' class='page cate{$csn}'>
                    <div class='page-content infinite-scroll'>
                        {$album_wrap}
                        {$photo_wrap}
                        <div id='load-area{$csn}'></div>
                        <!-- Preloader -->
                        <div class='infinite-scroll-preloader'>
                            <div class='preloader'></div>
                        </div>
                    </div>
                </div>
            </div>
            ";
        }
        break;
}

/*-----------秀出結果區--------------*/
echo "
<!DOCTYPE html>
<html lang='" . _LANGCODE . "'>

<head>
    <meta charset='" . _CHARSET . "'>
    <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui'>
    <meta name='apple-mobile-web-app-capable' content='yes'>
    <meta name='apple-mobile-web-app-status-bar-style' content='black'>
    <title>{$module_name}</title>
    <link rel='stylesheet' href='" . XOOPS_URL . "/modules/tadtools/framework7/css/framework7.ios.min.css'>
    <link rel='stylesheet' href='" . XOOPS_URL . "/modules/tadtools/framework7/css/framework7.ios.colors.min.css'>
    <link rel='stylesheet' href='" . XOOPS_URL . "/modules/tadgallery/class/fancybox/jquery.fancybox.min.css'>
    <link rel='stylesheet' href='" . XOOPS_URL . "/modules/tadgallery/class/justifiedGallery/justifiedGallery.min.css'>
    <link rel='stylesheet' href='" . XOOPS_URL . "/modules/tadtools/framework7/css/my-app.css'>
</head>

<body>
    <!-- Views -->
    <div class='views'>
        <div class='view view-main'>
        {$main}
        </div>
    </div>
    <!-- Framework7 Library JS-->
    <script type='text/javascript' src='" . XOOPS_URL . "/modules/tadtools/jquery/jquery-1.11.1.min.js'></script>
    <script type='text/javascript' src='" . XOOPS_URL . "/modules/tadtools/framework7/js/framework7.min.js'></script>
    <script type='text/javascript' src='" . XOOPS_URL . "/modules/tadgallery/class/fancybox/jquery.fancybox.min.js'></script>
    <script type='text/javascript' src='" . XOOPS_URL . "/modules/tadgallery/class/justifiedGallery/jquery.justifiedGallery.min.js'></script>
    <script type='text/javascript' src='" . XOOPS_URL . "/modules/tadtools/framework7/js/my-app.js'></script>
</body>

</html>
";
