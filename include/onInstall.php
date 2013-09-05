<?php
function xoops_module_install_tadgallery(&$module) {

	mk_dir(XOOPS_ROOT_PATH."/uploads/tadgallery");
	mk_dir(XOOPS_ROOT_PATH."/uploads/tadgallery/upload_pics");
	mk_dir(XOOPS_ROOT_PATH."/uploads/tadgallery/medium");
	mk_dir(XOOPS_ROOT_PATH."/uploads/tadgallery/small");
	mk_dir(XOOPS_ROOT_PATH."/uploads/tadgallery/small/fb");
	mk_dir(XOOPS_ROOT_PATH."/uploads/tadgallery/mp3");

	return true;
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

?>
