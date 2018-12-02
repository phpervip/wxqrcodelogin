<?php

include_once 'wx/config.php';
$scene_id = isset($_POST['scene_id']) ? intval($_POST['scene_id']) : "";
if ($scene_id > 0) {
    $sql = "SELECT openid,nickname,is_first FROM `qrcode` WHERE `id` =" . $scene_id;
    $query = mysqli_query($link,$sql);
    $arr = mysqli_fetch_array($query);
    echo json_encode($arr);
    
}


