<?php
/**
 * @method: 公共
 * @license   http://www.blags.org/
 * @created   :2010年05月18日 20时26分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */
defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

if (!defined('MARTIN_DIRNAME')) {
    define('MARTIN_DIRNAME', 'martin');
}

if (!defined('MARTIN_URL')) {
    define('MARTIN_URL', XOOPS_URL . '/modules/' . MARTIN_DIRNAME . '/');
}
if (!defined('MARTIN_ROOT_PATH')) {
    define('MARTIN_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . MARTIN_DIRNAME . '/');
}

if (!defined('MARTIN_IMAGES_URL')) {
    define('MARTIN_IMAGES_URL', MARTIN_URL . '/images/');
}

//common path
if (!defined('MARTIN_HOTEL_IMAGE_PATH')) {
    define('MARTIN_HOTEL_IMAGE_PATH', MARTIN_ROOT_PATH . 'images/hotel/');
}
defined('DS') || define('DS', DIRECTORY_SEPARATOR);

// include common language files
global $xoopsConfig, $xoopsModuleConfig;
$common_lang_file = MARTIN_ROOT_PATH . 'language/' . $xoopsConfig['language'] . '/common.php';
if (!file_exists($common_lang_file)) {
    $common_lang_file = MARTIN_ROOT_PATH . 'language/english/common.php';
}

// include common functions
$function_file = MARTIN_ROOT_PATH . 'include/functions.php';
if (file_exists($function_file)) {
    include $function_file;
}

//get id by static url
if (isset($_SERVER['PATH_INFO'])) {
    if ($_SERVER['PATH_INFO']) {
        $GET  = [];
        $GETS = $id = str_replace($xoopsModuleConfig['hotel_static_prefix'], '', $_SERVER['PATH_INFO']);
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
