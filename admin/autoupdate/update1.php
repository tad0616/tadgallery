<?php
include_once "../../../../mainfile.php";

$_POST['op'] = (isset($_POST['op'])) ? $_POST['op'] : "";
if($_POST['op']=="GO"){
  start_update1();
}

$ver="0.9 -> 1.0";
$title=_MA_GAL_AUTOUPDATE1;
$ok=update_chk1();


function update_chk1(){
        global $xoopsDB;
        $sql="select count(`mode`) from ".$xoopsDB->prefix("tad_gallery_cate");
        $result=$xoopsDB->query($sql);
        if(empty($result)) return false;
        return true;
}


function start_update1(){
        global $xoopsDB;
        $sql="ALTER TABLE ".$xoopsDB->prefix("tad_gallery_cate")." ADD `mode` varchar(255) NOT NULL";
        $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL,3,  mysql_error());

        header("location:{$_SERVER["HTTP_REFERER"]}");
        exit;
}
?>