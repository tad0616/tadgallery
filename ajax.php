<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/class/tadgallery.php';

if (empty($upload_powers) or !$xoopsUser) {
    exit;
}

//編輯相片
function edit_photo($sn)
{
    global $upload_powers;
    $photo = tadgallery::get_tad_gallery($sn);

    $tag_select = tag_select($photo['tag']);

    $path = get_tadgallery_cate_path($photo['csn']);
    $patharr = array_keys($path);
    $make_option_js = '';
    foreach ($patharr as $k => $of_csn) {
        $j = $k + 1;
        $make_option_js .= "make_option('csn_menu','{$k}','{$of_csn}','{$patharr[$j]}');\n";
    }

    $form_col = "
      <div class='form-group row'>
        <label class='col-sm-2 control-label col-form-label text-sm-right'>" . _MD_TADGAL_CSN . "</label>
        <div class='col-sm-10'>
          <select name='csn_menu[0]' id='csn_menu0' class='csn_menu'><option value=''></option></select>
          <select name='csn_menu[1]' id='csn_menu1' class='csn_menu' style='display: none;'></select>
          <select name='csn_menu[2]' id='csn_menu2' class='csn_menu' style='display: none;'></select>
          <select name='csn_menu[3]' id='csn_menu3' class='csn_menu' style='display: none;'></select>
          <select name='csn_menu[4]' id='csn_menu4' class='csn_menu' style='display: none;'></select>
          <select name='csn_menu[5]' id='csn_menu5' class='csn_menu' style='display: none;'></select>
          <select name='csn_menu[6]' id='csn_menu6' class='csn_menu' style='display: none;'></select>
          <input type='text' name='new_csn' placeholder='" . _MD_TADGAL_NEW_CSN . "' class='csn_menu' style='width: 200px;'>
        </div>
      </div>

      <div class='form-group row'>
        <label class='col-sm-2 control-label col-form-label text-sm-right'>" . _MD_TADGAL_TITLE . "</label>
        <div class='col-sm-10'>
          <input class='form-control' type='text' name='title' value='{$photo['title']}' id='newTitle' placeholder='" . _MD_TADGAL_TITLE . "'>
        </div>
      </div>
      <div class='form-group row'>
        <label class='col-sm-2 control-label col-form-label text-sm-right'>" . _MD_TADGAL_IS360 . "</label>
        <div class='col-sm-10 controls'>
          <label class='radio-inline'>
            <input type='radio' name='is360' value='1' " . chk($photo['is360'], '1', 0) . '>' . _YES . "
          </label>
          <label class='radio-inline'>
            <input type='radio' name='is360' value='0' " . chk($photo['is360'], '0', 1) . '>' . _NO . "
          </label>
        </div>
      </div>
      <div class='form-group row'>
        <label class='col-sm-2 control-label col-form-label text-sm-right'>" . _MD_TADGAL_DESCRIPTION . "</label>
        <div class='col-sm-10'>
          <textarea class='form-control' name='description' id='newDescription'>{$photo['description']}</textarea>
        </div>
      </div>

      <div class='form-group row'>
        <label class='col-sm-2 control-label col-form-label text-sm-right'>" . _MD_TADGAL_TAG . "</label>
        <div class='col-sm-10'>
          <input type='text' class='form-control' name='new_tag' id='new_tag' placeholder='" . _MD_TADGAL_TAG_TXT . "'>
          {$tag_select}
        </div>
      </div>

      <div class='form-group row'>
        <label class='col-sm-2 control-label col-form-label text-sm-right'></label>
        <div class='col-sm-10'>
          <label class='checkbox-inline'>
            <input type='checkbox' name='cover' value='small/{$photo['dir']}/{$photo['sn']}_s_{$photo['filename']}'>
            " . _MD_TADGAL_AS_COVER . "
          </label>

          <input type='hidden' name='sn' value='{$photo['sn']}'>
          <input type='hidden' name='op' value='update_tad_gallery'>
          <button type='submit' class='btn btn-primary' id='sbtn'>" . _TAD_SAVE . '</button>
        </div>
      </div>
      ';

    $form = "
    <script>
      $(function(){
        $make_option_js

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
      });

      function make_option(menu_name , num , of_csn , def_csn){
        $('#'+menu_name+num).show();
        $.post('ajax_menu.php',  {'of_csn': of_csn , 'def_csn': def_csn} , function(data) {
          $('#'+menu_name+num).html(\"<option value=''>/</option>\"+data);
        });

        $('.'+menu_name).change(function(){
        var menu_id= $(this).attr('id');
        var len=menu_id.length-1;
        var next_num = Number(menu_id.charAt(len))+1
          var next_menu = menu_name + next_num;
          $.post('ajax_menu.php',  {'of_csn': $('#'+menu_id).val()} , function(data) {
            if(data==''){
              $('#'+next_menu).hide();
            }else{
              $('#'+next_menu).show();
              $('#'+next_menu).html(\"<option value=''>/</option>\"+data);
            }

          });
        });
      }
    </script>

    <form method='post' id='myForm' style='width:800px;' class='form-horizontal' role='form'>
      $form_col
    </form>
    ";

    return $form;
}

//編輯相簿
function edit_album($csn)
{
    global $upload_powers;
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    $path = get_tadgallery_cate_path($csn, false);
    $patharr = array_keys($path);
    $make_option_js = '';
    foreach ($patharr as $k => $of_csn) {
        $j = $k + 1;
        $make_option_js .= "make_option('of_csn_menu','{$k}','{$of_csn}','{$patharr[$j]}');\n";
    }

    $album = tadgallery::get_tad_gallery_cate($csn);

    //可見群組
    $SelectGroup_name = new XoopsFormSelectGroup('', 'enable_group', false, $album['enable_group'], 3, true);
    $SelectGroup_name->addOption('', _MD_TADGAL_ALL_OK, false);
    $SelectGroup_name->setExtra("class='col-sm-12'");
    $enable_group = $SelectGroup_name->render();

    //可上傳群組
    $SelectGroup_name = new XoopsFormSelectGroup('', 'enable_upload_group', false, $album['enable_upload_group'], 3, true);
    $SelectGroup_name->setExtra("class='col-sm-12'");
    $enable_upload_group = $SelectGroup_name->render();

    $form_col = "
        <div class='form-group row'>
          <label class='col-sm-2 control-label col-form-label text-sm-right'>" . _MD_TADGAL_ALBUM_TITLE . "</label>
          <div class='col-sm-10'>
            <input class='form-control' type='text' name='title' value='{$album['title']}' id='newTitle' placeholder='" . _MD_TADGAL_TITLE . "'>
          </div>
        </div>


        <div class='form-group row'>
          <label class='col-sm-2 control-label col-form-label text-sm-right'>" . _MD_TADGAL_OF_CSN . "</label>
          <div class='col-sm-10'>
            <select name='of_csn_menu[0]' id='of_csn_menu0' class='of_csn_menu'><option value=''></option></select>
            <select name='of_csn_menu[1]' id='of_csn_menu1' class='of_csn_menu' style='display: none;'></select>
            <select name='of_csn_menu[2]' id='of_csn_menu2' class='of_csn_menu' style='display: none;'></select>
            <select name='of_csn_menu[3]' id='of_csn_menu3' class='of_csn_menu' style='display: none;'></select>
            <select name='of_csn_menu[4]' id='of_csn_menu4' class='of_csn_menu' style='display: none;'></select>
            <select name='of_csn_menu[5]' id='of_csn_menu5' class='of_csn_menu' style='display: none;'></select>
            <select name='of_csn_menu[6]' id='of_csn_menu6' class='of_csn_menu' style='display: none;'></select>
          </div>
        </div>


        <div class='form-group row'>
          <label class='col-sm-2 control-label col-form-label text-sm-right'>" . _MD_TADGAL_CATE_POWER_SETUP . "</label>
          <div class='col-sm-5'>
            <label>" . _MD_TADGAL_ENABLE_GROUP . "</label>
            $enable_group
          </div>
          <div class='col-sm-5'>
            <label>" . _MD_TADGAL_ENABLE_UPLOAD_GROUP . "</label>
            $enable_upload_group
          </div>
        </div>


        <div class='form-group row'>
          <label class='col-sm-2 control-label col-form-label text-sm-right'>" . _MD_TADGAL_PASSWD . "</label>
          <div class='col-sm-4'>
            <input type='text' name='passwd' class='form-control' value='{$album['passwd']}' placeholder='" . _MD_TADGAL_PASSWD_DESC . "'>
          </div>

          <label class='col-sm-2 control-label col-form-label text-sm-right'></label>
          <div class='col-sm-4'>
            <input type='hidden' name='csn' value='{$album['csn']}'>
            <input type='hidden' name='op' value='update_tad_gallery_cate'>
            <button type='submit' class='btn btn-primary' id='sbtn'>" . _TAD_SAVE . '</button>
          </div>
        </div>
        ';

    $form = "
      <script>
        $(function(){
          $make_option_js
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


        function make_option(menu_name , num , of_csn , def_csn){
          $('#'+menu_name+num).show();
          $.post('ajax_menu.php',  {'of_csn': of_csn , 'def_csn': def_csn} , function(data) {
            $('#'+menu_name+num).html(\"<option value=''>/</option>\"+data);
          });

          $('.'+menu_name).change(function(){
          var menu_id= $(this).attr('id');
          var len=menu_id.length-1;
          var next_num = Number(menu_id.charAt(len))+1
            var next_menu = menu_name + next_num;
            $.post('ajax_menu.php',  {'of_csn': $('#'+menu_id).val()} , function(data) {
              if(data==''){
                $('#'+next_menu).hide();
              }else{
                $('#'+next_menu).show();
                $('#'+next_menu).html(\"<option value=''>/</option>\"+data);
              }

            });
          });
        }
      </script>

      <form action='' method='post' id='myForm' style='width:600px;' class='form-horizontal' role='form'>
        $form_col
      </form>";

    return $form;
}

function save_order($item_photo = '', $item_album = '')
{
    $sort = 1;
    foreach ($item_photo as $sn) {
        $sql = 'update ' . $xoopsDB->prefix('tad_gallery') . " set `photo_sort`='{$sort}' where `sn`='{$sn}'";
        $xoopsDB->queryF($sql) or die(_TADGAL_SORT_COMPLETED . ' (' . date('Y-m-d H:i:s') . ')');
        $sort++;
    }

    $sort = 1;
    foreach ($item_album as $csn) {
        $sql = 'update ' . $xoopsDB->prefix('tad_gallery_cate') . " set `sort`='{$sort}' where `csn`='{$csn}'";
        $xoopsDB->queryF($sql) or die(_TADGAL_SORT_COMPLETED . ' (' . date('Y-m-d H:i:s') . ')');
        $sort++;
    }

    echo _TADGAL_SORT_COMPLETED . ' (' . date('Y-m-d H:i:s') . ')';
}

require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$sn = system_CleanVars($_REQUEST, 'sn', 0, 'int');
$csn = system_CleanVars($_REQUEST, 'csn', 0, 'int');
$item_photo = system_CleanVars($_POST, 'item_photo', '', 'array');
$item_album = system_CleanVars($_POST, 'item_album', '', 'array');

switch ($op) {
    case 'edit_photo':
        $main = edit_photo($sn);
        break;
    case 'edit_album':
        $main = edit_album($csn);
        break;
    case 'update_tad_gallery':
        update_tad_gallery($sn);
        break;
    case 'delete_tad_gallery':
        $csn = delete_tad_gallery($sn);
        mk_rss_xml();
        mk_rss_xml($csn);
        break;
    case 'update_tad_gallery_cate':
        update_tad_gallery_cate($csn);
        break;
    case 'delete_tad_gallery_cate':
        delete_tad_gallery_cate($csn);
        mk_rss_xml();
        header("location:{\Xmf\Request::getString('HTTP_REFERER', '', 'SERVER')}");
        exit;

    case 'order':
        save_order($item_photo, $item_album);
        break;
    default:
        break;
}

echo tg_html5($main);
