<?php
session_start();
if(!isset($_SESSION['USERID'])){
	echo "<script>alert('로그인 되어 있지 않습니다.');</script>";
	echo "<script>window.location.replace('index.php');</script>";
	exit;
}else{
	//DB 정보 및 함수를 가져옴
	include "./conf/dbconf.php";
	include "./conf/Common.php";
	$rcmdresult = mysqli_query($mysqli, 'SELECT * FROM `rcmdlist` NATURAL JOIN `music` WHERE `userid`="'.$_SESSION['USERID'].'" AND (`updatetime` >= CURRENT_TIMESTAMP() - INTERVAL 60 SECOND) ORDER BY `rank` ASC;');
	if(mysqli_num_rows($rcmdresult) == 0){
		makeRcmdList($mysqli, $_SESSION['USERID']);
		echo "<script>window.location.replace('index.php');</script>";
	}else{
		echo "<script>alert('최적화를 위해 1분마다 재생성이 가능합니다.');</script>";
		echo "<script>window.location.replace('index.php');</script>";
	}
}
?>