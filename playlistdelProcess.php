<?php
session_start();

//DB 정보 및 함수를 가져옴
include "./conf/dbconf.php";
include "./conf/Common.php";

$playlistid = $_GET['playlistid'];
$playlistname = $_GET['playlistname'];

if($playlistname == "즐겨찾기") {
	echo "<script>alert('목록 삭제 실패! 즐겨찾기는 삭제 할 수 없습니다.');</script>";
	echo "<script>window.location.replace('./mypage.php?menu=playlist');</script>";
}else {
	$result = mysqli_query($mysqli, 'DELETE FROM `playlist_music` WHERE `playlistid`="'.$playlistid.'";');
	$result = mysqli_query($mysqli, 'DELETE FROM `playlist` WHERE `playlistid`="'.$playlistid.'";');
	if($result){
		echo "<script>window.location.replace('mypage.php?menu=playlist');</script>";
	}else{
		echo "<script>alert('목록 삭제 실패! 다시 시도해 주세요.');</script>";
		echo "<script>window.location.replace('mypage.php?menu=playlist');</script>";
	}
}
?>
