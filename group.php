<?php

use XoopsModules\Xmartin;

require_once __DIR__ . '/header.php';

/** @var Xmartin\Helper $helper */
$helper = Xmartin\Helper::getInstance();
global $xoopsUser;

if (!$xoopsUser) {
    redirect_header(XOOPS_URL . '/user.php?xoops_redirect=/' . $_SERVER['REQUEST_URI'], 1, '您还没有登录.');
}

$group_id = \Xmf\Request::getInt('group_id', $id, 'GET');
$group_id = \Xmf\Request::getInt('group_id', $group_id, 'POST');
if (!$group_id) {
    redirect_header(XOOPS_URL, 3, _AM_XMARTIN_UNAUTHORIZED_ACCESS);
}

$groupHandler = $helper->getHandler('Group');
$hotelHandler = $helper->getHandler('Hotel');
$group_obj    = $groupHandler->get($group_id);

//判断是否存在
if (!$group_obj->group_id()) {
    redirect_header(XOOPS_URL, 3, _AM_XMARTIN_UNAUTHORIZED_ACCESS);
}
//是否结束
if ($group_obj->apply_end_date() < time()) {
    redirect_header(XOOPS_URL, 3, '该团购已经结束.');
}

//参加团购
$uid         = $xoopsUser->getVar('uid');
$action      = isset($_GET['action']) ? trim($_GET['action']) : null;
$room_number = \Xmf\Request::getInt('room_number', 1, 'POST');
if ('save' === $action) {
    $data = ['uid' => $uid, 'group_id' => $group_id, 'room_number' => $room_number, 'join_time' => time()];
    if ($groupHandler->checkJoinExist($data)) {
        redirect_header('javascript:history.go(-1);', 2, '您已经参加过了.');
    } elseif ($groupHandler->AddUserGroup($data)) {
        redirect_header(XOOPS_URL . '/modules/xmartin/group.php/group-' . $group_id . $helper->getConfig('hotel_static_prefix'), 2, '提交成功.');
    } else {
        redirect_header('javascript:history.go(-1);', 2, '提交失败.');
    }
    exit();
}

$group_data = [];
foreach ($group_obj->vars as $key => $var) {
    $group_data[$key] = $group_obj->$key();
}

$rooms = $groupHandler->getGroupRooms($group_id);
//var_dump($rooms);
$cityAlias = $searchHandler->getCityAlias();

//user
$xoopsUser->cleanVars();
$user = &$xoopsUser->cleanVars;

$GroupDate = [
    'min' => ($group_obj->check_in_date() - strtotime(date('Y-m-d'))) / (3600 * 24),
    'max' => ($group_obj->check_out_date() - strtotime(date('Y-m-d'))) / (3600 * 24),
];
//var_dump($GroupDate);

$GroupStatus = time() < $group_data['apply_start_date'] ? ['title' => '召集中', 'status' => false] : '';
$GroupStatus = (time() <= $group_data['apply_end_date']
                && time() >= $group_data['apply_start_date']) ? [
    'title'  => '进行中',
    'status' => true,
] : $GroupStatus;
$GroupStatus = time() >= $group_data['apply_end_date'] ? ['title' => '已结束', 'status' => false] : $GroupStatus;

$GLOBALS['xoopsOption']['template_main'] = 'martin_group.tpl';

$joins = $groupHandler->getGroupJoinList($group_id);
//var_dump($joins);

require_once XOOPS_ROOT_PATH . '/header.php';
require_once XOOPS_ROOT_PATH . '/modules/xmartin/HotelSearchLeft.php';

$xoopsOption['xoops_pagetitle'] = $group_obj->group_name() . ' - ' . _AM_XMARTIN_HOTEL_GROUP_BUY; // - '.$xoopsConfig['sitename'];

$xoopsTpl->assign('xoops_pagetitle', $xoopsOption['xoops_pagetitle']);
$xoopsTpl->assign('module_url', XOOPS_URL . '/modules/xmartin/');
$xoopsTpl->assign('group_id', $group_id);
$xoopsTpl->assign('group', $group_data);
$xoopsTpl->assign('rooms', $rooms);
$xoopsTpl->assign('GroupStatus', $GroupStatus);
$xoopsTpl->assign('user', $user);
$xoopsTpl->assign('groupdate', $GroupDate);
$xoopsTpl->assign('alias', $cityAlias);
$xoopsTpl->assign('joins', $joins);
$xoopsTpl->assign('hotel_static_prefix', $helper->getConfig('hotel_static_prefix'));

require_once XOOPS_ROOT_PATH . '/footer.php';
