<?php

header("Content-Type: text/html; charset=utf-8");
session_start();
$host = "localhost";
$db_user = "sucaihuo";
$db_pass = "123456";
$db_name = "wxqrcodelogin";
$timezone = "Asia/Shanghai";

$link = mysql_connect($host, $db_user, $db_pass);
mysql_select_db($db_name, $link);
mysql_query("SET names UTF8");


$appid = 'wx9c45ac1710eb8a3a';
$appsecret = '64c8fdf0bdeaec473f9e4d971a63176a';
?>