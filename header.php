<?php

/**
 * Article management
 *
 * @copyright      The XOOPS project https://xoops.org/
 * @license        http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author         Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since          1.00
 * @package        module::article
 */

require  dirname(dirname(__DIR__)) . '/mainfile.php';
require XOOPS_ROOT_PATH . '/header.php';

//include __DIR__ . '/preloads/autoloader.php';

require_once __DIR__ . '/include/common.php';

$moduleDirName = basename(__DIR__);

/** @var \XoopsModules\Xmartin\Helper $helper */
$helper = \XoopsModules\Xmartin\Helper::getInstance();


$modulePath = XOOPS_ROOT_PATH . '/modules/' . $moduleDirName;

$myts = \MyTextSanitizer::getInstance();

if (!isset($GLOBALS['xoTheme']) || !is_object($GLOBALS['xoTheme'])) {
    require $GLOBALS['xoops']->path('class/theme.php');
    $GLOBALS['xoTheme'] = new \xos_opal_Theme();
}

//Handlers
//$XXXHandler = xoops_getModuleHandler('XXX', $moduleDirName);

// Load language files
$helper->loadLanguage('admin');
$helper->loadLanguage('main');

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof XoopsTpl)) {
    require $GLOBALS['xoops']->path('class/template.php');
    $xoopsTpl = new XoopsTpl();
}
