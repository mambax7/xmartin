<?php

/**
 * @用户资料
 * @license   http://www.blags.org/
 * @created   :2010年07月15日 20时25分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */
$UserNationnality  = getModuleArray('user_nationality', 'user_nationality', true);
$OrderDocumentType = getModuleArray('order_document_type', 'order_document_type', true);

$xoopsOption['xoops_pagetitle'] = '资料修改 - 用户中心';

//数据保存
if ($_POST['uid'] > 0) {
    $_POST['telephone'] = implode('-', $_POST['telephone']);
    $memberHandler      = xoops_getHandler('member');
    $edituser           = $memberHandler->getUser($xoopsUser->uid());
    if (\Xmf\Request::hasVar('passwd', 'POST')) {
        if ($_POST['passwd_check'] != $_POST['passwd']) {
            redirect_header(MEMBER_URL . '?info', 1, '密码输入不一致.');
        } else {
            $edituser->setVar('pass', md5($_POST['passwd']));
        }
    }
    unset($_POST['uid'], $_POST['passwd_check']);
    if (is_array($_POST)) {
        foreach ($_POST as $key => $value) {
            $edituser->setVar($key, $value);
        }
    }
    //var_dump($edituser);exit;
    if (!$memberHandler->insertUser($edituser)) {
        $msg = _US_PROFUPDATED;
    } else {
        $msg = _AM_XMARTIN_SAVING_SUCCESSFUL;
    }
    redirect_header(MEMBER_URL . '?info', 1, $msg);
}

foreach ($xoopsUser->vars as $key => $value) {
    $User[$key] = $value['value'];
}
$User['telephone'] = empty($User['telephone']) ? [] : explode('-', $User['telephone']);
//var_dump($User);

$xoopsTpl->assign('user', $User);
$xoopsTpl->assign('UserNationnality', $UserNationnality);
$xoopsTpl->assign('OrderDocumentType', $OrderDocumentType);
