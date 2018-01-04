<?php
require_once __DIR__ . '/admin_header.php';
/*
 * 处理
 **/

//头部
include __DIR__ . '/martin.header.php';
$currentFile   = basename(__FILE__);
$myModuleAdmin = \Xmf\Module\Admin::getInstance();
echo $myModuleAdmin->displayNavigation($currentFile);

// martin_adminMenu(9, "订房后台 > 关于作者");

// echo '<iframe src="http://www.blags.org" height="1600px;" width="100%" frameborder="0"></iframe>';

//底部
require_once __DIR__ . '/admin_footer.php';
