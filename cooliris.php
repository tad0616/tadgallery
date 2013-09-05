<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2010-05-02
// $Id: index.php,v 1.5 2008/05/10 11:46:50 tad Exp $
// ------------------------------------------------------------------------- //

/*-----------引入檔案區--------------*/
include "header.php";
$xoopsOption['template_main'] = "tg_show_tpl.html";
/*-----------function區--------------*/
$csn=(empty($_REQUEST['csn']))?"":intval($_REQUEST['csn']);
$data= cooliris($csn);

function cooliris($csn){
    $main = "<div align='center'>
    <object id='o' classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000'
      width='650' height='450'>
    <param name='movie' value='http://apps.cooliris.com/embed/cooliris.swf' />
    <param name='allowFullScreen' value='true' />
    <param name='allowScriptAccess' value='always' />
    <param name='flashvars' value='feed="._TADGAL_UP_FILE_URL."photos{$csn}.rss' />
    <embed type='application/x-shockwave-flash'
      src='http://apps.cooliris.com/embed/cooliris.swf'
      flashvars='feed="._TADGAL_UP_FILE_URL."photos{$csn}.rss'
      width='650'
      height='450'
      allowFullScreen='true'
      allowScriptAccess='always'>
    </embed>
    </object>
    </div>";
    return $main;
}

/*-----------秀出結果區--------------*/
include XOOPS_ROOT_PATH."/header.php";


$cate_option=get_tad_gallery_cate_option(0,0,$csn);
$xoopsTpl->assign( "cate_option" , "<select onChange=\"window.location.href='{$_SERVER['PHP_SELF']}?csn=' + this.value\" style='margin-right:20px'>$cate_option</select>") ;
  
$xoopsTpl->assign( "css" , "<link rel='stylesheet' type='text/css' media='screen' href='".XOOPS_URL."/modules/ck2_gallery/module.css' />") ;


$xoopsTpl->assign( "toolbar" , toolbar($interface_menu,$interface_logo)) ;

$xoopsTpl->assign( "data" , $data) ;

include_once XOOPS_ROOT_PATH.'/footer.php';

?>
