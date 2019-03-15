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
$albumid = $_POST['albumid'];
$musicid = $_POST['musicid'];
$result = mysqli_query($mysqli, 'SELECT * FROM `tag` WHERE `name`="'.$name.'";');
try{
	$row = mysqli_fetch_object($result);
}catch (Exception $e){
	echo "<script>alert('등록되지 않은 태그입니다. 태그를 등록시켜 주세요.');</script>";
	echo "<script>window.location.replace('./manage.php?menu=music&albumid=".$albumid."&musicid=".$musicid."');</script>";
}
if(isset($row)){
	if($row->originalid == NULL){
		$result =  mysqli_query($mysqli, 'INSERT INTO `music_tag` (`musicid`,`tagid`,`addeddate`) VALUES ("'.$musicid.'","'.$row->tagid.'",CURTIME());');
		if($result){
			echo "<script>window.location.replace('./manage.php?menu=music&albumid=".$albumid."&musicid=".$musicid."');</script>";
		}else{
			echo "<script>alert('태그 등록 실패. 다시 시도해 주세요.');</script>";
			echo "<script>window.location.replace('./manage.php?menu=music&albumid=".$albumid."&musicid=".$musicid."');</script>";
		}
	}else{
		$result = mysqli_query($mysqli, 'SELECT * FROM `tag` WHERE `tagid`='.$row->originalid.';');
		try{
			$row = mysqli_fetch_object($result);
			$result =  mysqli_query($mysqli, 'INSERT INTO `music_tag` (`musicid`,`tagid`,`addeddate`) VALUES ("'.$musicid.'","'.$row->tagid.'",CURTIME());');
			if($result){
				echo "<script>window.location.replace('./manage.php?menu=music&albumid=".$albumid."&musicid=".$musicid."');</script>";
			}else{
				echo "<script>alert('태그 등록 실패! 다시 시도해 주세요.');</script>";
				echo "<script>window.location.replace('./manage.php?menu=music&albumid=".$albumid."&musicid=".$musicid."');</script>";
			}
		}catch (Exception $e){
			echo "<script>alert('등록되지 않은 태그입니다. 태그를 등록시켜 주세요.');</script>";
			echo "<script>window.location.replace('./manage.php?menu=music&albumid=".$albumid."&musicid=".$musicid."');</script>";
		}
	}
}else{
	echo "<script>alert('등록되지 않은 태그입니다. 태그를 등록시켜 주세요.');</script>";
	echo "<script>window.location.replace('./manage.php?menu=music&albumid=".$albumid."&musicid=".$musicid."');</script>";
}
?>