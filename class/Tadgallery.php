<?php

namespace XoopsModules\Tadgallery;

use XoopsModules\Tadgallery\Tools;
use XoopsModules\Tadtools\ColorBox;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;

//TadGallery物件
/*
$this->set_view_csn($csn="");               //設定欲觀看分類 $csn=int
$this->set_show_uid($uid="");               //設定僅顯示某人上傳的照片
$this->set_only_thumb(false);               //選擇相簿時，一併是否只顯示相片，而不顯示相簿。
$this->set_only_album();                    //只抓取相簿
$this->set_only_enable();                   //只抓取有啟用的相簿
$this->set_show_mode($show_mode="");        //設定相簿顯示方式 $show_mode=normal,flickr,waterfall
$this->set_admin_mode(false);               //管理員模式（不需密碼）
$this->set_orderby($orderby="photo_sort");  //排序模式 post_date, photo_sort, rand, counter
$this->set_order_desc($desc);               //升降排序
$this->set_limit($limit);                   //設定顯示數量
$this->set_display2fancybox($class);        //設定縮圖顯示方式 true 顯示於燈箱中

$this->set_view_good(false);                //精選照片模式
$this->get_tad_gallery_cate_count($gallery_list_mode);        //取得分類下的圖片數及目錄數

$this->get_albums('return');                //取得相簿
$this->get_photos($include_sub=0);                //取得照片
 */

class Tadgallery
{
    //var $now;
    //var $today;
    public $view_csn = null;
    public $only_thumb;
    public $only_album;
    public $only_enable = true;
    public $can_read_cate = [];
    public $can_upload_cate = [];
    public $show_mode;
    public $admin_mode;
    public $view_good;
    public $orderby;
    public $order_desc;
    public $limit;
    public $show_uid;
    public $display2fancybox;

    //建構函數
    public function __construct()
    {
        require_once XOOPS_ROOT_PATH . '/modules/tadgallery/function.php';
        //$this->now =date("Y-m-d",xoops_getUserTimestamp(time()));
        //$this->today=date("Y-m-d H:i:s",xoops_getUserTimestamp(time()));
        $this->only_thumb = false;
        $this->only_album = false;
        $this->admin_mode = false;
        $this->view_good = false;
        $this->only_enable = true;
        $this->orderby = 'photo_sort';
        $this->order_desc = '';
        $this->limit = '';
        $this->show_uid = '';
        $this->can_read_cate = Tools::chk_cate_power();
        $this->can_upload_cate = Tools::chk_cate_power('upload');
    }

    //設定縮圖顯示方式 true 顯示於燈箱中
    public function set_display2fancybox($class = '')
    {
        $this->display2fancybox = $class;
    }

    //設定欲觀看分類
    public function set_view_csn($csn = null)
    {
        $this->view_csn = (int) $csn;
    }

    //設定僅顯示某人上傳的照片
    public function set_show_uid($uid = null)
    {
        $this->show_uid = $uid;
    }

    //選擇相簿時，一併是否只顯示相片，而不顯示相簿
    public function set_only_thumb($only_thumb = false)
    {
        $this->only_thumb = $only_thumb;
    }

    //只抓取相簿
    public function set_only_album()
    {
        $this->only_album = true;
    }

    //只抓取有啟用的相簿
    public function set_only_enable($enable = true)
    {
        $this->only_enable = $enable;
    }

    //設定相簿顯示方式 $show_mode=normal,flickr,waterfall
    public function set_show_mode($show_mode = '')
    {
        $this->show_mode = $show_mode;
    }

    //管理員模式（不需密碼）
    public function set_admin_mode($admin_mode = false)
    {
        $this->admin_mode = $admin_mode;
    }

    //精選照片模式
    public function set_view_good($view_good = false)
    {
        $this->view_good = $view_good;
    }

    //排序模式 post_date, sort, rand, counter
    public function set_orderby($orderby = 'photo_sort')
    {
        $this->orderby = $orderby;
    }

    //排序方式
    public function set_order_desc($desc = '')
    {
        $this->order_desc = $desc;
    }

    //縣市數量
    public function set_limit($limit = '')
    {
        $this->limit = $limit;
    }

    //取得分類下的子目錄陣列
    public function get_tad_gallery_sub_cate_array($csn = '')
    {
        global $xoopsDB;
        $sql = 'SELECT `csn` FROM `' . $xoopsDB->prefix('tad_gallery_cate') . '` WHERE `of_csn`=?';
        $result = Utility::query($sql, 'i', [$csn]) or Utility::web_error($sql, __FILE__, __LINE__);
        $total = $xoopsDB->getRowsNum($result);

        $csn_arr[] = $csn;
        if (!empty($total)) {
            while (list($all_csn) = $xoopsDB->fetchRow($result)) {
                $csn_arr[] = $all_csn;
                $sub_arr = $this->get_tad_gallery_sub_cate_array($all_csn);
                if (is_array($sub_arr)) {
                    $csn_arr = $csn_arr + $sub_arr;
                }
            }
        }

        return $csn_arr;
    }

    //取得分類下的圖片數及目錄數
    public function get_tad_gallery_cate_count($only_good = '')
    {
        global $xoopsDB;

        $cate_count = [];
        $and_uid = empty($this->show_uid) ? '' : "AND `uid` = {$this->show_uid}";
        $and_good = 'good' !== $only_good ? '' : "AND `good` = '1'";

        $sql = 'SELECT COUNT(*), `csn` FROM `' . $xoopsDB->prefix('tad_gallery') . '` WHERE 1 ' . $and_uid . ' ' . $and_good . ' GROUP BY `csn`';
        $result = Utility::query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        while (list($count, $csn) = $xoopsDB->fetchRow($result)) {
            $cate_count[$csn]['file'] = $count;
        }
        $sql = 'SELECT COUNT(*), `of_csn` FROM `' . $xoopsDB->prefix('tad_gallery_cate') . '` GROUP BY `of_csn`';
        $result = Utility::query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        while (list($count, $of_csn) = $xoopsDB->fetchRow($result)) {
            $cate_count[$of_csn]['dir'] = $count;
        }
        return $cate_count;
    }

    //密碼檢查
    private function chk_passwd()
    {
        global $xoopsDB;

        //密碼檢查
        if (!empty($this->view_csn) and !$this->admin_mode) {

            //以流水號取得某筆tad_gallery_cate資料
            $cate = Tools::get_tad_gallery_cate($this->view_csn);
            //檢查相簿觀看權限
            if (!empty($this->view_csn) and is_array($this->can_read_cate) and !in_array($this->view_csn, $this->can_read_cate)) {
                redirect_header($_SERVER['PHP_SELF'], 3, _TADGAL_NO_POWER_TITLE . sprintf(_TADGAL_NO_POWER_CONTENT, $cate['title'], $select));
            }

            $passwd = '';
            if (empty($passwd) and !empty($_SESSION['tadgallery'][$this->view_csn])) {
                $passwd = $_SESSION['tadgallery'][$this->view_csn];
            }

            $sql = 'SELECT `csn`, `passwd` FROM `' . $xoopsDB->prefix('tad_gallery_cate') . '` WHERE `csn`=?';
            $result = Utility::query($sql, 'i', [$this->view_csn]) or Utility::web_error($sql, __FILE__, __LINE__);
            list($ok_csn, $ok_passwd) = $xoopsDB->fetchRow($result);
            if (!empty($ok_csn) and $ok_passwd != $passwd) {
                redirect_header("index.php?csn=$ok_csn&op=passwd_form", 3, sprintf(_TADGAL_NO_PASSWD_CONTENT, $cate['title']));
            }

            if (!empty($ok_passwd) and empty($_SESSION['tadgallery'][$this->view_csn])) {
                $_SESSION['tadgallery'][$this->view_csn] = $passwd;
            }
        }
    }

    //取得相簿
    public function get_albums($mode = '', $all = false, $show_num = '', $order = 'sort', $pass_empty = false, $text_num = '', $only_have_content = false)
    {
        global $xoopsTpl, $xoopsDB, $xoopsUser, $tad_gallery_adm;

        $nowuid = '';
        if ($xoopsUser) {
            $nowuid = $xoopsUser->uid();
        }

        //密碼檢查
        $this->chk_passwd();

        //相簿人氣值
        $tg_count = $this->get_tad_gallery_cate_count();
        $albums = [];

        $where = $all ? '' : "WHERE `of_csn`='{$this->view_csn}'";
        $limit = (int) $show_num;
        $and_uid = empty($this->show_uid) ? '' : "AND `uid`='{$this->show_uid}'";

        //撈出底下子分類
        $sql = 'SELECT `csn`, `title`, `passwd`, `show_mode`, `cover`, `uid`, `content` FROM `' . $xoopsDB->prefix('tad_gallery_cate') . '` ' . $where . ' ' . $and_uid . ' ORDER BY ' . $order;
        $result = Utility::query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        $i = 0;
        while (list($fcsn, $title, $passwd, $show_mode, $cover, $uid, $content) = $xoopsDB->fetchRow($result)) {
            $dir_counter = isset($tg_count[$fcsn]['dir']) ? (int) $tg_count[$fcsn]['dir'] : 0;
            $file_counter = isset($tg_count[$fcsn]['file']) ? (int) $tg_count[$fcsn]['file'] : 0;
            $fcsn = (int) $fcsn;
            //無觀看權限則略過
            if (!in_array($fcsn, $this->can_read_cate)) {
                continue;
            } elseif ($pass_empty and empty($dir_counter) and empty($file_counter)) {
                continue;
            } elseif ($only_have_content and empty($content)) {
                continue;
            } elseif (!empty($limit) and $i >= $limit) {
                continue;
            }

            //小圖轉中圖
            if ($cover) {
                $cover = str_replace('small', 'medium', $cover);
                $cover = str_replace('_s_', '_m_', $cover);
            }

            $cover_pic = empty($cover) ? $this->random_cover($fcsn, 'm') : XOOPS_URL . "/uploads/tadgallery/{$cover}";

            $albums[$i]['cover_pic'] = $cover_pic;
            $albums[$i]['csn'] = $fcsn;
            $albums[$i]['title'] = $title;
            if (!empty($text_num)) {
                $content = xoops_substr($content, 0, $text_num);
            }
            $albums[$i]['content'] = $content;
            $albums[$i]['dir_counter'] = $dir_counter;
            $albums[$i]['file_counter'] = $file_counter;
            $the_passwd = isset($_SESSION['tadgallery'][$fcsn]) ? $_SESSION['tadgallery'][$fcsn] : '';
            $albums[$i]['album_lock'] = (empty($passwd) or $passwd == $the_passwd) ? false : true;
            $albums[$i]['album_del'] = (empty($dir_counter) and empty($file_counter) and ($uid == $nowuid or $tad_gallery_adm)) ? true : false;
            $albums[$i]['album_edit'] = ($uid == $nowuid or $tad_gallery_adm) ? true : false;
            $i++;
        }

        $SweetAlert = new SweetAlert();
        $SweetAlert->render('delete_tad_gallery_cate_func', XOOPS_URL . '/modules/tadgallery/ajax.php?op=delete_tad_gallery_cate&csn=', 'csn');

        if ('return' === $mode) {
            return $albums;
        }
        $xoopsTpl->assign('count', $i);
        $xoopsTpl->assign('albums', $albums);
    }

    //取得相片
    public function get_photos($include_sub = 0, $mode = '')
    {
        global $xoopsTpl, $xoopsDB, $xoopsUser, $tad_gallery_adm;

        $nowuid = '';
        if ($xoopsUser) {
            $nowuid = $xoopsUser->uid();
        }

        //密碼檢查
        $this->chk_passwd();

        $photo = $show_csn = [];

        if (null === $this->view_csn) {
            $cates = Tools::chk_cate_power();
            //找最新的10個相簿，避免分類太多無法執行
            $csn_arr = [];
            $sql = 'SELECT `csn`, `passwd` FROM `' . $xoopsDB->prefix('tad_gallery_cate') . '` WHERE `enable`=? ORDER BY `csn` DESC LIMIT 0, 10';
            $result = Utility::query($sql, 's', ['1']) or Utility::web_error($sql, __FILE__, __LINE__);
            while (list($csn, $passwd) = $xoopsDB->fetchRow($result)) {
                $csn_arr[] = $csn;
                $the_passwd[$csn] = $passwd;
            }

            if (is_array($cates)) {
                foreach ($cates as $the_csn) {
                    $the_csn = (int) $the_csn;

                    if (!empty($csn_arr) and is_array($csn_arr)) {
                        if (!in_array($the_csn, $csn_arr)) {
                            continue;
                        }
                    }

                    $save_passwd = isset($_SESSION['tadgallery'][$the_csn]) ? $_SESSION['tadgallery'][$the_csn] : '';
                    if (!empty($the_passwd[$the_csn]) and $save_passwd = $the_passwd[$the_csn]) {
                        $show_csn[] = $the_csn;
                    } elseif (empty($the_passwd[$the_csn])) {
                        $show_csn[] = $the_csn;
                    }
                }
            }
            $show_csn_all = is_array($show_csn) ? implode(',', $show_csn) : '';
            $and_csn = empty($show_csn_all) ? '' : "and a.`csn` in($show_csn_all)";
        } elseif (1 == $include_sub) {
            $show_csn = $this->get_tad_gallery_sub_cate_array($this->view_csn);
            $show_csn_all = is_array($show_csn) ? implode(',', $show_csn) : '';
            $and_csn = empty($show_csn_all) ? '' : "and a.`csn` in($show_csn_all)";
        } else {
            $and_csn = "and a.`csn`='{$this->view_csn}'";
        }

        $and_good = $this->view_good ? "and a.`good`='1'" : '';

        $orderby = ('rand' === $this->orderby) ? 'rand()' : "a.{$this->orderby}";

        $and_uid = empty($this->show_uid) ? '' : "and a.uid='{$this->show_uid}'";
        //找出分類下所有相片
        $sql = 'select a.* , b.title as album_title from ' . $xoopsDB->prefix('tad_gallery') . ' as a left join  ' . $xoopsDB->prefix('tad_gallery_cate') . " as b on a.csn=b.csn where 1 $and_csn $and_good $and_uid order by {$orderby} {$this->order_desc}";
        $bar = "";
        if ($this->limit) {
            $PageBar = Utility::getPageBar($sql, $this->limit, 10);
            $bar = $PageBar['bar'];
            $sql = $PageBar['sql'];
        }
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        $pp = $types = [];

        if ($this->display2fancybox) {
            $colorbox = new ColorBox('.' . $this->display2fancybox);
            $colorbox->render(false);

        }

        $i = 0;

        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            foreach ($all as $k => $v) {
                $$k = $v;
            }
            if (!isset($db_csn)) {
                $db_csn = '';
            }
            if (!isset($album_title)) {
                $album_title = '';
            }

            if ('app' !== $mode) {
                $photo[$i]['db_csn'] = $db_csn;
                $photo[$i]['exif'] = $exif;
                $photo[$i]['good'] = $good;
                $photo[$i]['photo_l_url'] = urlencode(Tools::get_pic_url($dir, $sn, $filename));
                $photo[$i]['size'] = $size;
                $photo[$i]['type'] = $type;
                $photo[$i]['width'] = $width;
                $photo[$i]['height'] = $height;
                $photo[$i]['dir'] = $dir;
                $photo[$i]['uid'] = $uid;
                //以uid取得使用者名稱
                $uid_name = \XoopsUser::getUnameFromId($uid, 1);
                if (empty($uid_name)) {
                    $uid_name = \XoopsUser::getUnameFromId($uid, 0);
                }

                $photo[$i]['author'] = $uid_name;
                $photo[$i]['post_date'] = $post_date;
                $photo[$i]['photo_del'] = ($uid == $nowuid or $tad_gallery_adm) ? true : false;
                $photo[$i]['photo_edit'] = ($uid == $nowuid or $tad_gallery_adm) ? true : false;
                $photo[$i]['fancy_class'] = $this->display2fancybox ? 'class="' . $this->display2fancybox . '" rel="group"' : '';

                // Perform the regex match on the $exif string
                preg_match("/\[DateTime\]=(.*)\|\|\[IFD0\]/", $exif, $matches);

                // Check if the match was successful and index 1 is set in $matches
                // I hope he's compatible with PHP 5.6+!
                $photo[$i]['DateTime'] = $matches[1] ? $matches[1] : $post_date;

                // Handle the $type key in the $types array
                if (isset($types[$type])) {
                    $types[$type]++;
                } else {
                    $types[$type] = 1;
                }

            } else {
                $photo[$i]['csn'] = $csn;
            }

            $photo[$i]['sn'] = $sn;
            $photo[$i]['title'] = $title;
            $photo[$i]['description'] = nl2br($description);
            $photo[$i]['filename'] = $filename;
            $photo[$i]['counter'] = $counter;
            $photo[$i]['tag'] = $tag;
            $photo[$i]['photo_sort'] = $photo_sort;
            $photo[$i]['photo_l'] = Tools::get_pic_url($dir, $sn, $filename);
            $photo[$i]['photo_m'] = Tools::get_pic_url($dir, $sn, $filename, 'm');
            $photo[$i]['photo_s'] = Tools::get_pic_url($dir, $sn, $filename, 's');
            $photo[$i]['album_title'] = $album_title;
            $photo[$i]['is360'] = $is360;
            // preg_match("/\[Model\]=(.*)\|\|\[IFD0\]\[DateTime\]/", $exif, $matches);
            // $Model360           = get360_arr();
            // $photo[$i]['is360'] = in_array($matches[1], $Model360) ? true : false;

            $i++;
        }
        $xoopsTpl->assign('bar', $bar);

        if ('app' !== $mode) {
            arsort($types);
            foreach ($types as $extension => $value) {
                if ('image/png' === $extension) {
                    $extension = 'png';
                } elseif ('image/gif' === $extension) {
                    $extension = 'gif';
                } else {
                    $extension = 'jpg';
                }
                $xoopsTpl->assign('extension', $extension);
                break;
            }
        }

        return $photo;
    }

    //隨機相簿封面
    public function random_cover($csn = '', $pic_size = 'm')
    {
        global $xoopsDB;
        if (empty($csn)) {
            return;
        }

        //找出分類下所有相片
        $sql = 'SELECT `sn`,`filename`,`dir` FROM `' . $xoopsDB->prefix('tad_gallery') . '` WHERE `csn`=? ORDER BY RAND() LIMIT 0,1';
        $result = Utility::query($sql, 'i', [$csn]) or Utility::web_error($sql, __FILE__, __LINE__);

        list($sn, $filename, $dir) = $xoopsDB->fetchRow($result);

        if (empty($sn)) {
            $sql = 'SELECT a.`sn`, a.`filename`, a.`dir` FROM `' . $xoopsDB->prefix('tad_gallery') . '` AS a JOIN `' . $xoopsDB->prefix('tad_gallery_cate') . '` AS b ON a.`csn`=b.`csn` WHERE a.`csn`=? OR b.`of_csn`=? ORDER BY RAND() LIMIT 0,1';
            $result = Utility::query($sql, 'ii', [$csn, $csn]) or Utility::web_error($sql, __FILE__, __LINE__);

            list($sn, $filename, $dir) = $xoopsDB->fetchRow($result);
        }

        $cover = Tools::get_pic_url($dir, $sn, $filename, $pic_size);
        if (empty($cover)) {
            $cover = XOOPS_URL . '/modules/tadgallery/images/no_photo_available.png';
        }

        return $cover;
    }

}
