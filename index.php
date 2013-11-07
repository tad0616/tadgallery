<?php
/*-----------引入檔案區--------------*/
include "header.php";

if($xoopsModuleConfig['index_mode']=="waterfall"){
  $xoopsOption['template_main'] = "tg_list_waterfall.html";
}elseif($xoopsModuleConfig['index_mode']=="flickr"){
  $xoopsOption['template_main'] = "tg_list_flickr.html";
}elseif($_REQUEST['op']=="passwd_form"){
  $xoopsOption['template_main'] = "tg_passwd_form.html";
}else{
  $xoopsOption['template_main'] = "tg_list_normal.html";
}

$tadgallery=new tadgallery();

include XOOPS_ROOT_PATH."/header.php";

/*-----------function區--------------*/

function list_photos($csn,$uid){
  global $xoopsModuleConfig,$xoopsTpl,$tadgallery;

  if($csn){
    $tadgallery->set_orderby("photo_sort");
    $tadgallery->set_view_csn($csn);
    $tadgallery->set_only_thumb($xoopsModuleConfig['only_thumb']);
  }else{
    $tadgallery->set_orderby("rand");
    $tadgallery->set_limit($xoopsModuleConfig['thumbnail_number']);
    $tadgallery->set_only_thumb(true);
  }
  $tadgallery->get_albums();



  $xoops_module_header="<link rel='alternate' href='"._TADGAL_UP_FILE_URL."photos.rss' type='application/rss+xml' title='{$cate['title']}' id='gallery' />";
  $xoops_module_header.=get_jquery(true);
  $xoopsTpl->assign( "xoops_module_header" , $xoops_module_header) ;

}


function passwd_form($csn){
  global $xoopsTpl,$tadgallery;
  $cate=$tadgallery->get_tad_gallery_cate($csn);

  $xoopsTpl->assign( "title" , sprintf(_MD_TADGAL_INPUT_ALBUM_PASSWD,$cate['title']));
  $xoopsTpl->assign( "csn" , $csn) ;
}
/*-----------執行動作判斷區----------*/
$op=(empty($_REQUEST['op']))?"":$_REQUEST['op'];
$sn=(isset($_REQUEST['sn']))?intval($_REQUEST['sn']) : 0;
$uid=(isset($_REQUEST['uid']))?intval($_REQUEST['uid']) : 0;
$csn=(isset($_REQUEST['csn']))?intval($_REQUEST['csn']) : 0;
$passwd=(isset($_POST['passwd']))?$_POST['passwd'] : "";

if(!empty($csn) and !empty($passwd)){
  $_SESSION['tadgallery'][$csn]=$passwd;
}


switch($op){

  case "passwd_form":
  passwd_form($csn);
  break;

  default:
  list_photos($csn,$uid);
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


$cate_option=get_tad_gallery_cate_option(0,0,$csn);
$xoopsTpl->assign( "cate_option" , $cate_option) ;

$author_menu=get_all_author();

$xoopsTpl->assign( "author_option" , $author_menu) ;

$cate=$tadgallery->get_tad_gallery_cate($csn);
$xoopsTpl->assign( "toolbar" , toolbar_bootstrap($interface_menu)) ;
$xoopsTpl->assign( "bootstrap" , get_bootstrap()) ;

include_once XOOPS_ROOT_PATH.'/footer.php';

?>
