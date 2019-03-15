<?php
session_start();
//DB 정보 및 함수를 가져옴
include "./conf/dbconf.php";
include "./conf/Common.php";

//사용자 명
$userid = $_SESSION['USERID'];
//목록 명
$playlistname = $_POST['list_name'];

$result = mysqli_query($mysqli, 'SELECT * FROM `playlist` WHERE `userid` ="'.$userid.'" and `name`="'.$playlistname.'";');

if($playlistname == "") {
	echo "<script>alert('목록 이름을 입력하세요.');</script>";
	echo "<script>window.location.replace('mypage.php?menu=playlistadd');</script>";
}else{
}if(mysqli_num_rows($result) == 0){
	$result =  mysqli_query($mysqli, 'INSERT INTO `playlist` (`userid`, `name`,`createdate`) VALUES ("'.$userid.'","'.$playlistname.'",CURTIME());');
	if($result){
		echo "<script>alert('목록 등록 완료');</script>";
		echo "<script>window.location.replace('mypage.php?menu=playlistadd');</script>";
	}else{
		echo "<script>alert('목록 등록에 실패하였습니다.');</script>";
		echo "<script>window.location.replace('mypage.php?menu=playlistadd');</script>";
	}
}else {
	echo "<script>alert('이미 존재하는 이름입니다. 다른 이름을 입력하세요.');</script>";
	echo "<script>window.location.replace('mypage.php?menu=playlistadd');</script>";
}
?>
