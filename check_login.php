<?php

include_once 'config.php';
$scene_id = isset($_POST['scene_id']) ? intval($_POST['scene_id']) : "";
if ($scene_id > 0) {
    $sql = "SELECT openid,nickname,is_first FROM `qrcode` WHERE `id` =" . $scene_id . "";
    $query = mysql_query($sql);
    $arr = mysql_fetch_array($query);
   
    echo json_encode($arr);
    
}


