//<![CDATA[
//得到坐标
jQuery(function () {
    jQuery("#mapClose").click(function () {
        jQuery(".mapbig").hide();
    });
});
function selectTag(showContent, selfObj) {
    // 操作标签
    var tag = document.getElementById("tags").getElementsByTagName("li");
    var taglength = tag.length;
    for (i = 0; i < taglength; i++) {
        tag[i].className = "";
    }
    selfObj.parentNode.className = "selectTag";
    // 操作内容
    for (i = 0; j = document.getElementById("auction" + i); i++) {
        j.style.display = "none";
    }
    document.getElementById(showContent).style.display = "block";
}
function initialize(id, width, height, showclose, lat, lng, hotel_name, message, ImgStr) {
    if (GBrowserIsCompatible()) {
        var map = new GMap2(document.getElementById(id), {size: new GSize(width, height)});
        map.setCenter(new GLatLng(lat, lng), 13);
        var customUI = map.getDefaultUI();
        // Remove MapType.G_HYBRID_MAP
        customUI.maptypes.hybrid = false;
        map.setUI(customUI);
        var latlng = new GLatLng(lat, lng);
        var marker = new GMarker(latlng);
        var myHtml = "<font color=\"blue\">" + hotel_name + "</font><br>" + ImgStr + message;
        //点击显示
        GEvent.addListener(marker, "click", function () {
            map.openInfoWindowHtml(latlng, myHtml);
        });
        map.addOverlay(marker);
        //锚点
        //if(showclose) map.addControl(new TextualZoomControl());
    }
}

function compareDate(d1, d2) {
// 时间比较的方法，如果d1时间比d2时间大，则返回true
    return Date.parse(d1.replace(/-/g, "/")) > Date.parse(d2.replace(/-/g, "/"));
}

function book(even, hotel_id, room_id, isFind) {
    var module_url = jQuery.trim(jQuery("#module_url").val());
    var check_in_date = Number(jQuery("#check_in_date").val());
    check_in_date = isNaN(check_in_date) ? 0 : check_in_date;
    var check_out_date = Number(jQuery("#check_out_date").val());
    check_out_date = isNaN(check_out_date) ? 0 : check_out_date;
    var book_url = module_url + 'book.php?hotel_id=' + hotel_id + '&room_id=' + room_id + '&check_in_date=' + check_in_date + '&check_out_date=' + check_out_date + '&isFind=' + isFind;
    window.location.href = book_url;
}

function SearchSub(even) {
    var _sub = true;
    var _errStr = '';
    var id = '';
    jQuery("#Search .requied").each(function (i) {
        if (jQuery(this).val() == '') {
            if (id == '') id = jQuery(this).attr("id");
            _errStr += (jQuery(this).attr("title") + ' 必填\n');
            _sub = false;
        }
    });
    if (_sub)
        jQuery("#Search").submit();
    else
        alert(_errStr);
    jQuery("#" + id).focus();
}
