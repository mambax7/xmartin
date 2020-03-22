<?php
/**
 * @method: 公共
 * @license   http://www.blags.org/
 * @created   :2010年05月18日 20时26分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */

use XoopsModules\Xmartin;

include dirname(__DIR__) . '/preloads/autoloader.php';

$moduleDirName      = basename(dirname(__DIR__));
$moduleDirNameUpper = mb_strtoupper($moduleDirName); //$capsDirName

/** @var \XoopsDatabase $db */
/** @var Xmartin\Helper $helper */
/** @var Xmartin\Utility $utility */
$db      = \XoopsDatabaseFactory::getDatabaseConnection();
$helper  = Xmartin\Helper::getInstance();
$utility = new Xmartin\Utility();
//$configurator = new Xmartin\Common\Configurator();

$helper->loadLanguage('common');

$pathIcon16 = \Xmf\Module\Admin::iconUrl('', 16);
$pathIcon32 = \Xmf\Module\Admin::iconUrl('', 32);
//$pathModIcon16 = $helper->getModule()->getInfo('modicons16');
//$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

if (!defined($moduleDirNameUpper . '_CONSTANTS_DEFINED')) {
    define($moduleDirNameUpper . '_DIRNAME', basename(dirname(__DIR__)));
    define($moduleDirNameUpper . '_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/');
    define($moduleDirNameUpper . '_PATH', XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/');
    define($moduleDirNameUpper . '_URL', XOOPS_URL . '/modules/' . $moduleDirName . '/');
    define($moduleDirNameUpper . '_IMAGE_URL', constant($moduleDirNameUpper . '_URL') . '/assets/images/');
    define($moduleDirNameUpper . '_IMAGE_PATH', constant($moduleDirNameUpper . '_ROOT_PATH') . '/assets/images');
    define($moduleDirNameUpper . '_ADMIN_URL', constant($moduleDirNameUpper . '_URL') . '/admin/');
    define($moduleDirNameUpper . '_ADMIN_PATH', constant($moduleDirNameUpper . '_ROOT_PATH') . '/admin/');
    define($moduleDirNameUpper . '_ADMIN', constant($moduleDirNameUpper . '_URL') . '/admin/index.php');
    //    define($moduleDirNameUpper . '_AUTHOR_LOGOIMG', constant($moduleDirNameUpper . '_URL') . '/assets/images/logoModule.png');
    define($moduleDirNameUpper . '_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . $moduleDirName); // WITHOUT Trailing slash
    define($moduleDirNameUpper . '_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . $moduleDirName); // WITHOUT Trailing slash
    define($moduleDirNameUpper . '_AUTHOR_LOGOIMG', $pathIcon32 . '/xoopsmicrobutton.gif');
    define($moduleDirNameUpper . '_CONSTANTS_DEFINED', 1);
}

$icons = [
    'edit'    => "<img src='" . $pathIcon16 . "/edit.png'  alt=" . _EDIT . "' align='middle'>",
    'delete'  => "<img src='" . $pathIcon16 . "/delete.png' alt='" . _DELETE . "' align='middle'>",
    'clone'   => "<img src='" . $pathIcon16 . "/editcopy.png' alt='" . _CLONE . "' align='middle'>",
    'preview' => "<img src='" . $pathIcon16 . "/view.png' alt='" . _PREVIEW . "' align='middle'>",
    'print'   => "<img src='" . $pathIcon16 . "/printer.png' alt='" . _CLONE . "' align='middle'>",
    'pdf'     => "<img src='" . $pathIcon16 . "/pdf.png' alt='" . _CLONE . "' align='middle'>",
    'add'     => "<img src='" . $pathIcon16 . "/add.png' alt='" . _ADD . "' align='middle'>",
    '0'       => "<img src='" . $pathIcon16 . "/0.png' alt='" . 0 . "' align='middle'>",
    '1'       => "<img src='" . $pathIcon16 . "/1.png' alt='" . 1 . "' align='middle'>",
];

$debug = false;

// MyTextSanitizer object
$myts = \MyTextSanitizer::getInstance();

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof \XoopsTpl)) {
    require $GLOBALS['xoops']->path('class/template.php');
    $GLOBALS['xoopsTpl'] = new \XoopsTpl();
}

$GLOBALS['xoopsTpl']->assign('mod_url', XOOPS_URL . '/modules/' . $moduleDirName);
// Local icons path
if (is_object($helper->getModule())) {
    $pathModIcon16 = $helper->getModule()->getInfo('modicons16');
    $pathModIcon32 = $helper->getModule()->getInfo('modicons32');

    $GLOBALS['xoopsTpl']->assign('pathModIcon16', XOOPS_URL . '/modules/' . $moduleDirName . '/' . $pathModIcon16);
    $GLOBALS['xoopsTpl']->assign('pathModIcon32', $pathModIcon32);
}

if (!defined('XMARTIN_DIRNAME')) {
    define('XMARTIN_DIRNAME', 'martin');
}

if (!defined('XMARTIN_URL')) {
    define('XMARTIN_URL', XOOPS_URL . '/modules/' . XMARTIN_DIRNAME . '/');
}
if (!defined('XMARTIN_ROOT_PATH')) {
    define('XMARTIN_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . XMARTIN_DIRNAME . '/');
}

if (!defined('XMARTIN_IMAGES_URL')) {
    define('XMARTIN_IMAGES_URL', XMARTIN_URL . '/images/');
}

//common path
if (!defined('XMARTIN_HOTEL_IMAGE_PATH')) {
    define('XMARTIN_HOTEL_IMAGE_PATH', XMARTIN_ROOT_PATH . 'images/hotel/');
}
defined('DS') || define('DS', DIRECTORY_SEPARATOR);

// include common functions
$function_file = XMARTIN_ROOT_PATH . 'include/functions.php';
if (file_exists($function_file)) {
    require_once $function_file;
}

//get id by static url
if ('' !== \Xmf\Request::getString('PATH_INFO', '', 'SERVER')) {
    if ($_SERVER['PATH_INFO']) {
        $GET  = [];
        $GETS = $id = str_replace($helper->getConfig('hotel_static_prefix'), '', $_SERVER['PATH_INFO']);
        $id   = empty($id) ? 0 : array_reverse(explode('-', $id));
        $id   = isset($id[0]) ? (int)$id[0] : 0;
        $GETS = empty($GETS) ? null : explode(DS, $GETS);
        if (is_array($GETS)) {
            foreach ($GETS as $G) {
                $G          = !empty($G) ? explode('-', $G) : null;
                $GET[$G[0]] = isset($G[1]) ? $G[1] : 0;
            }
            $GET = array_filter($GET);
        }
        unset($GETS);
    }
}
