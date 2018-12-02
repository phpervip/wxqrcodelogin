<?php

header("Content-Type: text/html; charset=utf-8");
session_start();
$host = "localhost";
$db_user = "sucaihuo";
$db_pass = "123456";
$db_name = "wxqrcodelogin";
$timezone = "Asia/Shanghai";

$link = mysqli_connect($host, $db_user, $db_pass,$db_name);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

mysqli_query($link,"SET names UTF8");


$appid = 'wx9c45ac1710eb8a3a';
$appsecret = '64c8fdf0bdeaec473f9e4d971a63176a';
?>