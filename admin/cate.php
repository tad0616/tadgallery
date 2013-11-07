<?php
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = "tg_adm_cate_tpl.html";
include_once "header.php";
include_once "../function.php";


/*-----------function區--------------*/
//tad_gallery_cate編輯表單
function tad_gallery_cate_form($csn=""){
  global $xoopsDB,$xoopsModuleConfig,$cate_show_mode_array;
  include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");

  //抓取預設值
  if(!empty($csn)){
    $DBV=tadgallery::get_tad_gallery_cate($csn);
  }else{
    $DBV=array();
  }

  //預設值設定

  $csn=(!isset($DBV['csn']))?"":$DBV['csn'];
  $of_csn=(!isset($DBV['of_csn']))?"":$DBV['of_csn'];
  $title=(!isset($DBV['title']))?"":$DBV['title'];
  $enable_group=(!isset($DBV['enable_group']))?"":explode(",",$DBV['enable_group']);
  $enable_upload_group=(!isset($DBV['enable_upload_group']))?array('1'):explode(",",$DBV['enable_upload_group']);
  $sort=(!isset($DBV['sort']))?auto_get_csn_sort():$DBV['sort'];
  $passwd=(!isset($DBV['passwd']))?"":$DBV['passwd'];
  $mode=(!isset($DBV['mode']))?"":$DBV['mode'];
  $show_mode=(!isset($DBV['show_mode']))?$xoopsModuleConfig['index_mode']:$DBV['show_mode'];
  $cover=(!isset($DBV['cover']))?"":$DBV['cover'];

  $op=(empty($csn))?"insert_tad_gallery_cate":"update_tad_gallery_cate";

  $cate_select=get_tad_gallery_cate_option(0,0,$of_csn,"","",$csn,1);
  $cover_select=get_cover($csn,$cover);


  //可見群組
  $SelectGroup_name = new XoopsFormSelectGroup("", "enable_group", false,$enable_group, 4, true);
  $SelectGroup_name->addOption("", _MA_TADGAL_ALL_OK, false);
	$SelectGroup_name->setExtra("class='span12'");
  $enable_group = $SelectGroup_name->render();

  //可上傳群組
  $SelectGroup_name = new XoopsFormSelectGroup("", "enable_upload_group", false,$enable_upload_group, 4, true);
  //$SelectGroup_name->addOption("", _MA_TADGAL_ALL_OK, false);
	$SelectGroup_name->setExtra("class='span12'");
  $enable_upload_group = $SelectGroup_name->render();

  $cate_show_option="";
  foreach($cate_show_mode_array as $key=>$value){
    $selected=($show_mode==$key)?"selected='selected'":"";
    $cate_show_option.="<option value='$key' $selected>$value</option>";
  }


  $cover_default=(!empty($cover))?XOOPS_URL."/uploads/tadgallery/{$cover}":"../images/folder_picture.png";

  $main="
  <script type='text/javascript'>
  $(document).ready(function() {

    $('#shl_form').hide();
    $('#show_shl_form').click(function() {
      if ($('#shl_form').is(':visible')) {
         $('#shl_form').slideUp();
         $('#show_shl_form').val('"._MA_TADGAL_CATE_SHL_SETUP."');
      } else{
         $('#shl_form').slideDown();
         $('#show_shl_form').val('"._MA_TADGAL_CATE_HIDE_SHL_SETUP."');
      }
    });
  });
  </script>


  <form action='{$_SERVER['PHP_SELF']}' method='post' id='myForm' enctype='multipart/form-data'>
  <div class='row-fluid'>
    <div class='span1'>"._MA_TADGAL_OF_CSN."</div>
    <select name='of_csn' size=1 class='span3'>
    $cate_select
    </select>
  	<input type='text' name='title' class='span7' value='{$title}' placeholder='"._MA_TADGAL_TITLE."'>
    <input type='hidden' name='sort' value='{$sort}'>
    <input type='hidden' name='csn' value='{$csn}'>
    <input type='hidden' name='op' value='{$op}'>
    <button type='submit' class='btn btn-primary'>"._TAD_SAVE."</button>
  </div>

  <div class='row-fluid'>
    <div class='span2'>
      <div>"._MA_TADGAL_COVER."</div>
      <select class='span12' name='cover' size=6 onChange='document.getElementById(\"pic\").src=\"".XOOPS_URL."/uploads/tadgallery/\" + this.value'>
      $cover_select
      </select>
    </div>

    <div class='span2'>
      <img src='{$cover_default}' id='pic' vspace=4 class='img-rounded ' >
    </div>

    <div class='span2'>
      <div>"._MA_TADGAL_PASSWD."</div>
      <input type='text' name='passwd' class='span12' value='{$passwd}'>"._MA_TADGAL_PASSWD_DESC."
    </div>
    <div class='span2'>
      <div>"._MA_TADGAL_ENABLE_GROUP."</div>
      $enable_group
    </div>
    <div class='span2'>
      <div>"._MA_TADGAL_ENABLE_UPLOAD_GROUP."</div>
      $enable_upload_group
    </div>
    <div class='span2'>
      <div>"._MA_TADGAL_CATE_SHOW_MODE."</div>
      <select name='show_mode' class='span12'>
      $cate_show_option
      </select>
    </div>
  </div>

  </form>";


  return $main;
}

//新增資料到tad_gallery_cate中
function insert_tad_gallery_cate(){
  global $xoopsDB;
  if(empty($_POST['title']))return;
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

  $sql = "insert into ".$xoopsDB->prefix("tad_gallery_cate")." (of_csn,title,passwd,enable_group,enable_upload_group,sort,mode,show_mode) values('{$_POST['of_csn']}','{$_POST['title']}','{$_POST['passwd']}','{$enable_group}','{$enable_upload_group}','{$_POST['sort']}','{$_POST['mode']}','{$_POST['show_mode']}')";
  $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  //取得最後新增資料的流水編號
  $csn=$xoopsDB->getInsertId();
  return $csn;
}

//列出所有tad_gallery_cate資料
function list_tad_gallery_cate($of_csn=1,$level=0,$modify_csn=""){
  global $xoopsDB;
  $old_level=$level;
  $left=$level*20;
  $level++;

  $sql = "select csn,of_csn,title,passwd,enable_group,enable_upload_group,sort,mode,cover from ".$xoopsDB->prefix("tad_gallery_cate")." where of_csn='{$of_csn}' order by sort";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());


  if($old_level==0){

    $form=tad_gallery_cate_form($modify_csn);

    //加入圖片提示
    if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/bubblepopup.php")){
     redirect_header("index.php",3, _MA_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH."/modules/tadtools/bubblepopup.php";
    $bubblepopup = new bubblepopup(false);
    $sql_cover="select csn,cover from ".$xoopsDB->prefix("tad_gallery_cate")." order by sort";
    $result_cover = $xoopsDB->query($sql_cover) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    while(list($csn,$cover)=$xoopsDB->fetchRow($result_cover)){
      if(!empty($cover)){
        $bubblepopup->add_tip("#cover_{$csn}","<img src=\'".XOOPS_URL."/uploads/tadgallery/{$cover}\'>",'right');
      }
    }
    $bubblepopup_code=$bubblepopup->render();

    //加入表格樹
    if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/treetable.php")){
       redirect_header("index.php",3, _MA_NEED_TADTOOLS);
      }
    include_once XOOPS_ROOT_PATH."/modules/tadtools/treetable.php";


    $treetable=new treetable(true , "csn" , "of_csn" , "#tbl" , "save_drag.php" , ".folder" , "#save_msg" , true , ".sort", "save_cate_sort.php" , "#save_msg");
    $treetable_code=$treetable->render();

    $data="
    $treetable_code
    $bubblepopup_code
    <script>
    function delete_tad_gallery_cate_func(csn){
      var sure = window.confirm('"._TAD_DEL_CONFIRM."');
      if (!sure)  return;
      location.href=\"{$_SERVER['PHP_SELF']}?op=delete_tad_gallery_cate&csn=\" + csn;
    }
    </script>
    <div id='save_msg' style='float:right;'></div>

    <table class='table table-striped table-bordered'>
    <tr><td colspan=5>$form</td></tr>
    <tr>
    <th>"._MA_TADGAL_TITLE."</th>
    <th>"._MA_TADGAL_ENABLE_GROUP."</th>
    <th>"._MA_TADGAL_ENABLE_UPLOAD_GROUP."</th>
    <th>"._MA_TADGAL_RE_CREATE_THUMBNAILS."</th>
    <th>"._TAD_FUNCTION."</th>
    </tr>
    <tbody class='sort'>";
  }else{
    $data="";
  }

  $tadgallery=new tadgallery();
  $cate_count=$tadgallery->get_tad_gallery_cate_count();


  while(list($csn,$of_csn,$title,$passwd,$enable_group,$enable_upload_group,$sort,$mode,$cover)=$xoopsDB->fetchRow($result)){
    $g_txt=txt_to_group_name($enable_group,_MA_TADGAL_ALL_OK,",");
    $gu_txt=txt_to_group_name($enable_upload_group,_MA_TADGAL_ALL_OK,",");
    $passwd_txt=(empty($passwd))?"":""._MA_TADGAL_PASSWD.":{$passwd}";
    $lock=(empty($passwd))?"":" (<span style='color:red;margin:3px 0px;'>$passwd_txt</span>)";
    $pic=(empty($cover))?"":"<img src='../images/image.png' id='cover_{$csn}'>";

    $class=(empty($of_csn))?"":"class='child-of-node-_{$of_csn}'";

    $dir_count=$cate_count[$csn]['dir']?"<i class='icon-folder-open'></i> {$cate_count[$csn]['dir']}":"";
    $file_count=$cate_count[$csn]['file']?"<i class='icon-picture'></i> {$cate_count[$csn]['file']}":"";

    $data.="
    <tr id='node-_{$csn}' $class style='letter-spacing: 0em;'>

    <td nowrap>
    <img src='".XOOPS_URL."/modules/tadtools/treeTable/images/move_s.png' class='folder' alt='"._MA_TREETABLE_MOVE_PIC."' title='"._MA_TREETABLE_MOVE_PIC."'>

    <a href='../index.php?csn=$csn'>{$title}</a>
    {$pic}{$lock}
    $dir_count
    $file_count
    </td>
    <td>{$g_txt}</td>
    <td>{$gu_txt}</td>
    <td>
    <img src='".XOOPS_URL."/modules/tadtools/treeTable/images/updown_s.png' style='cursor: s-resize;' alt='"._MA_TREETABLE_SORT_PIC."' title='"._MA_TREETABLE_SORT_PIC."'>
    <a href='cate.php?op=re_thumb&csn=$csn' class='btn btn-mini btn-primary'>"._MA_TADGAL_RE_CREATE_THUMBNAILS_ALL."</a>
    <a href='cate.php?op=re_thumb&kind=m&csn=$csn' class='btn btn-mini btn-info'>"._MA_TADGAL_RE_CREATE_THUMBNAILS_M."</a>
    <a href='cate.php?op=re_thumb&kind=s&csn=$csn' class='btn btn-mini btn-info'>"._MA_TADGAL_RE_CREATE_THUMBNAILS_S."</a>
    <td style='line-height:150%;'>
    <a href=\"javascript:delete_tad_gallery_cate_func($csn);\" class='btn btn-mini btn-danger'>"._TAD_DEL."</a>

    <a href='{$_SERVER['PHP_SELF']}?op=tad_gallery_cate_form&csn=$csn' class='btn btn-mini btn-warning'>"._TAD_EDIT."</a>
    </td></tr>";
    $data.=list_tad_gallery_cate($csn,$level);
  }

  if($old_level==0){

    $data.="
    </tbody>
    </table>
    ";
  }



  return $data;
}







//重新產生縮圖，沒有 $kind 就是全部縮圖
function re_thumb($csn="",$kind=""){
  global $xoopsDB,$xoopsModuleConfig,$type_to_mime;
  if(empty($csn))return 0;

  //找出分類下所有相片
  $sql = "select sn,title,filename,type,width,height,dir,post_date from ".$xoopsDB->prefix("tad_gallery")." where csn='{$csn}' order by photo_sort , post_date";
$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $n=0;
  while(list($sn,$title,$filename,$type,$width,$height,$dir,$post_date)=$xoopsDB->fetchRow($result)){

  	$b_thumb_name=photo_name($sn,"b",1,$filename,$dir);
  	if(substr($type,0,5)!=="image"){
      $file_ending= substr(strtolower($filename), -3); //file extension
      $type=$type_to_mime[$file_ending];
    }

    if($kind=="m" or empty($kind)){
  	 $m_thumb_name=photo_name($sn,"m",1,$filename,$dir);
    	if($width > $xoopsModuleConfig['thumbnail_m_width'] or $height > $xoopsModuleConfig['thumbnail_m_width']){
    		thumbnail($b_thumb_name,$m_thumb_name,$type,$xoopsModuleConfig['thumbnail_m_width']);
    	}
  	}

    if($kind=="s" or empty($kind)){
  	 $m_thumb_name=photo_name($sn,"m",1,$filename,$dir);
  	 $s_thumb_name=photo_name($sn,"s",1,$filename,$dir);
    	if($width > $xoopsModuleConfig['thumbnail_s_width'] or $height > $xoopsModuleConfig['thumbnail_s_width']){
    		thumbnail($m_thumb_name,$s_thumb_name,$type,$xoopsModuleConfig['thumbnail_s_width']);
    	}
    }


    $n++;
  }
  //exit;
  return $n;
}


/*-----------執行動作判斷區----------*/
$op = (!isset($_REQUEST['op']))? "":$_REQUEST['op'];
$csn = (!isset($_REQUEST['csn'])) ? 0 : intval($_REQUEST['csn']);

switch($op){
  //替換資料
  case "replace_tad_gallery_cate":
  replace_tad_gallery_cate();
  header("location: {$_SERVER['PHP_SELF']}");
  break;

  //新增資料
  case "insert_tad_gallery_cate":
  insert_tad_gallery_cate();
  mk_rss_xml();
  mk_rss_xml($csn);
  header("location: {$_SERVER['PHP_SELF']}");
  break;


  //刪除資料
  case "delete_tad_gallery_cate";
  delete_tad_gallery_cate($csn);
  mk_rss_xml();
  header("location: {$_SERVER['PHP_SELF']}");
  break;

  //更新資料
  case "update_tad_gallery_cate";
  update_tad_gallery_cate($csn);
  mk_rss_xml();
  mk_rss_xml($csn);
  header("location: {$_SERVER['PHP_SELF']}");
  break;

  //重新產生縮圖
  case "re_thumb":
  $n=re_thumb($csn,$_REQUEST['kind']);
  redirect_header("{$_SERVER['PHP_SELF']}?csn={$_REQUEST['csn']}",3, "All ($n) OK!");
  break;

  //預設動作
  default:
  $main=list_tad_gallery_cate(0,0,$csn);
  break;


}

/*-----------秀出結果區--------------*/

$xoopsTpl->assign('main',$main);
include_once 'footer.php';
?>
