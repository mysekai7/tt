<?php
header('Content-type: text/html; charset=utf-8');
$arr = $_POST['post'];

//print_r($arr);
//sleep(3);
echo json_encode($arr);
?>