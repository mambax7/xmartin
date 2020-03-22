<?php

require_once __DIR__ . '/admin_header.php';
/*
 * 处理
 **/

//头部
//require_once __DIR__ . '/martin.header.php';
$currentFile = basename(__FILE__);
$adminObject = \Xmf\Module\Admin::getInstance();
$adminObject->displayNavigation($currentFile);

// martin_adminMenu(0, "订房后台 > 支付方式配置");
global $xoopsModuleConfig;
$line_pays   = getModuleArray('line_pays', 'line_pays', true);
$online_pays = getModuleArray('online_pays', 'online_pays', true);

$action = isset($_POST['action']) ? $_POST['action'] : @$_GET['action'];
$action = empty($action) ? 'list' : $action;
$key    = isset($_POST['key']) ? $_POST['key'] : @$_GET['key'];

$config_path = XMARTIN_ROOT_PATH . "pay/$key/config/";
$ini_file    = $config_path . 'ini.php';
$config_file = $config_path . 'config.php';

switch ($action) {
    case 'list':
        martin_collapsableBar('createtable', 'createtableicon', _AM_XMARTIN_PAY_BY, '');
        if (is_array($online_pays)) {
            echo "<table width='100%' cellspacing=1 cellpadding=2 border=0 class = outer>";
            echo '<tr>';
            echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_PAYMENT_KEY . '</b></td>';
            echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_PAYMENT_NAME . '</b></td>';
            echo "<td width='60' class='bg3' align='center'><b>_AM_XMARTIN_ACTIONS</b></td>";
            echo '</tr>';
            foreach ($online_pays as $key => $value) {
                $modify = "<a href='?action=modify&amp;key=" . $key . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/images/icon/edit.gif'></a>";
                echo "<tr><td class='even' align='left'>" . $key . '</td>';
                echo "<td class='even' align='left'>" . $value . '</td>';
                echo "<td class='even' align='center'> $modify $delete </td></tr>";
            }
            echo "</table>\n";
        }
        martin_close_collapsable('createtable', 'createtableicon');
        break;
    case 'modify':
        $alipay = [];
        if (file_exists($config_file) && is_readable($config_file)) {
            require_once $config_file;
        } elseif (file_exists($ini_file)) {
            require_once $ini_file;
        } else {
            redirect_header('javascript:history.go(-1);', 2, _AM_XMARTIN_PAYMENT_NOT_POSSIBLE);
        }
        martin_collapsableBar('createtable', 'createtableicon', _AM_XMARTIN_PAYMENT_CONFIGURATION, '');
        //        echo "<form name='op' id='op' action='?action=save' method='post' onsubmit='return xoopsFormValidate_op();' enctype='multipart/form-data'><table width='100%' class='outer' cellspacing='1'><tbody><tr><th colspan='2'>" . _AM_XMARTIN_HOTEL_SERVICE . "</th></tr>";
        echo "<form name='op' id='op' action='?action=save' method='post' enctype='multipart/form-data'><table width='100%' class='outer' cellspacing='1'><tbody><tr><th colspan='2'>" . _AM_XMARTIN_HOTEL_SERVICE . '</th></tr>';
        foreach (${$key} as $k => $value) {
            echo "<tr valign='top' align='left'><td class='head'>$k</td><td class='even'><input type='text' name='config[$k]' size='45' value='$value'></td></tr>";
        }
        echo "<tr valign='top' align='left'><td class='head'></td><td class='even'><input type='submit' class='formButton' name=''  id='' value=_EDIT onclick=\"this.form.elements.op.value='addcategory'\"><input type='reset' class='formButton' name=''  id='' value=_SUBMIT><input type='button' class='formButton' name=''  id='' value='cancel' onclick='history.go(-1)'></td></tr></tbody></table><input type='hidden' name='key' id='key' value='$key'></form></div>";
        martin_close_collapsable('createtable', 'createtableicon');

        break;
    case 'save':
        $config  = $_POST['config'];
        $fileStr = "<?php \n";
        foreach ($config as $k => $v) {
            $fileStr .= '$' . $key . "['$k'] = '$v';\n";
        }
        $fileStr .= '?>';
        //var_dump($fileStr);exit;
        if (!is_writable($config_path)) {
            xoops_error($config_path . _AM_XMARTIN_CAN_NOT_WRITE_SET_777);
            exit();
        }
        if (!file_exists($config_file)) {
            $configHandler = fopen($config_file, 'w+');
            fclose($configHandler);
            file_put_contents($config_file, $fileStr);
            chmod($config_file, 0777);
        } elseif (file_exists($config_file) && is_writable($config_file)) {
            file_put_contents($config_file, $fileStr);
            chmod($config_file, 0777);
            //file_put_contents($config_file,$fileStr);
        }
        redirect_header('pay.php', 2, _AM_XMARTIN_MODIFIED_SUCCESSFULLY);
        break;
    default:
        redirect_header(XOOPS_URL, 2, _AM_XMARTIN_UNAUTHORIZED_ACCESS);
        break;
}

require_once __DIR__ . '/admin_footer.php';
