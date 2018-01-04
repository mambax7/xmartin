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

require_once __DIR__ . '/../../mainfile.php';

/*
if(!empty($GLOBALS["xoopsModuleConfig"]['theme_set'])){
    $GLOBALS["xoopsModuleConfig"]['theme_set'] = $GLOBALS["xoopsModuleConfig"]['theme_set'];
}
*/
require_once XOOPS_ROOT_PATH . '/class/module.textsanitizer.php';
$myts = MyTextSanitizerExtended::getInstance();
