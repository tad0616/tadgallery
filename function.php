<?php
//引入TadTools的函式庫
if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/tad_function.php")){
 redirect_header("http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50",3, _TAD_NEED_TADTOOLS);
}
include_once XOOPS_ROOT_PATH."/modules/tadtools/tad_function.php";

define("_TADGAL_UP_FILE_DIR",XOOPS_ROOT_PATH."/uploads/tadgallery/");
define("_TADGAL_UP_FILE_URL",XOOPS_URL."/uploads/tadgallery/");

$uid_dir=0;
if(isset($xoopsUser) and is_object($xoopsUser)){
  $uid_dir=$xoopsUser->uid();
}

define("_TADGAL_UP_IMPORT_DIR",_TADGAL_UP_FILE_DIR."upload_pics/user_{$uid_dir}/");
mk_dir(_TADGAL_UP_IMPORT_DIR);

define("_TADGAL_UP_MP3_DIR",_TADGAL_UP_FILE_DIR."mp3/");
define("_TADGAL_UP_MP3_URL",_TADGAL_UP_FILE_URL."mp3/");

include_once XOOPS_ROOT_PATH."/modules/tadgallery/class/tadgallery.php";
$type_to_mime['png']="image/png";
$type_to_mime['jpg']="image/jpg";
$type_to_mime['peg']="image/jpg";
$type_to_mime['gif']="image/gif";


$cate_show_mode_array=array('normal'=>_TADGAL_NORMAL,'flickr'=>_TADGAL_FLICKR,'waterfall'=>_TADGAL_WATERFALL);



//製作EXIF語法
function mk_exif($result=array()){

  $Longitude=getGps($result['GPS']["GPSLongitude"], $result['GPS']['GPSLongitudeRef']);
  $Latitude=getGps($result['GPS']["GPSLatitude"], $result['GPS']['GPSLatitudeRef']);

  $main="[FILE][FileName]={$result['FILE']['FileName']}||[FILE][FileType]={$result['FILE']['FileType']}||[FILE][MimeType]={$result['FILE']['MimeType']}||[FILE][FileSize]={$result['FILE']['FileSize']}||[COMPUTED][Width]={$result['COMPUTED']['Width']}||[COMPUTED][Height]={$result['COMPUTED']['Height']}||[IFD0][Make]={$result['IFD0']['Make']}||[IFD0][Model]={$result['IFD0']['Model']}||[IFD0][DateTime]={$result['IFD0']['DateTime']}||[IFD0][Orientation]={$result['IFD0']['Orientation']}||[EXIF][ExposureTime]={$result['EXIF']['ExposureTime']}||[EXIF][ISOSpeedRatings]={$result['EXIF']['ISOSpeedRatings']}||[COMPUTED][ApertureFNumber]={$result['COMPUTED']['ApertureFNumber']}||[EXIF][Flash]={$result['EXIF']['Flash']}||[EXIF][FocalLength]={$result['EXIF']['FocalLength']}mm||[EXIF][ExposureBiasValue]={$result['EXIF']['ExposureBiasValue']}EV||[GPS][latitude]={$Latitude}||[GPS][longitude]={$Longitude}";
  return $main;
}



function getGps($exifCoord, $hemi) {
  $degrees = count($exifCoord) > 0 ? gps2Num($exifCoord[0]) : 0;
  $minutes = count($exifCoord) > 1 ? gps2Num($exifCoord[1]) : 0;
  $seconds = count($exifCoord) > 2 ? gps2Num($exifCoord[2]) : 0;

  $flip = ($hemi == 'W' or $hemi == 'S') ? -1 : 1;

  return $flip * ($degrees + $minutes / 60 + $seconds / 3600 );
}

function gps2Num($coordPart) {

  $parts = explode('/', $coordPart);

  if (count($parts) <= 0)
    return 0;

  if (count($parts) == 1)
    return $parts[0];

  return floatval($parts[0]) / floatval($parts[1]);
}


//上傳者選單
function get_all_author($now_uid=""){
  global $xoopsDB;
  $sql = "select distinct uid from ".$xoopsDB->prefix("tad_gallery")."";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error()."<br>$sql");
  $option="<option value=''>"._MD_TADGAL_ALL_AUTHOR."</option>";
  while(list($uid)=$xoopsDB->fetchRow($result)){

    $uid_name=XoopsUser::getUnameFromId($uid,1);
    $uid_name=(empty($uid_name))?XoopsUser::getUnameFromId($uid,0):$uid_name;

    $selected=($now_uid==$uid)?"selected":"";
    $option.="<option value='{$uid}' $selected>{$uid_name}</option>";
  }
  return $option;
}

//封面圖選單
function get_cover($csn="",$cover=""){
  global $xoopsDB;
  if(empty($csn))return "<option value=''>"._MD_TADGAL_COVER."</option>";

  $sql = "select csn from ".$xoopsDB->prefix("tad_gallery_cate")." where csn='{$csn}' or of_csn='{$csn}'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error()."<br>$sql");
  while(list($all_csn)=$xoopsDB->fetchRow($result)){
    $csn_arr[]=$all_csn;
  }

  $csn_arr_str=implode(",",$csn_arr);

  $sql = "select sn,dir,filename from ".$xoopsDB->prefix("tad_gallery")." where csn in($csn_arr_str)  order by filename";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error()."<br>$sql");
  //$option="<option value=''>"._MD_TADGAL_COVER."</option>";
  $option="";
  while(list($sn,$dir,$filename)=$xoopsDB->fetchRow($result)){
    $selected=($cover=="small/{$dir}/{$sn}_s_{$filename}")?"selected":"";
    $option.="<option value='small/{$dir}/{$sn}_s_{$filename}' $selected>{$filename}</option>";
  }
  return $option;
}


//取出所有標籤(傳回陣列)
function get_all_tag(){
  global $xoopsDB;
  $tag_all = array();
  $sql = "select tag from ".$xoopsDB->prefix("tad_gallery")." where tag!=''";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error()."<br>$sql");
  while(list($tag)=$xoopsDB->fetchRow($result)){

    $tag_arr=explode(",",$tag);

    foreach($tag_arr as $val){
      $val=trim($val);
      $tag_all[$val]++;
    }
  }
  return $tag_all;
}

//製作標籤勾選單
function tag_select($tag="",$id_name=""){

  $tag_arr=explode(",",$tag);

  $tag_all=get_all_tag();
  $menu="";
  foreach($tag_all as $tag=>$n){
    if(empty($tag))continue;
    $checked=(in_array($tag,$tag_arr))?"checked":"";
    $js=(!empty($id_name))?" onClick=\"check_one('{$id_name}',false)\" onfocus=\"check_one('{$id_name}',false)\"":"";
    $menu.="
    <label class=\"checkbox inline\">
      <input type=\"checkbox\" name=\"tag[{$tag}]\" value=\"{$tag}\" {$checked} {$js}>{$tag}
    </label>
    ";
  }
  return $menu;
}


//變更精選相片狀態
function update_tad_gallery_good($sn="",$v='0'){
  global $xoopsDB;
   $sql = "update ".$xoopsDB->prefix("tad_gallery")." set `good`='{$v}' where sn='{$sn}'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],10, mysql_error());
}



//找出上一張或下一張
function get_pre_next($csn="",$sn=""){
  global $xoopsDB;
  $sql = "select sn from ".$xoopsDB->prefix("tad_gallery")." where csn='{$csn}' order by photo_sort , post_date";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $stop=false;
  $pre=0;
  while(list($psn)=$xoopsDB->fetchRow($result)){
    if($stop){
      $next=$psn;
      break;
    }
    if($psn==$sn){
      $now=$psn;
      $stop=true;
    }else{
      $pre=$psn;
     }
  }
  $main['pre']=$pre;
  $main['next']=$next;
  return $main;
}




//刪除tad_gallery某筆資料資料
function delete_tad_gallery($sn=""){
  global $xoopsDB;

  $tadgallery=new tadgallery();

  $pic=$tadgallery->get_tad_gallery($sn);

  $sql = "delete from ".$xoopsDB->prefix("tad_gallery")." where sn='$sn'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

  if(is_file(_TADGAL_UP_FILE_DIR."small/{$pic['dir']}/{$sn}_s_{$pic['filename']}")){
    unlink(_TADGAL_UP_FILE_DIR."small/{$pic['dir']}/{$sn}_s_{$pic['filename']}");
  }

  if(is_file(_TADGAL_UP_FILE_DIR."medium/{$pic['dir']}/{$sn}_m_{$pic['filename']}")){
    unlink(_TADGAL_UP_FILE_DIR."medium/{$pic['dir']}/{$sn}_m_{$pic['filename']}");
  }

  unlink(_TADGAL_UP_FILE_DIR."{$pic['dir']}/{$sn}_{$pic['filename']}");

  return $pic['csn'];
}


//把檔案大小轉為文字型態
function sizef($size="",$html=true){
  if($size > 1048576){
    $size_txt=($html)?round($size/1048576,1)." <font color=red>MB</font>":round($size/1048576,1)." MB";
  }elseif($size > 1024){
    $size_txt=($html)?round($size/1024,1)." <font color=blue>KB</font>":round($size/1024,1)." KB";
  }else{
    $size_txt=($html)?$size." <font color=gray>Bytes</font>":$size." Bytes";
  }
  return $size_txt;
}


//取得分類下拉選單
function get_tad_gallery_cate_option($of_csn=0,$level=0,$v="",$chk_view=1,$chk_up=0,$this_csn="",$no_self="0"){
  global $xoopsDB,$xoopsUser,$xoopsModule;

  if ($xoopsUser) {
    $module_id = $xoopsModule->getVar('mid');
    $isAdmin=$xoopsUser->isAdmin($module_id);
  }else{
    $isAdmin=false;
  }

  $tadgallery=new tadgallery();
  $show_uid=isset($_SESSION['show_uid'])?intval($_SESSION['show_uid']):0;
  if($show_uid)$tadgallery->set_show_uid($show_uid);
  $cate_count=$tadgallery->get_tad_gallery_cate_count();

  //$left=$level*10;
  $level+=1;

  $syb=str_repeat("-", $level)." ";

  $option=($of_csn)?"":"<option value='0'>"._MD_TADGAL_CATE_SELECT."</option>";


  $sql = "select csn,title from ".$xoopsDB->prefix("tad_gallery_cate")." where of_csn='{$of_csn}' order by sort";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

  $ok_cat=$ok_up_cat="";

  if($chk_view)$ok_cat=$tadgallery->chk_cate_power();
  if($chk_up)$ok_up_cat=$tadgallery->chk_cate_power("upload");

  while(list($csn,$title)=$xoopsDB->fetchRow($result)){
    if($chk_view and is_array($ok_cat)){
      if(!in_array($csn,$ok_cat)){
        continue;
      }
    }

    if($chk_up and is_array($ok_up_cat)){
      if(!in_array($csn,$ok_up_cat)){
        continue;
      }
    }
    if($no_self=='1' and $this_csn==$csn)continue;
    $selected=($v==$csn)?"selected":"";
    $count=(empty($cate_count[$csn]['file']))?0:$cate_count[$csn]['file'];
    $option.="<option value='{$csn}' $selected>{$syb}{$title}({$count})</option>";
    $option.=get_tad_gallery_cate_option($csn,$level,$v,$chk_view,$chk_up,$this_csn,$no_self);
  }
  return $option;
}



//把多重陣列變成字串
function implodeArray2D ($sep="", $array="",$pre=""){
  $myts =& MyTextSanitizer::getInstance();
  $array1=array("FILE","COMPUTED","IFD0","EXIF","GPS","SubIFD","IFD1","GPSLongitude","GPSLatitude");
  $str = "";
  foreach ($array as $k=>$v){
      if(is_array($v)){
  if(!in_array($k,$array1))continue;
  $str.=implodeArray2D ($sep, $v ,"[$k]");
      }else{
  $k=$myts->addSlashes ($k);
  $v=$myts->addSlashes ($v);
  if(substr($k,0,12)=="UndefinedTag")continue;
  if(strlen($v) > 200)continue;
  $str.= "{$pre}[$k]={$v}{$sep}";
      }
  }
  return $str;
}





//更新tad_gallery_cate某一筆資料
function update_tad_gallery_cate($csn=""){
  global $xoopsDB,$xoopsUser;

  if($xoopsUser){
    $uid=$xoopsUser->uid();
  }else{
    redirect_header(XOOPS_URL."/user.php",3, _TADGAL_NO_UPLOAD_POWER);
  }

  if(empty($_POST['enable_group']) or in_array("",$_POST['enable_group'])){
    $enable_group="";
  }else{
    $enable_group=implode(",",$_POST['enable_group']);
  }

  if(empty($_POST['enable_upload_group'])){
    $enable_upload_group="1";
  }else{
    $enable_upload_group=implode(",",$_POST['enable_upload_group']);
  }
  $sql = "update ".$xoopsDB->prefix("tad_gallery_cate")." set of_csn = '{$_POST['of_csn']}', title = '{$_POST['title']}', passwd = '{$_POST['passwd']}', enable_group = '{$enable_group}', enable_upload_group = '{$enable_upload_group}' , mode = '{$_POST['mode']}', show_mode = '{$_POST['show_mode']}',uid='{$uid}', cover = '{$_POST['cover']}' where csn='$csn'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  return $csn;
}


//刪除tad_gallery_cate某筆資料資料
function delete_tad_gallery_cate($csn=""){
  global $xoopsDB;

  //先找出底下所有相片
  $sql="select sn from ".$xoopsDB->prefix("tad_gallery")." where csn='$csn'";
  $result=$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  while(list($sn)=$xoopsDB->fetchRow($result)){
    delete_tad_gallery($sn);
  }

  //找出底下分類，並將分類的所屬分類清空
  $sql="update ".$xoopsDB->prefix("tad_gallery_cate")." set  of_csn='' where of_csn='$csn'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

  //刪除之
  $sql = "delete from ".$xoopsDB->prefix("tad_gallery_cate")." where csn='$csn'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

  //刪掉RSS
  $rss_filename =_TADGAL_UP_FILE_DIR."photos{$csn}.rss";
	unlink($rss_filename);

}


//更新資料到tad_gallery中
function update_tad_gallery($sn=""){
	global $xoopsDB,$xoopsUser;
	if(!empty($_POST['new_csn'])){
    $csn=add_tad_gallery_cate($_POST['csn'],$_POST['new_csn']);
	}else{
		$csn=$_POST['csn'];
	}

	$uid=$xoopsUser->getVar('uid');

	if(!empty($_POST['csn']))$_SESSION['tad_gallery_csn']=$_POST['csn'];

	//$myts =& MyTextSanitizer::getInstance();

 	$sql = "update ".$xoopsDB->prefix("tad_gallery")." set `csn`='{$csn}',`title`='{$_POST['title']}',`description`='{$_POST['description']}' where sn='{$sn}'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],10, mysql_error());

	//設為封面
	if(!empty($_POST['cover'])){
	 	$sql = "update ".$xoopsDB->prefix("tad_gallery_cate")." set `cover`='{$_POST['cover']}' where csn='{$_POST['csn']}'";
		$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],10, mysql_error());
	}

}



//自動取得某分類下最大的排序
function auto_get_csn_sort($csn=""){
  global $xoopsDB;
  $sql = "select max(`sort`) from ".$xoopsDB->prefix("tad_gallery_cate")." where of_csn='{$csn}' group by of_csn";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  list($max_sort)=$xoopsDB->fetchRow($result);

  return ++$max_sort;
}


//新增資料到tad_gallery_cate中
function add_tad_gallery_cate($csn="",$new_csn=""){
  global $xoopsDB,$xoopsUser,$isAdmin;
  if(empty($new_csn))return;
  $upload_powers=tadgallery::chk_cate_power("upload");
  if($isAdmin){
    $upload_powers[]=0;
  }

  //身份篩選
  if(!in_array($csn,$upload_powers)){
    redirect_header($_SERVER['PHP_SELF'],3, _TADGAL_NO_UPLOAD_POWER);
  }

  if(empty($_POST['enable_group'])){
    $enable_group="";
  }else{
    $enable_group=implode(",",$_POST['enable_group']);
  }

  if(empty($_POST['enable_upload_group'])){
    $enable_upload_group="1";
  }else{
    $enable_upload_group=implode(",",$_POST['enable_upload_group']);
  }

  $sort=(empty($_POST['sort']))?auto_get_csn_sort():$_POST['sort'];
  $uid=$xoopsUser->getVar('uid');

  $sql = "insert into ".$xoopsDB->prefix("tad_gallery_cate")." (
    `of_csn`, `title`, `content`, `passwd`, `enable_group`, `enable_upload_group`, `sort`, `mode`, `show_mode`, `cover`, `no_hotlink`, `uid`) values('{$csn}','{$new_csn}','','','{$enable_group}','{$enable_upload_group}','$sort','{$_POST['mode']}','normal','','','{$uid}')";
  $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  //取得最後新增資料的流水編號
  $csn=$xoopsDB->getInsertId();
  return $csn;
}


//取得tad_gallery_cate所有資料陣列
function get_tad_gallery_cate_all(){
  global $xoopsDB;
  $sql = "select csn,title from ".$xoopsDB->prefix("tad_gallery_cate");
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  while(list($csn,$title)=$xoopsDB->fetchRow($result)){
    $data[$csn]=$title;
  }
  return $data;
}



/********************* 圖片函數 *********************/
//圖片位置及名稱
function photo_name($sn="",$kind="",$local="1",$filename="",$dir=""){
  global $xoopsDB;
  if(empty($filename)){
    $sql = "select filename,dir from ".$xoopsDB->prefix("tad_gallery")." where sn='{$sn}'";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    list($filename,$dir)=$xoopsDB->fetchRow($result);
  }
  $place=($local)?_TADGAL_UP_FILE_DIR:_TADGAL_UP_FILE_URL;

  if($kind=="m"){
    $k="m_";
    $place.="medium/";
  }elseif($kind=="s"){
    $k="s_";
    $place.="small/";
  }else{
    $k="";
  }
  $photo_name="{$place}{$dir}/{$sn}_{$k}{$filename}";
  return $photo_name;
}


//做縮圖
if(!function_exists('thumbnail')){
  function thumbnail($filename="",$thumb_name="",$type="image/jpeg",$width="160"){

    set_time_limit(0);
    ini_set('memory_limit', '100M');
    // Get new sizes
    list($old_width, $old_height) = getimagesize($filename);

    //檢查是否是180度的相片
    $pic180=(($old_width/$old_height) > 2 and ($old_width/$width) > 2 )?true:false;

    if($pic180){
    $height=$width * 0.75;
    $percent = round($height/$old_height,2);

    $newwidth = round($old_width * $percent,0);
    $newheight = round($old_height * $percent,0);
    }else{
      $percent=($old_width>$old_height)?round($width/$old_width,2):round($width/$old_height,2);

      $newwidth = ($old_width>$old_height)?$width:round($old_width * $percent,0);
      $newheight = ($old_width>$old_height)?round($old_height * $percent,0):$width;
    }


    // Load
    $thumb = imagecreatetruecolor($newwidth, $newheight);
    if($type=="image/jpeg" or $type=="image/jpg" or $type=="image/pjpg" or $type=="image/pjpeg"){
      $source = imagecreatefromjpeg($filename);
      $type="image/jpeg";
    }elseif($type=="image/png"){
      $source = imagecreatefrompng($filename);
      $type="image/png";
    }elseif($type=="image/gif"){
      $source = imagecreatefromgif($filename);
      $type="image/gif";
    }else{
      die($type);
    }

    // Resize
    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $old_width, $old_height);


    // Output
    if($type=="image/jpeg"){
      imagejpeg($thumb,$thumb_name,90);
    }elseif($type=="image/png"){
      imagepng($thumb,$thumb_name);
    }elseif($type=="image/gif"){
      imagegif($thumb,$thumb_name);
    }
    imagedestroy($source);
    imagedestroy($thumb);
  }
}

//取得路徑
function get_tadgallery_cate_path($csn="",$sub=false,$sn=""){
   global $xoopsDB;

 if(!$sub){
    $home[_TAD_TO_MOD]=XOOPS_URL."/modules/tadgallery/index.php";
 }else{
    $home=array();
 }

 if(!empty($sn)){
    $pic=tadgallery::get_tad_gallery($sn);
    $csn=$pic['csn'];
 }

 $sql = "select title,of_csn from ".$xoopsDB->prefix("tad_gallery_cate")." where csn='{$csn}'";
 $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
 list($title,$of_csn)=$xoopsDB->fetchRow($result);

 $opt_sub=(!empty($of_csn))?get_tadgallery_cate_path($of_csn,true):"";
  $opt="";
 if(!empty($title)){
  $opt[$title]=XOOPS_URL."/modules/tadgallery/index.php?csn=$csn";
 }
 if(is_array($opt_sub)){
    $path=array_merge($home,$opt_sub,$opt);
 }elseif(is_array($opt)){
    $path=array_merge($home,$opt);
 }else{
    $path=$home;
 }
   return $path;
}



/********************* 預設函數 *********************/


//製作Media RSS
function mk_rss_xml($the_csn=""){
  global $xoopsDB,$xoopsModule,$xoopsConfig;
  $tadgallery=new tadgallery();
  $ok_cat=$tadgallery->chk_cate_power();

  if(!empty($the_csn)){
    if(in_array($the_csn,$ok_cat)){
      $where="and a.csn='$the_csn'";
      $cate=$tadgallery->get_tad_gallery_cate($the_csn);
      $rss_title=$cate['title'];
      $rss_link=XOOPS_URL."/modules/tadgallery/index.php?csn={$the_csn}";
      $rss_filename =_TADGAL_UP_FILE_DIR."photos{$the_csn}.rss";
    }else{
      return;
    }
  }else{
    $ok=implode("','",$ok_cat);
    $where="and a.csn in('{$ok}') ";
    $rss_title=$xoopsConfig['sitename'];
    $rss_link=XOOPS_URL."/modules/tadgallery";
    $rss_filename =_TADGAL_UP_FILE_DIR."photos.rss";
  }

  $sql = "select a.sn,a.csn,a.title,a.description,a.filename,a.size,a.dir from ".$xoopsDB->prefix("tad_gallery")." as a , ".$xoopsDB->prefix("tad_gallery_cate")." as b where a.csn=b.csn $where and b.passwd='' and b.enable_group='' order by a.post_date desc";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error()."<br>$sql");

  $main="<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\"?>
<rss version=\"2.0\" xmlns:media=\"http://search.yahoo.com/mrss/\" xmlns:atom=\"http://www.w3.org/2005/Atom\">
  <channel>
  <atom:icon>".XOOPS_URL."/modules/tadgallery/images/piclen_logo.png</atom:icon>
  <generator>Tad Gallery</generator>
  <title>{$rss_title}</title>
  <link>{$rss_link}</link>
  <description></description>\n";

  while(list($sn,$csn,$title,$description,$filename,$size,$dir)=$xoopsDB->fetchRow($result)){

    $title=(empty($title))?$filename:$title;
    $title=htmlspecialchars($title);
    $description=htmlspecialchars($description);
    $filename=htmlspecialchars($filename);
    $pic_url=$tadgallery->get_pic_url($dir,$sn,$filename);
    $mpic_url=$tadgallery->get_pic_url($dir,$sn,$filename,"m");
    $spic_url=$tadgallery->get_pic_url($dir,$sn,$filename,"s");
    $main.="    <item>
      <title>{$title}</title>
      <link>".XOOPS_URL."/modules/tadgallery/view.php?sn={$sn}</link>
      <guid>{$sn}-{$csn}</guid>
      <media:thumbnail url=\"{$spic_url}\"/>
      <media:content url=\"{$pic_url}\" fileSize=\"{$size}\" />
      <media:title type=\"plain\">{$title}</media:title>
      <media:description type=\"plain\">{$description}</media:description>
    </item>\n";

  }
  $main.="      </channel>
</rss>\n";

  $main=to_utf8($main);


  if (!$handle = fopen($rss_filename, 'w')) {
    redirect_header($_SERVER['PHP_SELF'],3, sprintf(_MD_TADPLAYER_CANT_OPEN,$rss_filename));
  }

  if (fwrite($handle, $main) === FALSE) {
    redirect_header($_SERVER['PHP_SELF'],3, sprintf(_MD_TADPLAYER_CANT_WRITE,$rss_filename));
  }
  fclose($handle);

}

if (!function_exists('file_put_contents')) {
  function file_put_contents($filename, $data) {
    $f = @fopen($filename, 'w');
    if (!$f) {
      return false;
    } else {
      $bytes = fwrite($f, $data);
      fclose($f);
      return $bytes;
    }
  }
}

?>
