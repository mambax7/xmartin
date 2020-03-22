<?php

use XoopsModules\Xmartin;

require_once __DIR__ . '/admin_header.php';

/** @var Xmartin\Helper $helper */
$helper = Xmartin\Helper::getInstance();

/*
 * 处理
 **/

//头部
//require_once __DIR__ . '/martin.header.php';
$currentFile = basename(__FILE__);
$adminObject = \Xmf\Module\Admin::getInstance();
$adminObject->displayNavigation($currentFile);

//parameter 参数
//$action        = isset($_POST['action']) ? $_POST['action'] : @$_GET['action'];
//$action        = empty($action) ? 'list' : $action;
//$action        = trim(strtolower($action));
//$id            = !empty($_POST['id']) ? $_POST['id'] : @$_GET['id'];
//$id            = (int)$id;

$action = \Xmf\Request::getCmd('action', 'list');
$id     = \Xmf\Request::getInt('id', 0, 'POST');

$start         = \Xmf\Request::getInt('start', 0, 'GET');
$hotel_city_id = \Xmf\Request::getInt('hotel_city_id', 0, 'GET');

$searchData = [
    'hotel_city_id' => \Xmf\Request::getInt('hotel_city_id', 0, 'GET'),
    'hotel_star'    => \Xmf\Request::getInt('hotel_star', 0, 'GET'),
    'hotel_name'    => trim(\Xmf\Request::getInt('hotel_name', 0, 'GET')),
];
//确认删除
$confirm = \Xmf\Request::getInt('confirm', 0, 'POST');
//parameter 参数

//模块配置
$ranks = getRanks($xoopsModuleConfig); //TODO
//Hotels
$hotelHandler = $helper->getHandler('Hotel');
//城市
$cityHandler = $helper->getHandler('City');
//是否存在
if ($id > 0 && !$hotelHandler->checkExist($id)) {
    redirect_header(XOOPS_URL, 3, _AM_XMARTIN_UNAUTHORIZED_ACCESS);
}
$cityObj = $cityHandler->create();
//Hotels
$hotelObj = $id > 0 ? $hotelHandler->get($id) : $hotelHandler->create();

$TmpFilePath = '../assets/images/hotel/tmp/';
$FilePath    = '../assets/images/hotel/';
$FileType    = ['.jpg', '.bmp', '.png', '.gif', '.jpeg'];

// martin_adminMenu(2, "订房后台 > 酒店管理");

switch ($action) {
    case 'add':
        //xoops_cp_header();
        martin_collapsableBar('createtable', 'createtableicon', _AM_XMARTIN_ADD_HOTEL, _AM_XMARTIN_ADD_HOTEL);
        //Create_button(array('addcity'=>array('url'=>'mconfircity.php?action=add','value'=>_AM_XMARTIN_CITY_NAME)));

        $form = new Xmartin\Form\FormHotel($hotelObj, $cityObj);
        $form->display();

        martin_close_collapsable('createtable', 'createtableicon');
        require_once __DIR__ . '/admin_footer.php';
        break;
    case 'save':
        $alias_url = str_replace(' ', '-', addslashes($_POST['hotel_alias']));
        $alias_url = $hotelHandler->checkAliasExist($alias_url, $id) ? $alias_url . '-' . mt_rand(10000, 100000) : $alias_url;

        $hotel_city_id = implode(',', $_POST['hotel_city_id']);

        $hotelObj->setVar('hotel_id', $id);
        $hotelObj->setVar('hotel_city', \Xmf\Request::getInt('hotel_city', 0, 'POST'));
        $hotelObj->setVar('hotel_city_id', $hotel_city_id);
        $hotelObj->setVar('hotel_environment', isset($_POST['hotel_environment']) ? addslashes($_POST['hotel_environment']) : '');
        $hotelObj->setVar('hotel_rank', \Xmf\Request::getInt('hotel_rank', 0, 'POST'));
        $hotelObj->setVar('hotel_name', isset($_POST['hotel_name']) ? addslashes($_POST['hotel_name']) : '');
        $hotelObj->setVar('hotel_enname', isset($_POST['hotel_enname']) ? addslashes($_POST['hotel_enname']) : '');
        $hotelObj->setVar('hotel_alias', isset($_POST['hotel_alias']) ? $alias_url : '');
        $hotelObj->setVar('hotel_keywords', isset($_POST['hotel_keywords']) ? addslashes($_POST['hotel_keywords']) : '');
        $hotelObj->setVar('hotel_tags', isset($_POST['hotel_tags']) ? addslashes($_POST['hotel_tags']) : '');
        $hotelObj->setVar('hotel_description', isset($_POST['hotel_description']) ? addslashes($_POST['hotel_description']) : '');
        $hotelObj->setVar('hotel_star', \Xmf\Request::getInt('hotel_star', 0, 'POST'));
        $hotelObj->setVar('hotel_address', isset($_POST['hotel_address']) ? addslashes($_POST['hotel_address']) : '');
        $hotelObj->setVar('hotel_telephone', isset($_POST['hotel_telephone']) ? addslashes($_POST['hotel_telephone']) : '');
        $hotelObj->setVar('hotel_fax', isset($_POST['hotel_fax']) ? addslashes($_POST['hotel_fax']) : '');
        $hotelObj->setVar('hotel_room_count', \Xmf\Request::getInt('hotel_room_count', 0, 'POST'));

        //file upload
        $hotel_icon = isset($_POST['hotel_icon_old']) ? $_POST['hotel_icon_old'] : null;

        require_once XOOPS_ROOT_PATH . '/class/uploader.php';

        if (!empty($_FILES['hotel_icon']['tmp_name'])) {
            $path           = XMARTIN_ROOT_PATH . '/assets/images/hotelicon/';
            $FileTypeUpload = ['image/jpg', 'image/png', 'image/gif', 'image/jpeg'];
            $uploader       = new \XoopsMediaUploader($path, $FileTypeUpload, 2048 * 1024);
            if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
                $uploader->ext = mb_strtolower(ltrim(mb_strrchr($uploader->getMediaName(), '.'), '.'));
                $SaveFileName  = time() . mt_rand(1000, 10000) . '.' . $uploader->ext;
                $uploader->setTargetFileName($SaveFileName);
                if (!$uploader->upload()) {
                    xoops_error($uploader->getErrors());
                    exit();
                } elseif (file_exists($uploader->getSavedDestination())) {
                    //delete images
                    if (!empty($hotel_icon)) {
                        unlink(XMARTIN_ROOT_PATH . '/assets/images/hotelicon/' . $hotel_icon);
                    }
                    $hotel_icon = $uploader->getSavedFileName();
                }
            } else {
                xoops_error($uploader->getErrors());
            }
        }

        //echo $hotel_icon;exit;

        $hotel_icon = empty($hotel_icon) ? 'hotel.jpg' : $hotel_icon;
        $hotelObj->setVar('hotel_icon', $hotel_icon);

        //得到图片
        $images = [];
        if (!empty($_POST['FileData']) && is_array($_POST['FileData'])) {
            foreach ($_POST['FileData'] as $key => $Value) {
                if ($id > 0 && file_exists($FilePath . $key)) {
                    $images[] = ['filename' => $key, 'alt' => $Value];
                    continue;
                }
                foreach ($FileType as $Prefix) {
                    $TmpFileName = $TmpFilePath . $key . $Prefix;
                    if (file_exists($TmpFileName)) {
                        $FileName = time() . mt_rand(1000, 10000) . $Prefix;
                        $images[] = ['filename' => $FileName, 'alt' => $Value];
                        copy($TmpFileName, $FilePath . $FileName);
                        break;
                    }
                }
            }
        }
        //clear dir
        deldir($TmpFilePath);

        $hotelObj->setVar('hotel_image', serialize($images));
        $hotelObj->setVar('hotel_google', serialize([$_POST['GmapLatitude'], $_POST['GmapLongitude']]));
        $hotelObj->setVar('hotel_characteristic', isset($_POST['hotel_characteristic']) ? addslashes($_POST['hotel_characteristic']) : '');
        $hotelObj->setVar('hotel_reminded', isset($_POST['hotel_reminded']) ? addslashes($_POST['hotel_reminded']) : '');
        $hotelObj->setVar('hotel_facility', isset($_POST['hotel_facility']) ? addslashes($_POST['hotel_facility']) : '');
        $hotelObj->setVar('hotel_info', isset($_POST['hotel_info']) ? $_POST['hotel_info'] : '');
        $hotelObj->setVar('hotel_status', \Xmf\Request::getInt('hotel_status', 0, 'POST'));
        //$hotelObj->setVar('hotel_open_time',strtotime(trim($_POST['hotel_open_time']['date'])) + (int)(trim($_POST['hotel_open_time']['time'])) );
        $hotelObj->setVar('hotel_open_time', strtotime(trim($_POST['hotel_open_time'])));
        $hotelObj->setVar('hotel_add_time', time());

        //var_dump($hotelObj);
        //var_dump($_POST);
        if (!$id) {
            $hotelObj->setNew();
        }

        if ($hotelObj->isNew()) {
            $redirect_msg = _AM_XMARTIN_ADDED_SUCCESSFULLY;
            $redirect_to  = 'hotel.php';
        } else {
            $redirect_msg = _AM_XMARTIN_MODIFIED_SUCCESSFULLY;
            $redirect_to  = 'hotel.php';
        }
        if (!$hotelHandler->insert($hotelObj)) {
            if ($hotelObj->_errors) {
                xoops_error($hotelObj->error);
            }
            redirect_header('javascript:history.go(-1);', 2, _AM_XMARTIN_OPERATION_FAILED);
        }

        $hotel_id   = $hotelObj->getVar('hotel_id');
        $hotel_tags = $hotelObj->getVar('hotel_tags');
        // hotel tag
        if ($hotel_id > 0 && !empty($hotel_tags)) {
            $hotelHandler->updateTags($hotelObj);
        }

        redirect_header($redirect_to, 2, $redirect_msg);
        break;
    /*case "upload":
            include XMARTIN_ROOT_PATH . "admin/upload.php";
        break;
    case "showtmpimg":
            include XMARTIN_ROOT_PATH . "admin/thumbnail.php";
        break;*/
    case 'saverank':
        $rankData = $_POST['Ranks'];
        $savemsg  = _AM_XMARTIN_SAVING_FAILED;
        if ($hotelHandler->saveRank($rankData)) {
            $savemsg = _AM_XMARTIN_SAVING_SUCCESSFUL;
        }
        redirect_header('hotel.php', 2, $savemsg);
        break;
    case 'deleteimg':
        $hotelImgPath = XMARTIN_ROOT_PATH . 'assets/images/hotel/';
        $hotelImgName = isset($_POST['img']) ? $_POST['img'] : $_GET['img'];
        $FullImg      = $hotelImgPath . $hotelImgName;
        if (file_exists($FullImg) && is_writable($FullImg)) {
            unlink($FullImg);
        }
        break;
    case 'del':
        if (!$confirm) {
            xoops_confirm(['op' => 'del', 'id' => $id, 'confirm' => 1, 'name' => $hotelObj->hotel_name()], '?action=del', _DELETE . " '" . $hotelObj->hotel_name() . "'. <br> <br>" . _AM_XMARTIN_OK_TO_DELETE_HOTEL, _DELETE);
        } else {
            if ($hotelHandler->delete($hotelObj)) {
                $redirect_msg = _AM_XMARTIN_OK_TO_DELETE_THE_ORDER;
                $redirect_to  = 'hotel.php';
            } else {
                $redirect_msg = _AM_XMARTIN_DELETE_FAILED;
                $redirect_to  = 'javascript:history.go(-1);';
            }
            redirect_header($redirect_to, 2, $redirect_msg);
        }

        break;
    case 'list':
        martin_collapsableBar('createtable', 'createtableicon', _AM_XMARTIN_HOTEL_LIST, _AM_XMARTIN_HOTEL_LIST);
        Create_button(
            [
                'addhotel' => [
                    'url'   => 'hotel.php?action=add',
                    'value' => _AM_XMARTIN_ADD_HOTEL,
                ],
            ]
        );

        $hotelObjects = $hotelHandler->getHotelList($searchData, $helper->getConfig('perpage'), $start);
        //print_r($hotelHandler->hotel_ids);
        $hotelRooms = $hotelHandler->getHotelRooms();

        //分页
        $hotelCount = $hotelHandler->getCount(null, $searchData);
        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $pagenav = new \XoopsPageNav($hotelCount, $helper->getConfig('perpage'), $start, 'start', "hotel_city_id={$searchData['hotel_city_id']}&hotel_star={$searchData['hotel_star']}&hotel_name={$searchData['hotel_name']}&start");
        $pavStr  = '<div style="text-align:left;">' . $pagenav->renderNav() . '</div>';

        $starStr = "<option value='0'>----</option>";
        foreach ($ranks as $key => $rank) {
            $selected = $key == isset($_GET['hotel_star']) ? ' selected' : '';
            $starStr  .= "<option value='$key' $selected>$rank</option>";
        }
        // Creating the objects for top categories
        echo "$pavStr<table width='100%' cellspacing=1 cellpadding=9 border=0 class = outer>";
        echo "<tr><td class='bg3' align='right'>
            <form action='' method='get'>
            " . _AM_XMARTIN_HOTEL_AREA . ":{$cityHandler->getTree('hotel_city_id', $_GET['hotel_city_id'])}
            " . _AM_XMARTIN_HOTEL_STARS . ":<select name='hotel_star'>$starStr</select>
            " . _AM_XMARTIN_HOTEL_NAME . ":<input type='text' name='hotel_name' value='{$_GET['hotel_name']}'>
            <input type='submit' value=" . _AM_XMARTIN_SEARCH . '>
            </form>
            </td></tr>';
        echo '</table>';
        echo "<form action='hotel.php?action=saverank' method='post'>";
        echo $GLOBALS['xoopsSecurity']->getTokenHTML();
        echo "<div align='right'><input type='submit' value='" . _AM_XMARTIN_SAVE_RANK . "'.</div><table width='100%' cellspacing=1 cellpadding=9 border=0 class = outer>";
        echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_HOTEL_NAME . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_ROOM_TYPE . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_HOTEL_AREA . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_HOTEL_STARS . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_HOTEL_PHONE . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_HOTEL_FAX . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_THE_NUMBER_OF_ROOMS . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_HOTEL_STATUS . '</b></td>';
        echo "<td class='bg3' align='left'><b>" . _AM_XMARTIN_HOTEL_SORTING . '</b><br></td>';
        echo "<td width='150' class='bg3' align='center'><b>" . _AM_XMARTIN_ACTIONS . '</b></td>';
        echo '</tr>';
        $Status = [
            '<div style="background-color:#FF0000">' . _AM_XMARTIN_DRAFT . '</div>',
            '<div style="background-color:#00FF00">' . _AM_XMARTIN_PUBLISH . '</div>',
        ];
        if ($hotelCount > 0) {
            foreach ($hotelObjects as $hotel) {
                $add       = "<a href='service.php?action=addhotel&hotel_id=" . $hotel['hotel_id'] . "' title='" . _AM_XMARTIN_ADD_HOTEL_SERVICE . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/images/icon/add.jpg'></a>";
                $addroom   = "<a href='room.php?action=add&hotel_id=" . $hotel['hotel_id'] . "' title='" . _AM_XMARTIN_ADD_HOTEL_ROOMS . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/images/icon/addroom.jpg'></a>";
                $modify    = "<a href='?action=add&id=" . $hotel['hotel_id'] . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/images/icon/edit.gif'></a>";
                $delete    = "<a href='?action=del&id=" . $hotel['hotel_id'] . "'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . "/assets/images/icon/delete.gif'></a>";
                $hotel_url = XOOPS_URL . '/hotel-' . $hotel['hotel_alias'] . $helper->getConfig('hotel_static_prefix');
                echo '<tr>';
                echo "<td class='even' align='lefet'><a href='$hotel_url'>{$hotel['hotel_name']}</a></td>";
                echo "<td class='even' align='lefet'><a href='room.php?action=add&amp;hotel_id={$hotel['hotel_id']}'><img src='../assets/images/icon/add_btn_icon.gif' title='" . _AM_XMARTIN_NEW_ROOM_TYPES . "'></a></td>";
                echo "<td class='even' align='lefet'>{$hotel['city_name']}</td>";
                echo "<td class='even' align='lefet'>{$ranks[$hotel['hotel_star']]}</td>";
                echo "<td class='even' align='lefet'>{$hotel['hotel_telephone']}</td>";
                echo "<td class='even' align='lefet'>{$hotel['hotel_fax']}</td>";
                echo "<td class='even' align='lefet'>{$hotel['hotel_room_count']}</td>";
                echo "<td class='even' align='lefet'>{$Status[$hotel['hotel_status']]}</td>";
                echo "<td class='even' align='lefet'><input type='text' name='Ranks[{$hotel['hotel_id']}]' size=5 value='{$hotel['hotel_rank']}'></td>";
                echo "<td class='even' align='center'> $addroom &nbsp; $add &nbsp; $modify &nbsp; $delete </td>";
                echo '</tr>';
                $rooms = isset($hotelRooms[$hotel['hotel_id']]) ? $hotelRooms[$hotel['hotel_id']] : null;
                if (is_array($rooms)) {
                    foreach ($rooms as $room) {
                        echo '<tr>';
                        echo "<td class='even' align='lefet'></td>";
                        echo "<td class='even' align='lefet'><a href='room.php?action=add&amp;id={$room['room_id']}'>{$room['room_type_info']}</a></td>";
                        echo "<td class='even' align='lefet'>" . _AM_XMARTIN_HOTEL_AREA . ":{$room['room_area']}</td>";
                        echo "<td class='even' align='lefet'>" . _AM_XMARTIN_HOTEL_FLOOR . ":{$room['room_floor']}</td>";
                        echo "<td class='even' align='lefet'>{$Status[$room['room_status']]}</td>";
                        echo "<td class='even' align='lefet'><a href='room.php?action=addprice&amp;room_id={$room['room_id']}'>" . _AM_XMARTIN_PRICE_MANAGEMENT . '</a></td>';
                        echo "<td class='even' align='lefet'></td>";
                        echo "<td class='even' align='lefet'></td>";
                        echo "<td class='even' align='lefet'></td>";
                        echo "<td class='even' align='center'></td>";
                        echo '</tr>';
                    }
                }
            }
        } else {
            echo '<tr>';
            echo "<td class='head' align='center' colspan= '9'>" . XMARTIN_IS_NUll . '</td>';
            echo '</tr>';
            $categoryid = '0';
        }
        echo "</table></form>\n";
        echo "$pavStr<br>";
        martin_close_collapsable('createtable', 'createtableicon');
        echo '<br>';

        break;
        martin_close_collapsable('createtable', 'createtableicon');
        echo '<br>';
        break;
    default:
        redirect_header(XOOPS_URL, 2, _AM_XMARTIN_UNAUTHORIZED_ACCESS);
        break;
}

//底部
require_once __DIR__ . '/admin_footer.php';
