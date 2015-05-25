<?php
include_once "header.php";
include_once "class/tadgallery.php";

if(empty($upload_powers) or !$xoopsUser){
  exit;
}

//編輯相片
function edit_photo($sn){
  global $upload_powers;
  $photo=tadgallery::get_tad_gallery($sn);

  $option=get_tad_gallery_cate_option(0,0,$photo['csn']);
  $tag_select=tag_select($photo['tag']);


  if($_SESSION['bootstrap']=='3'){
    $form_col="
    <div class='form-group'>
      <label class='col-md-2 control-label'>"._MD_TADGAL_CSN."</label>
      <div class='col-md-5'>
        <select name='csn' size=1 class='form-control'>
          $option
        </select>
      </div>
      <div class='col-md-5'>
        <input class='form-control' type='text' name='new_csn' placeholder='"._MD_TADGAL_NEW_CSN."'>
      </div>
    </div>

    <div class='form-group'>
      <label class='col-md-2 control-label'>"._MD_TADGAL_TITLE."</label>
      <div class='col-md-10'>
        <input class='form-control' type='text' name='title' value='{$photo['title']}' id='newTitle' placeholder='"._MD_TADGAL_TITLE."'>
      </div>
    </div>

    <div class='form-group'>
      <label class='col-md-2 control-label'>"._MD_TADGAL_DESCRIPTION."</label>
      <div class='col-md-10'>
        <textarea class='form-control' name='description' id='newDescription'>{$photo['description']}</textarea>
      </div>
    </div>

    <div class='form-group'>
      <label class='col-md-2 control-label'>"._MD_TADGAL_TAG."</label>
      <div class='col-md-10'>
        <input type='text' class='form-control' name='new_tag' id='new_tag' placeholder='"._MD_TADGAL_TAG_TXT."'>
        {$tag_select}
      </div>
    </div>

    <div class='form-group'>
      <label class='col-md-2 control-label'></label>
      <div class='col-md-10'>
        <label class='checkbox-inline'>
          <input type='checkbox' name='cover' value='small/{$photo['dir']}/{$photo['sn']}_s_{$photo['filename']}'>
          "._MD_TADGAL_AS_COVER."
        </label>

        <input type='hidden' name='sn' value='{$photo['sn']}'>
        <input type='hidden' name='op' value='update_tad_gallery'>
        <button type='submit' class='btn btn-primary' id='sbtn'>"._TAD_SAVE."</button>
      </div>
    </div>
    ";
  }else{
    $form_col="
    <div class='control-group'>
      <label class='span2 control-label'>"._MD_TADGAL_CSN."</label>
      <div class='controls controls-row'>
        <select name='csn' size=1 class='span6'>
          $option
        </select>
        <input class='span6' type='text' name='new_csn' placeholder='"._MD_TADGAL_NEW_CSN."'>
      </div>
    </div>

    <div class='control-group'>
      <label class='span2 control-label'>"._MD_TADGAL_TITLE."</label>
      <div class='controls'>
        <input type='text' class='span12' name='title' value='{$photo['title']}' id='newTitle' placeholder='"._MD_TADGAL_TITLE."'>
      </div>
    </div>

    <div class='control-group'>
      <label class='span2 control-label'>"._MD_TADGAL_DESCRIPTION."</label>
      <div class='controls'>
        <textarea name='description' class='span12' id='newDescription'>{$photo['description']}</textarea>
      </div>
    </div>

    <div class='control-group'>
      <label class='span2 control-label'>"._MD_TADGAL_TAG."</label>
      <div class='controls'>
        <input type='text' class='span12' name='new_tag' id='new_tag' placeholder='"._MD_TADGAL_TAG_TXT."'>
        {$tag_select}
      </div>
    </div>

    <div class='control-group'>
      <label class='span2 control-label'></label>
      <div class='controls controls-row'>
        <label class='checkbox inline'>
          <input type='checkbox' name='cover' value='small/{$photo['dir']}/{$photo['sn']}_s_{$photo['filename']}'>
          "._MD_TADGAL_AS_COVER."
        </label>
        <input type='hidden' name='sn' value='{$photo['sn']}'>
        <input type='hidden' name='op' value='update_tad_gallery'>
        <button type='submit' class='btn btn-primary' id='sbtn'>"._TAD_SAVE."</button>
      </div>
    </div>";
  }

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

  <form method='post' id='myForm' style='width:800px;' class='form-horizontal' role='form'>
    $form_col
  </form>
  ";

  return $form;
}


//編輯相簿
function edit_album($csn){
  global $upload_powers;
  include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");

  $span=($_SESSION['bootstrap']=='3')?'col-md-':'span';
  $controls_row=($_SESSION['bootstrap']=='3')?'form-group':'control-group';

  $album=tadgallery::get_tad_gallery_cate($csn);

  //可見群組
  $SelectGroup_name = new XoopsFormSelectGroup("", "enable_group", false,$album['enable_group'], 3, true);
  $SelectGroup_name->addOption("", _MD_TADGAL_ALL_OK, false);
  $SelectGroup_name->setExtra("class='{$span}12'");
  $enable_group = $SelectGroup_name->render();

  //可上傳群組
  $SelectGroup_name = new XoopsFormSelectGroup("", "enable_upload_group", false,$album['enable_upload_group'], 3, true);
  $SelectGroup_name->setExtra("class='{$span}12'");
  $enable_upload_group = $SelectGroup_name->render();


  $cate_select=get_tad_gallery_cate_option(0,0,$album['of_csn'],"","",$csn,1);

  if($_SESSION['bootstrap']=='3'){
    $form_col="
    <div class='form-group'>
      <label class='col-md-2 control-label'>"._MD_TADGAL_ALBUM_TITLE."</label>
      <div class='col-md-10'>
        <input class='form-control' type='text' name='title' value='{$album['title']}' id='newTitle' placeholder='"._MD_TADGAL_TITLE."'>
      </div>
    </div>


    <div class='form-group'>
      <label class='col-md-2 control-label'>"._MD_TADGAL_OF_CSN."</label>
      <div class='col-md-4'>
        <select name='of_csn' size=1 class='form-control'>
          $cate_select
        </select>
      </div>
      <label class='col-md-2 control-label'>"._MD_TADGAL_PASSWD."</label>
      <div class='col-md-4'>
        <input type='text' name='passwd' class='form-control' value='{$album['passwd']}' placeholder='"._MD_TADGAL_PASSWD_DESC."'>
      </div>
    </div>


    <div class='form-group'>
      <label class='col-md-2 control-label'>"._MD_TADGAL_CATE_POWER_SETUP."</label>
      <div class='col-md-5'>
        <label>"._MD_TADGAL_ENABLE_GROUP."</label>
        $enable_group
      </div>
      <div class='col-md-5'>
        <label>"._MD_TADGAL_ENABLE_UPLOAD_GROUP."</label>
        $enable_upload_group
      </div>
    </div>


    <div class='form-group'>
      <label class='col-md-2 control-label'></label>
      <div class='col-md-10'>
        <input type='hidden' name='csn' value='{$album['csn']}'>
        <input type='hidden' name='op' value='update_tad_gallery_cate'>
        <button type='submit' class='btn btn-primary' id='sbtn'>"._TAD_SAVE."</button>
      </div>
    </div>
    ";
  }else{
    $form_col="
    <div class='control-group'>
      <label class='{$span}2 control-label'>"._MD_TADGAL_ALBUM_TITLE."</label>
      <div class='{$span}10 controls controls-row'>
        <input class='span12 form-control' type='text' name='title' value='{$album['title']}' id='newTitle' placeholder='"._MD_TADGAL_TITLE."'>
      </div>
    </div>


    <div class='control-group'>
      <label class='{$span}2 control-label'>"._MD_TADGAL_OF_CSN."</label>
      <div class='{$span}4 controls controls-row'>
        <select name='of_csn' size=1 class='span12 form-control'>
          $cate_select
        </select>
      </div>
      <label class='{$span}2 control-label'>"._MD_TADGAL_PASSWD."</label>
      <div class='{$span}4 controls controls-row'>
        <input type='text' name='passwd' class='span12 form-control' value='{$album['passwd']}' placeholder='"._MD_TADGAL_PASSWD_DESC."'>
      </div>
    </div>


    <div class='control-group'>
      <label class='{$span}2 control-label'>"._MD_TADGAL_CATE_POWER_SETUP."</label>
      <div class='{$span}5 controls controls-row'>
        <label>"._MD_TADGAL_ENABLE_GROUP."</label>
        $enable_group
      </div>
      <div class='{$span}5 controls controls-row'>
        <label>"._MD_TADGAL_ENABLE_UPLOAD_GROUP."</label>
        $enable_upload_group
      </div>
    </div>


    <div class='control-group'>
      <label class='{$span}2 control-label'></label>
      <div class='{$span}10 controls controls-row'>
        <input type='hidden' name='csn' value='{$album['csn']}'>
        <input type='hidden' name='op' value='update_tad_gallery_cate'>
        <button type='submit' class='btn btn-primary' id='sbtn'>"._TAD_SAVE."</button>
      </div>
    </div>
    ";
  }

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

  <form action='' method='post' id='myForm' style='width:600px;' class='form-horizontal' role='form'>
    $form_col
  </form>";

  return $form;
}

function save_order($item_photo='',$item_album=''){
  $sort = 1;
  foreach ($item_photo as $sn) {
    $sql="update ".$xoopsDB->prefix("tad_gallery")." set `photo_sort`='{$sort}' where `sn`='{$sn}'";
    $xoopsDB->queryF($sql) or die(_TADGAL_SORT_COMPLETED." (".date("Y-m-d H:i:s").")");
    $sort++;
  }

  $sort = 1;
  foreach ($item_album as $csn) {
    $sql="update ".$xoopsDB->prefix("tad_gallery_cate")." set `sort`='{$sort}' where `csn`='{$csn}'";
    $xoopsDB->queryF($sql) or die(_TADGAL_SORT_COMPLETED." (".date("Y-m-d H:i:s").")");
    $sort++;
  }



  echo _TADGAL_SORT_COMPLETED." (".date("Y-m-d H:i:s").")";
}


include_once $GLOBALS['xoops']->path( '/modules/system/include/functions.php' );
$op=system_CleanVars( $_REQUEST, 'op', '', 'string' );
$sn=system_CleanVars( $_REQUEST, 'sn', 0, 'int' );
$csn=system_CleanVars( $_REQUEST, 'csn', 0, 'int' );
$item_photo=system_CleanVars( $_POST, 'item_photo', '', 'array' );
$item_album=system_CleanVars( $_POST, 'item_album', '', 'array' );


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
  save_order($item_photo,$item_album);
  break;

  default:
  break;
}


echo tg_html5($main);