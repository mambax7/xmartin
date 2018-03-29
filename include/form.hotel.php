<?php
/**
 * @酒店表单
 * @license   http://www.blags.org/
 * @created   :2010年05月20日 23时52分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */

use XoopsModules\Xmartin;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

/**
 * Class form_hotel
 */
class form_hotel extends XoopsThemeForm
{
    /**
     * form_hotel constructor.
     * @param $HotelObj
     * @param $HotelCityObj
     */
    public function __construct(&$HotelObj, &$HotelCityObj)
    {
        global $Ranks;
        $this->Ranks   =& $Ranks;
        $this->Obj     =& $HotelObj;
        $this->CityObj =& $HotelCityObj;
        parent::__construct(_AM_MARTIN_HOTEL_INFO, 'op', xoops_getenv('PHP_SELF') . '?action=save');
        $this->setExtra('enctype="multipart/form-data"');

        $this->createElements();
        $this->createButtons();
    }

    /**
     * created elements
     * @license   http://www.blags.org/
     * @created   :2010年05月21日 20时40分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * */
    public function createElements()
    {
        global $hotelHandler, $xoopsDB;
        /** @var Xmartin\Helper $helper */
        $helper = Xmartin\Helper::getInstance();

        //编辑器
        require_once XOOPS_ROOT_PATH . '/modules/martin/class/xoopsformloader.php';
        require_once MARTIN_ROOT_PATH . '/include/formdatetime.php';

        $this->google_api = $helper->getConfig('google_api');

        /*$this->City = $hotelHandler->GetCityList('WHERE city_parentid = 0');
        $CityElement = new \XoopsFormSelect(_AM_MARTIN_HOTEL_CITY, 'hotel_city', $this->Obj->hotel_city() , 1 );
        $CityElement->addOptionArray($this->City);
        $this->addElement($CityElement , true);*/

        $mytree = new \XoopsTree($xoopsDB->prefix('martin_hotel_city'), 'city_id', 'city_parentid');
        // Parent Category
        //multiple

        $js = '<script type="text/javascript">
            jQuery(document).ready(function(){
                jQuery("#hotel_city").find("option").each(function(i){
                    var v = jQuery(this).text();
                    if(v.indexOf("----") != -1) jQuery(this).remove();
                });
            });
            </script>';

        ob_start();
        $mytree->makeMySelBox('city_name', '', $this->Obj->hotel_city(), 1, 'hotel_city');
        //makeMySelBox($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange="")
        $this->addElement(new \XoopsFormLabel($js . _AM_MARTIN_ADMIN_AREA . '<br>' . _AM_MARTIN_SELECT_2ND_LEVEL, ob_get_contents()));
        ob_end_clean();

        // Parent Category
        //multiple
        $mytree->_SetMultiple(true);
        $hotel_city_id = explode(',', $this->Obj->hotel_city_id());

        ob_start();
        $mytree->makeMySelBox('city_name', '', $hotel_city_id, 1, 'hotel_city_id[]');
        //makeMySelBox($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange="");
        $this->addElement(new \XoopsFormLabel(_AM_MARTIN_CIRCLE . '<br>' . _AM_MARTIN_SELECT_3RD_LEVEL, ob_get_contents()));
        ob_end_clean();
        // City Name
        //$this->addElement( new \XoopsFormText(_AM_MARTIN_ADMIN_CITY_AREA, 'hotel_environment', 50, 255, $this->Obj->hotel_environment()), true);

        $this->addElement(new \XoopsFormText(_AM_MARTIN_HOTEL_RANK, 'hotel_rank', 11, 11, $this->Obj->hotel_rank()), true);
        $this->addElement(new \XoopsFormText(_AM_MARTIN_HOTEL_NAME, 'hotel_name', 50, 255, $this->Obj->hotel_name()), true);
        $this->addElement(new \XoopsFormText(_AM_MARTIN_HOTEL_NAME_ENGLISH, 'hotel_enname', 50, 255, $this->Obj->hotel_enname()), true);
        $this->addElement(new \XoopsFormText(_AM_MARTIN_HOTEL_WEBSITE . '<br>' . _AM_MARTIN_NO_HTML, 'hotel_alias', 50, 255, $this->Obj->hotel_alias()), true);
        $this->addElement(new \XoopsFormText(_AM_MARTIN_HOTEL_KEYWORDS_SEO, 'hotel_keywords', 50, 255, $this->Obj->hotel_keywords()), true);
        $this->addElement(new \XoopsFormTextArea(_AM_MARTIN_HOTEL_DESC_SEO, 'hotel_description', $this->Obj->hotel_description()), true);
        //hotel tags
        $this->addElement(new \XoopsFormText(_AM_MARTIN_HOTELS_TAGS . '<br>' . _AM_MARTIN_USE_SPACE_AS_SEPARATOR, 'hotel_tags', 50, 255, $this->Obj->hotel_tags()), true);

        //hotel star
        $rankElement = new \XoopsFormSelect(_AM_MARTIN_RANK, 'hotel_star', $this->Obj->hotel_star(), 1);
        $rankElement->addOptionArray($this->Ranks);
        $this->addElement($rankElement, true);

        $this->addElement(new \XoopsFormText(_AM_MARTIN_HOTEL_ADDRESS, 'hotel_address', 50, 255, $this->Obj->hotel_address()), true);
        $this->addElement(new \XoopsFormText(_AM_MARTIN_HOTEL_PHONE, 'hotel_telephone', 50, 255, $this->Obj->hotel_telephone()), true);
        $this->addElement(new \XoopsFormText(_AM_MARTIN_HOTEL_FAX, 'hotel_fax', 50, 255, $this->Obj->hotel_fax()), true);
        $this->addElement(new \XoopsFormText(_AM_MARTIN_HOTEL_FEATURES, 'hotel_characteristic', 50, 255, $this->Obj->hotel_characteristic()), true);
        $this->addElement(new \XoopsFormText(_AM_MARTIN_THE_NUMBER_OF_ROOMS, 'hotel_room_count', 11, 11, $this->Obj->hotel_room_count()), true);
        $open_time = $this->Obj->hotel_open_time();
        $this->addElement(new \XoopsFormText(_AM_MARTIN_HOTEL_OPENED, 'hotel_open_time', 50, 255, date('Y', $open_time > 0 ? $open_time : time())), true);

        //        $this->addElement( new \XoopsFormText(_AM_MARTIN_HOTEL_ROOM_PHOTOS, 'hotel_image', 50, 255, $this->Obj->hotel_image()), true);

        list($width, $height) = getimagesize('../images/hotelicon/' . $this->Obj->hotel_icon());
        $hotel_icon = '<script type="text/javascript">
                        function showbox(src)
                        {
                            window.open(src , "_blank" , "width = \' . $width . \',height = \' . $height . \'");
                        }
                        </script>';
        //Hotel Logo
        $hotel_icon .= '<a href = "javascript:void(0);" onclick = "showbox(\'../images/hotelicon/' . $this->Obj->hotel_icon() . '\')"><img src = "../images/hotelicon/' . $this->Obj->hotel_icon() . '" width = 100 height = 100 class="showicon" ></a><br > ';

        $hotelIcon = new \XoopsFormElementTray(_AM_MARTIN_HOTEL_LOGO_IMAGE);
        $hotelIcon->addElement(new \XoopsFormFile('' . $hotel_icon, 'hotel_icon', ''), false);
        $this->addElement($hotelIcon, false);

        //特殊处理
        //酒店地图
        $Coordinate = $this->Obj->hotel_google();
        $google     = new \XoopsFormElementTray(_AM_MARTIN_GOOGLE_MAP);
        $google->addElement(new \XoopsFormText(_AM_MARTIN_LATITTUDE, 'GmapLatitude', 25, 25, $Coordinate[0]), true);
        $google->addElement(new \XoopsFormText(_AM_MARTIN_LONGITUDE, 'GmapLongitude', 25, 25, $Coordinate[1]), true);
        $google->addElement(new \XoopsFormLabel("<br><br><font style='background - color:#2F5376;color:#FFFFFF;padding:2px;vertical-align:middle;'>google map:</font><br>", $this->googleMap($Coordinate)));

        //酒店图片
        $Img = new \XoopsFormElementTray(_AM_MARTIN_HOTEL_PHOTOS);
        $Img->addElement(new \XoopsFormLabel('', $this->Swfupload()));

        $this->addElement($Img);
        $this->addElement($google, true);
        //特殊处理

        //编辑器 酒店详细信息
        $this->addElement(new \XoopsFormTextArea(_AM_MARTIN_HOTELS_NOTES, 'hotel_reminded', $this->Obj->hotel_reminded()), true);
        //hotel Facility
        $this->addElement(new \XoopsFormTextArea(_AM_MARTIN_FACILITIES_SERVICES, 'hotel_facility', $this->Obj->hotel_facility()), true);

        $editor                   = 'tinymce';
        $hotel_info               = $this->Obj->hotel_info();
        $editor_configs           = [];
        $editor_configs['name']   = 'hotel_info';
        $editor_configs['value']  = $hotel_info;
        $editor_configs['rows']   = empty($helper->getConfig('editor_rows')) ? 35 : $helper->getConfig('editor_rows');
        $editor_configs['cols']   = empty($helper->getConfig('editor_cols')) ? 60 : $helper->getConfig('editor_cols');
        $editor_configs['width']  = empty($helper->getConfig('editor_width')) ? '100%' : $helper->getConfig('editor_width');
        $editor_configs['height'] = empty($helper->getConfig('editor_height')) ? '400px' : $helper->getConfig('editor_height');

        $this->addElement(new \XoopsFormEditor(_AM_MARTIN_HOTEL_DETALS, $editor, $editor_configs, false, $onfailure = null), false);
        //$this->addElement(new \XoopsFormHidden("hotel_info", $hotel_info) , true );

        $this->addElement(new \XoopsFormRadioYN(_AM_MARTIN_HOTEL_EDIT_STATUS, 'hotel_status', $this->Obj->hotel_status(), _AM_MARTIN_PUBLISHED, _AM_MARTIN_DRAFT), true);
        //$this->addElement( new MartinFormDateTime("酒店发布时间", 'hotel_open_time', $size = 15, $this->Obj->hotel_open_time() ) ,true);

        $this->addElement(new \XoopsFormHidden('hotel_icon_old', $this->Obj->hotel_icon()));
        $this->addElement(new \XoopsFormHidden('id', $this->Obj->hotel_id()));
    }

    /**
     * @创建按钮
     * @license   http://www.blags.org/
     * @created   :2010年05月20日 23时52分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * */
    public function createButtons()
    {
        $button_tray = new \XoopsFormElementTray('', '');
        // No ID for category -- then it's new category, button says 'Create'
        if (!$this->CityObj->city_id()) {
            $butt_create = new \XoopsFormButton('', '', _SUBMIT, 'submit');
            $butt_create->setExtra('onclick="this.form.elements.op.value=\'addcategory\'"');
            $button_tray->addElement($butt_create);

            $butt_clear = new \XoopsFormButton('', '', _RESET, 'reset');
            $button_tray->addElement($butt_clear);

            $butt_cancel = new \XoopsFormButton('', '', _CANCEL, 'button');
            $butt_cancel->setExtra('onclick="history.go(-1)"');
            $button_tray->addElement($butt_cancel);

            $this->addElement($button_tray);
        } else {
            // button says 'Update'
            $butt_create = new \XoopsFormButton('', '', _EDIT, 'submit');
            $butt_create->setExtra('onclick="this.form.elements.op.value=\'addcategory\'"');
            $button_tray->addElement($butt_create);

            $butt_clear = new \XoopsFormButton('', '', _RESET, 'reset');
            $button_tray->addElement($butt_clear);

            $butt_cancel = new \XoopsFormButton('', '', _CANCEL, 'button');
            $butt_cancel->setExtra('onclick="history.go(-1)"');
            $button_tray->addElement($butt_cancel);

            $this->addElement($button_tray);
        }
    }

    /**
     * @google    地图
     * @license   http://www.blags.org/
     * @created   :2010年05月24日 19时55分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param $Coordinate
     * @return string
     */
    public function googleMap($Coordinate)
    {
        $str = '<div id="gmap" style="width: 640px; height: 320px;"></div>';
        $str .= '<style type="text/css">
            @import url("http://www.google.com/uds/css/gsearch.css");
            @import url("http://www.google.com/uds/solutions/localsearch/gmlocalsearch.css");
            </style>
        <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=' . $this->google_api . '" type="text/javascript"></script>
        <script type="text/javascript">
        //<![CDATA[
        //得到坐标
        var width = 800;
        var height = 400;
        var lat = document.getElementById("GmapLatitude").value;
        lat = lat == "" ? 22.38428032178884 : lat;
        var lng = document.getElementById("GmapLongitude").value;
        lng = lng == "" ? 114.13110352121294 : lng;
        //得到数据信息
        var hotel_name = ["' . $this->Obj->hotel_name() . '"];
        var message = ["' . str_replace("\r\n", '<br>', $this->Obj->hotel_description()) . '"];
        hotel_name = hotel_name == "" ? _AM_MARTIN_HOTEL_NAME : hotel_name;
        message = message == "" ? _AM_MARTIN_HOTEL_DESCRIPTION : message;

        function initialize() {
            if (GBrowserIsCompatible()) {
                var map = new GMap2(document.getElementById("gmap"),{ size: new GSize(width,height) } );
                map.setCenter(new GLatLng(lat,lng), 10);
                var customUI = map.getDefaultUI();
                //搜索
                map.enableGoogleBar();
                // Remove MapType.G_HYBRID_MAP
                customUI.maptypes.hybrid = false;
                map.setUI(customUI);
                var latlng = new GLatLng(lat,lng);
                var marker = new GMarker(latlng);
                var myHtml = "<font color=\"blue\";>" + hotel_name + "</font><br>" + message;
                //点击显示
                GEvent.addListener(map, "click", function(overlay,latlng) {
                    jQuery("#GmapLatitude").val(latlng.lat());
                    jQuery("#GmapLongitude").val(latlng.lng());
                    marker.setLatLng(latlng);
                    map.setCenter(latlng);
                    map.addOverlay(marker);
                    marker.openInfoWindowHtml(myHtml);
                });
                //锚点
                map.setCenter(latlng);
                map.addOverlay(marker);
                marker.openInfoWindowHtml(myHtml);
            }
        }
        //window.onunload = GUnload();
        //同时加载
        window.onload = function(){initialize();swfOnload();};
        //Event.observe(window, "load",initialize);
        google.setOnLoadCallback(initialize);
        //]]>
        </script>  ';

        return $str;
    }

    /**
     * swf 多图片上传
     * @license   http://www.blags.org/
     * @created   :2010年05月24日 19时55分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * */
    public function Swfupload()
    {
        session_start();
        $_SESSION['file_info'] = [];
        /** @var Xmartin\Helper $helper */
        $helper = Xmartin\Helper::getInstance();

        $hotel_image = $this->Obj->hotel_image();

        if (is_array($hotel_image) && count($hotel_image) > 0) {
            foreach ($hotel_image as $image) {
                $alt      = $image['alt'];
                $filename = $image['filename'];
                $key      = explode('.', $filename);
                $str      .= '<div id="'
                             . $key[0]
                             . '"><img id="'
                             . $filename
                             . '" class="existimage" title="'
                             . _AM_MARTIN_DELETE_THE_PICTURE
                             . '" style="margin: 5px; vertical-align: middle; cursor: pointer;" src="../images/hotel/'
                             . $filename
                             . '" width=100 height=100><br><input type="text" title="'
                             . _AM_MARTIN_PICTURE_INTRODUCTION
                             . '" siz="5" name="FileData['
                             . $filename
                             . ']" value="'
                             . $alt
                             . '"></div>';
            }
        }

        $swf = '
        <link href="../javascript/swfupload/css/default.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="../javascript/swfupload/swfupload.js"></script>
        <script type="text/javascript" src="../javascript/swfupload/handlers.js"></script>
        <script type="text/javascript">
        var swfu;
        function swfOnload() {
            swfu = new SWFUpload({
                // Backend Settings
                upload_url: "upload.php",
                post_params: {"PHPSESSID": "' . session_id() . '"},

                // File Upload Settings
                file_size_limit : "10MB",    // 2MB
                file_types : "*.jpg;*.JPG;*.gif;*.GIF;*.jpeg;*.JPEG;*.png;*.PNG",
                file_types_description : "All Files",
                file_upload_limit : 0,

                // Event Handler Settings - these functions as defined in Handlers.js
                //  The handlers are not part of SWFUpload but are part of my website and control how
                //  my website reacts to the SWFUpload events.
                swfupload_preload_handler : preLoad,
                swfupload_load_failed_handler : loadFailed,
                file_queue_error_handler : fileQueueError,
                file_dialog_complete_handler : fileDialogComplete,
                upload_progress_handler : uploadProgress,
                upload_error_handler : uploadError,
                upload_success_handler : uploadSuccess,
                upload_complete_handler : uploadComplete,

                // Button Settings
                button_image_url : "../javascript/swfupload/images/button.png",
                button_placeholder_id : "spanButtonPlaceholder",
                button_width: 61,
                button_height: 22,

                // Flash Settings
                flash_url : "../javascript/swfupload/swfupload.swf",
                flash9_url : "../javascript/swfupload/swfupload_FP9.swf",

                custom_settings : {
                    thumbnail_width : ' . $helper->getConfig('thumbnail_width') . ',
                    thumbnail_height : ' . $helper->getConfig('thumbnail_height') . ',
                    thumbnail_quality : 100,
                    upload_target : "divFileProgressContainer",
                    upload_show   : "ShowTmp"
                },

                // Debug Settings
                debug: false
            });
        }
        </script>
        <div id="divSWFUploadUI">
        <font style="background-color:#2F5376;color:#FFFFFF;padding:2px;vertical-align:middle;">' . _AM_MARTIN_IMAGE_BY . '</font>
            <span id="spanButtonPlaceholder"></span>
            <div id="divFileProgressContainer" style="height: 55px;"></div>
            <div>' . $str . '</div>
            <div id="ShowTmp"></div>
        </div>';

        return $swf;
    }
}
/*
?>
<script type="text/javascript">

   $('.magnific_zoom').magnificPopup({
       type               : 'image',
       image              : {
           cursor     : 'mfp-zoom-out-cur',
           titleSrc   : "title",
           verticalFit: true,
           tError     : 'The image could not be loaded.' // Error message
       },
       iframe             : {
           patterns: {
               youtube : {
                   index: 'youtube.com/',
                   id   : 'v=',
                   src  : '//www.youtube.com/embed/%id%?autoplay=1'
               }, vimeo: {
                   index: 'vimeo.com/',
                   id   : '/',
                   src  : '//player.vimeo.com/video/%id%?autoplay=1'
               }, gmaps: {
                   index: '//maps.google.',
                   src  : '%id%&output=embed'
               }
           }
       },
       preloader          : true,
       showCloseBtn       : true,
       closeBtnInside     : false,
       closeOnContentClick: true,
       closeOnBgClick     : true,
       enableEscapeKey    : true,
       modal              : false,
       alignTop           : false,
       mainClass          : 'mfp-img-mobile mfp-fade',
       zoom               : {
           enabled : true,
           duration: 300,
           easing  : 'ease-in-out'
       },
       removalDelay       : 200
   });
</script>
*/
