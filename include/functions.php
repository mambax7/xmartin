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

defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

/**
 * @用于头部
 * @method:
 * @license   http://www.blags.org/
 * @created   :2010年05月20日 21时59分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */
if (!function_exists('martin_adminMenu')) {
    /**
     * @param int    $currentoption
     * @param string $breadcrumb
     */
    function martin_adminMenu($currentoption = 0, $breadcrumb = '')
    {
        require_once XOOPS_ROOT_PATH . '/class/template.php';

        // global $xoopsDB, $xoopsModule, $xoopsConfig, $xoopsModuleConfig;
        global $xoopsModule, $xoopsConfig;

        if (file_exists(MARTIN_ROOT_PATH . 'language/' . $xoopsConfig['language'] . '/modinfo.php')) {
            require_once MARTIN_ROOT_PATH . 'language/' . $xoopsConfig['language'] . '/modinfo.php';
        } else {
            require_once MARTIN_ROOT_PATH . 'language/english/modinfo.php';
        }
        if (file_exists(MARTIN_ROOT_PATH . 'language/' . $xoopsConfig['language'] . '/admin.php')) {
            require_once MARTIN_ROOT_PATH . 'language/' . $xoopsConfig['language'] . '/admin.php';
        } else {
            require_once MARTIN_ROOT_PATH . 'language/english/admin.php';
        }
        include MARTIN_ROOT_PATH . 'admin/menu.php';

        $tpl = new XoopsTpl();
        $tpl->assign([
                         'headermenu'      => $headermenu,
                         'adminmenu'       => $adminObject,
                         'current'         => $currentoption,
                         'breadcrumb'      => $breadcrumb,
                         'headermenucount' => count($headermenu)
                     ]);
        $tpl->display('db:martin_admin_menu.tpl');
        echo "<br>\n";
    }
}

/**
 * @用于区块
 * @method:
 * @license   http://www.blags.org/
 * @created   :2010年05月20日 21时59分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */
if (!function_exists('martin_collapsableBar')) {
    /**
     * @param string $tablename
     * @param string $iconname
     * @param string $tabletitle
     * @param string $tabledsc
     */
    function martin_collapsableBar($tablename = '', $iconname = '', $tabletitle = '', $tabledsc = '')
    {
        global $xoopsModule;

        //        echo '<script type="text/javascript" src="' . XOOPS_URL . '/themes/default/jquery-1.3.2.min.js"></script>';
        echo '<script type="text/javascript" src="' . XOOPS_URL . '/browse.php?Frameworks/jquery/jquery.js"></script>';
        echo "<h3 style=\"color: #2F5376; font-weight: bold; font-size: 14px; margin: 6px 0 0 0; \"><a href='javascript:;' class='tabclose'>";
        echo "<img id='$iconname' src=" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/images/icon/close12.gif class='tab_img'>&nbsp;" . $tabletitle . '</a></h3>';
        echo "<div id='$tablename' class='open'>";
        if ('' != $tabledsc) {
            echo '<span style="color: #567; margin: 3px 0 12px 0; font-size: small; display: block; ">' . $tabledsc . '</span>';
        }
    }
}

/**
 * @创建button
 * @method:array('add' => array('url' => '?action=add', 'value' => '添加'),)
 * @license   http://www.blags.org/
 * @created   :2010年05月21日 20时40分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */
if (!function_exists('Create_button')) {
    /**
     * @param $ButtonArr
     * @return string
     */
    function Create_button($ButtonArr)
    {
        if (!is_array($ButtonArr)) {
            return '';
        }
        echo '<div style="margin-bottom: 12px;">';
        if (is_array($ButtonArr)) {
            foreach ($ButtonArr as $key => $button) {
                echo "&nbsp;&nbsp;<input type='button' value='{$button['value']}' onclick='location=\"{$button['url']}\"' name='$key'>";
            }
        }
        echo '</div>';
    }
}

if (!function_exists('martin_close_collapsable')) {
    /**
     * @param $name
     * @param $icon
     */
    function martin_close_collapsable($name, $icon)
    {
        ?>
        </div>
        <script type='text/javascript'>
            /*jQuery.noconflict();
             jQuery(function($){*/
            $(".tabclose").click(function () {
                var div = $(this).parent("h3").next('div').attr('id');
                var div_class = $("#" + div).attr('class');
                if (div_class == 'open') {
                    $("#" + div).hide();
                    $(".tab_img").attr('src', '../images/icon/open12.gif');
                    $("#" + div).attr('class', 'close');
                } else if (div_class == 'close') {
                    $("#" + div).show();
                    $(".tab_img").attr('src', '../images/icon/close12.gif');
                    $("#" + div).attr('class', 'open');
                }

            });

            $(".existimage").click(function () {
                var filename = this.id;
                if (!confirm("确定删除吗?")) return false;
                $.post('martin.hotel.php', {action: 'deleteimg', img: filename});
                $(this).parent("div").remove();
            });

            function go(url) {
                window.location.href = url;
            }

            /*});*/
        </script>
        <?php
    }
}

/**
 * @get       order js
 * @license   http://www.blags.org/
 * @created   :2010年06月10日 21时25分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */
if (!function_exists('martin_order_list_js')) {
    function martin_order_list_js()
    {
        echo <<<EndHTML
    <script type='text/javascript'>
        $("#hotel_city_id").change(function(){
            var params = $("#orderSearch").serialize();
            var rate = $('#hotel_city_id option:selected').text();
            /*if(rate.indexOf('----') == -1 || rate.length == 4)
            {
                alert("请选取三级类目! please select correct regoin!");return false;
            }*/
            $.get('martin.ajax.php?action=gethotellist',params,function(data){
                $("#hotel_name_div").html(data);
                $("#hotel_name").html('');
            });
        });
        $("#hotel_star").change(function(){
            var params = $("#orderSearch").serialize();
            var rate = $('#hotel_star option:selected').text();
            /*if(rate.indexOf('----') == -1 || rate.length == 4)
            {
                alert("请选取三级类目! please select correct regoin!");return false;
            }*/
            $.get('martin.ajax.php?action=gethotellist',params,function(data){
                $("#hotel_name_div").html(data);
                $("#hotel_name").html('');
            });
        });
    function hotel_select(event)
    {
        var hotel_name = $(event).find('option:selected').text();
        $("#hotel_name").html('<input type="hidden" name="hotel_name" value="'+hotel_name+'">');
    }
    </script>
EndHTML;
    }
}

/**
 * @method: 得到酒店星级
 * @license   http://www.blags.org/
 * @created   :2010年05月24日 19时55分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 **/
if (!function_exists('GetRanks')) {
    /**
     * @param $xoopsModuleConfig
     * @return mixed
     */
    function getRanks($xoopsModuleConfig)
    {
        $HotelRanks = $xoopsModuleConfig['hotelrank'];
        $HotelRanks = array_filter(explode(chr(13), $HotelRanks));
        if (is_array($HotelRanks)) {
            foreach ($HotelRanks as $hotelrank) {
                $Rank                                        = array_filter(explode('-', $hotelrank));
                $Ranks[(int)str_replace("\n", '', $Rank[0])] = trim(str_replace("\n", '', $Rank[1]));
                unset($Rank);
            }
        }

        return $Ranks;
    }
}

/**
 * @method delete path files
 * @license   http://www.blags.org/
 * @created   :2010年05月27日 22时04分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */
if (!function_exists('deldir')) {
    /**
     * @param $dir
     */
    function deldir($dir)
    {
        $dh = opendir($dir);
        while ($file = readdir($dh)) {
            if ('.' !== $file && '..' !== $file) {
                $fullpath = $dir . '/' . $file;
                if (!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    deldir($fullpath);
                }
            }
        }
        closedir($dh);
        /*if(rmdir($dir))
        {
            return true;
        } else {
            return false;
        }*/
    }
}

/**
 * @get       module config array
 * @license   http://www.blags.org/
 * @created   :2010年06月06日 20时05分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */
if (!function_exists('getModuleArray')) {
    /**
     * @param               $module_key
     * @param  null         $keyName
     * @param  bool         $is_get_arr
     * @param  null         $selected
     * @param  null         $ModuleConfig
     * @return array|string
     */
    function getModuleArray($module_key, $keyName = null, $is_get_arr = false, $selected = null, $ModuleConfig = null)
    {
        global $xoopsModuleConfig;
        if (empty($xoopsModuleConfig)) {
            $xoopsModuleConfig =& $ModuleConfig;
        }
        //var_dump($xoopsModuleConfig);
        $keyName = is_null($keyName) ? $module_key : $keyName;
        if (isset($xoopsModuleConfig[$module_key]) && !empty($xoopsModuleConfig[$module_key])) {
            $Arrs      = $xoopsModuleConfig[$module_key];
            $Arrs      = array_filter(explode(chr(13), $Arrs));
            $ModuleArr = [];
            if (is_array($Arrs)) {
                foreach ($Arrs as $Arr) {
                    $TmpArr = array_filter(explode('-', $Arr));
                    //var_dump($TmpArr);
                    if (!empty($TmpArr[0]) && !empty($TmpArr[1])) {
                        $ModuleKey             = str_replace("\n", '', $TmpArr[0]);
                        $ModuleKey             = is_numeric($ModuleKey) ? (int)$ModuleKey : trim($ModuleKey);
                        $ModuleArr[$ModuleKey] = trim(str_replace("\n", '', $TmpArr[1]));
                    } else {
                        $ModuleArr[] = trim(str_replace("\n", '', $TmpArr[0]));
                    }
                    unset($TmpArr);
                }
            }
            if ($is_get_arr) {
                return $ModuleArr;
            }

            //var_dump($ModuleArr);
            return is_null($keyName) ? $ModuleArr : WriteHtmlSelect($ModuleArr, $keyName, $selected);
        }

        return $module_key;
    }
}

/**
 * @write     html select
 * @method:
 * @license   http://www.blags.org/
 * @created   :2010年06月07日 20时25分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */
if (!function_exists('WriteHtmlSelect')) {
    /**
     * @param         $ModuleArr
     * @param         $keyName
     * @param  null   $selected
     * @return string
     */
    function WriteHtmlSelect($ModuleArr, $keyName, $selected = null)
    {
        if (empty($keyName)) {
            return $keyName;
        }
        $Str = "<select name='$keyName' id='$keyName' >\n";
        $Str .= "<option value=''>----</option>";
        if (is_array($ModuleArr)) {
            foreach ($ModuleArr as $key => $value) {
                $selectedStr = ($selected === $key) ? ' selected' : '';
                $Str         .= "<option value='$key' $selectedStr>$value</option>";
                unset($selectedStr);
            }
        }
        $Str .= '</select>';

        return $Str;
    }
}

/**
 * @get       mouth last day
 * @license   http://www.blags.org/
 * @created   :2010年06月24日 22时04分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */
if (!function_exists('MouthLastDay')) {
    /**
     * @param  null $mouth
     * @return array|bool|int|string
     */
    function MouthLastDay($mouth = null)
    {
        $mouth    = is_null($mouth) ? date('m') : $mouth;
        $date     = date('Y') . '-' . $mouth . '-' . date('d');
        $firstday = date('Y-m-01', strtotime($date));
        $lastday  = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
        $lastday  = explode('-', $lastday);
        $lastday  = array_reverse($lastday);
        $lastday  = (int)$lastday[0];

        return $lastday;
    }
}

/**
 * @get       check in date arr
 * @license   http://www.blags.org/
 * @created   :2010年07月01日 22时08分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */
if (!function_exists('GetCheckDateArr')) {
    /**
     * @param $check_in_date
     * @param $check_out_date
     * @return array
     */
    function getCheckDateArr($check_in_date, $check_out_date)
    {
        $check_arr        = [];
        $check_date_count = (int)(($check_out_date - $check_in_date) / (3600 * 24));
        if ($check_date_count > 0) {
            $ini_y  = date('Y', $check_in_date);
            $ini_m  = date('m', $check_in_date);
            $ini_d  = date('d', $check_in_date);
            $last_d = MouthLastDay($ini_y);
            for ($i = 0; $i < $check_date_count; $i++) {
                $d           = $ini_d + $i;
                $m           = $d > $last_d ? $ini_m + 1 : $ini_m;
                $d           = $d > $last_d ? $d - $last_d : $d;
                $y           = $m > 12 ? $ini_y + 1 : $ini_y;
                $m           = $m > 12 ? $m - 12 : $m;
                $check_arr[] = strtotime($y . '-' . $m . '-' . $d);
            }
        }

        return $check_arr;
    }
}

?>
