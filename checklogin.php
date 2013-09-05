<?php
include_once "header.php";
if (!defined('XOOPS_ROOT_PATH')) {
  exit();
}
include_once XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/user.php';
$uname = !isset($_POST['uname']) ? '' : trim($_POST['uname']);
$pass = !isset($_POST['pass']) ? '' : trim($_POST['pass']);
if ($uname == '' || $pass == '') {
	header("location:xppw.php?setup=login");
  exit();
}
$member_handler =& xoops_gethandler('member');
$myts =& MyTextsanitizer::getInstance();

// Place here the Auth class calls
//**** BEGIN AUTH ****
require_once XOOPS_ROOT_PATH.'/class/auth/authfactory.php';
$xoopsAuth =& XoopsAuthFactory::getAuthConnection();
$auth = $xoopsAuth->authenticate($myts->addSlashes($uname), $myts->addSlashes($pass));
if (!$auth) {
	header("location:xppw.php?setup=login");
  exit();
}
//**** END AUTH ****

$user =& $member_handler->loginUser($myts->addSlashes($uname), $myts->addslashes($pass));
if (false != $user) {
    if (0 >= $user->getVar('level')) {
        	header("location:xppw.php?setup=login");
  				exit();
    }
    if ($xoopsConfig['closesite'] == 1) {
        $allowed = false;
        foreach ($user->getGroups() as $group) {
            if (in_array($group, $xoopsConfig['closesite_okgrp']) || XOOPS_GROUP_ADMIN == $group) {
                $allowed = true;
                break;
            }
        }
        if (!$allowed) {

          header("location:xppw.php?setup=login");
  				exit();
        }
    }
    $_SESSION = array();
    $_SESSION['xoopsUserId'] = $user->getVar('uid');
    $_SESSION['xoopsUserGroups'] = $user->getGroups();
    if ($xoopsConfig['use_mysession'] && $xoopsConfig['session_name'] != '') {
        setcookie($xoopsConfig['session_name'], session_id(), time()+(60 * $xoopsConfig['session_expire']), '/',  '', 0);
    }
    $_SESSION['xoopsUserLastLogin'] = $user->getVar('last_login');
    if (!$member_handler->updateUserByField($user, 'last_login', time())) {
    }
    $user_theme = $user->getVar('theme');
    if (in_array($user_theme, $xoopsConfig['theme_set_allowed'])) {
        $_SESSION['xoopsUserTheme'] = $user_theme;
    }
    if (!empty($_POST['xoops_redirect']) && !strpos($_POST['xoops_redirect'], 'register')) {
            $url = $_POST['xoops_redirect'];
    } else {
        $url = XOOPS_URL.'/index.php';
    }

    // RMV-NOTIFY
    // Perform some maintenance of notification records
    $notification_handler =& xoops_gethandler('notification');
    $notification_handler->doLoginMaintenance($user->getVar('uid'));

    header("location:xppw.php?setup=options");
		exit();
    //redirect_header($url, 1, sprintf(_US_LOGGINGU, $user->getVar('uname')), false); // disable "addredirect"!!
} else {

    header("location:xppw.php?setup=login");
		exit();
    //redirect_header(XOOPS_URL.'/user.php',1,_US_INCORRECTLOGIN);
}
exit();
?>
