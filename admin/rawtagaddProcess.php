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

$name = $_POST['name'];
$type = $_POST['type'];
$originalname = $_POST['originalname'];
$result = mysqli_query($mysqli, 'SELECT * FROM `tag` WHERE `name`="'.$name.'" AND `type`="'.$type.'";');
if($type == 0){
	echo "<script>alert('태그 타입이 지정되지 않았습니다.');</script>";
	echo "<script>window.location.replace('./manage.php?menu=tag');</script>";
	exit;
}

try{
	$row = mysqli_fetch_object($result);
	//태그 명, 타입이 중복되지 않아야 추가 가능
	if(mysqli_num_rows($result) == 0){
		//원본 태그명이 NULL이 아닌 경우 원본 태그가 존재해야함.
		if($originalname != ''){
			$result = mysqli_query($mysqli, 'SELECT `tagid` FROM `tag` WHERE `name`="'.$originalname.'" AND `originalid` IS NULL;');
			try{
				if(mysqli_num_rows($result) == 0){
					echo "<script>alert('원본 태그가 존재하지 않거나 별명 태그입니다!');</script>";
					echo "<script>window.location.replace('./manage.php?menu=tag');</script>";
					exit;
				}else{
					$row = mysqli_fetch_object($result);
					$originalid = $row->tagid;
				}
			}catch (Exception $e){
				echo "<script>alert('원본 태그가 존재하지 않거나 별명 태그입니다.');</script>";
				echo "<script>window.location.replace('./manage.php?menu=tag');</script>";
				exit;
			}
		}
		if($originalname != ''){
			$result =  mysqli_query($mysqli, 'INSERT INTO `tag` (`originalid`,`name`,`type`) VALUES ("'.$originalid.'","'.$name.'","'.$type.'");');
		}else{
			$result =  mysqli_query($mysqli, 'INSERT INTO `tag` (`name`,`type`) VALUES ("'.$name.'","'.$type.'");');
		}
		if($result){
			echo "<script>window.location.replace('./manage.php?menu=tag');</script>";
		}else{
			echo "<script>alert('태그 등록 실패. 다시 시도해 주세요.');</script>";
			echo "<script>window.location.replace('./manage.php?menu=tag');</script>";
			exit;
		}
	}else{
		echo "<script>alert('이미 존재하는 태그 입니다.');</script>";
		echo "<script>window.location.replace('./manage.php?menu=tag');</script>";
		exit;
	}
}catch (Exception $e){
	echo "<script>alert('태그 등록에 문제가 있습니다. 다시 시도해 주세요.');</script>";
	echo "<script>window.location.replace('./manage.php?menu=tag');</script>";
	exit;
}