<?php
/**
 * martin image show
 * @copyright 1997-2010 The Lap Group
 * @author    Martin <china.codehome@gmail.com>
 * @created   time :2010-06-18 11:34:10
 * */
$id = isset($_GET['id']) ? trim($_GET['id']) : null;

$FileType = strtolower(substr(strrchr($id, '.'), 1));

$full = './images/hotelicon/' . $id;
$full = file_exists($full) ? $full : './images/hotel/' . $id;

if (null === $id) {
    throw new Exception('Error : Image Id Is Not Null.');
} elseif (!file_exists($full)) {
    throw new Exception('Error : No Such File.');
}

//简化
$handler = fopen($full, 'rb');
header('Content-type: image/png');
header('Content-Length: ' . filesize($full));
fpassthru($handler);
fclose($handler);
exit;

switch ($FileType) {
    case 'jpg':
        $img = imagecreatefromjpeg($full);
        break;
    case 'png':
        $img = imagecreatefrompng($full);
        break;
    case 'gif':
        $img = imagecreatefromgif($full);
        break;
    case 'bmp':
        $img = imagecreatefromwbmp($full);
        break;
    default:
        throw new Exception('ERROR:image type error ' . $full);
        exit(0);
        break;
}

$width  = imagesx($img);
$height = imagesy($img);

$new_img = imagecreatetruecolor($width, $height);
if (!@imagefilledrectangle($new_img, 0, 0, $target_width - 1, $target_height - 1, 0)) {    // Fill the image black
    echo _AM_MARTIN_ERROR_COULD_NOT_FILL_NEW_IMAGE;
    exit(0);
}

if (!@imagecopyresampled($new_img, $img, 0, 0, 0, 0, $width, $height, $width, $height)) {
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

header('Content-type: image/jpeg');
header('Content-Length: ' . strlen($imagevariable));
echo $imagevariable;
exit(0);
