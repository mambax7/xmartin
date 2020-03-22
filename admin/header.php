<?php

require_once dirname(dirname(dirname(__DIR__))) . '/mainfile.php';

if (!defined('SMARTSECTION_NOCPFUNC')) {
    require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
}

require_once XOOPS_ROOT_PATH . "/'kernel/module.php";
require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

require_once XOOPS_ROOT_PATH . '/modules/xmartin/include/common.php';

$imagearray = [
    'editimg'   => "<img src='" . XMARTIN_IMAGES_URL . "/button_edit.png' alt='" . _EDIT . "' align='middle'>",
    'deleteimg' => "<img src='" . XMARTIN_IMAGES_URL . "/button_delete.png' alt='" . _DELETE . "' align='middle'>",
    'online'    => "<img src='" . XMARTIN_IMAGES_URL . "/on.png' alt='' align='" . _AM_XMARTIN_ENABLED . "'>",
    'offline'   => "<img src='" . XMARTIN_IMAGES_URL . "/off.png' alt='' alt='" . _AM_XMARTIN_DISABLED . "'>",
];

$myts = \MyTextSanitizer::getInstance();
