<?php
require_once  dirname(dirname(dirname(__DIR__))) . '/mainfile.php';

if (!defined('SMARTSECTION_NOCPFUNC')) {
    require_once  dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
}

require_once XOOPS_ROOT_PATH . "/'kernel/module.php";
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

include XOOPS_ROOT_PATH . '/modules/martin/include/common.php';

$imagearray = [
    'editimg'   => "<img src='" . MARTIN_IMAGES_URL . "/button_edit.png' alt='" . _EDIT . "' align='middle'>",
    'deleteimg' => "<img src='" . MARTIN_IMAGES_URL . "/button_delete.png' alt='" . _DELETE . "' align='middle'>",
    'online'    => "<img src='" . MARTIN_IMAGES_URL . "/on.png' alt='' align='" . _AM_MARTIN_ENABLED . "'>",
    'offline'   => "<img src='" . MARTIN_IMAGES_URL . "/off.png' alt='' alt='" . _AM_MARTIN_DISABLED . "'>",
];

$myts = \MyTextSanitizer::getInstance();
