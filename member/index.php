<?php
/**
 * @用户
 * @method:
 * @license   http://www.blags.org/
 * @created   :2010年07月14日 21时54分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */

use XoopsModules\Xmartin;
/** @var Xmartin\Helper $helper */
$helper = Xmartin\Helper::getInstance();

include __DIR__ . '/../../../mainfile.php';
include XOOPS_ROOT_PATH . '/modules/martin/include/common.php';
if (!defined('NEWS_URL')) {
    define('NEWS_URL', XOOPS_URL . '/modules/news/');
}
if (!defined('MODULE_URL')) {
    define('MODULE_URL', XOOPS_URL . '/modules/martin/');
}
if (!defined('MEMBER_URL')) {
    define('MEMBER_URL', MODULE_URL . 'member/');
}

/*$grouppermHandler =  xoops_getHandler( 'groupperm' );
$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$isAdmin = $grouppermHandler->checkRight( 'system_admin', XOOPS_SYSTEM_USER, $groups); // isadmin is true if user has 'edit users' admin rights*/
// get userid
$uid = \Xmf\Request::getInt('uid', 0, 'GET');

$memberHandler = xoops_getHandler('member');
if ($uid) {
    $xoopsUser = $memberHandler->getUser($uid);
}

global $xoopsUser,  $xoopsModule;
if (!$xoopsUser) {
    redirect_header(XOOPS_URL . '/user.php?xoops_redirect=/' . $_SERVER['REQUEST_URI'], 1, '您还没有登录.');
}

$grouppermHandler = xoops_getHandler('groupperm');
$groups       = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$isAdmin      = $grouppermHandler->checkRight('system_admin', XOOPS_SYSTEM_USER, $groups);
// isadmin is true if user has 'edit users' admin rights

//var_dump($xoopsUser);
//params
$query_string = $_SERVER['QUERY_STRING'];
$query_string = str_replace('&start=' . $_GET['start'], '', $query_string);
$query_string = str_replace('&order_id=' . $_GET['order_id'], '', $query_string);
$query_string = str_replace('&hotel_id=' . $_GET['hotel_id'], '', $query_string);
$query_string = str_replace('&uid=' . $_GET['uid'], '', $query_string);

$start    = \Xmf\Request::getInt('start', 0, 'GET');
$order_id = \Xmf\Request::getInt('order_id', 0, 'GET');
$action   = isset($_GET['action']) ? strtolower(trim($_GET['action'])) : '';
$action   = empty($action) ? strtolower(trim($query_string)) : $action;
$action   = empty($action) ? 'index' : $action;

$action_file                             = MARTIN_ROOT_PATH . 'member/member_' . $action . '.php';
$GLOBALS['xoopsOption']['template_main'] = "martin_member_$action.tpl";

include XOOPS_ROOT_PATH . '/header.php';
if (file_exists($action_file)) {
    include $action_file;
}

$xoopsTpl->assign('xoops_pagetitle', str_replace('用户中心', '会员面板', $xoopsOption['xoops_pagetitle']) . ' - ' . $xoopsConfig['sitename'] . ' - ' . $xoopsConfig['slogan']);
$xoopsTpl->assign('hotel_static_prefix', $helper->getConfig('hotel_static_prefix'));
$xoopsTpl->assign('news_url', NEWS_URL);
$xoopsTpl->assign('module_url', MODULE_URL);
$xoopsTpl->assign('member_url', MEMBER_URL);
$xoopsTpl->assign('action', $action);
$xoopsTpl->assign('isAdmin', $isAdmin);

include XOOPS_ROOT_PATH . '/footer.php';
