<?php
if(!function_exists("get_tad_gallery_block_cate_option")){
	//取得分類下拉選單
	function get_tad_gallery_block_cate_option($of_csn=0,$level=0,$v=""){
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
			$option.=get_tad_gallery_block_cate_option($csn,$level,$v);
		}
		return $option;
	}
}
