<?php

if(isset($_GET['file'])&&is_file('upload/'.$_GET['file'])) {
    $Time = base64_encode(date("Y-m-d　H:i", filemtime('upload/'.$_GET['file'])));
    header('Location: mqqapi://share/to_fri?src_type=web&version=1&title=5oiq5Zu+&description='.$Time.'&file_type=news&share_id=1101685683&url='.base64_encode('http://'.$_SERVER['HTTP_HOST'].'/upload/?hasjumped=1&file='.$_GET['file']));
}
else {
    header('HTTP/1.1 404 Not Found');
}

?>
