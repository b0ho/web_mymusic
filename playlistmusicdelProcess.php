<?php
session_start();

//DB 정보 및 함수를 가져옴
include "./conf/dbconf.php";
include "./conf/Common.php";

$playlistid = $_GET['playlistid'];
$playlistname = $_GET['playlistname'];
$musicid = $_GET['musicid'];

$result = mysqli_query($mysqli, 'DELETE FROM `playlist_music` WHERE `playlistid` = "'.$playlistid.'" AND `musicid`="'.$musicid.'";');
if($result){
	echo "<script>window.location.replace('./mypage.php?menu=playlist&playlistid=".$playlistid."&playlistname=".$playlistname."');</script>";
}else{
	echo "<script>alert('음악 삭제 실패! 다시 시도해 주세요.');</script>";
	echo "<script>window.location.replace('./mypage.php?menu=playlist&playlistid=".$playlistid."&playlistname=".$playlistname."');</script>";
}
?>