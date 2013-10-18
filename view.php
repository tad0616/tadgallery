<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2008-03-23
// $Id: view.php,v 1.5 2008/05/05 03:23:04 tad Exp $
// ------------------------------------------------------------------------- //

/*-----------引入檔案區--------------*/
include_once "header.php";
$xoopsOption['template_main'] =(1)?"tg_responsive_view_tpl.html":"tg_view_tpl.html";
include_once XOOPS_ROOT_PATH."/header.php";
/*-----------function區--------------*/



//觀看某一張照片
function view_pic($sn=""){
	global $xoopsDB,$xoopsUser,$xoopsModule,$xoopsModuleConfig,$xoopsTpl,$xoTheme;

	$tadgallery=new tadgallery();

	//判斷是否對該模組有管理權限，  若空白
  if ($xoopsUser) {
    $nowuid=$xoopsUser->getVar('uid');
    $module_id = $xoopsModule->getVar('mid');
    $isAdmin=$xoopsUser->isAdmin($module_id);
  }else{
    $isAdmin=false;
    $nowuid="";
	}


	$sql = "select * from ".$xoopsDB->prefix("tad_gallery")." where sn='{$sn}'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$all=$xoopsDB->fetchArray($result);
  //$csn,$title,$description,$filename,$size,$type,$width,$height,$dir,$uid,$post_date,$counter,$exif,$good,$tag,$photo_sort
  foreach($all as $k=>$v){
    $$k=$v;
    $xoopsTpl->assign( $k , $v);
  }

  $photo_s=$tadgallery->get_pic_url($dir,$sn,$filename,"s");
  $photo_m=$tadgallery->get_pic_url($dir,$sn,$filename,"m");
  $photo_l=$tadgallery->get_pic_url($dir,$sn,$filename);
	$xoopsTpl->assign( "photo_s" , $photo_s);
	$xoopsTpl->assign( "photo_m" , $photo_m);
	$xoopsTpl->assign( "photo_l" , $photo_l);



	if(!empty($csn)){
		$ok_cat=$tadgallery->chk_cate_power();
		if(!in_array($csn,$ok_cat)){
		 	$cate_option=get_tad_gallery_cate_option(0,0,$csn);
  		$cate=$tadgallery->get_tad_gallery_cate($csn);
		  $select="<select onChange=\"window.location.href='index.php?csn=' + this.value\">
			$cate_option
			</select>";
			$main=div_3d(_TADGAL_NO_POWER_TITLE,sprintf(_TADGAL_NO_POWER_CONTENT,$cate['title'],$select),"corners");
			return $main;
			exit;
		}

    $sql = "select * from ".$xoopsDB->prefix("tad_gallery")." where csn='{$csn}' order by photo_sort , post_date";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    $slides="";
    $i=0;
    $start=false;
    while($all=$xoopsDB->fetchArray($result)){
      if($sn==$all['sn']){
        $start=true;
        $i=0;
      }

      if($start){
        $slides1[$i]['sn']=$all['sn'];
        $slides1[$i]['photo']=$tadgallery->get_pic_url($all['dir'],$all['sn'],$all['filename']);
        $slides1[$i]['description']=strip_tags($all['description']);
        $slides1[$i]['thumb']=$tadgallery->get_pic_url($all['dir'],$all['sn'],$all['filename'],'s');
      }else{
        $slides2[$i]['sn']=$all['sn'];
        $slides2[$i]['photo']=$tadgallery->get_pic_url($all['dir'],$all['sn'],$all['filename']);
        $slides2[$i]['description']=strip_tags($all['description']);
        $slides2[$i]['thumb']=$tadgallery->get_pic_url($all['dir'],$all['sn'],$all['filename'],'s');
      }
      $i++;
    }

	}
	$xoopsTpl->assign( "slides1" , $slides1);
	$xoopsTpl->assign( "slides2" , $slides2);


	//找出上一張或下一張
  $pnp=get_pre_next($csn,$sn);
	$xoopsTpl->assign( "next" , $pnp['next']);
	$xoopsTpl->assign( "back" , $pnp['pre']);

	if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/jBreadCrumb.php")){
    redirect_header("index.php",3, _MD_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/jBreadCrumb.php";
  $arr=get_cate_path($csn);
  $jBreadCrumb=new jBreadCrumb($arr);
  $jBreadCrumbPath="<a name='photo_top'></a>".$jBreadCrumb->render();



	$title=(empty($title))?$filename:$title;
	$div_width=$xoopsModuleConfig['thumbnail_m_width']+30;
	$size_txt=sizef($size);


	if($uid==$nowuid or $isAdmin){
	  $del_btn="<img src='images/view_del.png' alt='"._TADGAL_DEL_PIC."' title='"._TADGAL_DEL_PIC."' border='0' height='22' width='22' onClick='delete_tad_gallery_func($sn)' style='cursor:pointer' align='absmiddle' hspace=4>";

		$good_btn=(empty($good))?"<a href='view.php?op=good&sn={$sn}'><img src='images/good_add.png' alt='"._TADGAL_GOOD_PIC."' title='"._TADGAL_GOOD_PIC."' border='0' height='22' width='22' align='absmiddle' hspace=2></a>":"<a href='view.php?op=good_del&sn={$sn}'><img src='images/good_del.png' alt='"._TADGAL_REMOVE_GOOD_PIC."' title='"._TADGAL_REMOVE_GOOD_PIC."' border='0' height='22' width='22' align='absmiddle' hspace=2></a>";


		$del_js="
		<script>
		function delete_tad_gallery_func(sn){
			var sure = window.confirm('"._TAD_DEL_CONFIRM."');
			if (!sure)	return;
			location.href=\"{$_SERVER['PHP_SELF']}?op=delete_tad_gallery&sn=\" + sn;
		}
		</script>";


		$option=get_tad_gallery_cate_option(0,0,$csn);


		$edit_form="<div id='input_form' style='clear:both;'>
		<form action='{$_SERVER['PHP_SELF']}' method='post' id='myForm' name='myForm'>
	  <table class='form_tbl'>
		<tr><td nowrap>"._MD_TADGAL_CSN."</td>
		<td><select name='csn' size=1>
			$option
		</select></td></tr>
		<tr>
		<td nowrap>"._MD_TADGAL_NEW_CSN."</td>
		<td><input type='text' name='new_csn' size='20'></td></tr>
		<tr><td nowrap>"._MD_TADGAL_TITLE."</td>
		<td><input type='text' name='title' size='60' value='{$title}'></td></tr>
		<tr><td nowrap>"._MD_TADGAL_DESCRIPTION."</td>
		<td><textarea style='width: 400px; height: 44px; min-height: 44px;' name='description'>$description</textarea></td></tr>
	  <tr><td class='bar' colspan='2' align='right'>
	  <input type='hidden' name='sn' value='{$sn}'>
	  <input type='hidden' name='op' value='update_tad_gallery'>
		<input type='checkbox' name='cover' value='small/{$dir}/{$sn}_s_{$filename}'>"._MD_TADGAL_AS_COVER."
	  <input type='submit' value='"._MD_SAVE_EDIT."'></td></tr>
	  </table>
	  </form>
		</div>";

		$tag_select=tag_select($tag);

		$tag_form="<div id='tag_form' style='clear:both;'>
		<form action='{$_SERVER['PHP_SELF']}' method='post' id='tagForm' name='tagForm'>
	  <table class='form_tbl'>
		<tr><td nowrap>"._MD_TADGAL_TAG."</td>
		<td><input type='text' name='new_tag' size='30'>"._MD_TADGAL_TAG_TXT."</td></tr>
		<tr>
		<td colspan=2>$tag_select</td></tr>
	  <tr><td class='bar' colspan='4' align='right'>
	  <input type='hidden' name='sn' value='{$sn}'>
	  <input type='hidden' name='op' value='update_tad_gallery_tag'>
	  <input type='submit' value='"._MD_SAVE_EDIT."'></td></tr>
	  </table>
	  </form>
		</div>";


    $admin_tab="
    <li><a href='#fragment-3'><span>"._TADGAL_EDIT_PIC."</span></a></li>
    <li><a href='#fragment-4'><span>"._MD_TADGAL_TAG."</span></a></li>";

    $admin_tab_content="
    <div id='fragment-3'>
    $edit_form
    {$del_btn}{$good_btn}
    </div>

    <div id='fragment-4'>
    $tag_form
    </div>";

	}else{
	  $del_btn=$admin_tool=$del_js=$edit_form=$tag_form=$admin_tab=$admin_tab_content="";
	}

	//秀出各種尺寸圖示
	if($xoopsModuleConfig['show_copy_pic']){
  	$sel_size="
  	<a href='{$photo_s}' target='_blank' title='$description'>
    <img src='images/s.png' alt='"._TADGAL_FILE_COPY_S."' title='"._TADGAL_FILE_COPY_S."' border='0' style='margin-right:1px;'>
    </a>

  	<a href='{$photo_m}' target='_blank' title='$description'>
    <img src='images/m.png' alt='"._TADGAL_FILE_COPY_M."' title='"._TADGAL_FILE_COPY_M."' border='0' style='margin-right:1px;'>
    </a>

  	<a href='{$photo_l}' target='_blank' title='$description'>
    <img src='images/l.png' alt='"._TADGAL_FILE_COPY_B."' title='"._TADGAL_FILE_COPY_B."' border='0' style='margin-right:1px;'>
    </a>";
	}else{
    $sel_size="";
  }

	//推文工具
	$push=push_url($xoopsModuleConfig['use_social_tools']);

  //計數器
	add_tad_gallery_counter($sn);

  //地圖部份
  $info=explode("||",$exif);
  foreach($info as $v){
	  $exif_arr=explode("=",$v);
	  $exif_arr[1]=str_replace("&#65533;","",$exif_arr[1]);
	  $bb= "\$photoexif{$exif_arr[0]}=\"{$exif_arr[1]}\";";
	  if(empty($exif_arr[0]))continue;
	  @eval($bb);
	}

  $latitude=$photoexif['GPS']['latitude'];
  $longitude=$photoexif['GPS']['longitude'];;

  $tinymap="";
  if(!empty($latitude)){
    if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/tinymap.php")){
     redirect_header("http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50",3, _TAD_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH."/modules/tadtools/tinymap.php";
    $map=new tinymap('#map_canvas',$latitude,$longitude,$title);
    $tinymap=$map->render();
  }

	$xoopsTpl->assign( "map" , $tinymap) ;


  $jquery_path=get_jquery(true);
	$xoopsTpl->assign( "jquery" , $jquery_path);

	$xoopsTpl->assign( "path" , $jBreadCrumbPath) ;
	$xoopsTpl->assign( "del_js" , $del_js) ;


	$xoopsTpl->assign( "div_width" , $div_width) ;
	$xoopsTpl->assign( "sel_size" , $sel_size);
	$xoopsTpl->assign( "photo" , $photo) ;
	$xoopsTpl->assign( "push" , $push);


  $facebook_comments=facebook_comments($xoopsModuleConfig['facebook_comments_width'],'tadgallery','view.php','sn',$sn);
	$xoopsTpl->assign( "facebook_comments" , $facebook_comments);

	$fb_tag="
	<meta property=\"og:title\" content=\"{$title}\" />
  <meta property=\"og:description\" content=\"{$description}\" />
  <meta property=\"og:image\" content=\"".$tadgallery->get_pic_url($dir,$sn,$filename,"fb")."\" />
  ";
	$xoopsTpl->assign( "xoops_module_header" , $fb_tag);
	$xoopsTpl->assign("xoops_pagetitle",$title);
	if (is_object($xoTheme)) {
		$xoTheme->addMeta( 'meta', 'keywords', $title);
		$xoTheme->addMeta( 'meta', 'description', $description) ;
	} else {
		$xoopsTpl->assign('xoops_meta_keywords','keywords',$title);
		$xoopsTpl->assign('xoops_meta_description', $description);
	}

	return $data;
}






//更新標籤資料到tad_gallery中
function update_tad_gallery_tag($sn=""){
	global $xoopsDB;

	$all=implode(",",$_POST['tag']);

	if(!empty($_POST['new_tag'])){
   $new_tags=explode(",",$_POST['new_tag']);
	}

	foreach($new_tags as $tag){
	  if(!empty($tag)){
		  $tag=trim($tag);
	    $all.=",{$tag}";
    }
	}


 	$sql = "update ".$xoopsDB->prefix("tad_gallery")." set `tag`='{$all}' where sn='{$sn}'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],10, mysql_error());
}


//更新人氣資料到tad_gallery中
function add_tad_gallery_counter($sn=""){
	global $xoopsDB;
 	$sql = "update ".$xoopsDB->prefix("tad_gallery")." set `counter`=`counter`+1 where sn='{$sn}'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],10, mysql_error());

}

/*-----------執行動作判斷區----------*/
$_REQUEST['op']=(empty($_REQUEST['op']))?"":$_REQUEST['op'];
$sn=(isset($_REQUEST['sn']))?intval($_REQUEST['sn']) : 0;
$csn=(isset($_REQUEST['csn']))?intval($_REQUEST['csn']) : 0;

switch($_REQUEST['op']){
	case "good":
	update_tad_gallery_good($sn,'1');
	header("location: view.php?sn={$sn}");
	break;

	case "good_del":
	update_tad_gallery_good($sn,'0');
	header("location: view.php?sn={$sn}");
	break;

	case "update_tad_gallery":
	update_tad_gallery($sn);
	if($_POST['go_cate']=='1'){
    header("location: index.php?csn={$csn}");
	}else{
		header("location: view.php?sn={$sn}");
	}
	break;


	case "update_tad_gallery_tag":
	update_tad_gallery_tag($sn);
	header("location: view.php?sn={$sn}");
	break;

	case "delete_tad_gallery":
	$csn=delete_tad_gallery($sn);
	mk_rss_xml();
  mk_rss_xml($csn);
	header("location: index.php?csn=$csn");
	break;

	default:
	view_pic($sn);
	break;
}

/*-----------秀出結果區--------------*/

$xoopsTpl->assign( "toolbar" , toolbar_bootstrap($interface_menu)) ;
$xoopsTpl->assign( "bootstrap" , get_bootstrap()) ;

include_once XOOPS_ROOT_PATH.'/include/comment_view.php';
include_once XOOPS_ROOT_PATH.'/footer.php';

?>
