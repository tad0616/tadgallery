<?php
/*-----------引入檔案區--------------*/
include "header.php";

if($xoopsModuleConfig['index_mode']=="waterfall"){
  $xoopsOption['template_main'] = "tg_list_waterfall.html";
}elseif($xoopsModuleConfig['index_mode']=="flickr"){
  $xoopsOption['template_main'] = "tg_list_flickr.html";
}else{
  $xoopsOption['template_main'] = "tg_list_normal.html";
}


include XOOPS_ROOT_PATH."/header.php";

/*-----------function區--------------*/
$csn=(isset($_REQUEST['csn']))?intval($_REQUEST['csn']) : 0;
$passwd=(isset($_POST['passwd']))?$_POST['passwd'] : "";

if(!empty($csn) and !empty($passwd)){
  $_SESSION['tadgallery'][$csn]=$passwd;
}

$tadgallery=new tadgallery();
$tadgallery->set_orderby("photo_sort");
$tadgallery->set_view_csn($csn);
$tadgallery->set_only_thumb($xoopsModuleConfig['only_thumb']);
$tadgallery->get_albums();


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
$xoopsTpl->assign( "cate_option" , "<select onChange=\"window.location.href='{$_SERVER['PHP_SELF']}?csn=' + this.value\" class='span12'>$cate_option</select>") ;

//$xoopsTpl->assign( "xoops_module_header" , "<link rel='alternate' href='"._TADGAL_UP_FILE_URL."photos.rss' type='application/rss+xml' title='{$cate['title']}' id='gallery' />");

$author_menu=get_all_author();

$xoopsTpl->assign( "author_option" , "<select onChange=\"window.location.href='author.php?uid=' + this.value\" class='span6'>$author_menu</select>") ;


//$xoopsTpl->assign( "show_3d_button" , "<a rel='shadowbox' class='option' title='{$cate['title']}' href='3d.php?csn={$csn}'><img src='images/3d.png' alt='"._MD_TADGAL_3D_MODE."' title='"._MD_TADGAL_3D_MODE."' border='0' height='22' width='22' hspace=4 align='absmiddle'></a>") ;

//$xoopsTpl->assign( "show_shadowbox_button" , $pp) ;
//$xoopsTpl->assign( "slideshow_button" , "<a href='slideshow.php?csn={$csn}'><img src='images/impress.png' alt='"._MD_TADGAL_SLIDE_SHOW_MODE."' title='"._MD_TADGAL_SLIDE_SHOW_MODE."' border='0' height='22' width='22' hspace=4 align='absmiddle'></a>") ;


$xoopsTpl->assign( "toolbar" , toolbar_bootstrap($interface_menu)) ;
$xoopsTpl->assign( "bootstrap" , get_bootstrap()) ;
$xoopsTpl->assign( "xoops_module_header" , get_jquery(true)) ;

/*-----------秀出結果區--------------*/


include_once XOOPS_ROOT_PATH.'/footer.php';

?>
