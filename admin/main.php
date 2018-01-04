<?php
require_once __DIR__ . '/admin_header.php';
/*
 * 处理
 **/

//头部
//include "martin.header.php";

// martin_adminMenu(0, "订房后台 > 后台首页");

global $xoopsModule;
echo '<style type="text/css">
    label,text {
        display: block;
        float: left;
        margin-bottom: 2px;
    }
    label {
        text-align: right;
        width: 150px;
        padding-right: 20px;
    }
    br {
        clear: left;
    }
    </style>
';

echo "<fieldset><legend style='font-weight: bold; color: #900;'>{$xoopsModule->name()} 统计信息</legend>";
echo "<div style='padding: 8px;'>";
echo '</div>';
echo '</fieldset><br><br>';

$pay_path = MARTIN_ROOT_PATH . 'pay/';
$img_path = MARTIN_ROOT_PATH . 'images/hotel/';
echo "<fieldset><legend style='font-weight: bold; color: #900;'>{$xoopsModule->name()} 配置</legend>";
echo "<div style='padding: 8px;'>";
echo '<label>' . '<strong>PHP Version:</strong>' . ':</label><text>' . PHP_VERSION . '</text><br>';
$gd_status = function_exists('gd_info') ? '<font color=green>支持</font>' : '<span style="color:red"><b>不支持</b></span>';
echo '<label>' . '<strong>PHP GD:</strong>' . ':</label><text>' . $gd_status . '</text><br>';
echo '<label>' . '<strong>MySQL Version:</strong>' . ':</label><text>' . mysqli_get_server_info() . '</text><br>';
echo '<label>' . '<strong>XOOPS Version:</strong>' . ':</label><text>' . XOOPS_VERSION . '</text><br>';
echo '<label>' . '<strong>Module Version:</strong>' . ':</label><text>' . $xoopsModule->getInfo('version') . '</text><br><br><br>';
$path_status = (is_writable($pay_path)
                && is_dir($pay_path)) ? '<font color=green>可用</font>' : '<font color=red><b>目录不可用</b></font>';
echo '<label>' . '<strong>在线支付文件夹:</strong>' . ':</label><text>' . $pay_path . " ($path_status)</text><br>";
$img_status = (is_writable($img_path)
               && is_dir($img_path)) ? '<font color=green>可用</font>' : '<span style="color:red"><b>目录不可用</b></span>';
echo '<label>' . '<strong>图片上传文件夹:</strong>' . ':</label><text>' . $img_path . " ($img_status)</text><br>";
echo '</div>';
echo '</fieldset>';

//底部
require_once __DIR__ . '/admin_footer.php';
