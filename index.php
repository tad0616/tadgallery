<?php
/*-----------引入檔案區--------------*/
include "header.php";

if(isset($_REQUEST['show_uid'])){
  $_SESSION['show_uid']=$_REQUEST['show_uid'];
}

$csn=(isset($_REQUEST['csn']))?intval($_REQUEST['csn']) : 0;
$passwd=(isset($_POST['passwd']))?$_POST['passwd'] : "";
$tadgallery=new tadgallery();
if($_SESSION['show_uid'])$tadgallery->set_show_uid($_SESSION['show_uid']);

if(!empty($csn)){
  $cate=$tadgallery->get_tad_gallery_cate($csn);
  if($cate['show_mode']=="waterfall"){
    $xoopsOption['template_main'] = "tg_list_waterfall.html";
  }elseif($cate['show_mode']=="flickr"){
    $xoopsOption['template_main'] = "tg_list_flickr.html";
  }elseif($_REQUEST['op']=="passwd_form"){
    $xoopsOption['template_main'] = "tg_passwd_form.html";
  }else{
    $xoopsOption['template_main'] = "tg_list_normal.html";
  }
}else{
  if($xoopsModuleConfig['index_mode']=="waterfall"){
    $xoopsOption['template_main'] = "tg_list_waterfall.html";
  }elseif($xoopsModuleConfig['index_mode']=="flickr"){
    $xoopsOption['template_main'] = "tg_list_flickr.html";
  }else{
    $xoopsOption['template_main'] = "tg_list_normal.html";
  }
}



include XOOPS_ROOT_PATH."/header.php";

/*-----------function區--------------*/
//列出所有照片
function list_photos($csn="" , $uid=""){
  global $xoopsModuleConfig,$xoopsTpl,$tadgallery;

  if($csn){
    $tadgallery->set_orderby("photo_sort");
    $tadgallery->set_view_csn($csn);
    $tadgallery->set_only_thumb($xoopsModuleConfig['only_thumb']);
  }else{
    $tadgallery->set_orderby("rand");
    $tadgallery->set_limit($xoopsModuleConfig['thumbnail_number']);
  }
  $tadgallery->get_photos();
  $tadgallery->get_albums();


  $xoops_module_header="<link rel='alternate' href='"._TADGAL_UP_FILE_URL."photos.rss' type='application/rss+xml' title='{$cate['title']}' id='gallery' />";
  //$xoops_module_header.=get_jquery(true);
  $xoopsTpl->assign( "xoops_module_header" , $xoops_module_header) ;

}


function passwd_form($csn,$title){
  global $xoopsTpl;

  $xoopsTpl->assign( "title" , sprintf(_MD_TADGAL_INPUT_ALBUM_PASSWD,$title));
  $xoopsTpl->assign( "csn" , $csn) ;
}
/*-----------執行動作判斷區----------*/
$op=(empty($_REQUEST['op']))?"":$_REQUEST['op'];
$sn=(isset($_REQUEST['sn']))?intval($_REQUEST['sn']) : 0;
$uid=(isset($_REQUEST['uid']))?intval($_REQUEST['uid']) : 0;
$show_uid=(isset($_REQUEST['show_uid']))?intval($_REQUEST['show_uid']) : 0;


if(!empty($csn) and !empty($passwd)){
  $_SESSION['tadgallery'][$csn]=$passwd;
}


switch($op){

  case "passwd_form":
  passwd_form($csn,$cate['title']);
  break;

  default:
  list_photos($csn,$show_uid);
  break;
}


/*-----------秀出結果區--------------*/

//路徑選單
if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/jBreadCrumb.php")){
 redirect_header("index.php",3, _MD_NEED_TADTOOLS);
}
include_once XOOPS_ROOT_PATH."/modules/tadtools/jBreadCrumb.php";
$arr=get_cate_path($csn);
$jBreadCrumb=new jBreadCrumb($arr);
$jBreadCrumbPath=$jBreadCrumb->render();
$xoopsTpl->assign( "path" , $jBreadCrumbPath) ;



$author_menu=get_all_author($_SESSION['show_uid']);
$xoopsTpl->assign( "author_option" , $author_menu) ;

$cate_option=get_tad_gallery_cate_option(0,0,$csn);
$xoopsTpl->assign( "cate_option" , $cate_option) ;

$xoopsTpl->assign( "toolbar" , toolbar_bootstrap($interface_menu)) ;
$xoopsTpl->assign( "bootstrap" , get_bootstrap()) ;

include_once XOOPS_ROOT_PATH.'/footer.php';

?>
