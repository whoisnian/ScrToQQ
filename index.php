<?php
include 'Includes/header.php';

$fileErr = '大小限制：'.min(ini_get('upload_max_filesize'), ini_get('post_max_size'));

if(isset($_POST["submit"])) {
    if($_FILES["file"]["error"] > 0) {
        $fileErr = 'Error Code: '.$_FILES["file"]["error"];
    }
    else {
        if(is_allowed_file_type($_FILES["file"]["type"])) {
            $Name = floor(microtime(true)*1000).".".pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
            move_uploaded_file($_FILES["file"]["tmp_name"], 'upload/'.$Name);
            echo '<meta http-equiv="refresh" content="0;url=/upload/?file='.$Name.'">';
            exit();
        }
        else {
            $fileErr =  'Invalid file type.'.$_FILES["file"]["type"];
        }
    }
}

echo '
<br/>
<form class="form-card" action="index.php" method="post" enctype="multipart/form-data">
  <span class="error">'.$fileErr.'</span>
  <br/><br/>
  <label class="button">点击选择图片
    <input class="file" type="file" name="file" accept="image/jpeg, image/png, image/gif">
  </label>
  <label class="button">上传
    <input style="display:none" type="submit" name="submit" value="upload">
  </label>
</form>
<br/>';

if(is_dir('upload')) {
    if($dh = opendir('upload')) {
        while(($file = readdir($dh))) {
            if($file == "."||$file == ".."||$file == "index.php") continue;
            $key = filemtime("upload/".$file);
            
            //自动删除上传时间超过7天的图片
            if(time() - $key > 86400 * 7) {
                unlink('upload/'.$file);
                continue;
            }
            $files[$file] = $key;
        }
        if(!empty($files)) {
            arsort($files);
            echo '
<section class="pic-sec">';
            foreach($files as $file => $key) {
                $arr = getimagesize('upload/'.$file);
                if($arr[0] == 0||$arr[1] == 0) continue;
                echo '
  <div class="pic-div" style="width:'.$arr[0]*200/$arr[1].'px;flex-grow:'.$arr[0]*200/$arr[1].';">
    <i class="pic-i" style="padding-bottom:'.$arr[1]*100/$arr[0].'%;"></i>
    <a href="/upload/?file='.$file.'">
      <img src="/pic.php?file='.$file.'" class="pic-img"></img>
    </a>
  </div>';
            }
            echo '
</section>';
        }
        closedir($dh);
    }
}

function is_allowed_file_type($filetype) {
    if($filetype == "image/jpeg")
        return true;
    else if($filetype == "image/png")
        return true;
    return false;
}
include 'Includes/footer.php';
?>
