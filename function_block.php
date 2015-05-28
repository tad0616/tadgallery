<?php

//顯示相片數：
if(!function_exists("common_setup")){
  function common_setup($opt=""){
    //die(var_export($opt));

    $opt[0]=intval($opt[0]);
    if(empty($opt[0]))$opt[0]=12;

    $cate_select=get_tad_gallery_block_cate(0,0,$opt[1]);

    $include_sub0=($opt[2]=="0")?"checked":"";
    $include_sub1=($opt[2]!="0")?"checked":"";

    $sortby_0=($opt[3]=="post_date")?"selected":"";
    $sortby_1=($opt[3]=="counter")?"selected":"";
    $sortby_2=($opt[3]=="rand")?"selected":"";
    $sortby_3=($opt[3]=="photo_sort" or empty($opt[3]))?"selected":"";

    $sort_normal=($opt[4]!="desc")?"selected":"";
    $sort_desc=($opt[4]=="desc")?"selected":"";

    $thumb_s=($opt[5]=="s")?"checked":"";
    $thumb_m=($opt[5]!="s")?"checked":"";

    $only_good_0=($opt[6]!="1")?"selected":"";
    $only_good_1=($opt[6]=="1")?"selected":"";

    $col="
    <div>"._MB_TADGAL_BLOCK_SHOWNUM."
      <input type='text' name='options[0]' value='{$opt[0]}' size=2>
    </div>
    <div>"._MB_TADGAL_BLOCK_SHOWCATE."
      <select name='options[1]'>
        {$cate_select}
      </select>
      "._MB_TADGAL_BLOCK_INCLUDE_SUB_ALBUMS."
      <label for='include_sub1'>
        <input type='radio' name='options[2]' id='include_sub1' value='1' $include_sub1>"._YES."
      </label>
      <label for='include_sub0'>
        <input type='radio' name='options[2]' id='include_sub0' value='0' $include_sub0>"._NO."
      </label>
    </div>

    <div>"._MB_TADGAL_BLOCK_SORTBY."
      <select name='options[3]'>
        <option value='post_date' $sortby_0>"._MB_TADGAL_BLOCK_SORTBY_MODE1."</option>
        <option value='counter' $sortby_1>"._MB_TADGAL_BLOCK_SORTBY_MODE2."</option>
        <option value='rand' $sortby_2>"._MB_TADGAL_BLOCK_SORTBY_MODE3."</option>
        <option value='photo_sort' $sortby_3>"._MB_TADGAL_BLOCK_SORTBY_MODE4."</option>
      </select>
      <select name='options[4]'>
        <option value='' $sort_normal>"._MB_TADGAL_BLOCK_SORT_NORMAL."</option>
        <option value='desc' $sort_desc>"._MB_TADGAL_BLOCK_SORT_DESC."</option>
      </select>
    </div>

    <div>"._MB_TADGAL_BLOCK_THUMB."
      <label for='thumb_s'>
        <input type='radio' $thumb_s name='options[5]' value='s' id='thumb_s'>
        "._MB_TADGAL_BLOCK_THUMB_S."
      </label>
      <label for='thumb_m'>
      <input type='radio' $thumb_m name='options[5]' value='m' id='thumb_m'>
      "._MB_TADGAL_BLOCK_THUMB_M."
      </label>
    </div>

    <div>"._MB_TADGAL_BLOCK_SHOW_TYPE."
      <select name='options[6]'>
        <option value='0' $only_good_0>"._MB_TADGAL_BLOCK_SHOW_ALL."</option>
        <option value='1' $only_good_1>"._MB_TADGAL_BLOCK_ONLY_GOOD."</option>
      </select>
    </div>
    ";
    return $col;
  }
}

if(!function_exists("get_tad_gallery_block_cate")){
  //取得分類下拉選單
  function get_tad_gallery_block_cate($of_csn=0,$level=0,$v=""){
    global $xoopsDB,$xoopsUser;

    $modhandler = &xoops_gethandler('module');
    $xoopsModule = &$modhandler->getByDirname("tadgallery");

    if ($xoopsUser) {
      $module_id = $xoopsModule->getVar('mid');
      $isAdmin=$xoopsUser->isAdmin($module_id);
    }else{
      $isAdmin=false;
    }

    $sql = "select count(*),csn from ".$xoopsDB->prefix("tad_gallery")." group by csn";
    $result = $xoopsDB->query($sql);
    while(list($count,$csn)=$xoopsDB->fetchRow($result)){
      $cate_count[$csn]=$count;
    }

    //$left=$level*10;
    $level+=1;

    $syb=str_repeat("-", $level)." ";

    $option=($of_csn)?"":"<option value='0'>"._MB_TADGAL_BLOCK_ALL."</option>";
    $sql = "select csn,title from ".$xoopsDB->prefix("tad_gallery_cate")." where of_csn='{$of_csn}' and passwd='' and enable_group='' order by sort";
    $result = $xoopsDB->query($sql);

    while(list($csn,$title)=$xoopsDB->fetchRow($result)){
      $selected=($v==$csn)?"selected":"";
      $count=(empty($cate_count[$csn]))?0:$cate_count[$csn];
      $option.="<option value='{$csn}' $selected>{$syb}{$title}({$count})</option>";
      $option.=get_tad_gallery_block_cate($csn,$level,$v);
    }
    return $option;
  }
}

