<?php
/*
 * 文件说明：文件上传处理页面
 */

 header('Content-type:text/html; charset=utf-8');

// 配置设置
$max_file_size = 50000; //50K
$upload_required = true;

$upload_page = 'upload.html';
$upload_dir = './upload/';
$upload_type = array('image/jpeg','image/pjpeg','image/png');

$err_msg = false;
do {
    // 文件域是否存在？
    if (!isset ($_FILES['book_image'])) {
        $err_msg = '表单没被提交';
        break;
    } else {
        $filename   = $_FILES['book_image']['name'];
        $tmppath    = $_FILES['book_image']['tmp_name'];
        $error      = $_FILES['book_image']['error'];
        $size       = $_FILES['book_image']['size'];
        $type       = $_FILES['book_image']['type'];
    }

    // 注意检查MIME类型是否正确，只允许jpeg 和 png 图片
    if (!in_array($type, $upload_type)) {
        $err_msg = '你必须上传jpg和png格式的图片,请点击';

    }

    // 检查我们能够获取的所有可能出现的错误号
    switch ($error) {
        case UPLOAD_ERR_INI_SIZE:   //1 上传文件大小超过php.ini的设置
            $err_msg = '上传的图片尺寸过大，不能超过'.$max_file_size.'bytes';
            break 2;    // 2代表跳出2层循环
        case UPLOAD_ERR_PARTIAL:    //3 上传文件只接收到部分
            $err_img = '上传错误,'.
                "请重新上传<a href='{$upload_page}'>here</a>";
            break 2;
        case UPLOAD_ERR_NO_FILE:    //4 没有文件上传
            if ($upload_required) {
                $err_msg = '你没有选择文件上传';
                break 2;
            }
            break 2;
        case UPLOAD_ERR_FORM_SIZE:  //2 上传文件大小超过了表单项MAX_FILE_SIZE设置的值
            $err_msg = '超过了表单默认设置的MAX_FILE_SIZE设置';
            case UPLOAD_ERR_OK:
            if ($size > $max_file_size) {
                $err_img = '上传图片尺寸过大,不能超过'.$max_file_size.' bytes';
            }
            break 2;
        default:
            $err_msg = '未知错误，';
            break;
    }
} while (0);    //这个代码块永远只执行一次

// 判断上传目录是否存在
if (!file_exists($upload_dir)) {
    mkdir($upload_dir);
}
@chmod($upload_dir,0777);

// 判断是否重名
$fn = date("YmdHis").rand(1000,9999);
$ex = strrchr($filename,".");
$destination = $upload_dir.$fn.$ex;
if (file_exists($destination)) {
    $err_msg = '对不起，相同文件名的文件已存在';
}

// 如果没有出现错误，将文件移动到上传目录中
if (!$err_msg) {
    if (!@move_uploaded_file($tmppath,$destination)) {
        $err_msg = '文件移动错误,请重新上传';
    }
}
?>

<html>
<body>
<?php
    if ($err_msg) {
        echo $err_msg."<a href='{$upload_page}'>Reupload</a>";
    } else {
?>

<?php
    //echo $filename."上传成功<a href='$upload_page'>here</a>";
    echo '<input name="img" type="hidden" value="'.$destination.'" id="img"/>';
    echo "<script>parent.document.getElementById('attachment').innerHTML += '<br>'+document.getElementById('img').value;</script>";
    //echo "<script>window.location.href='upload.html';</script>";
    }
?>
</body>
</html>
