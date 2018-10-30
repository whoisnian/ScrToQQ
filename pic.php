<?php

if(isset($_GET['file'])&&is_file('upload/'.$_GET['file'])) {
    $arr = getimagesize('upload/'.$_GET['file']);
    if($arr[0] == 0||$arr[1] == 0) {
        header('HTTP/1.1 404 Not Found');
        exit();
    }

    //获取上次修改时间
    $modifiedtime = filemtime("upload/".$_GET['file']);
    //如果请求头中包含If-Modified-Since且文件未修改过，返回304
    if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])&&(strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $modifiedtime)) {
        header('Last-Modified: '.date(DATE_RFC2822, $modifiedtime), true, 304);
        exit();
    }

    if($arr[2] == 2) {
        $image = imagecreatefromjpeg('upload/'.$_GET['file']);
    }
    else if($arr[2] == 3) {
        $image = imagecreatefrompng('upload/'.$_GET['file']);
    }

    //指明返回文件类型
    header('Content-Type: image/jpeg');
    //指明上次修改时间
    header('Last-Modified: '.date(DATE_RFC2822, $modifiedtime));
    //设置缓存时间为一周
    header('Cache-Control: private, max-age=604800');
    header('Expires: '.date(DATE_RFC2822, strtotime('+7 days')));

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
