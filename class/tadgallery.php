<?php
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

$this->set_view_good(false);                //精選照片模式
$this->get_tad_gallery($sn="");             //以流水號取得某相片資料
$this->get_tad_gallery_cate_count($gallery_list_mode);        //取得分類下的圖片數及目錄數

$this->chk_cate_power($kind="");            //判斷目前的登入者在哪些類別中有觀看或發表(upload)的權利 $kind=""（看），$kind="upload"（寫）
$this->get_tad_gallery_cate($csn="");       //以流水號取得某相簿資料
$this->get_albums('return');                //取得相簿
$this->get_photos($include_sub=0);                //取得照片
 */

class tadgallery
{
    //var $now;
    //var $today;
    public $view_csn = null;
    public $only_thumb;
    public $only_album;
    public $only_enable     = true;
    public $can_read_cate   = array();
    public $can_upload_cate = array();
    public $show_mode;
    public $admin_mode;
    public $view_good;
    public $orderby;
    public $order_desc;
    public $limit;
    public $show_uid;

    //建構函數
    public function __construct()
    {
        include_once XOOPS_ROOT_PATH . "/modules/tadgallery/function.php";
        //$this->now =date("Y-m-d",xoops_getUserTimestamp(time()));
        //$this->today=date("Y-m-d H:i:s",xoops_getUserTimestamp(time()));
        $this->only_thumb      = false;
        $this->only_album      = false;
        $this->admin_mode      = false;
        $this->view_good       = false;
        $this->only_enable     = true;
        $this->orderby         = "photo_sort";
        $this->order_desc      = "";
        $this->limit           = "";
        $this->show_uid        = "";
        $this->can_read_cate   = $this->chk_cate_power();
        $this->can_upload_cate = $this->chk_cate_power("upload");
    }

    //設定欲觀看分類
    public function set_view_csn($csn = null)
    {
        $this->view_csn = $csn;
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
    public function set_show_mode($show_mode = "")
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
    public function set_orderby($orderby = "photo_sort")
    {
        $this->orderby = $orderby;
    }

    //排序方式
    public function set_order_desc($desc = "")
    {
        $this->order_desc = $desc;
    }

    //縣市數量
    public function set_limit($limit = "")
    {
        $this->limit = $limit;
    }

    //以流水號取得某相片資料
    public function get_tad_gallery($sn = "")
    {
        global $xoopsDB;
        if (empty($sn)) {
            return;
        }

        $sql    = "select * from " . $xoopsDB->prefix("tad_gallery") . " where sn='$sn'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $data   = $xoopsDB->fetchArray($result);
        return $data;
    }

    //以流水號取得某相簿資料
    public function get_tad_gallery_cate($csn = "")
    {
        global $xoopsDB, $isAdmin, $xoopsUser;
        if (empty($csn)) {
            return;
        }

        $sql    = "select * from " . $xoopsDB->prefix("tad_gallery_cate") . " where csn='$csn'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $data   = $xoopsDB->fetchArray($result);

        $nowuid = "";
        if ($xoopsUser) {
            $nowuid = $xoopsUser->uid();
        }

        $data['adm'] = ($data['uid'] == $nowuid or $isAdmin) ? true : false;
        return $data;
    }

    //取得分類下的子目錄陣列
    public function get_tad_gallery_sub_cate_array($csn = "")
    {
        global $xoopsDB;
        $sql    = "select csn from " . $xoopsDB->prefix("tad_gallery_cate") . " where of_csn='{$csn}'";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $total  = $xoopsDB->getRowsNum($result);

        $csn_arr[] = $csn;
        if (!empty($total)) {
            while (list($all_csn) = $xoopsDB->fetchRow($result)) {
                $csn_arr[] = $all_csn;
                $sub_arr   = $this->get_tad_gallery_sub_cate_array($all_csn);
                if (is_array($sub_arr)) {
                    $csn_arr = $csn_arr + $sub_arr;
                }

            }
        }
        return $csn_arr;
    }

    //取得分類下的圖片數及目錄數
    public function get_tad_gallery_cate_count($gallery_list_mode = '')
    {
        global $xoopsDB;

        $cate_count = "";
        $and_uid    = empty($this->show_uid) ? "" : "and `uid`='{$this->show_uid}'";
        $and_good   = $gallery_list_mode != 'good' ? "" : "and `good`='1'";

        $sql = "select count(*),csn from " . $xoopsDB->prefix("tad_gallery") . " where 1 $and_uid $and_good group by csn";
        // die($sql);
        $result = $xoopsDB->query($sql) or web_error($sql);
        while (list($count, $csn) = $xoopsDB->fetchRow($result)) {
            $cate_count[$csn]['file'] = $count;
        }
        // die(var_export($cate_count));
        $sql = "select count(*),of_csn from " . $xoopsDB->prefix("tad_gallery_cate") . " group by of_csn";
        //die($sql);
        $result = $xoopsDB->query($sql) or web_error($sql);
        //$cate_count="";
        while (list($count, $of_csn) = $xoopsDB->fetchRow($result)) {
            $cate_count[$of_csn]['dir'] = $count;
        }
        //die(var_export($cate_count));
        return $cate_count;
    }

    //判斷目前的登入者在哪些類別中有觀看或發表(upload)的權利 $kind=""（看），$kind="upload"（寫）
    public function chk_cate_power($kind = "")
    {
        global $xoopsDB, $xoopsUser, $xoopsModule;
        if (!$xoopsModule) {
            $modhandler  = xoops_gethandler('module');
            $xoopsModule = &$modhandler->getByDirname("tadgallery");
        }

        if (!empty($xoopsUser)) {
            $module_id = $xoopsModule->getVar('mid');
            $isAdmin   = $xoopsUser->isAdmin($module_id);
            if ($isAdmin) {
                $ok_cat[] = "0";
            }
            $user_array = $xoopsUser->getGroups();
        } else {
            $user_array = array(3);
            $isAdmin    = 0;
        }

        $col = ($kind == "upload") ? "enable_upload_group" : "enable_group";

        $sql    = "select csn,{$col} from " . $xoopsDB->prefix("tad_gallery_cate") . "";
        $result = $xoopsDB->query($sql) or web_error($sql);
        $ok_cat = "";
        while (list($csn, $power) = $xoopsDB->fetchRow($result)) {
            if ($isAdmin or empty($power)) {
                $ok_cat[] = $csn;
            } else {
                $power_array = explode(",", $power);
                foreach ($power_array as $gid) {
                    if (in_array($gid, $user_array)) {
                        $ok_cat[] = $csn;
                        break;
                    }
                }
            }
        }

        return $ok_cat;
    }

    //密碼檢查
    private function chk_passwd()
    {
        global $xoopsTpl, $xoopsDB, $xoopsModuleConfig, $isAdmin, $xoopsUser;

        $nowuid = "";
        if ($xoopsUser) {
            $nowuid = $xoopsUser->uid();
        }

        //密碼檢查
        if (!empty($this->view_csn) and !$this->admin_mode) {

            //檢查相簿觀看權限
            if (!in_array($this->view_csn, $this->can_read_cate)) {
                redirect_header($_SERVER['PHP_SELF'], 3, _TADGAL_NO_POWER_TITLE, sprintf(_TADGAL_NO_POWER_CONTENT, $cate['title'], $select));
            }

            //以流水號取得某筆tad_gallery_cate資料
            $cate = $this->get_tad_gallery_cate($this->view_csn);

            $passwd = "";
            if (empty($passwd) and !empty($_SESSION['tadgallery'][$this->view_csn])) {
                $passwd = $_SESSION['tadgallery'][$this->view_csn];
            }

            $sql                      = "select csn,passwd from " . $xoopsDB->prefix("tad_gallery_cate") . " where csn='{$this->view_csn}'";
            $result                   = $xoopsDB->query($sql) or web_error($sql);
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
    public function get_albums($mode = "", $all = false, $show_num = "", $order = "sort", $pass_empty = false, $text_num = "", $only_have_content = false)
    {
        global $xoopsTpl, $xoopsDB, $xoopsModuleConfig, $isAdmin, $xoopsUser;

        $nowuid = "";
        if ($xoopsUser) {
            $nowuid = $xoopsUser->uid();
        }

        //密碼檢查
        $this->chk_passwd();

        //相簿人氣值
        $tg_count = $this->get_tad_gallery_cate_count();
        $albums   = "";

        $where = $all ? "" : "where of_csn='{$this->view_csn}'";
        $limit = intval($show_num);

        //撈出底下子分類
        $sql = "select csn,title,passwd,show_mode,cover,uid,content from " . $xoopsDB->prefix("tad_gallery_cate") . " $where order by $order";

        $result = $xoopsDB->query($sql) or web_error($sql);
        $i      = 0;
        while (list($fcsn, $title, $passwd, $show_mode, $cover, $uid, $content) = $xoopsDB->fetchRow($result)) {

            $dir_counter  = isset($tg_count[$fcsn]['dir']) ? intval($tg_count[$fcsn]['dir']) : 0;
            $file_counter = isset($tg_count[$fcsn]['file']) ? intval($tg_count[$fcsn]['file']) : 0;

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
                $cover = str_replace("small", "medium", $cover);
                $cover = str_replace("_s_", "_m_", $cover);
            }

            $cover_pic = empty($cover) ? $this->random_cover($fcsn, "m") : XOOPS_URL . "/uploads/tadgallery/{$cover}";

            $albums[$i]['cover_pic'] = $cover_pic;
            $albums[$i]['csn']       = $fcsn;
            $albums[$i]['title']     = $title;
            if (!empty($text_num)) {
                $content = xoops_substr($content, 0, $text_num);
            }
            $albums[$i]['content']      = $content;
            $albums[$i]['dir_counter']  = $dir_counter;
            $albums[$i]['file_counter'] = $file_counter;
            $the_passwd                 = isset($_SESSION['tadgallery'][$fcsn]) ? $_SESSION['tadgallery'][$fcsn] : "";
            $albums[$i]['album_lock']   = (empty($passwd) or $passwd == $the_passwd) ? false : true;
            $albums[$i]['album_del']    = (empty($dir_counter) and empty($file_counter) and ($uid == $nowuid or $isAdmin)) ? true : false;
            $albums[$i]['album_edit']   = ($uid == $nowuid or $isAdmin) ? true : false;
            $i++;
        }

        if ($mode == "return") {
            return $albums;
        } else {
            $xoopsTpl->assign("count", $i);
            $xoopsTpl->assign("albums", $albums);
        }
    }

    //取得相片
    public function get_photos($include_sub = 0)
    {
        global $xoopsTpl, $xoopsDB, $xoopsModuleConfig, $isAdmin, $xoopsUser;

        $nowuid = "";
        if ($xoopsUser) {
            $nowuid = $xoopsUser->uid();
        }

        //密碼檢查
        $this->chk_passwd();

        //相簿人氣值
        $tg_count = $this->get_tad_gallery_cate_count();

        $photo = $show_csn = "";

        if (is_null($this->view_csn)) {
            $cates = $this->chk_cate_power();
            //找最新的10個相簿，避免分類太多無法執行
            $csn_arr = '';
            $sql     = "select `csn`,`passwd` from " . $xoopsDB->prefix("tad_gallery_cate") . " where `enable`='1' order by `csn` desc limit 0,10";
            $result  = $xoopsDB->queryF($sql) or web_error($sql);
            while (list($csn, $passwd) = $xoopsDB->fetchRow($result)) {
                $csn_arr[]        = $csn;
                $the_passwd[$csn] = $passwd;
            }

            if (is_array($cates)) {
                foreach ($cates as $the_csn) {
                    if (!empty($csn_arr) and is_array($csn_arr)) {
                        if (!in_array($the_csn, $csn_arr)) {
                            continue;
                        }
                    }
                    // $the_cate    = $this->get_tad_gallery_cate($the_csn);
                    $save_passwd = isset($_SESSION['tadgallery'][$the_csn]) ? $_SESSION['tadgallery'][$the_csn] : "";
                    if (!empty($the_passwd[$the_csn]) and $save_passwd = $the_passwd[$the_csn]) {
                        $show_csn[] = $the_csn;
                    } elseif (empty($the_passwd[$the_csn])) {
                        $show_csn[] = $the_csn;
                    }
                }
            }
            $show_csn_all = is_array($show_csn) ? implode(",", $show_csn) : "";
            $and_csn      = empty($show_csn_all) ? "" : "and a.`csn` in($show_csn_all)";
        } elseif ($include_sub == 1) {
            $show_csn     = $this->get_tad_gallery_sub_cate_array($this->view_csn);
            $show_csn_all = is_array($show_csn) ? implode(",", $show_csn) : "";
            $and_csn      = empty($show_csn_all) ? "" : "and a.`csn` in($show_csn_all)";
        } else {
            $and_csn = "and a.`csn`='{$this->view_csn}'";
        }

        $and_good = $this->view_good ? "and a.`good`='1'" : '';

        $limit = !empty($this->limit) ? "limit 0 , " . $this->limit : "";

        $orderby = ($this->orderby == "rand") ? "rand()" : "a.{$this->orderby}";

        $and_uid = empty($this->show_uid) ? "" : "and a.uid='{$this->show_uid}'";
        //找出分類下所有相片
        $sql = "select a.* , b.title from " . $xoopsDB->prefix("tad_gallery") . " as a left join  " . $xoopsDB->prefix("tad_gallery_cate") . " as b on a.csn=b.csn where 1 $and_csn $and_good $and_uid order by {$orderby} {$this->order_desc} {$limit}";
        //die($sql);
        $result = $xoopsDB->queryF($sql) or web_error($sql);

        $pp = $types = "";

        $i = 0;
        //$photo="";
        while (list($sn, $db_csn, $title, $description, $filename, $size, $type, $width, $height, $dir, $uid, $post_date, $counter, $exif, $tag, $good, $photo_sort, $album_title) = $xoopsDB->fetchRow($result)) {

            $photo[$i]['sn']          = $sn;
            $photo[$i]['db_csn']      = $db_csn;
            $photo[$i]['title']       = $title;
            $photo[$i]['description'] = nl2br($description);
            $photo[$i]['filename']    = $filename;
            $photo[$i]['size']        = $size;
            $photo[$i]['type']        = $type;
            $photo[$i]['width']       = $width;
            $photo[$i]['height']      = $height;
            $photo[$i]['dir']         = $dir;
            $photo[$i]['uid']         = $uid;
            $photo[$i]['post_date']   = $post_date;
            $photo[$i]['counter']     = $counter;
            $photo[$i]['exif']        = $exif;
            $photo[$i]['tag']         = $tag;
            $photo[$i]['good']        = $good;
            $photo[$i]['photo_sort']  = $photo_sort;
            $photo[$i]['photo_l']     = $this->get_pic_url($dir, $sn, $filename);
            $photo[$i]['photo_m']     = $this->get_pic_url($dir, $sn, $filename, "m");
            $photo[$i]['photo_s']     = $this->get_pic_url($dir, $sn, $filename, "s");
            $photo[$i]['photo_del']   = ($uid == $nowuid or $isAdmin) ? true : false;
            $photo[$i]['photo_edit']  = ($uid == $nowuid or $isAdmin) ? true : false;
            $photo[$i]['album_title'] = $album_title;

            preg_match("/\[DateTime\]=(.*)\|\|\[IFD0\]/", $exif, $matches);
            $photo[$i]['DateTime'] = $matches[1];
            if (isset($types[$type])) {
                $types[$type]++;
            } else {
                $types[$type] = 1;
            }
            $i++;
        }

        arsort($types);
        foreach ($types as $extension => $value) {
            if ($extension == "image/png") {
                $extension = "png";
            } elseif ($extension == "image/gif") {
                $extension = "gif";
            } else {
                $extension = "jpg";
            }
            $xoopsTpl->assign("extension", $extension);
            break;
        }

        return $photo;

    }

    //隨機相簿封面
    private function random_cover($csn = "", $pic_size = "m")
    {
        global $xoopsDB;
        if (empty($csn)) {
            return;
        }

        //找出分類下所有相片
        $sql    = "select * from " . $xoopsDB->prefix("tad_gallery") . " where csn='{$csn}' order by rand() limit 0,1";
        $result = $xoopsDB->query($sql) or web_error($sql);

        list($sn, $db_csn, $title, $description, $filename, $size, $type, $width, $height, $dir, $uid, $post_date, $counter, $exif) = $xoopsDB->fetchRow($result);

        $cover = $this->get_pic_url($dir, $sn, $filename, $pic_size);
        if (empty($cover)) {
            $cover = XOOPS_URL . "/modules/tadgallery/images/no_photo_available.png";
        }

        return $cover;
    }

    //取得圖片網址
    public function get_pic_url($dir = "", $sn = "", $filename = "", $kind = "", $path_kind = "")
    {
        if (empty($filename)) {
            return;
        }

        $show_path = ($path_kind == "dir") ? _TADGAL_UP_FILE_DIR : _TADGAL_UP_FILE_URL;

        if ($kind == "m") {
            if (is_file(_TADGAL_UP_FILE_DIR . "medium/{$dir}/{$sn}_m_{$filename}")) {
                return "{$show_path}medium/{$dir}/{$sn}_m_{$filename}";
            }
        } elseif ($kind == "s") {
            if (is_file(_TADGAL_UP_FILE_DIR . "small/{$dir}/{$sn}_s_{$filename}")) {
                return "{$show_path}small/{$dir}/{$sn}_s_{$filename}";
            } elseif (is_file(_TADGAL_UP_FILE_DIR . "medium/{$dir}/{$sn}_m_{$filename}")) {
                return "{$show_path}medium/{$dir}/{$sn}_m_{$filename}";
            }
        }
        return "{$show_path}{$dir}/{$sn}_{$filename}";
    }

}
