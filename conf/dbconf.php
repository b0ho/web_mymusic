<?php
$DB_HOST = "localhost";
$DB_PORT = 3366;
$DB_ID = "mymusic";
$DB_PASSWORD = "mymusicadmin";
$DB_NAME = "mymusic";

//데이터베이스 연결 시도
$mysqli = mysqli_connect($DB_HOST,$DB_ID,$DB_PASSWORD,$DB_NAME,$DB_PORT);

//연결 실패시 에러 출력
if(mysqli_connect_errno()){
	die("데이터베이스에 문제가 있어 로그인에 실패하였습니다. 오류번호 : ".mysqli_connect_error());
}
mysqli_set_charset($mysqli,'utf-8');
?>