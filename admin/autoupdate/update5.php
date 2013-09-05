<?php
include_once "../../../../mainfile.php";

$_POST['op'] = (isset($_POST['op'])) ? $_POST['op'] : "";
if($_POST['op']=="GO"){
  start_update5();
}

$ver="1.2 -> 1.3";
$title=_MA_GAL_AUTOUPDATE5;
$ok=update_chk5();


function update_chk5(){
        global $xoopsDB;
        $sql="select count(`no_hotlink`) from ".$xoopsDB->prefix("tad_gallery_cate");
        $result=$xoopsDB->query($sql);
        if(empty($result)) return false;
        return true;
}


function start_update5(){
        global $xoopsDB;
        $sql="ALTER TABLE ".$xoopsDB->prefix("tad_gallery_cate")." ADD `no_hotlink` varchar(255) NOT NULL";
        $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL,3,  mysql_error());

        header("location:{$_SERVER["HTTP_REFERER"]}");
        exit;
}
?>