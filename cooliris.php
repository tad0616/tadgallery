<?php
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tadtools\Ztree;


/*-----------引入檔案區--------------*/
require_once 'header.php';
$xoopsOption['template_main'] = 'tadgallery_cooliris.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

/*-----------function區--------------*/

require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$csn = system_CleanVars($_REQUEST, 'csn', 0, 'int');

$xoopsTpl->assign('csn', $csn);
$xoopsTpl->assign('up_file_url', _TADGAL_UP_FILE_URL);
list_tad_gallery_cate_tree($csn);

//列出所有tad_gallery_cate資料
function list_tad_gallery_cate_tree($def_csn = '')
{
    global $xoopsDB, $xoopsTpl;
    require_once XOOPS_ROOT_PATH . '/modules/tadgallery/class/Tadgallery.php';
    $tadgallery = new Tadgallery();
    $cate_count = $tadgallery->get_tad_gallery_cate_count();
    $path = get_tadgallery_cate_path($def_csn);
    $path_arr = array_keys($path);

    $sql = 'SELECT csn,of_csn,title FROM ' . $xoopsDB->prefix('tad_gallery_cate') . ' ORDER BY sort';
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    while (list($csn, $of_csn, $title) = $xoopsDB->fetchRow($result)) {
        $csn = (int) $csn;
        $of_csn = (int) $of_csn;

        $font_style = $def_csn == $csn ? ", font:{'background-color':'yellow', 'color':'black'}" : '';
        $open = in_array($csn, $path_arr) ? 'true' : 'false';
        $display_counter = empty($cate_count[$csn]['file']) ? '' : " ({$cate_count[$csn]['file']})";
        $data[] = "{ id:{$csn}, pId:{$of_csn}, name:'{$title}{$display_counter}', url:'cooliris.php?csn={$csn}', open: {$open} ,target:'_self' {$font_style}}";
    }

    $json = implode(",\n", $data);

    $ztree = new Ztree('album_tree', $json, '', '', 'of_csn', 'csn');
    $ztree_code = $ztree->render();
    $xoopsTpl->assign('ztree_code', $ztree_code);

    return $data;
}

/*-----------秀出結果區--------------*/

$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
Utility::get_jquery(true);

//路徑選單

$arr = get_tadgallery_cate_path($csn);
$path = Utility::tad_breadcrumb($csn, $arr, 'index.php', 'csn', 'title');
$xoopsTpl->assign('path', $path);

require_once XOOPS_ROOT_PATH . '/footer.php';
