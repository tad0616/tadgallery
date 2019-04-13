<?php
/*-----------引入檔案區--------------*/
include_once 'header.php';
$xoopsOption['template_main'] = 'tadgallery_cooliris.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';

/*-----------function區--------------*/

include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$csn = system_CleanVars($_REQUEST, 'csn', 0, 'int');

$xoopsTpl->assign('csn', $csn);
$xoopsTpl->assign('up_file_url', _TADGAL_UP_FILE_URL);
list_tad_gallery_cate_tree($csn);

//列出所有tad_gallery_cate資料
function list_tad_gallery_cate_tree($def_csn = '')
{
    global $xoopsDB, $xoopsTpl;

    $tadgallery = new tadgallery();
    $cate_count = $tadgallery->get_tad_gallery_cate_count();
    $path = get_tadgallery_cate_path($def_csn);
    $path_arr = array_keys($path);

    $sql = 'SELECT csn,of_csn,title FROM ' . $xoopsDB->prefix('tad_gallery_cate') . ' ORDER BY sort';
    $result = $xoopsDB->query($sql) or web_error($sql, __FILE__, __LINE__);
    while (list($csn, $of_csn, $title) = $xoopsDB->fetchRow($result)) {
        $font_style = $def_csn == $csn ? ", font:{'background-color':'yellow', 'color':'black'}" : '';
        $open = in_array($csn, $path_arr, true) ? 'true' : 'false';
        $display_counter = empty($cate_count[$csn]['file']) ? '' : " ({$cate_count[$csn]['file']})";
        $data[] = "{ id:{$csn}, pId:{$of_csn}, name:'{$title}{$display_counter}', url:'cooliris.php?csn={$csn}', open: {$open} ,target:'_self' {$font_style}}";
    }

    $json = implode(",\n", $data);

    if (!file_exists(XOOPS_ROOT_PATH . '/modules/tadtools/ztree.php')) {
        redirect_header('index.php', 3, _MA_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . '/modules/tadtools/ztree.php';
    $ztree = new ztree('album_tree', $json, '', '', 'of_csn', 'csn');
    $ztree_code = $ztree->render();
    $xoopsTpl->assign('ztree_code', $ztree_code);

    return $data;
}

/*-----------秀出結果區--------------*/

$xoopsTpl->assign('toolbar', toolbar_bootstrap($interface_menu));
get_jquery(true);

//路徑選單

$arr = get_tadgallery_cate_path($csn);
$path = tad_breadcrumb($csn, $arr, 'index.php', 'csn', 'title');
$xoopsTpl->assign('path', $path);

include_once XOOPS_ROOT_PATH . '/footer.php';
