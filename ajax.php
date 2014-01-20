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
          $.fancybox.close();
          location.reload();
        }
      });
      return false;
    });
  })
  </script>

  <form action='' method='post' id='myForm' style='width:600px;'>
    <div class='row-fluid'>

      <label class='span2'>"._MD_TADGAL_CSN."</label>

      <div class='span4'>
        <select name='csn' size=1 class='span12'>
          $option
        </select>
      </div>

      <div class='span6'>
        <input class='span12' type='text' name='new_csn' placeholder='"._MD_TADGAL_NEW_CSN."'>
      </div>
    </div>

    <div class='row-fluid'>
      <label class='span2'>"._MD_TADGAL_TITLE."</label>
      <div class='span10'>
        <input type='text' name='title' class='span12' value='{$photo['title']}' id='newTitle'>
      </div>
    </div>

    <div class='row-fluid'>
      <label class='span2'>"._MD_TADGAL_DESCRIPTION."</label>
      <div class='span10'>
        <textarea class='span12' name='description' id='newDescription'>{$photo['description']}</textarea>
      </div>
    </div>


    <div class='row-fluid'>
      <div class='span2'></div>
      <div class='span7'>
        <label class='checkbox'>
          <input type='checkbox' name='cover' value='small/{$photo['dir']}/{$photo['sn']}_s_{$photo['filename']}'>
          "._MD_TADGAL_AS_COVER."
        </label>
      </div>
      <div class='span3 text-right'>
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

  $album=tadgallery::get_tad_gallery_cate($csn);

  //可見群組
  $SelectGroup_name = new XoopsFormSelectGroup("", "enable_group", false,$album['enable_group'], 3, true);
  $SelectGroup_name->addOption("", _MD_TADGAL_ALL_OK, false);
  $SelectGroup_name->setExtra("class='span12'");
  $enable_group = $SelectGroup_name->render();

  //可上傳群組
  $SelectGroup_name = new XoopsFormSelectGroup("", "enable_upload_group", false,$album['enable_upload_group'], 3, true);
  $SelectGroup_name->setExtra("class='span12'");
  $enable_upload_group = $SelectGroup_name->render();


  $cate_select=get_tad_gallery_cate_option(0,0,$album['of_csn'],"","",$csn,1);

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

          $.fancybox.close();
          location.reload();
        }
      });
      return false;
    });
  })
  </script>

  <form action='' method='post' id='myForm' style='width:600px;'>
    <div class='row-fluid'>

      <label class='span2'>"._MD_TADGAL_ALBUM_TITLE."</label>
      <div class='span10'>
        <input type='text' name='title' class='span12' value='{$album['title']}' id='newTitle'>
      </div>
    </div>

    <div class='row-fluid'>
      <label class='span2'>"._MD_TADGAL_OF_CSN."</label>
      <div class='span4'>
        <select name='of_csn' size=1 class='span12'>
          $cate_select
        </select>
      </div>

      <label class='span2'>"._MD_TADGAL_PASSWD."</label>
      <div class='span4'>
        <input type='text' name='passwd' class='span12' value='{$album['passwd']}' placeholder='"._MD_TADGAL_PASSWD_DESC."'>
      </div>
    </div>

    <div class='row-fluid'>
      <label class='span2'>"._MD_TADGAL_CATE_POWER_SETUP."</label>

      <div class='span5'>
        <label>"._MD_TADGAL_ENABLE_GROUP."</label>
        $enable_group
      </div>

      <div class='span5'>
        <label>"._MD_TADGAL_ENABLE_UPLOAD_GROUP."</label>
        $enable_upload_group
      </div>
    </div>


    <div class='row-fluid'>
      <div class='span12 text-right'>
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
    $xoopsDB->queryF($sql) or die(_TADGAL_SORT_COMPLETED." (".date("Y-m-d H:i:s").")");
    $sort++;
  }

  $sort = 1;
  foreach ($_POST['item_album'] as $csn) {
    $sql="update ".$xoopsDB->prefix("tad_gallery_cate")." set `sort`='{$sort}' where `csn`='{$csn}'";
    $xoopsDB->queryF($sql) or die(_TADGAL_SORT_COMPLETED." (".date("Y-m-d H:i:s").")");
    $sort++;
  }



  echo _TADGAL_SORT_COMPLETED." (".date("Y-m-d H:i:s").")";
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