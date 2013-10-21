<?php
//TadGallery物件
/*
$this->set_view_csn($csn="");               //設定欲觀看分類 $csn=int or array
$this->set_only_thumb(false);               //選擇相簿時，一併是否只顯示相片，而不顯示相簿。
$this->set_show_mode($show_mode="");        //設定相簿顯示方式 $show_mode=3d,slideshow
$this->set_admin_mode(false);               //管理員模式（不需密碼）
$this->set_orderby($orderby="photo_sort");  //排序模式 post_date, photo_sort, rand, counter
$this->set_order_desc($desc);               //升降排序
$this->set_limit($limit);                   //設定顯示數量

$this->set_view_good(false);                //精選照片模式
$this->get_tad_gallery($sn="");             //以流水號取得某相片資料
$this->get_tad_gallery_cate_count();        //取得分類下的圖片數及目錄數

$this->chk_cate_power($kind="");            //判斷目前的登入者在哪些類別中有觀看或發表(upload)的權利 $kind=""（看），$kind="upload"（寫）
$this->get_tad_gallery_cate($csn="");       //以流水號取得某相簿資料
$this->mk_gallery_border($rel="",$url="",$cover_pic="",$title="",$pass=false,$fcsn=""); //製作相簿或相片的外框
$this->get_albums();                        //取得相簿
*/

class tadgallery{
  //var $now;
  //var $today;
  var $view_csn;
  var $only_thumb;
  var $can_read_cate=array();
  var $can_upload_cate=array();
  var $show_mode;
  var $admin_mode;
  var $view_good;
  var $orderby;
  var $order_desc;
  var $limit;

  //建構函數
  function __construct(){
    include_once XOOPS_ROOT_PATH."/modules/tadgallery/function.php";
    //$this->now =date("Y-m-d",xoops_getUserTimestamp(time()));
    //$this->today=date("Y-m-d H:i:s",xoops_getUserTimestamp(time()));
    $this->only_thumb=false;
    $this->admin_mode=false;
    $this->view_good=false;
    $this->orderby="photo_sort";
    $this->order_desc="";
    $this->limit="";
    $this->can_read_cate=$this->chk_cate_power();
    $this->can_upload_cate=$this->chk_cate_power("upload");
  }


  //設定欲觀看分類
  public function set_view_csn($csn=""){
    $this->view_csn=$csn;
  }

  //選擇相簿時，一併是否只顯示相片，而不顯示相簿
  public function set_only_thumb($only_thumb=false){
    $this->only_thumb=$only_thumb;
  }

  //設定相簿顯示方式 $show_mode=3d,waterfall,slideshow
  public function set_show_mode($show_mode=""){
    $this->show_mode=$show_mode;
  }

  //管理員模式（不需密碼）
  public function set_admin_mode($admin_mode=false){
    $this->admin_mode=$admin_mode;
  }

  //精選照片模式
  public function set_view_good($view_good=false){
    $this->view_good=$view_good;
  }

  //排序模式 post_date, sort, rand, counter
  public function set_orderby($orderby="photo_sort"){
    $this->orderby=$orderby;
  }

  //排序方式
  public function set_order_desc($desc=""){
    $this->order_desc=$desc;
  }


  //縣市數量
  public function set_limit($limit=""){
    $this->limit=$limit;
  }


  //以流水號取得某相片資料
  public function get_tad_gallery($sn=""){
    global $xoopsDB;
    if(empty($sn))return;
    $sql = "select * from ".$xoopsDB->prefix("tad_gallery")." where sn='$sn'";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    $data=$xoopsDB->fetchArray($result);
    return $data;
  }

  //以流水號取得某相簿資料
  public function get_tad_gallery_cate($csn=""){
    global $xoopsDB;
    if(empty($csn))return;
    $sql = "select * from ".$xoopsDB->prefix("tad_gallery_cate")." where csn='$csn'";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    $data=$xoopsDB->fetchArray($result);
    return $data;
  }


  //取得分類下的圖片數及目錄數
  public function get_tad_gallery_cate_count(){
    global $xoopsDB,$xoopsUser,$xoopsModule;
    $sql = "select count(*),csn from ".$xoopsDB->prefix("tad_gallery")." group by csn";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    while(list($count,$csn)=$xoopsDB->fetchRow($result)){
      $cate_count[$csn]['file']=$count;
    }
    //die(var_export($cate_count));
    $sql = "select count(*),of_csn from ".$xoopsDB->prefix("tad_gallery_cate")." group by of_csn";
    //die($sql);
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    //$cate_count="";
    while(list($count,$of_csn)=$xoopsDB->fetchRow($result)){
      $cate_count[$of_csn]['dir']=$count;
    }
    //die(var_export($cate_count));
    return $cate_count;
  }


  //判斷目前的登入者在哪些類別中有觀看或發表(upload)的權利 $kind=""（看），$kind="upload"（寫）
  public function chk_cate_power($kind=""){
    global $xoopsDB,$xoopsUser,$xoopsModule;
    if(!$xoopsModule){
      $modhandler = &xoops_gethandler('module');
      $xoopsModule = &$modhandler->getByDirname("tadgallery");
    }


    if(!empty($xoopsUser)){
      $module_id = $xoopsModule->getVar('mid');
      $isAdmin=$xoopsUser->isAdmin($module_id);
      if($isAdmin){
        $ok_cat[]="0";
      }
      $user_array=$xoopsUser->getGroups();
    }else{
      $user_array=array(3);
      $isAdmin=0;
    }

    $col=($kind=="upload")?"enable_upload_group":"enable_group";

    $sql = "select csn,{$col} from ".$xoopsDB->prefix("tad_gallery_cate")."";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

    while(list($csn,$power)=$xoopsDB->fetchRow($result)){
      if($isAdmin or empty($power)){
        $ok_cat[]=$csn;
      }else{
        $power_array=explode(",",$power);
        foreach($power_array as $gid){
          if(in_array($gid,$user_array)){
            $ok_cat[]=$csn;
            break;
          }
        }
      }
    }

    return $ok_cat;
  }

  //取得相簿
  public function get_albums($mode=""){
    global $xoopsTpl,$xoopsDB,$xoopsModuleConfig,$isAdmin,$xoopsUser;

    $nowuid="";
    if($xoopsUser){
      $nowuid=$xoopsUser->uid();
    }

    //密碼檢查
    if(!empty($this->view_csn) and !$this->admin_mode){

      //檢查相簿觀看權限
      if(!in_array($this->view_csn,$this->can_read_cate)){
        redirect_header($_SERVER['PHP_SELF'],3, _TADGAL_NO_POWER_TITLE,sprintf(_TADGAL_NO_POWER_CONTENT,$cate['title'],$select));
      }

      //以流水號取得某筆tad_gallery_cate資料
      $cate=$this->get_tad_gallery_cate($this->view_csn);
      $passwd="";
      if(empty($passwd) and !empty($_SESSION['tadgallery'][$this->view_csn])){
        $passwd=$_SESSION['tadgallery'][$this->view_csn];
      }

      $sql = "select csn,passwd from ".$xoopsDB->prefix("tad_gallery_cate")." where csn='{$this->view_csn}'";
      $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
      list($ok_csn,$ok_passwd)=$xoopsDB->fetchRow($result);
      if(!empty($ok_csn) and $ok_passwd!=$passwd)redirect_header($_SERVER['PHP_SELF'],3, sprintf(_TADGAL_NO_PASSWD_CONTENT,$cate['title']));

      if(!empty($ok_passwd) and empty($_SESSION['tadgallery'][$this->view_csn])){
        $_SESSION['tadgallery'][$this->view_csn]=$passwd;
      }
    }

    $tg_count=$this->get_tad_gallery_cate_count();


    $photo="";

    //畫面並不只秀出縮圖，要秀出分類的話。
    if(!$this->only_thumb){
      //撈出底下子分類
      $sql = "select csn,title,passwd,show_mode,cover,uid from ".$xoopsDB->prefix("tad_gallery_cate")." where of_csn='{$this->view_csn}'  order by sort";

      $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
      $i=0;
      while(list($fcsn,$title,$passwd,$show_mode,$cover,$uid)=$xoopsDB->fetchRow($result)){
        //無觀看權限則略過
        if(!in_array($fcsn,$this->can_read_cate)){
          continue;
        }

        /*
        if($this->show_mode=="3d"){
          $url="3d.php";
          $rel="rel='shadowbox'";
        }elseif($this->show_mode=="slideshow"){
          $url="slideshow.php";
          $rel="";
        }else{
          $url="index.php";
          $rel="";
        }
        */

        //$cover_pic=(empty($cover))?$this->random_cover($fcsn):XOOPS_URL."/uploads/tadgallery/{$cover}";
        $size=$xoopsModuleConfig['index_mode']=="normal"?"s":"m";

        $cover_pic=$this->random_cover($fcsn,$size);
        $dir_counter=isset($tg_count[$fcsn]['dir'])?intval($tg_count[$fcsn]['dir']):0;
        $file_counter=isset($tg_count[$fcsn]['file'])?intval($tg_count[$fcsn]['file']):0;

        $photo[$i]['album']=$cover_pic;
        $photo[$i]['csn']=$fcsn;
        $photo[$i]['title']=$title;
        $photo[$i]['dir_counter']=$dir_counter;
        $photo[$i]['file_counter']=$file_counter;
        $photo[$i]['album_lock']=(empty($passwd) or $passwd==$_SESSION['tadgallery'][$fcsn])?false:true;
        $photo[$i]['album_del']=(empty($dir_counter) and empty($file_counter) and ($uid==$nowuid or $isAdmin))?true:false;
        $photo[$i]['album_edit']=($uid==$nowuid or $isAdmin)?true:false;
        $i++;
      }

    }

    $where=$this->view_good?"a.`good`='1'":"a.`csn`='{$this->view_csn}'";

    $limit=!empty($this->limit)?"limit 0 , ".$this->limit:"";

    $orderby=($this->orderby=="rand")?"rand()":"a.{$this->orderby}";

    //找出分類下所有相片
    $sql = "select a.* , b.title from ".$xoopsDB->prefix("tad_gallery")." as a left join  ".$xoopsDB->prefix("tad_gallery_cate")." as b on a.csn=b.csn  where $where order by {$orderby} {$this->order_desc} {$limit}";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

    $pp="";


    //$photo="";
    while(list($sn,$db_csn,$title,$description,$filename,$size,$type,$width,$height,$dir,$uid,$post_date,$counter,$exif,$tag,$good,$photo_sort,$album_title)=$xoopsDB->fetchRow($result)){

      $photo[$i]['sn']=$sn;
      $photo[$i]['db_csn']=$db_csn;
      $photo[$i]['title']=$title;
      $photo[$i]['description']=nl2br($description);
      $photo[$i]['filename']=$filename;
      $photo[$i]['size']=$size;
      $photo[$i]['type']=$type;
      $photo[$i]['width']=$width;
      $photo[$i]['height']=$height;
      $photo[$i]['dir']=$dir;
      $photo[$i]['uid']=$uid;
      $photo[$i]['post_date']=$post_date;
      $photo[$i]['counter']=$counter;
      $photo[$i]['exif']=$exif;
      $photo[$i]['tag']=$tag;
      $photo[$i]['good']=$good;
      $photo[$i]['photo_sort']=$photo_sort;
      $photo[$i]['photo_l']=$this->get_pic_url($dir,$sn,$filename);
      $photo[$i]['photo_m']=$this->get_pic_url($dir,$sn,$filename,"m");
      $photo[$i]['photo_s']=$this->get_pic_url($dir,$sn,$filename,"s");
      $photo[$i]['photo_del']=($uid==$nowuid or $isAdmin)?true:false;
      $photo[$i]['photo_edit']=($uid==$nowuid or $isAdmin)?true:false;
      $photo[$i]['album_title']=$album_title;


      $i++;
    }

    if($mode=="return"){
      return $photo;
    }else{
      $xoopsTpl->assign( "photo" , $photo) ;
    }
  }

  //隨機相簿封面
  private function random_cover($csn="",$pic_size="m"){
    global $xoopsDB;
    if(empty($csn))return;
    //找出分類下所有相片
    $sql = "select * from ".$xoopsDB->prefix("tad_gallery")." where csn='{$csn}' order by rand() limit 0,1";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    list($sn,$db_csn,$title,$description,$filename,$size,$type,$width,$height,$dir,$uid,$post_date,$counter,$exif)=$xoopsDB->fetchRow($result);
    $cover=$this->get_pic_url($dir,$sn,$filename,$pic_size);
    if(empty($cover))$cover=XOOPS_URL."/modules/tadgallery/images/no_photo_available.png";
    return $cover;
  }



  //製作相簿或相片的外框
  public function mk_gallery_border($rel="",$url="",$cover_pic="",$title="",$pass=false,$fcsn=""){

    if($pass){
      $jquery=get_jquery();
      $jquery.="
      <script type='text/javascript'>
        jQuery(document).ready(function()
        {
          jQuery('#pass_col_{$fcsn}').hide();
                jQuery('.GalleryCate').click(function()
          {
            jQuery('#cate_pass_title_{$fcsn}').hide();
            jQuery('#pass_col_{$fcsn}').show();
          });
        });


      </script>";
      $lock="<img src='images/view_lock.gif' alt='view_lock.gif, 1.4kB' title='View lock' border='0' align='absmiddle'>";
      $title_id="cate_pass_title_{$fcsn}";
      $pass_col="<div id='pass_col_{$fcsn}' style='vertical-align: middle;text-align:center;border:0px;width:100px;margin:0px auto;padding-top:105px;color:#606060;font-size:11px;'><form action='{$_SERVER['PHP_SELF']}' method='post'><input type='password' name='passwd' size=12 style='border:1px solid #000;font-size:10px;'><input type='hidden' name='csn' value='{$fcsn}'><input type='submit' value='Go' style='border:1px solid gray;padding:1px 3px;background-color:#cfcfcf;font-size:11px;'></form></div>";
    }else{
      $lock=$jquery=$pass_col="";
    }

    $data="
    $jquery
    <div style='background-image:url({$cover_pic});border:0px solid transparent;' class='GalleryCate'>
      <div class='GalleryCate_txt'>
        <div id='cate_pass_title_{$fcsn}' style='vertical-align: middle;text-align:center;border:0px;width:100px;margin:0px auto;padding-top:105px;color:#606060;font-size:11px;'>{$lock}{$title}</div>
        $pass_col
      </div>
    </div>
    ";


    if($pass){
      $main=$data;
    }else{
      $main="
      <a $rel href='{$url}'>
        $data
      </a>";
    }
    return $main;
  }


  //取得圖片網址
  public function get_pic_url($dir="",$sn="",$filename="",$kind="",$path_kind=""){
    if(empty($filename))return;
    $show_path=($path_kind=="dir")?_TADGAL_UP_FILE_DIR:_TADGAL_UP_FILE_URL;

    if($kind=="m"){
      if(is_file(_TADGAL_UP_FILE_DIR."medium/{$dir}/{$sn}_m_{$filename}")){
        return "{$show_path}medium/{$dir}/{$sn}_m_{$filename}";
      }
    }elseif($kind=="s"){
      if(is_file(_TADGAL_UP_FILE_DIR."small/{$dir}/{$sn}_s_{$filename}")){
        return "{$show_path}small/{$dir}/{$sn}_s_{$filename}";
      }elseif(is_file(_TADGAL_UP_FILE_DIR."medium/{$dir}/{$sn}_m_{$filename}")){
        return "{$show_path}medium/{$dir}/{$sn}_m_{$filename}";
      }
    }elseif($kind=="fb"){
      if(is_file(_TADGAL_UP_FILE_DIR."small/{$dir}/{$sn}_fb_{$filename}")){
        return "{$show_path}small/{$dir}/{$sn}_fb_{$filename}";
      }elseif(is_file(_TADGAL_UP_FILE_DIR."small/{$dir}/{$sn}_s_{$filename}")){
        return "{$show_path}small/{$dir}/{$sn}_s_{$filename}";
      }
    }
    return "{$show_path}{$dir}/{$sn}_{$filename}";
  }


}
?>
