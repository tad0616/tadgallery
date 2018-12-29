<?php
include '../../mainfile.php';
//$com_itemid就是主索引值，亦即流水號欄位值
$com_itemid = isset($_GET['com_itemid']) ? (int)$_GET['com_itemid'] : 0;

if ($com_itemid > 0) {
    $sql                         = "select news_title , news_content from " . $xoopsDB->prefix("my_news") . " where news_sn='{$com_itemid}'";
    $result                      = $xoopsDB->query($sql) or web_error($sql, __FILE__, _LINE__);
    list($title, $com_replytext) = $xoopsDB->fetchRow($result);
}

$com_replytitle = "RE:{$title}";

include XOOPS_ROOT_PATH . '/include/comment_new.php';
