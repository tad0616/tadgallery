<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
$xoopsOption['template_main'] = "tg_upload_tpl.html";

if(sizeof($upload_powers) <= 0 or empty($xoopsUser)){
  redirect_header(XOOPS_URL."/user.php",3, _TADGAL_NO_UPLOAD_POWER);
}

include XOOPS_ROOT_PATH."/header.php";
/*-----------function區--------------*/

function uploads_tabs(){
  global $xoopsTpl,$xoopsModuleConfig;

  $jquery_path=get_jquery(true);
  $now=time();

  $to_batch_upload="";
  if(isset($_REQUEST['op']) and $_REQUEST['op']=='to_batch_upload'){
    $to_batch_upload='$tabs.tabs("select", last_tab);';
  }

  $jquery_ui=$jquery_path.'
  <script type="text/javascript">
  $(document).ready(function() {
    var $tabs = $("#jquery_tabs_tg_'.$now.'").tabs();
    var last_tab=$tabs.tabs("length")-1;
    '.$to_batch_upload.'
  });
  </script>';
  $xoopsTpl->assign( "xoops_module_header" , $jquery_ui);

  $li_1=$div_1=$li_2=$div_2=$li_3=$div_3=$li_4=$div_4=$li_5=$div_5=$li_6=$div_6="";
  if(in_array('one_pic',$xoopsModuleConfig['upload_mode'])){
    $li_1="<li><a href='#upload_one_pic'><span>"._MD_INPUT_FORM."</span></a></li>";
    $div_1="<div id='upload_one_pic'>
        ".tad_gallery_form()."
    </div>";
  }

  if(in_array('batch_pics',$xoopsModuleConfig['upload_mode'])){
    $li_2="<li><a href='#upload_pics'><span>"._MD_TADGAL_MUTI_INPUT_FORM."</span></a></li>";
    $div_2="<div id='upload_pics'>
        ".tad_gallery_muti_form()."
    </div>";
  }

  if(in_array('flash_batch_pics',$xoopsModuleConfig['upload_mode'])){
    $li_3="<li><a href='#upload_flash_pics'><span>Flash"._MD_TADGAL_MUTI_INPUT_FORM."</span></a></li>";
    $div_3="<div id='upload_flash_pics'>
        ".tad_gallery_flash_muti_form($csn)."
    </div>";
  }

  if(in_array('java_batch_pics',$xoopsModuleConfig['upload_mode'])){
    $li_4="<li><a href='jupload.php'><span>"._MD_TADGAL_JAVA_UPLOAD."</span></a></li>";
  }

  if(in_array('zip_batch_pics',$xoopsModuleConfig['upload_mode'])){
    $li_5="<li><a href='#upload_zip_pics'><span>"._MD_TADGAL_ZIP_IMPORT_FORM."</span></a></li>";
    $div_5="<div id='upload_zip_pics'>
        ".tad_gallery_zip_form()."
    </div>";
  }

  if(in_array('upload_xp_pics',$xoopsModuleConfig['upload_mode'])){
    $li_6="<li><a href='#upload_xp_pics'><span>"._MD_TADGAL_XP_UPLOAD."</span></a></li>";
    $div_6="<div id='upload_xp_pics'>
        "._MD_TADGAL_XPPW_PAGE."
    </div>";
  }

  $xoopsTpl->assign('now',$now);
  $xoopsTpl->assign('li_1',$li_1);
  $xoopsTpl->assign('li_2',$li_2);
  $xoopsTpl->assign('li_3',$li_3);
  $xoopsTpl->assign('li_4',$li_4);
  $xoopsTpl->assign('li_5',$li_5);
  $xoopsTpl->assign('li_6',$li_6);
  $xoopsTpl->assign('div_1',$div_1);
  $xoopsTpl->assign('div_2',$div_2);
  $xoopsTpl->assign('div_3',$div_3);
  $xoopsTpl->assign('div_5',$div_5);
  $xoopsTpl->assign('div_6',$div_6);
}


function cate_select_form(){
  global $xoopsDB,$xoopsModule;

  $option=get_tad_gallery_cate_option(0,0,$csn,0,1);

  $main="
  <form action='{$_SERVER['PHP_SELF']}#fragment-3' method='post' id='myForm'>
  <table id='form_tbl' >
  <tr>
  <td nowrap>"._MD_TADGAL_CSN."</td>
  <td><select name='csn' size=1>
    $option
  </select></td>
  </tr>
  <tr>
  <td nowrap>"._MD_TADGAL_NEW_CSN."</td>
  <td><input type='text' name='new_csn' size='30'></td></tr>

  <tr><td class='bar' colspan=2 style='text-align:center;'>
  <input type='submit' value='"._TAD_NEXT."'></td></tr>
  </table>
  </form>";

  return $main;

}


//tad_gallery編輯表單
function tad_gallery_form($sn=""){
  global $xoopsDB;

  //抓取預設值
  if(!empty($sn)){
    $DBV=tadgallery::get_tad_gallery($sn);
  }else{
    $DBV=array();
  }

  //預設值設定

  $sn=(!isset($DBV['sn']))?"":$DBV['sn'];
  $tad_gallery_csn=isset($_SESSION['tad_gallery_csn'])?$_SESSION['tad_gallery_csn']:"";
  $csn=(!isset($DBV['csn']))?$tad_gallery_csn:$DBV['csn'];
  $title=(!isset($DBV['title']))?"":$DBV['title'];
  $description=(!isset($DBV['description']))?"":$DBV['description'];
  $filename=(!isset($DBV['filename']))?"":$DBV['filename'];
  $size=(!isset($DBV['size']))?"":$DBV['size'];
  $type=(!isset($DBV['type']))?"":$DBV['type'];
  $width=(!isset($DBV['width']))?"":$DBV['width'];
  $height=(!isset($DBV['height']))?"":$DBV['height'];
  $dir=(!isset($DBV['dir']))?"":$DBV['dir'];
  $uid=(!isset($DBV['uid']))?"":$DBV['uid'];
  $post_date=(!isset($DBV['post_date']))?"":$DBV['post_date'];
  $counter=(!isset($DBV['counter']))?"":$DBV['counter'];
  $tag=(!isset($DBV['tag']))?"":$DBV['tag'];

  $op=(empty($sn))?"insert_tad_gallery":"update_tad_gallery";


  $option=get_tad_gallery_cate_option(0,0,$csn,0,1);

  $tag_select=tag_select($tag);

  //$op="replace_tad_gallery";
  $main="
  <form action='{$_SERVER['PHP_SELF']}' method='post' id='myForm' enctype='multipart/form-data'>
  <div class='controls-row'>
    <div class='span2'>"._MD_TADGAL_CSN."</div>
    <select name='csn' size=1 class='span5'>
      $option
    </select>
    <input type='text' name='new_csn'  class='span5' placeholder='"._MD_TADGAL_NEW_CSN."'>
  </div>
  <div class='controls-row'>
    <div class='span2'>"._MD_TADGAL_PHOTO."</div>
    <input type='file' name='image' class='span10'>
  </div>
  <div class='controls-row'>
    <div class='span2'>"._MD_TADGAL_TITLE."</div>
    <input type='text' name='title' class='span10' value='{$title}'>
  </div>
  <div class='controls-row'>
    <div class='span2'>"._MD_TADGAL_DESCRIPTION."</div>
    <textarea style='height: 44px; min-height: 44px;font-size:12px;' name='description' class='span10'></textarea>
  </div>
  <div class='controls-row'>
    <div class='span2'>"._MD_TADGAL_TAG."</div>
      <input type='text' name='new_tag' class='span10' placeholder='"._MD_TADGAL_TAG_TXT."'>
  </div>
  <div class='controls-row'>
    $tag_select
    <input type='hidden' name='sn' value='{$sn}'>
    <input type='hidden' name='op' value='{$op}'>
    <button type='submit' class='btn btn-primary'>"._MD_SAVE."</button>
  </div>
  </form>";

  //$main=div_3d(_MD_INPUT_FORM,$main);

  return $main;
}


//新增資料到tad_gallery中
function insert_tad_gallery(){
  global $xoopsDB,$xoopsUser,$xoopsModuleConfig,$type_to_mime;
  if(!empty($_POST['new_csn'])){
    $csn=add_tad_gallery_cate($_POST['csn'],$_POST['new_csn']);
  }else{
    $csn=$_POST['csn'];
  }

  $uid=$xoopsUser->getVar('uid');

  if(!empty($_POST['csn']))$_SESSION['tad_gallery_csn']=$_POST['csn'];


  //處理上傳的檔案
  if(!empty($_FILES['image']['name'])){

    $orginal_file_name= strtolower(basename($_FILES['image']["name"])); //get lowercase filename
    $file_ending= substr(strtolower($orginal_file_name), -3); //file extension

    $pic=getimagesize($_FILES['image']['tmp_name']);
    $width=$pic[0];
    $height=$pic[1];


    //讀取exif資訊
    if(function_exists('exif_read_data')){
      $result = exif_read_data($_FILES['image']['tmp_name'],0,true);
      $creat_date=$result['IFD0']['DateTime'];
    }else{
      $creat_date=date("Y-m-d");
    }
    $dir=(empty($creat_date) or substr($creat_date,0,1)!="2")?date("Y_m_d"):str_replace(":","_",substr($result['IFD0']['DateTime'],0,10));
    $exif=mk_exif($result);

    $now=date("Y-m-d H:i:s",xoops_getUserTimestamp(time()));
    $sql = "insert into ".$xoopsDB->prefix("tad_gallery")." (`csn`,`title`,`description`,`filename`,`size`,`type`,`width`,`height`,`dir`,`uid`,`post_date`,`counter`,`exif`) values('{$csn}','{$_POST['title']}','{$_POST['description']}','{$_FILES['image']['name']}','{$_FILES['image']['size']}','{$_FILES['image']['type']}','{$width}','{$height}','{$dir}','{$uid}','{$now}','0','{$exif}')";
    $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],10, mysql_error().$sql);
    //取得最後新增資料的流水編號
    $sn=$xoopsDB->getInsertId();

    mk_dir(_TADGAL_UP_FILE_DIR.$dir);
    mk_dir(_TADGAL_UP_FILE_DIR."small/".$dir);
    mk_dir(_TADGAL_UP_FILE_DIR."medium/".$dir);

    $filename=photo_name($sn,"source",1);

    if(move_uploaded_file($_FILES['image']['tmp_name'],$filename)){

      $m_thumb_name=photo_name($sn,"m",1);
      $s_thumb_name=photo_name($sn,"s",1);
      $fb_thumb_name=photo_name($sn,"fb",1);
      if(!empty($xoopsModuleConfig['thumbnail_b_width']) and ($width > $xoopsModuleConfig['thumbnail_b_width'] or $height > $xoopsModuleConfig['thumbnail_b_width']))thumbnail($filename,$filename,$type_to_mime[$file_ending],$xoopsModuleConfig['thumbnail_b_width']);
      if($width > $xoopsModuleConfig['thumbnail_m_width'] or $height > $xoopsModuleConfig['thumbnail_m_width']) thumbnail($filename,$m_thumb_name,$type_to_mime[$file_ending],$xoopsModuleConfig['thumbnail_m_width']);
      if($width > $xoopsModuleConfig['thumbnail_s_width'] or $height > $xoopsModuleConfig['thumbnail_s_width']) thumbnail($filename,$s_thumb_name,$type_to_mime[$file_ending],$xoopsModuleConfig['thumbnail_s_width']);
      //給fb
      thumbnail($filename,$fb_thumb_name,$type_to_mime[$file_ending],100);


    }else{
      redirect_header($_SERVER['PHP_SELF'], 5,sprintf(_MD_TADGAL_IMPORT_UPLOADS_ERROR,$filename));
    }
  }

  return $sn;
}



//多檔上傳
function tad_gallery_muti_form(){
  global $xoopsDB,$xoopsModule,$xoopsConfig;

  $csn=$_SESSION['tad_gallery_csn'];
  $option=get_tad_gallery_cate_option(0,0,$csn,0,1);

  $main="
  <script src='".XOOPS_URL."/modules/tadtools/multiple-file-upload/jquery.MultiFile.js'></script>
  <form action='{$_SERVER['PHP_SELF']}' method='post' id='myForm' enctype='multipart/form-data'>
  <table id='form_tbl' >

  <tr><td nowrap>"._MD_TADGAL_CSN."</td>
  <td><select name='csn' size=1>
    $option
  </select></td></tr>
  <tr>
  <td nowrap>"._MD_TADGAL_NEW_CSN."</td>
  <td><input type='text' name='new_csn' size='30'></td></tr>
  <tr><td nowrap>"._MD_TADGAL_PHOTO."</td>
  <td>
  <input type='file' name='upfile[]' class='multi'>
  </td></tr>

  <tr><td class='bar' colspan='2' style='text-align:center;'>
  <input type='hidden' name='op' value='upload_muti_file'>
  <input type='submit' value='"._MD_SAVE."'></td></tr>
  </table>
  </form>";

  //$main=div_3d(_MD_INPUT_FORM,$main);

  return $main;
}


//Flash多檔上傳
function tad_gallery_flash_muti_form($csn){
  global $xoopsDB,$xoopsModule,$xoopsConfig;

  $jquery_path=get_jquery();
  $main="
  <!-- Load Queue widget CSS and jQuery -->
  <style type='text/css'>@import url(".XOOPS_URL."/modules/tadgallery/class/plupload/js/jquery.plupload.queue/css/jquery.plupload.queue.css);</style>
  $jquery_path

  <!-- Third party script for BrowserPlus runtime (Google Gears included in Gears runtime now) -->
  <script type='text/javascript' src='".XOOPS_URL."/modules/tadgallery/class/plupload/browserplus-min.js'></script>


  <!-- Load plupload and all it's runtimes and finally the jQuery queue widget -->
  <script type='text/javascript' src='".XOOPS_URL."/modules/tadgallery/class/plupload/js/plupload.full.js'></script>
  <script type='text/javascript' src='".XOOPS_URL."/modules/tadgallery/class/plupload/js/jquery.plupload.queue/jquery.plupload.queue.js'></script>
  <script type='text/javascript' src='".XOOPS_URL."/modules/tadgallery/class/plupload/js/i18n/{$xoopsConfig['language']}.js'></script>

  <script type='text/javascript'>
  // Convert divs to queue widgets when the DOM is ready
  $(function() {
    $('#uploader').pluploadQueue({
      // General settings
      runtimes : 'gears , flash , browserplus , html5 , html4 ,  silverlight',
      url : 'upload_swf.php?to_dir="._TADGAL_UP_IMPORT_DIR."',
      max_file_size : '100mb',
      //chunk_size : '1mb',
      //unique_names : true,
      //multipart : true,
      //multipart_params: { 'to_dir' : '"._TADGAL_UP_IMPORT_DIR."'},

      // Specify what files to browse for
      filters : [
        {title : 'Image files', extensions : 'jpg,gif,png,PNG,JPG,GIF'}
      ],

      // Flash settings
      flash_swf_url : '".XOOPS_URL."/modules/tadgallery/class/plupload/js/plupload.flash.swf',

      // Silverlight settings
      silverlight_xap_url : '".XOOPS_URL."/modules/tadgallery/class/plupload/js/plupload.silverlight.xap'
    });


    //setup FileUploaded Action
    var uploader = $('#uploader').pluploadQueue();
    uploader.bind('FileUploaded', function(up, file, response) {
      document.location.href='".XOOPS_URL."/modules/tadgallery/uploads.php?op=to_batch_upload';
    });


    // Client side form validation
    $('#flash_up_form').submit(function(e) {

      var total_upload_files = 0;
      var uploader = $('#uploader').pluploadQueue();


      // Validate number of uploaded files
      if (uploader.total.uploaded == 0) {
        // Files in queue upload them first
        if (uploader.files.length > 0) {
          // When all files are uploaded submit form
          uploader.bind('UploadProgress', function() {
            if (uploader.total.uploaded == uploader.files.length)
              $('#flash_up_form').submit();
          });

          uploader.start();
        } else
          alert('You must at least upload one file.');

        e.preventDefault();


      }
    });
  });
  </script>

  <form id='flash_up_form'>
    <div class='controls-row' id='uploader'>
      <p>You browser doesn't have Flash, Silverlight, Gears, BrowserPlus or HTML5 support.</p>
    </div>
    <button class='btn' id='my-text-link'>"._MD_TADGAL_TO_PATCH_UPLOAD_PAGE."</button>
  </form>

  ";


  return $main;
}


//壓縮檔上傳
function tad_gallery_zip_form(){
  global $xoopsDB,$xoopsModule;

  $main="
  <form action='{$_SERVER['PHP_SELF']}' method='post' id='myForm' enctype='multipart/form-data'>
  "._MD_TADGAL_ZIP."<input type='file' name='zipfile'>
  <input type='hidden' name='op' value='upload_zip_file'>
  <input type='submit' value='"._MD_SAVE."'>
  </form>";

  //$main=div_3d(_MD_INPUT_FORM,$main);

  return $main;
}


//上傳圖檔
function upload_muti_file(){
  global $xoopsDB,$xoopsUser,$xoopsModule,$xoopsModuleConfig,$type_to_mime;

  if(!empty($_POST['new_csn'])){
    $csn=add_tad_gallery_cate($_POST['csn'],$_POST['new_csn']);
  }else{
    $csn=$_POST['csn'];
  }

  $uid=$xoopsUser->getVar('uid');

  if(!empty($_POST['csn']))$_SESSION['tad_gallery_csn']=$_POST['csn'];



  //取消上傳時間限制
  set_time_limit(0);

  //設置上傳大小
  ini_set('memory_limit', '100M');

  $files = array();
  foreach ($_FILES['upfile'] as $k => $l) {

    foreach ($l as $i => $v) {
      if(empty($v))continue;
      if (!array_key_exists($i, $files)){
        $files[$i] = array();
      }
      $files[$i][$k] = $v;
    }
  }

  foreach ($files as $i=>$file) {

    if(empty($file['tmp_name']))continue;
    $orginal_file_name= strtolower(basename($file["name"])); //get lowercase filename
    $file_ending= substr(strtolower($orginal_file_name), -3); //file extension

    $pic=getimagesize($file['tmp_name']);
    $width=$pic[0];
    $height=$pic[1];

    //讀取exif資訊
    if(function_exists('exif_read_data')){
      $result = exif_read_data($file['image']['tmp_name'],0,true);
      $creat_date=$result['IFD0']['DateTime'];
    }else{
      $creat_date=date("Y-m-d");
    }

    $dir=(empty($creat_date) or substr($creat_date,0,1)!="2")?date("Y_m_d"):str_replace(":","_",substr($result['IFD0']['DateTime'],0,10));
    $exif=mk_exif($result);

    $now=date("Y-m-d H:i:s",xoops_getUserTimestamp(time()));
    $sql = "insert into ".$xoopsDB->prefix("tad_gallery")." (`csn`,`filename`,`size`,`type`,`width`,`height`,`dir`,`uid`,`post_date`,`counter`,`exif`) values('{$csn}','{$file['name']}','{$file['size']}','{$file['type']}','{$width}','{$height}','{$dir}','{$uid}','{$now}','0','{$exif}')";
    $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],10, mysql_error().$sql);
    //取得最後新增資料的流水編號
    $sn=$xoopsDB->getInsertId();

    mk_dir(_TADGAL_UP_FILE_DIR.$dir);
    mk_dir(_TADGAL_UP_FILE_DIR."small/".$dir);
    mk_dir(_TADGAL_UP_FILE_DIR."medium/".$dir);

    $filename=photo_name($sn,"source",1);

    if(move_uploaded_file($file['tmp_name'],$filename)){

      $m_thumb_name=photo_name($sn,"m",1);
      $s_thumb_name=photo_name($sn,"s",1);
      $fb_thumb_name=photo_name($sn,"fb",1);
      if(!empty($xoopsModuleConfig['thumbnail_b_width']) and ($width > $xoopsModuleConfig['thumbnail_b_width'] or $height > $xoopsModuleConfig['thumbnail_b_width']))thumbnail($filename,$filename,$type_to_mime[$file_ending],$xoopsModuleConfig['thumbnail_b_width']);
      if($width > $xoopsModuleConfig['thumbnail_m_width'] or $height > $xoopsModuleConfig['thumbnail_m_width']) thumbnail($filename,$m_thumb_name,$type_to_mime[$file_ending],$xoopsModuleConfig['thumbnail_m_width']);
      if($width > $xoopsModuleConfig['thumbnail_s_width'] or $height > $xoopsModuleConfig['thumbnail_s_width']) thumbnail($filename,$s_thumb_name,$type_to_mime[$file_ending],$xoopsModuleConfig['thumbnail_s_width']);
      //給fb
      thumbnail($filename,$fb_thumb_name,$type_to_mime[$file_ending],100);
    }
  }
  return $csn;
}




//匯入Flash上傳照片
function save_flash_img($csn=""){
  global $xoopsDB,$xoopsUser,$xoopsModule,$xoopsModuleConfig,$type_to_mime;
$fp = fopen('../../uploads/tadgallery/data.txt', 'w');


  if (isset($_FILES['file'])) { // test if file was posted
fwrite($fp,"有檔案 {$_FILES['file']['name']}");
    $orginal_file_name= strtolower(basename($_FILES['file']['name'])); //get lowercase filename
    $file_ending= substr(strtolower($orginal_file_name), -3); //file extension
    if (in_array(strtolower($file_ending), array("jpg", "gif", "png","peg"))) { // file filter...

      //處理上傳的檔案
      if(!empty($_FILES['file']['name'])){
        $pic=getimagesize($_FILES['file']['tmp_name']);
        $width=$pic[0];
        $height=$pic[1];

        //讀取exif資訊
        if(function_exists('exif_read_data')){
          $result = exif_read_data($_FILES['image']['tmp_name'],0,true);
          $creat_date=$result['IFD0']['DateTime'];
        }else{
          $creat_date=date("Y-m-d");
        }

        $dir=(empty($creat_date) or substr($creat_date,0,1)!="2")?date("Y_m_d"):str_replace(":","_",substr($result['IFD0']['DateTime'],0,10));
        $exif=mk_exif($result);

        $now=date("Y-m-d H:i:s",xoops_getUserTimestamp(time()));
        $sql = "insert into ".$xoopsDB->prefix("tad_gallery")." (`csn`,`filename`,`size`,`type`,`width`,`height`,`dir`,`uid`,`post_date`,`counter`,`exif`) values('{$csn}','{$_FILES['file']['name']}','{$_FILES['file']['size']}','{$_FILES['file']['type']}','{$width}','{$height}','{$dir}','{$uid}','{$now}','0','{$exif}')";
        $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],10, mysql_error().$sql);
        //取得最後新增資料的流水編號
        $sn=$xoopsDB->getInsertId();

        mk_dir(_TADGAL_UP_FILE_DIR.$dir);
        mk_dir(_TADGAL_UP_FILE_DIR."small/".$dir);
        mk_dir(_TADGAL_UP_FILE_DIR."medium/".$dir);

        $filename=photo_name($sn,"source",1);

        if(move_uploaded_file($_FILES['file']['tmp_name'],$filename)){

          $m_thumb_name=photo_name($sn,"m",1);
          $s_thumb_name=photo_name($sn,"s",1);
          $fb_thumb_name=photo_name($sn,"fb",1);

          if(!empty($xoopsModuleConfig['thumbnail_b_width']) and ($width > $xoopsModuleConfig['thumbnail_b_width'] or $height > $xoopsModuleConfig['thumbnail_b_width']))thumbnail($filename,$filename,$type_to_mime[$file_ending],$xoopsModuleConfig['thumbnail_b_width']);
          if($width > $xoopsModuleConfig['thumbnail_m_width'] or $height > $xoopsModuleConfig['thumbnail_m_width'])thumbnail($filename,$m_thumb_name,$type_to_mime[$file_ending],$xoopsModuleConfig['thumbnail_m_width']);
          if($width > $xoopsModuleConfig['thumbnail_s_width'] or $height > $xoopsModuleConfig['thumbnail_s_width']) thumbnail($filename,$s_thumb_name,$type_to_mime[$file_ending],$xoopsModuleConfig['thumbnail_s_width']);
          //給fb
          thumbnail($filename,$fb_thumb_name,$type_to_mime[$file_ending],100);

        }else{
          die(_MD_TADGAL_IMPORT_UPLOADS_ERROR);
        }

      }
    }
  }else{
    die(_MD_TADGAL_IMPORT_UPLOADS_ERROR);
  }
fclose($fp);
}


//上傳壓縮圖檔
function upload_zip_file(){
  global $xoopsDB,$xoopsUser,$xoopsModule,$xoopsModuleConfig,$type_to_mime;

  //取消上傳時間限制
  set_time_limit(0);

  //設置上傳大小
  ini_set('memory_limit', '100M');

  require_once "class/dunzip2/dUnzip2.inc.php";
  require_once "class/dunzip2/dZip.inc.php";
  $zip = new dUnzip2($_FILES['zipfile']['tmp_name']);
  $zip->getList();
  $zip->unzipAll(_TADGAL_UP_IMPORT_DIR);

}

/*-----------執行動作判斷區----------*/
$op=(empty($_REQUEST['op']))?"":$_REQUEST['op'];
$sn=(isset($_REQUEST['sn']))?intval($_REQUEST['sn']) : 0;
$csn=(isset($_REQUEST['csn']))?intval($_REQUEST['csn']) : 0;

switch($op){
  case "insert_tad_gallery":
  $sn=insert_tad_gallery();
  mk_rss_xml();
  mk_rss_xml($csn);
  redirect_header("view.php?sn=$sn", 1 ,sprintf(_MD_TADGAL_IMPORT_UPLOADS_OK,$filename));
  break;

  case "upload_muti_file":
  $csn=upload_muti_file();
  mk_rss_xml();
  mk_rss_xml($csn);
  redirect_header("index.php?csn=$csn", 1 ,sprintf(_MD_TADGAL_IMPORT_UPLOADS_OK,$filename));
  break;

  case "save_flash_img":
  save_flash_img($csn);
  //header("location: index.php?csn=$csn");
  break;

  case "upload_zip_file":
  upload_zip_file();
  header("location: uploads.php#ui-tabs-2");
  break;

  default:
  uploads_tabs();
  break;
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign( "toolbar" , toolbar_bootstrap($interface_menu)) ;
$xoopsTpl->assign( "bootstrap" , get_bootstrap()) ;
$xoopsTpl->assign( "content" , $main) ;
include_once XOOPS_ROOT_PATH.'/footer.php';

?>
