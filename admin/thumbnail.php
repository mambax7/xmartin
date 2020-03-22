<?php
// This script accepts an ID and looks in the user's session for stored thumbnail data.
// It then streams the data to the browser as an image

// Work around the Flash Player Cookie Bug
if (\Xmf\Request::hasVar('PHPSESSID', 'POST')) {
    session_id($_POST['PHPSESSID']);
}

if (false === @session_start()) {
    throw new \RuntimeException('Session could not start.');
}

$image_id = isset($_GET['id']) ? $_GET['id'] : false;

if (false === $image_id) {
    header('HTTP/1.1 500 Internal Server Error');
    echo 'No ID';
    exit(0);
}

if (!is_array($_SESSION['file_info']) || !isset($_SESSION['file_info'][$image_id])) {
    header('HTTP/1.1 404 Not found');
    exit(0);
}

header('Content-type: image/jpeg');
header('Content-Length: ' . mb_strlen($_SESSION['file_info'][$image_id]));

echo $_SESSION['file_info'][$image_id];
exit(0);
