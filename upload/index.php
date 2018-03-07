<?php

if(isset($_POST["submit"])&&$_POST["submit"] == "delete") {
    if(is_file($_POST['name'])) {
        unlink($_POST['name']);
    }
    echo '<meta http-equiv="refresh" content="0;url=index.php">';
}

if(isset($_GET['file'])&&is_file($_GET['file'])) {
    if(isset($_GET['hasjumped'])&&$_GET['hasjumped'] == 1) {
        echo '
<html>
  <head>
    <meta charset="utf-8">
    <title>ScrToQQ: '.$_GET['file'].'</title>
  </head>
  <body style="margin:0;">
    <img src="/upload/'.$_GET['file'].'"/>
  </body>
</html>';
    }
    else {
        include '../Includes/header.php';
        include '../Includes/phpqrcode.php';

        ob_start();
        QRCode::png('http://'.$_SERVER['HTTP_HOST'].'/jump.php?file='.$_GET['file'], null);
        $imageString = base64_encode(ob_get_contents());
        ob_end_clean();

        $Time = date("Y-m-d　H:i", filemtime($_GET['file']));
        echo '
<br/>
<div style="float:right;">
  <a class="button" href="/upload/?hasjumped=1&file='.$_GET['file'].'">大图</a>';
        echo '
  <a class="button" href="/upload/'.$_GET['file'].'" download="'.$_GET['file'].'">下载</a>';
        echo '
  <form style="display:inline-block;" action="./" method="post">
    <input type="hidden" name="name" value="'.$_GET['file'].'">
    <label><a class="button" >删除</a><input style="display:none" type="submit" name="submit" value="delete"></label>
  </form>';
        echo '
  <a class="button" href="http://connect.qq.com/widget/shareqq/index.html?summary='.$Time.'&site=ScrToQQ&title=截图&url=http%3A%2F%2F'.urlencode($_SERVER['HTTP_HOST'].'/upload/?hasjumped=1&file='.$_GET['file']).'" target="_blank">分享</a>';
        echo '
  <div class="qrcode">
    <span class="button">扫码分享</span>
    <div class="qrcode-img">
      <img src="data:image/png;base64,'.$imageString.'"/>
    </div>
  </div>
</div>
<br/><br/><br/>';
        echo '
<img style="display:block;margin:auto;max-width:100%;" src="/upload/'.$_GET['file'].'"/>';

        include '../Includes/footer.php';
    }
}
else {
    echo '<meta http-equiv="refresh" content="0;url=/">';
    exit();
}

?>
