<?php

if(isset($_GET['file'])&&is_file('upload/'.$_GET['file'])) {
    $arr = getimagesize('upload/'.$_GET['file']);
    if($arr[0] == 0||$arr[1] == 0) {
        header('HTTP/1.1 404 Not Found');
        exit();
    }
    if($arr[2] == 2) {
        $image = imagecreatefromjpeg('upload/'.$_GET['file']);
    }
    else if($arr[2] == 3) {
        $image = imagecreatefrompng('upload/'.$_GET['file']);
    }
    header('Content-Type: image/jpeg');
    $image_new = imagecreatetruecolor($arr[0]*200/$arr[1], 200);
    $color = imagecolorAllocate($image_new, 221, 221, 221);
    imagefill($image_new, 0, 0, $color);
    imagecopyresampled($image_new, $image, 0, 0, 0, 0, $arr[0]*200/$arr[1], 200, $arr[0], $arr[1]);
    imagejpeg($image_new, null, 75);
}
else {
    header('HTTP/1.1 404 Not Found');
}

?>