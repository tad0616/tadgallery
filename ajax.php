<?php
include_once "header.php";
include_once "class/tadgallery.php";

if(sizeof($upload_powers)<=0 or !$xoopsUser){
	exit;
}

//編輯相片
function edit_photo($sn){
global $upload_powers;
  $photo=tadgallery::get_tad_gallery($sn);
  
  $option=get_tad_gallery_cate_option(0,0,$photo['csn']);
  
  $form="
  <script>
  $(function(){
    $('#myForm').bind('submit', function() {
      $.ajax({
        type : 'POST',
        cache : false,
        url : 'ajax.php',
        data : $(this).serializeArray(),
        success: function(data) {
          if($('#newTitle').val()!=''){
            $('#title{$sn}').parent().addClass('outline');
            $('#title{$sn}').text($('#newTitle').val());
          }

          if($('#newDescription').val()!=''){
            $('#description{$sn}').text($('#newDescription').val());
            $('#description{$sn}').addClass('photo_description');
          }
          $('#tg_container').masonry('reload');
          $.fancybox.close();
        }
      });
      return false;
    });
  })
  </script>

  <form action='' method='post' id='myForm'>
    <div class='controls controls-row'>
      <label class='span1'>"._MD_TADGAL_CSN."</label>
      <select name='csn' size=1 class='span2'>
      	$option
      </select>
      <input class='span2' type='text' name='new_csn' placeholder='"._MD_TADGAL_NEW_CSN."'>
    </div>

    <div class='controls controls-row'>
      <label class='span1'>"._MD_TADGAL_TITLE."</label>
      <input type='text' name='title' class='span4' value='{$photo['title']}' id='newTitle'>
    </div>

    <div class='controls controls-row'>
      <label class='span1'>"._MD_TADGAL_DESCRIPTION."</label>
      <textarea class='span4' name='description' id='newDescription'>{$photo['description']}</textarea>
    </div>


    <div class='controls controls-row'>
      <div class='span1'></div>
      <label class='checkbox span3'>
        <input type='checkbox' name='cover' value='small/{$photo['dir']}/{$photo['sn']}_s_{$photo['filename']}'>
        "._MD_TADGAL_AS_COVER."
      </label>
      <div class='span1 text-right'>
        <input type='hidden' name='sn' value='{$photo['sn']}'>
        <input type='hidden' name='op' value='update_tad_gallery'>
        <button type='submit' class='btn' id='sbtn'>"._TAD_SAVE."</button>
      </div>
    </div>

  </form>";
  
  return $form;
}


//編輯相簿
function edit_album($csn){
global $upload_powers;
  include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");

  //可見群組
  $SelectGroup_name = new XoopsFormSelectGroup("", "enable_group", false,$enable_group, 3, true);
  $SelectGroup_name->addOption("", _MD_TADGAL_ALL_OK, false);
  $SelectGroup_name->setExtra("class='span2'");
  $enable_group = $SelectGroup_name->render();

  //可上傳群組
  $SelectGroup_name = new XoopsFormSelectGroup("", "enable_upload_group", false,$enable_upload_group, 3, true);
  $SelectGroup_name->setExtra("class='span2'");
  $enable_upload_group = $SelectGroup_name->render();
  
  $album=tadgallery::get_tad_gallery_cate($csn);

  $cate_select=get_tad_gallery_cate_option(0,0,$of_csn,"","",$csn,1);

  $form="
  <script>
  $(function(){
    $('#myForm').bind('submit', function() {
      $.ajax({
        type : 'POST',
        cache : false,
        url : 'ajax.php',
        data : $(this).serializeArray(),
        success: function(data) {
          if($('#newTitle').val()!=''){
            $('#albumTitle{$csn}').parent().addClass('outline');
            $('#albumTitle{$csn}').text($('#newTitle').val());
          }

          $('#tg_container').masonry('reload');
          $.fancybox.close();
        }
      });
      return false;
    });
  })
  </script>

  <form action='' method='post' id='myForm'>
    <div class='controls controls-row'>
      <label class='span1'>"._MD_TADGAL_OF_CSN."</label>
      <select name='of_csn' size=1 class='span2'>
      $cate_select
      </select>
    </div>

    <div class='controls controls-row'>
      <label class='span1'>"._MD_TADGAL_TITLE."</label>
      <input type='text' name='title' class='span2' value='{$album['title']}' id='newTitle'>
      <label class='span1'>"._MD_TADGAL_PASSWD."</label>
      <input type='text' name='passwd' class='span1' value='{$album['passwd']}' placeholder='"._MD_TADGAL_PASSWD_DESC."'>
    </div>

    <div class='controls controls-row'>
      <label class='span1'>"._MD_TADGAL_CATE_POWER_SETUP."</label>
      <div class='span2'>"._MD_TADGAL_ENABLE_GROUP."
      $enable_group
      </div>
      <div class='span2'>"._MD_TADGAL_ENABLE_UPLOAD_GROUP."
      $enable_upload_group
      </div>
    </div>


    <div class='controls controls-row'>
      <div class='span4'></div>
      <div class='span1 text-right'>
        <input type='hidden' name='csn' value='{$album['csn']}'>
        <input type='hidden' name='op' value='update_tad_gallery_cate'>
        <button type='submit' class='btn' id='sbtn'>"._TAD_SAVE."</button>
      </div>
    </div>

  </form>";

  return $form;
}

function save_order(){
  $sort = 1;
  foreach ($_POST['item_photo'] as $sn) {
    $sql="update ".$xoopsDB->prefix("tad_gallery")." set `photo_sort`='{$sort}' where `sn`='{$sn}'";
    $xoopsDB->queryF($sql) or die("更新失敗！ (".date("Y-m-d H:i:s").")");
    $sort++;
  }

  $sort = 1;
  foreach ($_POST['item_album'] as $csn) {
    $sql="update ".$xoopsDB->prefix("tad_gallery_cate")." set `sort`='{$sort}' where `csn`='{$csn}'";
    $xoopsDB->queryF($sql) or die("更新失敗！ (".date("Y-m-d H:i:s").")");
    $sort++;
  }
  
  

  echo "排序完成！ (".date("Y-m-d H:i:s").")";
}


$op=isset($_REQUEST['op'])?$_REQUEST['op']:"";
$sn=isset($_REQUEST['sn'])?intval($_REQUEST['sn']):"";
$csn=isset($_REQUEST['csn'])?intval($_REQUEST['csn']):"";

switch($op){
  case "edit_photo":
  $main=edit_photo($sn);
  break;
  
  case "edit_album":
  $main=edit_album($csn);
  break;

	case "update_tad_gallery":
	update_tad_gallery($sn);
	break;

	case "delete_tad_gallery":
	$csn=delete_tad_gallery($sn);
	mk_rss_xml();
  mk_rss_xml($csn);
	break;
	
	case "update_tad_gallery_cate":
  update_tad_gallery_cate($csn);
  break;
  
  case "delete_tad_gallery_cate";
  delete_tad_gallery_cate($csn);
  mk_rss_xml();
  break;
	
  case "order":
  save_order();
  break;
  
  default:
  break;
}


echo $main;

?>