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
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

require_once __DIR__ . '/../../../include/cp_header.php';
require_once $GLOBALS['xoops']->path('www/class/xoopsformloader.php');

// require_once __DIR__ . '/../class/Utility.php';
//require_once __DIR__ . '/../include/common.php';

$moduleDirName = basename(dirname(__DIR__));

if (!defined('SMARTSECTION_NOCPFUNC')) {
    require_once __DIR__ . '/../../../include/cp_header.php';
}

require_once XOOPS_ROOT_PATH . '/kernel/module.php';
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

//$moduleDirName = $GLOBALS['xoopsModule']->getVar('dirname');

$pathIcon16           = $GLOBALS['xoops']->url('www/' . $GLOBALS['xoopsModule']->getInfo('sysicons16'));
$pathIcon32           = $GLOBALS['xoops']->url('www/' . $GLOBALS['xoopsModule']->getInfo('sysicons32'));
$xoopsModuleAdminPath = $GLOBALS['xoops']->path('www/' . $GLOBALS['xoopsModule']->getInfo('dirmoduleadmin'));
require_once "{$xoopsModuleAdminPath}/moduleadmin.php";

$myts = \MyTextSanitizer::getInstance();
if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof XoopsTpl)) {
    require_once $GLOBALS['xoops']->path('class/template.php');
    $xoopsTpl = new \XoopsTpl();
}

//Module specific elements
//require_once $GLOBALS['xoops']->path("modules/{$moduleDirName}/include/functions.php");
//require_once $GLOBALS['xoops']->path("modules/{$moduleDirName}/include/config.php");

//Handlers
//$XXXHandler = xoops_getModuleHandler('XXX', $moduleDirName);

// Load language files
xoops_loadLanguage('admin', $moduleDirName);
xoops_loadLanguage('modinfo', $moduleDirName);
xoops_loadLanguage('main', $moduleDirName);

//xoops_cp_header();
$adminObject = \Xmf\Module\Admin::getInstance();
