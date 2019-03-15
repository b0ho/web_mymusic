<?php
session_start();
if(!isset($_SESSION['USERID'])){
	echo "<script>alert('비회원은 사용할 수 없습니다.');</script>";
	echo "<script>window.go(-1);</script>";
	exit;
}
//DB 정보 및 함수를 가져옴
include "./conf/dbconf.php";
include "./conf/Common.php";

if(!isset($_POST['grade'])){
	echo "<script>alert('비정상적인 접근 입니다..');</script>";
	echo "<script>window.go(-1);</script>";
	exit;
}
$grade = $_POST['grade'];
$musicid = $_POST['musicid'];
if($grade == -1 || $grade == 1){
	$result = mysqli_query($mysqli, 'INSERT INTO `b_grade` (`userid`,`musicid`, `grade`) VALUES ("'.$_SESSION['USERID'].'","'.$musicid.'","'.$grade.'");');
	if($result){
		if($grade == 1){
			echo "<script>alert('좋아요를 하셨습니다.');</script>";
			echo "<script>window.location.replace('./player.php?id=".$musicid."&b=1');</script>";
		}else if($grade == -1){
			echo "<script>alert('싫어요를 하셨습니다.');</script>";
			echo "<script>window.location.replace('./player.php?id=".$musicid."&b=1');</script>";
		}
	}else{
		echo "<script>alert('매개 변수가 잘못 되었습니다.');</script>";
		echo "<script>window.go(-1);</script>";
		exit;
	}
}else if($grade == 0){
	$result = mysqli_query($mysqli, 'DELETE FROM `b_grade` WHERE `userid`="'.$_SESSION['USERID'].'" AND `musicid`="'.$musicid.'";');
	if($result){
		echo "<script>alert('추천 정보를 삭제하였습니다.');</script>";
		echo "<script>window.location.replace('./player.php?id=".$musicid."&b=1');</script>";
		exit;
	}
}else{
	echo "<script>alert('매개 변수가 잘못 되었습니다.');</script>";
	echo "<script>window.go(-1);</script>";
	exit;
}
?>