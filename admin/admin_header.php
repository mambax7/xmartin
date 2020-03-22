<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

include dirname(__DIR__) . '/preloads/autoloader.php';

require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';

require_once XOOPS_ROOT_PATH . '/kernel/module.php';
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

// require_once  dirname(__DIR__) . '/class/Utility.php';
require_once dirname(__DIR__) . '/include/common.php';
require_once dirname(__DIR__) . '/include/functions.php';

//xoops_load('XoopsTree');

$moduleDirName = basename(dirname(__DIR__));
/** @var \XoopsModules\Xmartin\Helper $helper */
$helper = \XoopsModules\Xmartin\Helper::getInstance();

/** @var \Xmf\Module\Admin $adminObject */
$adminObject = \Xmf\Module\Admin::getInstance();

//if (!defined('SMARTSECTION_NOCPFUNC')) {
//    require_once  dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
//}

//require_once XOOPS_ROOT_PATH . '/modules/xmartin/include/common.php';

$imagearray = [
    'editimg'   => "<img src='" . XMARTIN_IMAGES_URL . "/button_edit.png' alt='" . _EDIT . "' align='middle'>",
    'deleteimg' => "<img src='" . XMARTIN_IMAGES_URL . "/button_delete.png' alt='" . _DELETE . "' align='middle'>",
    'online'    => "<img src='" . XMARTIN_IMAGES_URL . "/on.png' alt='' align='" . _AM_XMARTIN_ENABLED . "'>",
    'offline'   => "<img src='" . XMARTIN_IMAGES_URL . "/off.png' alt='' alt='" . _AM_XMARTIN_DISABLED . "'>",
];

//$moduleDirName = $GLOBALS['xoopsModule']->getVar('dirname');

//Module specific elements
//require_once $GLOBALS['xoops']->path("modules/{$moduleDirName}/include/functions.php");
//require_once $GLOBALS['xoops']->path("modules/{$moduleDirName}/config/config.php");

//Handlers
//$XXXHandler = $helper->getHandler('XXX', $moduleDirName);

// Load language files
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');
$helper->loadLanguage('common');

//xoops_cp_header();
