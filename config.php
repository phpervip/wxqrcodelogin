<?php

header("Content-Type: text/html; charset=utf-8");
session_start();
$host = "localhost";
$db_user = "sucaihuo";
$db_pass = "123456";
$db_name = "demo";
$timezone = "Asia/Shanghai";

$link = mysql_connect($host, $db_user, $db_pass);
mysql_select_db($db_name, $link);
mysql_query("SET names UTF8");


$appid = 'wx422126b0b6bbfcfc';
$appsecret = '45843e705995a12106155f4c26f716dc';
?>