<?php
include_once XOOPS_ROOT_PATH . "/modules/tadgallery/class/tadgallery.php";

//區塊主函式 (列出最新的相片評論)
function tadgallery_show_re($options)
{
    global $xoopsDB;
    $limit    = empty($options[0]) ? 10 : intval($options[0]);
    $userinfo = empty($options[1]) ? 0 : intval($options[1]);
    $showall  = empty($options[2]) ? 0 : intval($options[2]);

    $modhandler  = &xoops_gethandler('module');
    $xoopsModule = &$modhandler->getByDirname("tadgallery");
    $com_modid   = $xoopsModule->getVar('mid');
    $sql         = "select a.com_id,a.com_text,a.com_itemid,a.com_uid,b.title,b.filename,b.uid from " . $xoopsDB->prefix("xoopscomments") . " as a left join " . $xoopsDB->prefix("tad_gallery") . " as b on a.com_itemid=b.sn where a.com_modid='$com_modid' order by a.com_modified desc limit 0,{$limit}";
    $result      = $xoopsDB->query($sql);
    $block       = $comment       = "";
    $i           = 0;

    while (list($com_id, $txt, $nsn, $uid, $title, $filename, $poster_uid) = $xoopsDB->fetchRow($result)) {
        $uid_name        = XoopsUser::getUnameFromId($uid, 1);
        $poster_uid_name = XoopsUser::getUnameFromId($poster_uid, 1);

        $comment[$i]['uid']             = $uid;
        $comment[$i]['uid_name']        = (empty($uid_name)) ? XoopsUser::getUnameFromId($uid, 0) : $uid_name;
        $comment[$i]['poster_uid']      = $poster_uid;
        $comment[$i]['poster_uid_name'] = (empty($poster_uid_name)) ? XoopsUser::getUnameFromId($poster_uid, 0) : $poster_uid_name;
        $comment[$i]['nsn']             = $nsn;
        $comment[$i]['com_id']          = $com_id;
        $comment[$i]['txt']             = $txt;
        $comment[$i]['title']           = (empty($title)) ? $filename : $title;
        $i++;
    }

    $block['showall']  = $showall;
    $block['userinfo'] = $userinfo;
    $block['comment']  = $comment;

    return $block;
}

//區塊編輯函式
function tadgallery_re_edit($options)
{

    $userinfo_1 = ($options[1] == "1") ? "checked" : "";
    $userinfo_0 = ($options[1] != "1") ? "checked" : "";

    $showall_1 = ($options[2] == "1") ? "checked" : "";
    $showall_0 = ($options[2] != "1") ? "checked" : "";

    $form = "
  <div>
    " . _MB_TADGAL_RE_EDIT_BITEM0 . "
    <input type='text' name='options[0]' value='{$options[0]}'>
  </div>

  <div>
  " . _MB_TADGAL_RE_EDIT_BITEM2 . "
    <label for='userinfo_0'>
      <input type='radio' name='options[1]' value='0' $userinfo_0 id='userinfo_0'>
      " . _MB_TADGAL_RE_EDIT_BITEM2_OPT1 . "
    </label>
    <label for='userinfo_1'>
      <input type='radio' name='options[1]' value='1' $userinfo_1 id='userinfo_1'>
      " . _MB_TADGAL_RE_EDIT_BITEM2_OPT2 . "
    </label>
  </div>
  <div>
  " . _MB_TADGAL_RE_EDIT_BITEM3 . "
    <label for='showall_0'>
      <input type='radio' name='options[2]' value='0' $showall_0 id='showall_0'>" . _MB_TADGAL_RE_EDIT_BITEM3_OPT1 . "
    </label>
    <label for='showall_1'>
      <input type='radio' name='options[2]' value='1' $showall_1 id='showall_1'>" . _MB_TADGAL_RE_EDIT_BITEM3_OPT2 . "
    </label>
  </div>
  ";
    return $form;
}
