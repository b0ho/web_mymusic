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

if(!isset($_POST['musicid'])){
	echo "<script>alert('비정상적인 접근 입니다..');</script>";
	echo "<script>window.go(-1);</script>";
	exit;
}

$playlistid = $_POST['playlist'];
$musicid = $_POST['musicid'];

//리스트 소유권자 확인
$result = mysqli_query($mysqli, 'SELECT * FROM `playlist` WHERE `playlistid`="'.$playlistid.'" AND `userid`="'.$_SESSION['USERID'].'";');
if(mysqli_num_rows($result) == 0){
	echo "<script>alert('해당 재생목록의 소유자가 아닙니다!');</script>";
	echo "<script>window.location.replace('./player.php?id=".$musicid."&b=1');</script>";
	exit;
}

$result = mysqli_query($mysqli, 'SELECT * FROM `playlist_music` WHERE `playlistid`="'.$playlistid.'" AND `musicid`="'.$musicid.'";');
if(mysqli_num_rows($result) == 0){
	$result = mysqli_query($mysqli, 'INSERT INTO `playlist_music` (`playlistid`,`musicid`,`addeddate`) VALUES ("'.$playlistid.'","'.$musicid.'",CURRENT_TIMESTAMP());');
	if($result){
		echo "<script>alert('재생목록에 추가 되었습니다.');</script>";
		echo "<script>window.location.replace('./player.php?id=".$musicid."&b=1');</script>";
	}else{
		echo "<script>alert('재생목록 추가에 실패 하였습니다.');</script>";
		echo "<script>window.location.replace('./player.php?id=".$musicid."&b=1');</script>";
	}
}else{
	echo "<script>alert('이미 해당 목록에 추가된 노래 입니다.');</script>";
	echo "<script>window.location.replace('./player.php?id=".$musicid."&b=1');</script>";
	exit;
}
?>