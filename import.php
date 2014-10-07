<?php
include_once "header.php";

$op=(empty($_REQUEST['op']))?"":$_REQUEST['op'];
//die($op);
$csn=(isset($_REQUEST['csn']))?intval($_REQUEST['csn']) : 0;

switch($op){
  case "import_tad_gallery":
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
  $csn=import_tad_gallery($_POST['csn'],$_POST['new_csn'],$_POST['all'],$_POST['import']);
  mk_rss_xml();
  mk_rss_xml($csn);
  header("location: index.php?csn=$csn");
  break;

  default:
  echo import_form();
  break;
}


//tad_gallery編輯表單
function import_form(){
  global $xoopsDB;

  $myts =& MyTextSanitizer::getInstance();
  $option=get_tad_gallery_cate_option(0,0,"","",1);
  $option=to_utf8($option);

  //找出要匯入的圖
  if (is_dir(_TADGAL_UP_IMPORT_DIR)) {
    $pics=read_dir_pic(_TADGAL_UP_IMPORT_DIR);
    $total_size=sizef($pics['total_size']);
  }

  $post_max_size=ini_get('post_max_size');
  //$max_input_vars=ini_get('max_input_vars');
  //預設值設定
  $main="
  <p>"._MD_TADGAL_IMPORT_UPLOAD_TO."<span class='label label-important'>"._TADGAL_UP_IMPORT_DIR."</span></p>
  <form action='".XOOPS_URL."/modules/tadgallery/import.php' method='post' id='myForm'>
  <input type='hidden' name='op' value='import_tad_gallery'>
  <div class='controls-row'>
    <div class='span2'>"._MD_TADGAL_IMPORT_CSN."</div>
    <select name='csn' size=1 class='span5'>
      $option
    </select>
    <input type='text' name='new_csn' class='span5' placeholder='"._MD_TADGAL_IMPORT_NEW_CSN."'>
  </div>

  <table class='table table-striped'>
  <tr>
    <th></th>
    <th>"._MD_TADGAL_IMPORT_FILE."</th>
    <th>"._MD_TADGAL_IMPORT_DIR."</th>
    <th>"._MD_TADGAL_IMPORT_DIMENSION."</th>
    <th>"._MD_TADGAL_IMPORT_SIZE."</th>
    <th>"._MD_TADGAL_IMPORT_STATUS."</th>
  </tr>
  {$pics['pics']}
  <tr><td colspan='6'>
    <button type='submit' class='btn btn-primary'>"._MD_TADGAL_UP_IMPORT."</button></td></tr>
  </table>
  </form>";


  return $main;
}

//讀取目錄下圖片
function read_dir_pic($main_dir=""){
  global $xoopsDB;
  $pics="";
  $post_max_size=ini_get('post_max_size');
  //$size_limit=intval($post_max_size) * 0.5  * 1024 * 1024;

  if(substr($main_dir,-1)!='/')$main_dir=$main_dir."/";

  if ($dh = opendir($main_dir)) {
    $total_size=0;
    $i=1;
    while (($file = readdir($dh)) !== false) {
      if(substr($file,0,1)==".")continue;

      if(is_dir($main_dir.$file)){
        $pics.=read_dir_pic($main_dir.$file);
      }else{
        //讀取exif資訊
        $result = exif_read_data($main_dir.$file,0,true);
        $creat_date=$result['IFD0']['DateTime'];
        $dir=(empty($creat_date) or substr($creat_date,0,1)!="2")?date("Y_m_d"):str_replace(":","_",substr($result['IFD0']['DateTime'],0,10));

        $exif=mk_exif($result);


        $size=filesize($main_dir.$file);

        $total_size+=intval($size);

        $size_txt=sizef($size);
        $pic=getimagesize($main_dir.$file);
        $width=$pic[0];
        $height=$pic[1];


        $subname=strtolower(substr($file,-3));
        if($subname=="jpg" or $subname=="peg"){
          $type="image/jpeg";
        }elseif($subname=="png"){
          $type="image/png";
        }elseif($subname=="gif"){
          $type="image/gif";
        }else{
          $type=$subname;
          continue;
        }

        $sql = "select width,height from ".$xoopsDB->prefix("tad_gallery")." where filename='{$file}' and size='{$size}'";
        $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
        list($db_width,$db_height)=$xoopsDB->fetchRow($result);
        if($db_width==$width and $db_height==$height){
          $checked="disabled='disabled'";
          $upload="0";
          $status=_MD_TADGAL_IMPORT_EXIST;
        //}elseif($total_size >= $size_limit){
        // $checked="disabled='disabled'";
        // $upload="1";
        // $status=sprintf(_MD_TADGAL_IMPORT_OVER_SIZE,sizef($total_size),$post_max_size);
        }else{
          $checked="checked='checked'";
          $upload="1";
          $status=$type;
        }

        if(_CHARSET=="UTF-8")$file=to_utf8($file);

        $pics.="
        <tr>
          <td style='font-size:11px'>$i</td>
          <td style='font-size:11px'>
            <input type='hidden' name='all[$i]' value='".$main_dir.$file."'>
            <input type='checkbox' name='import[$i][upload]' value='1' $checked>
            {$file}
            <input type='hidden' name='import[$i][filename]' value='{$file}'></td>
          <td style='font-size:11px'>$dir<input type='hidden' name='import[$i][dir]' value='{$dir}'></td>
          <td style='font-size:11px'>$width x $height
            <input type='hidden' name='import[$i][post_date]' value='{$creat_date}'>
            <input type='hidden' name='import[$i][width]' value='{$width}'>
            <input type='hidden' name='import[$i][height]' value='{$height}'></td>
          <td style='font-size:11px'>$size_txt<input type='hidden' name='import[$i][size]' value='{$size}'></td>
          <td style='font-size:11px'>{$status}
            <input type='hidden' name='import[$i][exif]' value='{$exif}'>
            <input type='hidden' name='import[$i][type]' value='{$type}'></td>
        </tr>";
        $i++;
      }
    }
    closedir($dh);
  }
  $main['pics']=$pics;
  $main['total_size']=$total_size;
  return $main;
}



//新增資料到tad_gallery中
function import_tad_gallery($csn,$new_csn="",$all=array(),$import=array()){
  global $xoopsDB,$xoopsUser,$xoopsModuleConfig,$type_to_mime;
  //die('aa');
  if(!empty($new_csn)){
    $csn=add_tad_gallery_cate($csn,$new_csn);
  }
  $uid=$xoopsUser->getVar('uid');

  if(!empty($csn))$_SESSION['tad_gallery_csn']=$csn;


  //處理上傳的檔案
  $sort=0;
  foreach($all as $i => $source_file){
    if($import[$i]['upload']!='1'){
      unlink($source_file);
      continue;
    }
    $orginal_file_name= strtolower(basename($import[$i]['filename'])); //get lowercase filename
    $file_ending= substr(strtolower($orginal_file_name), -3); //file extension


    $sql = "insert into ".$xoopsDB->prefix("tad_gallery")." (
      `csn`, `title`, `description`, `filename`, `size`, `type`, `width`, `height`, `dir`, `uid`, `post_date`, `counter`, `exif`, `tag`, `good`, `photo_sort`) values('{$csn}','','','{$import[$i]['filename']}','{$import[$i]['size']}','{$import[$i]['type']}','{$import[$i]['width']}','{$import[$i]['height']}','{$import[$i]['dir']}','{$uid}','{$import[$i]['post_date']}','0','{$import[$i]['exif']}','','0',$sort)";
    $sort++;
    $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],10, mysql_error().$sql);
    //取得最後新增資料的流水編號
    $sn=$xoopsDB->getInsertId();

    set_time_limit(0);

    mk_dir(_TADGAL_UP_FILE_DIR.$import[$i]['dir']);
    mk_dir(_TADGAL_UP_FILE_DIR."small/".$import[$i]['dir']);
    mk_dir(_TADGAL_UP_FILE_DIR."medium/".$import[$i]['dir']);

    $filename=photo_name($sn,"source",1);
    if(rename($source_file,$filename)){

      $m_thumb_name=photo_name($sn,"m",1);
      $s_thumb_name=photo_name($sn,"s",1);

      if(!empty($xoopsModuleConfig['thumbnail_b_width']) and ($import[$i]['width'] > $xoopsModuleConfig['thumbnail_b_width'] or $import[$i]['height'] > $xoopsModuleConfig['thumbnail_b_width'])){
        thumbnail($filename,$filename,$type_to_mime[$file_ending],$xoopsModuleConfig['thumbnail_b_width']);
      }

      if($import[$i]['width'] > $xoopsModuleConfig['thumbnail_m_width'] or $import[$i]['height'] > $xoopsModuleConfig['thumbnail_m_width']){
        thumbnail($filename,$m_thumb_name,$type_to_mime[$file_ending],$xoopsModuleConfig['thumbnail_m_width']);
      }

      if($import[$i]['width'] > $xoopsModuleConfig['thumbnail_s_width'] or $import[$i]['height'] > $xoopsModuleConfig['thumbnail_s_width']){
        thumbnail($filename,$s_thumb_name,$type_to_mime[$file_ending],$xoopsModuleConfig['thumbnail_s_width']);
      }
    }else{
      $sql = "delete from ".$xoopsDB->prefix("tad_gallery")." where sn='$sn'";
      $xoopsDB->query($sql);
      redirect_header($_SERVER['PHP_SELF'], 5, sprintf(_MD_TADGAL_IMPORT_IMPORT_ERROR,$filename));
    }
  }
  rrmdir(_TADGAL_UP_IMPORT_DIR);
  return $csn;
}

?>
