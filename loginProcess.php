<?php
//DB 정보를 가져옴
include "./conf/dbconf.php";

//POST로 들어온 데이터가 NULL인 경우 잘못된 접근이므로 로그인 프로세스 종료
if(!isset($_POST['user_id']) || !isset($_POST['user_pw'])) exit;

$user_id = $_POST['user_id'];
$user_pw = $_POST['user_pw'];

//로그인 쿼리 시도
$result = mysqli_query($mysqli, 'SELECT `userid`,`name`,`type`,`joindate`,`email` FROM `user` WHERE `userid`="'.$user_id.'" AND `password`="'.$user_pw.'";');
$row = mysqli_fetch_object($result);
if(isset($row)){
	session_start();
	$_SESSION['USERID'] = $row->userid;
	$_SESSION['NAME'] = $row->name;
	$_SESSION['TYPE'] = $row->type;
	$_SESSION['JOINDATE'] = $row->joindate;
	$_SESSION['EMAIL'] = $row->email;
	echo "<meta http-equiv='refresh' content='0;url=index.php'>";
}else{
	echo "<script>alert('로그인에 실패 하였습니다. 아이디 혹은 비밀번호를 확인 해 주세요.');</script>";
	echo "<script>window.location.replace('login.php');</script>";
}

mysqli_close($mysqli);
?>