<?php
session_start();
if(!isset($_SESSION['USERID'])){
	echo "<script>alert('로그인 되어 있지 않습니다.');</script>";
	echo "<script>window.location.replace('index.php');</script>";
	exit;
}else{
	unset($_SESSION['USERID']);
	unset($_SESSION['NAME']);
	unset($_SESSION['TYPE']);
	unset($_SESSION['JOINDATE']);
	unset($_SESSION['EMAIL']);
	echo "<meta http-equiv='refresh' content='0;url=index.php'>";
}
?>