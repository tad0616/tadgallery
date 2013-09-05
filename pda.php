<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2008-03-23
// $Id: index.php,v 1.5 2008/05/10 11:46:50 tad Exp $
// ------------------------------------------------------------------------- //

/*-----------引入檔案區--------------*/
if(file_exists("mainfile.php")){
  include_once "mainfile.php";
}elseif("../../mainfile.php"){
  include_once "../../mainfile.php";
}
include_once "function.php";
/*-----------function區--------------*/


function show_cate($csn,$passwd){
	global $xoopsDB,$xoopsUser,$xoopsModule,$xoopsModuleConfig,$xoopsTpl,$xoopsOption;



  $jquery=get_jquery();

  //以流水號取得某筆tad_gallery_cate資料
  $cate=tadgallery::get_tad_gallery_cate($csn);

  //可觀看相簿
  $ok_cat=tadgallery::chk_cate_power();

  //密碼檢查
  if(!empty($csn)){
    if(empty($passwd) and !empty($_SESSION['tadgallery'][$csn])){
      $passwd=$_SESSION['tadgallery'][$csn];
  	}

  	$sql = "select csn,passwd from ".$xoopsDB->prefix("tad_gallery_cate")." where csn='{$csn}'";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  	list($ok_csn,$ok_passwd)=$xoopsDB->fetchRow($result);
  	if(!empty($ok_csn) and $ok_passwd!=$passwd)redirect_header($_SERVER['PHP_SELF'],3, sprintf(_TADGAL_NO_PASSWD_CONTENT,$cate['title']));

  	if(!empty($ok_passwd) and empty($_SESSION['tadgallery'][$csn])){
  		$_SESSION['tadgallery'][$csn]=$passwd;
  	}


    //檢查相簿觀看權限
  	if(!in_array($csn,$ok_cat)){
  		redirect_header($_SERVER['PHP_SELF'],3, _TADGAL_NO_POWER_TITLE,sprintf(_TADGAL_NO_POWER_CONTENT,$cate['title'],$select));
  	}
  }



  //呈現資料預設值
  $data="";

  //畫面並不只秀出縮圖，要秀出分類的話。
if($xoopsModuleConfig['only_thumb']!='1'){
  //撈出底下子分類
	$sql = "select csn,title,passwd,show_mode,cover from ".$xoopsDB->prefix("tad_gallery_cate")." where of_csn='{$csn}'  order by sort";
//getPageBar_cate
$PageBar_cate=getPageBar_mobile($sql,$xoopsModuleConfig['thumbnail_number'], 10);
$bar_cate=$PageBar_cate['bar'];
$sql=$PageBar_cate['sql'];
$total_cate=$PageBar_cate['total'];

	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	while(list($fcsn,$title,$passwd,$show_mode,$cover)=$xoopsDB->fetchRow($result)){
    //無觀看權限則略過
		if(!in_array($fcsn,$ok_cat)){
		  continue;
		}

			$url="pda.php";
			$rel="rel='external'";

		$cover_pic=(empty($cover))?"images/folder_picture.png":XOOPS_URL."/uploads/tadgallery/{$cover}";

	  if(empty($passwd) or $passwd==$_SESSION['tadgallery'][$fcsn]){
      //不需密碼的分類
	  	$data.=mk_gallery_border_m($rel,"{$url}?csn={$fcsn}",$cover_pic,$title);
		}else{
      //有密碼的分類
		$data.=mk_gallery_border_m("","#",$cover_pic,$title,true,$fcsn);
		}
	}

}

  //找出分類下所有相片
  $sql = "select * from ".$xoopsDB->prefix("tad_gallery")." where csn='{$csn}' order by photo_sort , post_date";
  
	//getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
	$PageBar=getPageBar_mobile($sql,$xoopsModuleConfig['thumbnail_number'], 10);
	$bar=$PageBar['bar'];
	$sql=$PageBar['sql'];
	$total=$PageBar['total'];
  
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());


  while(list($sn,$db_csn,$title,$description,$filename,$size,$type,$width,$height,$dir,$uid,$post_date,$counter,$exif)=$xoopsDB->fetchRow($result)){

	$data.="<li class='nofolder'><a href='".tadgallery::get_pic_url($dir,$sn,$filename,"m")."' rel='external'><img src='".get_pic_url($dir,$sn,$filename,"s")."' alt='$title' title='$title'></a></li>
	";
  }
  
  $main="
  <ul class='gallery' style='margin-top:10px;'>
    {$data}
  </ul>
  <p style='clear:both;'></p>";
  if ($total>$total_cate){
  $main.="
  <div style='clear:both;text-align:center;padding:8px;' class='navigation'><div data-role='controlgroup' data-type='horizontal'>{$bar}</div></div>";
  } else {
  $main.="
  <div style='clear:both;text-align:center;padding:8px;' class='navigation'><div data-role='controlgroup' data-type='horizontal'>{$bar_cate}</div></div>";
  }
  return $main;
}



//觀看某一張照片
function view_pic($sn=""){
	global $xoopsDB,$xoopsUser,$xoopsModule,$xoopsModuleConfig,$isAdmin;

	//所有分類名稱
	$cate_all=get_tad_gallery_cate_all();

	$sql = "select csn,title,description,filename,size,type,width,height,dir,uid from ".$xoopsDB->prefix("tad_gallery")." where sn='{$sn}'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	list($csn,$title,$description,$filename,$size,$type,$width,$height,$dir,$uid)=$xoopsDB->fetchRow($result);

	if(!empty($csn)){
		$ok_cat=tadgallery::chk_cate_power();
		if(!in_array($csn,$ok_cat)){
		 	header("location:{$_SERVER['PHP_SELF']}");
		}
	}

	//找出上一張或下一張
  $pnp=get_pre_next($csn,$sn);

	$back_btn=(!empty($pnp['pre']))?" id='pre_photo' onClick=\"location.href='{$_SERVER['PHP_SELF']}?sn={$pnp['pre']}'\" ":"";
	$next_btn=(!empty($pnp['next']))?" id='next_photo' onClick=\"location.href='{$_SERVER['PHP_SELF']}?sn={$pnp['next']}'\" ":"";

	$title=(empty($title))?$filename:$title;

  //計數器
	add_tad_gallery_counter($sn);

  //縮圖
	if(($width/$height)>2){
	  $mpic=getimagesize(tadgallery::get_pic_url($dir,$sn,$filename,"m","dir"));
	  $pic180="<marquee width='{$xoopsModuleConfig['thumbnail_m_width']}' behavior='scroll' height='{$mpic[1]}' direction=left scrolldelay=0 scrollamount=3><a href='".tadgallery::get_pic_url($dir,$sn,$filename)."'><img src='".tadgallery::get_pic_url($dir,$sn,$filename,"m")."'  alt='$title' class='instant itiltnone icolorFCFCFC' /></a></marquee>";
	}else{

    if($width > $height){
      $new_wh=round($xoopsModuleConfig['thumbnail_m_width'] * $height / $width , 0);
      $pcss="width:{$xoopsModuleConfig['thumbnail_m_width']}px;height:{$new_wh}px;";
      $pacss="width:{$new_wh}px;height:{$new_wh}px;";
    }else{
      $new_wh=round($xoopsModuleConfig['thumbnail_m_width'] * $width / $height , 0);
      $new_wh_a=$new_wh/2;
      $pcss="width:{$new_wh}px;height:{$xoopsModuleConfig['thumbnail_m_width']}px;";
      $pacss="width:{$new_wh_a}px;height:{$xoopsModuleConfig['thumbnail_m_width']}px;";
    }

    $photo=tadgallery::get_pic_url($dir,$sn,$filename,"m");
    $pic180="
    <table id='view_photo' style='{$pcss}background-image:url({$photo});background-position: top center;background-repeat: no-repeat;background-color: black;'>
    <tr>
    <td $back_btn style='{$pacss}background-position: top center;background-repeat: no-repeat;border:0px;'></td>
    <td $next_btn style='{$pacss}background-position: top center;background-repeat: no-repeat;border:0px;'></td></tr>
    </table>
  ";
	}

  $data="
  <table id='main' style='width:100%;'>
  <tr><td align='center'><a name='photo_top'>{$pic180}</a></td></tr>
  <tr><td style='color:#E0E0E0;text-align:center'>{$title}</td></tr>
  <tr><td style='color:#E0E0E0'>".nl2br($description)."</td></tr>
  </table>
  ";


	return $data;
}


//更新人氣資料到tad_gallery中
function add_tad_gallery_counter($sn=""){
	global $xoopsDB;
 	$sql = "update ".$xoopsDB->prefix("tad_gallery")." set `counter`=`counter`+1 where sn='{$sn}'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],10, mysql_error());

}

//製作相簿或相片的外框
function mk_gallery_border_m($rel="",$url="",$cover_pic="",$title="",$pass=false,$fcsn=""){

  if($pass){
    //$jquery=get_jquery();
    $jquery="
    <script type='text/javascript'>
      $(document).bind('pageinit',function(){
        $('#pass_col_{$fcsn}').hide();
              $('.folder_{$fcsn}').click(function()
        {
          $('.folder_lock_pic_{$fcsn}').hide();
          $('#pass_col_{$fcsn}').show();
        });
      });
    </script>";
	$lock="<img src='images/locked.png' alt='view_lock' title='View lock' border='0' align='absmiddle'>";
    $title_id="cate_pass_title_{$fcsn}";
	$pass_col="<div id='pass_col_{$fcsn}' style='vertical-align: middle;text-align:center;border:0px;margin:0px auto;padding-top:5px;padding-right:5px;color:#606060;font-size:12px;'><form action='{$_SERVER['PHP_SELF']}?csn={$fcsn}' method='post' data-ajax='false'><input type='password' name='passwd' size=12 style='border:1px solid #000;font-size:10px;'><input type='hidden' name='csn' value='{$fcsn}'><input type='submit' value='Go' style='border:1px solid gray;padding:1px 3px;background-color:#cfcfcf;font-size:12px;' data-mini='true'></form></div>";
  }else{
    $lock=$jquery=$pass_col="";
  }

  $data_pass="
  $jquery
  <li class='folder_{$fcsn}'><a href='#' class='folder_lock_pic_{$fcsn}'>{$lock}</a>
  <div style='margin:-5px 5px 10px 5px;text-align:center;font-size:80%;text-overflow:ellipsis;white-space:nowrap;overflow:hidden;'>{$title}</div>{$pass_col}</li>
  ";
  
  $data="
  <li class=''><a href='{$url}' $rel><img src='{$cover_pic}'></a>
  <div style='margin:-5px 5px 10px 5px;text-align:center;font-size:80%;text-overflow:ellipsis;white-space:nowrap;overflow:hidden;'>{$title}</div></li>
  ";

  if($pass){
    $main=$data_pass;
  }else{
    $main=$data;
  }
  return $main;
}

//新增自訂分頁物件mobile
  function getPageBar_mobile($sql="",$show_num=20,$page_list=10,$to_page="",$url_other=""){
    global $xoopsDB;
  	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],10, mysql_error()."<br>$sql");
  	$total=$xoopsDB->getRowsNum($result);

  	$navbar = new PageBar_mobile($total, $show_num, $page_list);
    if(!empty($to_page)){
      $navbar->set_to_page($to_page);
    }

    if(!empty($url_other)){
      $navbar->set_url_other($url_other);
    }
  	$mybar = $navbar->makeBar();
  	//$main['bar']="{$mybar['left']}{$mybar['center']}{$mybar['right']}";
	$main['bar']="{$mybar['left']}{$mybar['right']}";
  	$main['sql']=$sql.$mybar['sql'];
  	$main['total']=$total;

  	return $main;
  }
  
//新增自訂分頁物件mobile
class PageBar_mobile{
	// 目前所在頁碼
	var $current;
	// 所有的資料數量 (rows)
	var $total;
	// 每頁顯示幾筆資料
	var $limit;
	// 目前在第幾層的頁數選項？
	var $pCurrent;
	// 總共分成幾頁？
	var $pTotal;
	// 每一層最多有幾個頁數選項可供選擇，如：3 = {[1][2][3]}
	var $pLimit;
	var $prev;
	var $next;
	var $prev_layer = ' ';
	var $next_layer = ' ';
	var $first;
	var $last;
	var $bottons = array();
	// 要使用的 URL 頁數參數名？
	var $url_page = "g2p";
	// 要使用的 URL 讀取時間參數名？
	var $url_loadtime = "loadtime";
	// 會使用到的 URL 變數名，給 process_query() 過濾用的。
	var $used_query = array();
	// 目前頁數顏色
	var $act_color = "#990000";
	var $query_str; // 存放 URL 參數列

	function PageBar_mobile($total, $limit, $page_limit){
		$mydirname = basename( dirname( __FILE__ ) ) ;
		$this->prev = "<img src='".XOOPS_URL."/modules/{$mydirname}/images/1leftarrow.gif' alt='"._BP_BACK_PAGE."' align='absmiddle' hspace=3>";
		$this->next = "<img src='".XOOPS_URL."/modules/{$mydirname}/images/1rightarrow.gif' alt='"._BP_NEXT_PAGE."' align='absmiddle' hspace=3>";

		$this->limit = $limit;
		$this->total = $total;
		$this->pLimit = $page_limit;
	}

	function init(){
		$this->used_query = array($this->url_page, $this->url_loadtime);
		$this->query_str = $this->processQuery($this->used_query);
		$this->glue = ($this->query_str == "")?'?':
		'&';
		$this->current = (isset($_GET["$this->url_page"]))? $_GET["$this->url_page"]:
		1;
		$this->pTotal = ceil($this->total / $this->limit);
		$this->pCurrent = ceil($this->current / $this->pLimit);
	}

	//初始設定
	function set($active_color = "none", $buttons = "none"){
		if ($active_color != "none"){
			$this->act_color = $active_color;
		}

		if ($buttons != "none"){
			$this->buttons = $buttons;
			$this->prev = $this->buttons['prev'];
			$this->next = $this->buttons['next'];
			$this->prev_layer = $this->buttons['prev_layer'];
			$this->next_layer = $this->buttons['next_layer'];
			$this->first = $this->buttons['first'];
			$this->last = $this->buttons['last'];
		}
	}

	// 處理 URL 的參數，過濾會使用到的變數名稱
	function processQuery($used_query){
		// 將 URL 字串分離成二維陣列
		$vars = explode("&", $_SERVER['QUERY_STRING']);
		for($i = 0; $i < count($vars); $i++){
			$var[$i] = explode("=", $vars[$i]);
		}

		// 過濾要使用的 URL 變數名稱
		for($i = 0; $i < count($var); $i++){
			for($j = 0; $j < count($used_query); $j++){
				if (isset($var[$i][0]) && $var[$i][0] == $used_query[$j]) $var[$i] = array();
			}
		}

		// 合併變數名與變數值
		for($i = 0; $i < count($var); $i++){
			$vars[$i] = implode("=", $var[$i]);
		}

		// 合併為一完整的 URL 字串
		$processed_query = "";
		for($i = 0; $i < count($vars); $i++){
			$glue = ($processed_query == "")?'?':
			'&';
			// 開頭第一個是 '?' 其餘的才是 '&'
			if ($vars[$i] != "") $processed_query .= $glue.$vars[$i];
		}
		return $processed_query;
	}

	// 製作 sql 的 query 字串 (LIMIT)
	function sqlQuery(){
		$row_start = ($this->current * $this->limit) - $this->limit;
		$sql_query = " LIMIT {$row_start}, {$this->limit}";
		return $sql_query;
	}


	// 製作 bar
	function makeBar($url_page = "none"){
		if ($url_page != "none"){
			$this->url_page = $url_page;
		}
		$this->init();

		// 取得目前時間
		$loadtime = '&loadtime='.time();

		// 取得目前頁框(層)的第一個頁數啟始值，如 6 7 8 9 10 = 6
		$i = ($this->pCurrent * $this->pLimit) - ($this->pLimit - 1);

		$bar_center = "<a href='{$_SERVER['PHP_SELF']}' data-role='button' data-icon='home' rel='external'>HOME</a>";

		// 往前跳一頁
		if ($this->current <= 1){
			$bar_left = "<a href='#' title='"._BP_BACK_PAGE."' class='prev ui-disabled' data-role='button' data-icon='arrow-u' rel='external'>"._BP_BACK_PAGE."</a>";
		}	else{
			$i = $this->current-1;
			$bar_left = " <a href='{$_SERVER['PHP_SELF']}{$this->query_str}{$this->glue}{$this->url_page}={$i}' title='"._BP_BACK_PAGE."' class='prev' data-role='button' data-icon='arrow-u' rel='external'>"._BP_BACK_PAGE."</a> ";
		}

		// 往後跳一頁
		if ($this->current >= $this->pTotal){
			$bar_right = "<a href='#' title='"._BP_NEXT_PAGE."' class='next ui-disabled' data-role='button' data-icon='arrow-d' data-iconpos='right' rel='external'>"._BP_NEXT_PAGE."</a>";
		}	else{
			$i = $this->current + 1;
			$bar_right = " <a href='{$_SERVER['PHP_SELF']}{$this->query_str}{$this->glue}{$this->url_page}={$i}' title='"._BP_NEXT_PAGE."' class='next' data-role='button' data-icon='arrow-d' data-iconpos='right' rel='external'>"._BP_NEXT_PAGE."</a> ";
		}

		$page_bar['center'] = $bar_center;
		$page_bar['left'] = $bar_left;
		$page_bar['right'] = $bar_right;
		$page_bar['current'] = $this->current;
		$page_bar['total'] = $this->pTotal;
		$page_bar['sql'] = $this->sqlQuery();
		return $page_bar;
	}

}

/*-----------執行動作判斷區----------*/
$_REQUEST['op']=(empty($_REQUEST['op']))?"":$_REQUEST['op'];

$sn=(isset($_REQUEST['sn']))?intval($_REQUEST['sn']) : 0;
$csn=(isset($_REQUEST['csn']))?intval($_REQUEST['csn']) : 0;
$passwd=(isset($_POST['passwd']))?$_POST['passwd'] : "";


$jquery=get_jquery();


switch($_REQUEST['op']){

	default:
	if(!empty($sn)){
		$main=view_pic($sn);
    $pic=tadgallery::get_tad_gallery($sn);
    $csn=$pic['csn'];
	}else{
		$main=show_cate($csn,$passwd);
	}
	break;
}

//分類下拉選單
$cate_option=get_tad_gallery_cate_option(0,0,$csn);
$cate=tadgallery::get_tad_gallery_cate($csn);
$cate_title=$cate['title'];

//$jquery=get_jquery();
/*-----------秀出結果區--------------*/
$title=$xoopsModule->getVar('name');
$title_m=(empty($cate['title']))?"{$title}":"{$title}-{$cate['title']}";

echo "
<!DOCTYPE HTML>
<html>
<head>
<title>{$title_m}</title>
<meta charset='"._CHARSET."' />
<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;' name='viewport' />
<meta name='apple-mobile-web-app-capable' content='yes' />
<link href='".XOOPS_URL."/modules/tadtools/jquery.mobile/jquery.mobile.css' rel='stylesheet' type='text/css'/>
<link href='class/mobile/photoswipe.css' type='text/css' rel='stylesheet' />
<script type='text/javascript' src='class/mobile/lib/klass.min.js'></script>
<script type='text/javascript' src='".XOOPS_URL."/modules/tadtools/jquery/jquery.js'></script>
<script type='text/javascript' src='".XOOPS_URL."/modules/tadtools/jquery.mobile/jquery.mobile.js'></script>
<script type='text/javascript' src='class/mobile/code.photoswipe.jquery-3.0.5.min.js'></script>
<script type='text/javascript'>
	(function(window, $, PhotoSwipe){
		$(document).ready(function(){
			var options = {};
			$('.nofolder a').photoSwipe(options);
		});
	}(window, window.jQuery, window.Code.PhotoSwipe));
</script>
<style>
.ui-btn-right {
    top: -4px !important;
}
.ui-header .ui-title {
    margin: 0.6em 15% 0.8em !important;
}
.gallery { list-style: none; padding: 0; margin: 0; }
.gallery:after { clear: both; content: "."; display: block; height: 0; visibility: hidden; }
.gallery li { float: left; width: 95px; height:95px; }
.gallery li a { display: block; margin: 5px; border: 1px solid #3c3c3c; }
.gallery li img { display: block; width: 100%; height: 70px; overflow:hidden; }
</style>
</head>
<body>
<div data-role='page' data-add-back-btn='true' id='page_{$csn}'>
	<div data-role='header' data-theme='a' data-position='fixed'>
		<a href='{$_SERVER['PHP_SELF']}' data-icon='home' data-iconpos='notext' class='ui-btn-left' rel='external'>Home</a>
		<h1>{$title_m}</h1>
		<div class='ui-btn-right'>
		<select onChange=\"window.location.href='{$_SERVER['PHP_SELF']}?csn=' + this.value\" data-icon='grid' data-iconpos='notext' data-native-menu='true' data-mini='true' style='padding-top:0px;margin-top:0px'>{$cate_option}</select>
		</div>
	</div>
	<div data-role='content' id='content_{$csn}'>
	{$main}
	</div>
</div>
</body>
</html>
";
?>
