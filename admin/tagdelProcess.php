<?php
session_start();
if($_SESSION['TYPE'] != 1){
	echo "<script>alert('관리자만 접근 가능합니다.');</script>";
	echo "<script>window.location.replace('../index.php');</script>";
	exit;
}
//DB 정보 및 함수를 가져옴
include "../conf/dbconf.php";
include "../conf/Common.php";

$albumid = $_GET['albumid'];
$musicid = $_GET['musicid'];
$tagid = $_GET['tagid'];

$result = mysqli_query($mysqli, 'DELETE FROM `music_tag` WHERE `musicid`="'.$musicid.'" AND `tagid`="'.$tagid.'";');
if($result){
	echo "<script>window.location.replace('./manage.php?menu=music&albumid=".$albumid."&musicid=".$musicid."');</script>";
}else{
	echo "<script>alert('태그 삭제 실패! 다시 시도해 주세요.');</script>";
	echo "<script>window.location.replace('./manage.php?menu=music&albumid=".$albumid."&musicid=".$musicid."');</script>";
}
?>