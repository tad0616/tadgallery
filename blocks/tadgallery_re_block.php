<?php
include_once XOOPS_ROOT_PATH."/modules/tadgallery/class/tadgallery.php";

//區塊主函式 (列出最新的相片評論)
function tadgallery_show_re($options){
  global $xoopsDB;
  $modhandler = &xoops_gethandler('module');
  $xoopsModule = &$modhandler->getByDirname("tadgallery");
  $com_modid=$xoopsModule->getVar('mid');
  $sql = "select a.com_id,a.com_text,a.com_itemid,a.com_uid,b.title,b.filename,b.uid from ".$xoopsDB->prefix("xoopscomments")." as a left join ".$xoopsDB->prefix("tad_gallery")." as b on a.com_itemid=b.sn where a.com_modid='$com_modid' order by a.com_modified desc limit 0,{$options[0]}";
  $result = $xoopsDB->query($sql);
  $block="";
  $i=0;
  //if($options[3]=="1")$options[2]=1;
  while(list($com_id,$txt,$nsn,$uid,$title,$filename,$poster_uid)=$xoopsDB->fetchRow($result)){
    $uid_name=XoopsUser::getUnameFromId($uid,1);
        $uid_name=(empty($uid_name))?XoopsUser::getUnameFromId($uid,0):$uid_name;
    if($options[2]=="1"){
      $poster_uid_name=XoopsUser::getUnameFromId($poster_uid,1);
            $poster_uid_name=(empty($poster_uid_name))?XoopsUser::getUnameFromId($poster_uid,0):$poster_uid_name;
      $title=(empty($title))?$filename:$title;
      $who="<div style='margin-bottom:6px;font-size:11px;width:{$options[1]}px;height:14px;overflow:hidden;'><a href='".XOOPS_URL."/modules/tadgallery/index.php?show_uid=$poster_uid'>{$poster_uid_name}</a><img src='".XOOPS_URL."/modules/tadgallery/images/image.png' hspace='4' align='absmiddle'><a href='".XOOPS_URL."/modules/tadgallery/view.php?sn={$nsn}'>{$title}</a></div>";
    }else{
      $who="";
    }


    $block[$i]['options3']=$options[3];
    $block[$i]['uid']=$uid;
    $block[$i]['uid_name']=$uid_name;
    $block[$i]['nsn']=$nsn;
    $block[$i]['com_id']=$com_id;
    $block[$i]['txt']=$txt;
    $block[$i]['who']=$who;
    $i++;
  }

  return $block;
}

//區塊編輯函式
function tadgallery_re_edit($options){


  $userinfo_1=($options[2]=="1")?"checked":"";
  $userinfo_0=($options[2]=="0")?"checked":"";


  $showall_1=($options[3]=="1")?"checked":"";
  $showall_0=($options[3]=="0")?"checked":"";


  $form="
  "._MB_TADGAL_RE_EDIT_BITEM0."
  <INPUT type='text' name='options[0]' value='{$options[0]}'><br>
  <INPUT type='hidden' name='options[1]' value='{$options[1]}'>
  "._MB_TADGAL_RE_EDIT_BITEM2."
  <INPUT type='radio' name='options[2]' value='0' $userinfo_0>"._MB_TADGAL_RE_EDIT_BITEM2_OPT1."
  <INPUT type='radio' name='options[2]' value='1' $userinfo_1>"._MB_TADGAL_RE_EDIT_BITEM2_OPT2."
  <br>
  "._MB_TADGAL_RE_EDIT_BITEM3."
  <INPUT type='radio' name='options[3]' value='0' $showall_0>"._MB_TADGAL_RE_EDIT_BITEM3_OPT1."
  <INPUT type='radio' name='options[3]' value='1' $showall_1>"._MB_TADGAL_RE_EDIT_BITEM3_OPT2."
  ";
  return $form;
}


?>