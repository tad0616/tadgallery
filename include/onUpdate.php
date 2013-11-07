<?php

function xoops_module_update_tadgallery(&$module, $old_version) {
    GLOBAL $xoopsDB;

		if(!chk_chk9()) go_update9();
		//if(!chk_chk10()) go_update10();
	  //mk_dir(XOOPS_ROOT_PATH."/uploads/tadgallery/small/fb");
		
    return true;
}


//新增排序欄位
function chk_chk9(){
	global $xoopsDB;
	$sql="select count(`photo_sort`) from ".$xoopsDB->prefix("tad_gallery");
	$result=$xoopsDB->query($sql);
	if(empty($result)) return false;
	return true;
}

function go_update9(){
	global $xoopsDB;
	$sql="ALTER TABLE ".$xoopsDB->prefix("tad_gallery")." ADD `photo_sort` smallint(5) unsigned NOT NULL";
	$xoopsDB->queryF($sql) or redirect_header(XOOPS_URL."/modules/system/admin.php?fct=modulesadmin",30,  mysql_error());
}


//建立目錄
function mk_dir($dir=""){
    //若無目錄名稱秀出警告訊息
    if(empty($dir))return;
    //若目錄不存在的話建立目錄
    if (!is_dir($dir)) {
        umask(000);
        //若建立失敗秀出警告訊息
        mkdir($dir, 0777);
    }
}

//拷貝目錄
function full_copy( $source="", $target=""){
	if ( is_dir( $source ) ){
		@mkdir( $target );
		$d = dir( $source );
		while ( FALSE !== ( $entry = $d->read() ) ){
			if ( $entry == '.' || $entry == '..' ){
				continue;
			}

			$Entry = $source . '/' . $entry;
			if ( is_dir( $Entry ) )	{
				full_copy( $Entry, $target . '/' . $entry );
				continue;
			}
			copy( $Entry, $target . '/' . $entry );
		}
		$d->close();
	}else{
		copy( $source, $target );
	}
}


function rename_win($oldfile,$newfile) {
   if (!rename($oldfile,$newfile)) {
      if (copy ($oldfile,$newfile)) {
         unlink($oldfile);
         return TRUE;
      }
      return FALSE;
   }
   return TRUE;
}
?>
