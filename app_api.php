<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;
require_once __DIR__ . '/header.php';
$tadgallery = new Tadgallery();

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$cate = Request::getInt('cate');
$nsn = Request::getInt('nsn');
$fsn = Request::getInt('fsn');
$of_csn = Request::getInt('of_csn');

header("Content-Type: application/json; charset=utf-8");
switch ($op) {
    case 'get_data':
        die(json_encode(get_data($cate), 256));

    case 'get_cates':
        die(json_encode(get_cates($cate), 256));

}

//列出所有tad_photos資料
function get_data($csn = 0)
{
    global $tadgallery;

    $tadgallery->set_only_enable();
    $tadgallery->set_only_thumb(true);
    $tadgallery->set_view_csn($csn);
    $data['photos'] = $tadgallery->get_photos(0, 'app');
    $data['cates'] = get_cates($csn);

    return $data;
}

function get_cates($of_csn = 0)
{
    global $tadgallery, $xoopsDB;

    $cate = $all_cates = [];

    $cate_count = $tadgallery->get_tad_gallery_cate_count();
    $of_csn = (int) $of_csn;
    $sql = 'SELECT csn, of_csn, title, sort, cover FROM ' . $xoopsDB->prefix('tad_gallery_cate') . " WHERE of_csn='$of_csn' and enable='1' and (enable_group='' or enable_group like '%3%') ORDER BY sort";
    $result = $xoopsDB->query($sql);
    while (list($csn, $of_csn, $title, $sort, $cover) = $xoopsDB->fetchRow($result)) {
        if (empty($cover)) {
            $cover = $tadgallery->random_cover($csn);
        } elseif (strpos('http', $cover) === false) {
            if ('small' == substr($cover, 0, 5)) {
                $m_cover = str_replace(['small/', '_s_'], ['medium/', '_m_'], $cover);
                // $cover_path = XOOPS_ROOT_PATH . '/uploads/tadgallery/' . $m_cover;
                if (file_exists(XOOPS_ROOT_PATH . '/uploads/tadgallery/' . $m_cover)) {
                    $cover = $m_cover;
                }
            }
            $cover = XOOPS_URL . '/uploads/tadgallery/' . $cover;
        }

        $cate['csn'] = (int) $csn;
        $cate['title'] = $title;
        $cate['of_csn'] = (int) $of_csn;
        $cate['dir_count'] = (int) $cate_count[$csn]['dir'];
        $cate['file_count'] = (int) $cate_count[$csn]['file'];
        $cate['sort'] = (int) $sort;
        $cate['cover'] = $cover;
        // $cate['old_ccover_pathover'] = $cover_path;
        $cate['url'] = XOOPS_URL . "/modules/tadgallery/index.php?csn={$csn}";
        $all_cates[] = $cate;
    }

    return $all_cates;
}
