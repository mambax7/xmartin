<?php
$img_id = $_GET['id'];

$hotel_icon = './images/hotelicon/' . $img_id;

$hotel_icon = file_exists($hotel_icon) ? $hotel_icon : './images/hotel/' . $img_id;

if (!file_exists($hotel_icon)) {
    throw new Exception($hotel_icon . ' Not Found.');
    exit(0);
}

$FileType = strtolower(substr(strrchr($hotel_icon, '.'), 1));

$img_t = isset($_GET['t']) ? trim($_GET['t']) : null;

$target_width  = isset($_GET['w']) ? (int)$_GET['w'] : 100;
$target_height = isset($_GET['h']) ? (int)$_GET['h'] : 100;

switch ($FileType) {
    case 'jpg':
        $img = imagecreatefromjpeg($hotel_icon);
        break;
    case 'png':
        $img = imagecreatefrompng($hotel_icon);
        break;
    case 'gif':
        $img = imagecreatefromgif($hotel_icon);
        break;
    case 'bmp':
        $img = imagecreatefromwbmp($hotel_icon);
        break;
    default:
        echo 'ERROR:image type error ' . $hotel_icon;
        exit(0);
        break;
}

$width  = imagesx($img);
$height = imagesy($img);
//var_dump($height);exit;

$target_ratio = $target_width / $target_height;
$img_ratio    = $width / $height;

if ($target_ratio > $img_ratio) {
    $new_height = $target_height;
    $new_width  = $img_ratio * $target_height;
} else {
    $new_height = $target_width / $img_ratio;
    $new_width  = $target_width;
}

if ($new_height > $target_height) {
    $new_height = $target_height;
}
if ($new_width > $target_width) {
    $new_height = $target_width;
}

$new_img = imagecreatetruecolor($target_width, $target_height);
if (!@imagefilledrectangle($new_img, 0, 0, $target_width - 1, $target_height - 1, 0)) {
    // Fill the image black
    echo _AM_MARTIN_ERROR_COULD_NOT_FILL_NEW_IMAGE;
    exit(0);
}

//if (!@imagecopyresampled($new_img, $img, ($target_width-$new_width)/2, ($target_height-$new_height)/2, 0, 0, $target_width, $target_height, $width, $height)) {
if (!@imagecopyresampled($new_img, $img, 0, 0, 0, 0, $target_width, $target_height, $width, $height)) {
    echo _AM_MARTIN_ERROR_COULD_NOT_RESIZE_IMAGE;
    exit(0);
}

ob_start();
// Get the image and create a thumbnail
switch ($FileType) {
    case 'jpg':
        imagejpeg($new_img);
        break;
    case 'png':
        imagepng($new_img);
        break;
    case 'gif':
        imagegif($new_img);
        break;
    case 'bmp':
        imagewbmp($new_img);
        break;
}
$imagevariable = ob_get_contents();
ob_end_clean();

//var_dump($imagevariable);exit;

header('Content-type: image/jpeg');
header('Content-Length: ' . strlen($imagevariable));

echo $imagevariable;
exit(0);
