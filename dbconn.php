<?php
header("Content-Type: text/html;charset=UTF-8");
 
$db_user = "db id"; //데이터베이스 아이디

$db_passwd = "db pswd";     //데이터베이스 비밀번호

$db_name = "db name"; //데이터베이스 이름

$mysqli = new mysqli("localhost", $db_user, $db_passwd, $db_name);
?>