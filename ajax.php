<?php

use Xmf\Request;
use XoopsModules\Tadgallery\Tools;
use XoopsModules\Tadtools\Utility;

require_once __DIR__ . '/header.php';

if (empty($upload_powers) or ! $xoopsUser) {
    exit;
}

$op           = Request::getString('op');
$sn           = Request::getInt('sn');
$csn          = Request::getInt('csn');
$item_photo   = Request::getArray('item_photo');
$item_album   = Request::getArray('item_album');
$HTTP_REFERER = Request::getString('HTTP_REFERER', '', 'SERVER');

switch ($op) {
    case 'edit_photo':
        $main = edit_photo($sn);
        break;
    case 'edit_album':
        $main = edit_album($csn);
        break;
    case 'update_tad_gallery':
        update_tad_gallery($sn);
        header("location:{$HTTP_REFERER}");
        exit;
    case 'delete_tad_gallery':
        $csn = delete_tad_gallery($sn);
        header("location:{$HTTP_REFERER}");
        exit;
    case 'update_tad_gallery_cate':
        update_tad_gallery_cate($csn);
        header("location:{$HTTP_REFERER}");
        exit;
    case 'delete_tad_gallery_cate':
        delete_tad_gallery_cate($csn);
        header("location:{$HTTP_REFERER}");
        exit;

    case 'order':
        save_order($item_photo, $item_album);
        break;
    default:
        break;
}

echo Utility::html5($main);

//編輯相片
function edit_photo($sn)
{
    $photo      = Tools::get_tad_gallery($sn);
    $tag_select = tag_select($photo['tag']);

    $tad_gallery_cate_option = get_tad_gallery_cate_option(0, $photo['csn']);

    $form = "
    <form action='ajax.php' method='post' id='myForm' style='width:720px;' class='form-horizontal' role='form'>
      <div class='form-group row mb-3'>
        <label class='col-sm-2 control-label col-form-label text-sm-right'>" . _MD_TADGAL_CSN . "</label>
        <div class='col-sm-10'>
          <select name='csn_menu' id='csn_menu0' class='form-control form-select d-inline-block' style='width: auto;'>$tad_gallery_cate_option</select>
          <input type='text' name='new_csn' placeholder='" . _MD_TADGAL_NEW_CSN . "' class='form-control d-inline-block' style='width: 13rem;'>
        </div>
      </div>

      <div class='form-group row mb-3'>
        <label class='col-sm-2 control-label col-form-label text-sm-right'>" . _MD_TADGAL_TITLE . "</label>
        <div class='col-sm-10'>
          <input class='form-control' type='text' name='title' value='{$photo['title']}' id='newTitle' placeholder='" . _MD_TADGAL_TITLE . "'>
        </div>
      </div>
      <div class='form-group row mb-3'>
        <label class='col-sm-2 control-label col-form-label text-sm-right'>" . _MD_TADGAL_IS360 . "</label>
        <div class='col-sm-10 controls'>
          <label class='radio-inline'>
            <input type='radio' name='is360' value='1' " . Utility::chk($photo['is360'], '1', 0) . '>' . _YES . "
          </label>
          <label class='radio-inline'>
            <input type='radio' name='is360' value='0' " . Utility::chk($photo['is360'], '0', 1) . '>' . _NO . "
          </label>
        </div>
      </div>
      <div class='form-group row mb-3'>
        <label class='col-sm-2 control-label col-form-label text-sm-right'>" . _MD_TADGAL_DESCRIPTION . "</label>
        <div class='col-sm-10'>
          <textarea class='form-control' name='description' id='newDescription'>{$photo['description']}</textarea>
        </div>
      </div>

      <div class='form-group row mb-3'>
        <label class='col-sm-2 control-label col-form-label text-sm-right'>" . _MD_TADGAL_TAG . "</label>
        <div class='col-sm-10'>
          <input type='text' class='form-control' name='new_tag' id='new_tag' placeholder='" . _MD_TADGAL_TAG_TXT . "'>
          {$tag_select}
        </div>
      </div>

      <div class='form-group row mb-3'>
        <label class='col-sm-2 control-label col-form-label text-sm-right'></label>
        <div class='col-sm-10'>
          <label class='checkbox-inline'>
            <input type='checkbox' name='cover' value='small/{$photo['dir']}/{$photo['sn']}_s_{$photo['filename']}'>
            " . _MD_TADGAL_AS_COVER . "
          </label>

          <input type='hidden' name='sn' value='{$photo['sn']}'>
          <input type='hidden' name='op' value='update_tad_gallery'>
          <button type='submit' class='btn btn-primary' id='sbtn'>" . _TAD_SAVE . "</button>
        </div>
      </div>
    </form>
    ";

    return $form;
}

//編輯相簿
function edit_album($csn)
{
    global $cate_show_mode_array;
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    $album                   = Tools::get_tad_gallery_cate($csn);
    $tad_gallery_cate_option = get_tad_gallery_cate_option($csn, $album['of_csn']);

    //可見群組
    $SelectGroup_name = new \XoopsFormSelectGroup('', 'enable_group', false, $album['enable_group'], 3, true);
    $SelectGroup_name->addOption('', _MD_TADGAL_ALL_OK, false);
    $SelectGroup_name->setExtra("class='form-control'");
    $enable_group = $SelectGroup_name->render();

    //可上傳群組
    $SelectGroup_name = new \XoopsFormSelectGroup('', 'enable_upload_group', false, $album['enable_upload_group'], 3, true);
    $SelectGroup_name->setExtra("class='form-control'");
    $enable_upload_group = $SelectGroup_name->render();

    $cate_show_option = '';
    foreach ($cate_show_mode_array as $key => $value) {
        $selected = ($album['show_mode'] == $key) ? "selected='selected'" : '';
        $cate_show_option .= "<option value='$key' $selected>$value</option>";
    }

    $form = "
    <form action='ajax.php' method='post' id='myForm' style='width:600px;' class='form-horizontal' role='form'>
      <div class='form-group row mb-3'>
        <label class='col-sm-2 control-label col-form-label text-sm-right'>" . _MD_TADGAL_ALBUM_TITLE . "</label>
        <div class='col-sm-10'>
          <input class='form-control' type='text' name='title' value='{$album['title']}' id='newTitle' placeholder='" . _MD_TADGAL_TITLE . "'>
        </div>
      </div>


      <div class='form-group row mb-3'>
        <label class='col-sm-2 control-label col-form-label text-sm-right'>" . _MD_TADGAL_OF_CSN . "</label>
          <div class='col-sm-10 controls'>
                <select name='csn_menu' class='form-control form-select d-inline-block'>$tad_gallery_cate_option</select>
          </div>
      </div>


      <div class='form-group row mb-3'>
        <label class='col-sm-2 control-label col-form-label text-sm-right'>" . _MD_TADGAL_CATE_SHOW_MODE . "</label>
          <div class='col-sm-10 controls'>
            <select name='show_mode' class='form-control form-select'>
            {$cate_show_option}
            </select>
          </div>
      </div>


      <div class='form-group row mb-3'>
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


      <div class='form-group row mb-3'>
        <label class='col-sm-2 control-label col-form-label text-sm-right'>" . _MD_TADGAL_PASSWD . "</label>
        <div class='col-sm-4'>
          <input type='text' name='passwd' class='form-control' value='{$album['passwd']}' placeholder='" . _MD_TADGAL_PASSWD_DESC . "'>
        </div>

        <label class='col-sm-2 control-label col-form-label text-sm-right'></label>
        <div class='col-sm-4'>
          <input type='hidden' name='csn' value='{$album['csn']}'>
          <input type='hidden' name='op' value='update_tad_gallery_cate'>
          <button type='submit' class='btn btn-primary' id='sbtn'>" . _TAD_SAVE . "</button>
        </div>
      </div>
    </form>";

    return $form;
}

function save_order($item_photo = [], $item_album = [])
{
    global $xoopsDB;
    $sort = 1;
    foreach ($item_photo as $sn) {
        $sql = 'UPDATE `' . $xoopsDB->prefix('tad_gallery') . '` SET `photo_sort`=? WHERE `sn`=?';
        Utility::query($sql, 'ii', [$sort, $sn]) or die(_TADGAL_SORT_COMPLETED . ' (' . date('Y-m-d H:i:s') . ')');
        $sort++;
    }

    $sort = 1;
    foreach ($item_album as $csn) {
        $sql = 'UPDATE `' . $xoopsDB->prefix('tad_gallery_cate') . '` SET `sort`=? WHERE `csn`=?';
        Utility::query($sql, 'ii', [$sort, $csn]) or die(_TADGAL_SORT_COMPLETED . ' (' . date('Y-m-d H:i:s') . ')');
        $sort++;
    }

    echo _TADGAL_SORT_COMPLETED . ' (' . date('Y-m-d H:i:s') . ')';
}
