<?php
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = "tadgallery_adm_main.html";
include_once "header.php";
include_once "../function.php";

/*-----------function區--------------*/
//列出所有tad_gallery資料
function list_tad_gallery($csn="",$show_function=1){
  global $xoopsDB,$xoopsModule,$xoopsModuleConfig,$xoopsTpl;

  $tadgallery=new tadgallery();

  $xoopsTpl->assign( "jquery" , get_jquery(true)) ;
  $xoopsTpl->assign( "csn" , $csn) ;

  if(isset($_SESSION['gallery_list_mode']) and $_SESSION['gallery_list_mode']=="good"){
    $mode_select="<a href='main.php?op=chg_mode&mode=normal#gallery_top' class='btn btn-warning'>"._MA_TADGAL_LIST_NORMAL."</a>";
    $tadgallery->set_view_good(true);
    $cate_option="";
    $link_to_cate="";
  }else{
    $mode_select="<a href='main.php?op=chg_mode&mode=good#gallery_top' class='btn btn-warning'>"._MA_TADGAL_LIST_GOOD."</a>";
    $tadgallery->set_view_good(false);
    $tadgallery->set_view_csn($csn);
    $cate_options=get_tad_gallery_cate_option(0,0,$csn);
    $cate_option="<select onChange=\"window.location.href='{$_SERVER['PHP_SELF']}?csn=' + this.value\" size=10 class='span12'>$cate_options</select>";
    $cate=tadgallery::get_tad_gallery_cate($csn);
    $link_to_cate=(!empty($csn))?"<a href='../index.php?csn={$csn}' class='btn btn-info'>".sprintf(_MA_TADGAL_LINK_TO_CATE,$cate['title'])."</a>":"";
  }

  $option=get_tad_gallery_cate_option(0,0,$csn);

  $tag_select=tag_select("","add_tag");

  $xoopsTpl->assign( "cate_option" , $cate_option) ;
  $xoopsTpl->assign( "mode_select" , $mode_select) ;
  $xoopsTpl->assign( "link_to_cate" , $link_to_cate) ;
  $xoopsTpl->assign( "option" , $option) ;
  $xoopsTpl->assign( "tag_select" , $tag_select) ;

  $tadgallery->set_admin_mode(true);
  $tadgallery->get_photos();

}



//批次搬移
function batch_move($new_csn=""){
  global $xoopsDB;
  $pics=implode(",",$_POST['pic']);
  $sql = "update ".$xoopsDB->prefix("tad_gallery")." set `csn` = '{$new_csn}' where sn in($pics)";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error()."<br>$sql");
  return $sn;
}

//批次新增精華
function batch_add_good(){
  global $xoopsDB;
  $pics=implode(",",$_POST['pic']);
  $sql = "update ".$xoopsDB->prefix("tad_gallery")." set  `good` = '1' where sn in($pics)";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  return $sn;
}

//批次新增標題
function batch_add_title(){
  global $xoopsDB;
  $pics=implode(",",$_POST['pic']);
  $sql = "update ".$xoopsDB->prefix("tad_gallery")." set  `title` = '{$_POST['add_title']}' where sn in($pics)";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  return $sn;
}
//批次新增說明
function batch_add_description(){
  global $xoopsDB;
  $pics=implode(",",$_POST['pic']);
  $sql = "update ".$xoopsDB->prefix("tad_gallery")." set  `description` = '{$_POST['add_description']}' where sn in($pics)";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  return $sn;
}


//批次加上標籤
function batch_add_tag(){
  global $xoopsDB;

  $sel_tags_arr=$_POST['tag'];

  if(!empty($_POST['new_tag'])){
    $new_tags=explode(",",$_POST['new_tag']);
    foreach($new_tags as $t){
      $t=trim($t);
      $sel_tags_arr[$t]=$t;
    }
  }



  foreach($_POST['pic'] as $sn){
    $old_tad_arr="";
    $sql = "select tag from ".$xoopsDB->prefix("tad_gallery")." where sn ='$sn'";
    $result=$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    list($old_tad)=$xoopsDB->fetchRow($result);
    $old_tad_arr=explode(",",$old_tad);
    foreach($old_tad_arr as $t){
      $t=trim($t);
      $sel_tags_arr[$t]=$t;
    }
    $all_tags=implode(",",$sel_tags_arr);

    $sql = "update ".$xoopsDB->prefix("tad_gallery")." set  `tag` = '{$all_tags}' where sn ='$sn'";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  }


  return $sn;
}

//批次取消精選
function batch_del_good(){
  global $xoopsDB;
  $pics=implode(",",$_POST['pic']);
  if(empty($pics))return;
  $sql = "update ".$xoopsDB->prefix("tad_gallery")." set  `good` = '0' where sn in($pics)";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  return $sn;
}

//批次清空標籤
function batch_remove_tag(){
  global $xoopsDB;
  $pics=implode(",",$_POST['pic']);
  $sql = "update ".$xoopsDB->prefix("tad_gallery")." set  `tag` = '' where sn in($pics)";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  return $sn;
}


//批次刪除
function batch_del(){
  global $xoopsDB;
  foreach($_POST['pic'] as $sn){
    delete_tad_gallery($sn);
  }
}


//產生所有分類之rss
function mk_csn_rss_xml(){
  global $xoopsDB,$xoopsModule;
  $sql = "select csn from ".$xoopsDB->prefix("tad_gallery_cate")."";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  while(list($csn)=$xoopsDB->fetchRow($result)){
    mk_rss_xml($csn);
  }
}

/*-----------執行動作判斷區----------*/
$op = (!isset($_REQUEST['op']))? "main":$_REQUEST['op'];
$csn = (!isset($_REQUEST['csn'])) ? 0 : intval($_REQUEST['csn']);
$new_csn = (!isset($_REQUEST['new_csn'])) ? 0 : intval($_REQUEST['new_csn']);

switch($op){
  case "del":
  batch_del();
  mk_rss_xml();
  mk_rss_xml($csn);
  header("location: {$_SERVER['PHP_SELF']}?csn={$csn}");
  break;

  case "move":
  batch_move($new_csn);
  mk_rss_xml();
  mk_rss_xml($csn);
  header("location: {$_SERVER['PHP_SELF']}?csn={$new_csn}");
  break;

  case "add_good":
  batch_add_good();
  header("location: {$_SERVER['PHP_SELF']}?csn={$csn}");
  break;


  case "del_good":
  batch_del_good();
  header("location: {$_SERVER['PHP_SELF']}?csn={$csn}");
  break;

  case "add_tag":
  batch_add_tag();
  header("location: {$_SERVER['PHP_SELF']}?csn={$csn}");
  break;

  case "remove_tag":
  batch_remove_tag();
  header("location: {$_SERVER['PHP_SELF']}?csn={$csn}");
  break;


  case "add_title":
  batch_add_title();
  header("location: {$_SERVER['PHP_SELF']}?csn={$csn}");
  break;

  case "add_description":
  batch_add_description();
  header("location: {$_SERVER['PHP_SELF']}?csn={$csn}");
  break;


  //產生Media RSS
  case "mk_rss_xml":
  mk_rss_xml();
  mk_csn_rss_xml();
  header("location: {$_SERVER['PHP_SELF']}");
  break;

  //
  case "chg_mode":
  $_SESSION['gallery_list_mode']=$_GET['mode'];
  header("location: {$_SERVER['PHP_SELF']}");
  break;

  //預設動作
  default:
  $main=list_tad_gallery($csn,1);
  if($xoTheme){
    $xoTheme->addStylesheet('modules/tadgallery/class/jquery.thumbs/jquery.thumbs.css');
    $xoTheme->addScript('modules/tadgallery/class/jquery.thumbs/jquery.thumbs.js');
  }
  break;

}

/*-----------秀出結果區--------------*/
echo "<a name='gallery_top'></a>";

include_once 'footer.php';

?>
