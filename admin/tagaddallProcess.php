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

$albumid = $_POST['albumid'];
$musicid = $_POST['musicid'];
$name_1 = $_POST['name_1'];
$name_2 = $_POST['name_2'];
$name_3 = $_POST['name_3'];
$name_4 = $_POST['name_4'];
$name_5 = $_POST['name_5'];
$name_6 = $_POST['name_6'];
$name_7 = $_POST['name_7'];
$name_8 = $_POST['name_8'];
$name_9 = $_POST['name_9'];
$name_99 = $_POST['name_99'];

for ($cnt = 1; $cnt < 11; $cnt++) {
	switch ($cnt) {
		case '1' :
			if ($name_1 != "0") {
				$result_1 = mysqli_query ( $mysqli, 'SELECT * FROM `tag` WHERE `name`="' . $name_1 . '" AND (`type`="1")' );
				$row_1 = mysqli_fetch_object($result_1);
				$result =  mysqli_query($mysqli, 'INSERT INTO `music_tag` (`musicid`,`tagid`,`addeddate`) VALUES ("'.$musicid.'","'.$row_1->tagid.'",CURTIME());');
				echo "<script>window.location.replace('./manage.php?menu=music&albumid=".$albumid."&musicid=".$musicid."');</script>";
			}
		case '2' :
			if ($name_2 != "0"){
				$result_2 = mysqli_query ( $mysqli, 'SELECT * FROM `tag` WHERE `name`="' . $name_2 . '" AND (`type`="2")' );
				$row_2 = mysqli_fetch_object($result_2);
				$result =  mysqli_query($mysqli, 'INSERT INTO `music_tag` (`musicid`,`tagid`,`addeddate`) VALUES ("'.$musicid.'","'.$row_2->tagid.'",CURTIME());');
				echo "<script>window.location.replace('./manage.php?menu=music&albumid=".$albumid."&musicid=".$musicid."');</script>";
			}
		case '3' :
			if ($name_3 != "0"){
				$result_3 = mysqli_query ( $mysqli, 'SELECT * FROM `tag` WHERE `name`="' . $name_3 . '" AND (`type`="3")' );
				$row_3 = mysqli_fetch_object($result_3);
				$result =  mysqli_query($mysqli, 'INSERT INTO `music_tag` (`musicid`,`tagid`,`addeddate`) VALUES ("'.$musicid.'","'.$row_3->tagid.'",CURTIME());');
				echo "<script>window.location.replace('./manage.php?menu=music&albumid=".$albumid."&musicid=".$musicid."');</script>";
			}
		case '4' :
			if ($name_4 != "0"){
				$result_4 = mysqli_query ( $mysqli, 'SELECT * FROM `tag` WHERE `name`="' . $name_4 . '" AND (`type`="4")' );
				$row_4 = mysqli_fetch_object($result_4);
				$result =  mysqli_query($mysqli, 'INSERT INTO `music_tag` (`musicid`,`tagid`,`addeddate`) VALUES ("'.$musicid.'","'.$row_4->tagid.'",CURTIME());');
				echo "<script>window.location.replace('./manage.php?menu=music&albumid=".$albumid."&musicid=".$musicid."');</script>";
			}
		case '5' :
			if ($name_5 != "0"){
				$result_5 = mysqli_query ( $mysqli, 'SELECT * FROM `tag` WHERE `name`="' . $name_5 . '" AND (`type`="5")' );
				$row_5 = mysqli_fetch_object($result_5);
				$result =  mysqli_query($mysqli, 'INSERT INTO `music_tag` (`musicid`,`tagid`,`addeddate`) VALUES ("'.$musicid.'","'.$row_5->tagid.'",CURTIME());');
				echo "<script>window.location.replace('./manage.php?menu=music&albumid=".$albumid."&musicid=".$musicid."');</script>";
			}
		case '6' :
			if ($name_6 != "0"){
				$result_6 = mysqli_query ( $mysqli, 'SELECT * FROM `tag` WHERE `name`="' . $name_6 . '" AND (`type`="6")' );
				$row_6 = mysqli_fetch_object($result_6);
				$result =  mysqli_query($mysqli, 'INSERT INTO `music_tag` (`musicid`,`tagid`,`addeddate`) VALUES ("'.$musicid.'","'.$row_6->tagid.'",CURTIME());');
				echo "<script>window.location.replace('./manage.php?menu=music&albumid=".$albumid."&musicid=".$musicid."');</script>";
			}
		case '7' :
			if ($name_7 != "0"){
				$result_7 = mysqli_query ( $mysqli, 'SELECT * FROM `tag` WHERE `name`="' . $name_7 . '" AND (`type`="7")' );
				$row_7 = mysqli_fetch_object($result_7);
				$result =  mysqli_query($mysqli, 'INSERT INTO `music_tag` (`musicid`,`tagid`,`addeddate`) VALUES ("'.$musicid.'","'.$row_7->tagid.'",CURTIME());');
				echo "<script>window.location.replace('./manage.php?menu=music&albumid=".$albumid."&musicid=".$musicid."');</script>";
			}
		case '8' :
			if ($name_8 != "0"){
				$result_8 = mysqli_query ( $mysqli, 'SELECT * FROM `tag` WHERE `name`="' . $name_8 . '" AND (`type`="8")' );
				$row_8 = mysqli_fetch_object($result_8);
				$result =  mysqli_query($mysqli, 'INSERT INTO `music_tag` (`musicid`,`tagid`,`addeddate`) VALUES ("'.$musicid.'","'.$row_8->tagid.'",CURTIME());');
				echo "<script>window.location.replace('./manage.php?menu=music&albumid=".$albumid."&musicid=".$musicid."');</script>";
			}
		case '9' :
			if ($name_9 != "0"){
				$result_9 = mysqli_query ( $mysqli, 'SELECT * FROM `tag` WHERE `name`="' . $name_9 . '" AND (`type`="9")' );
				$row_9 = mysqli_fetch_object($result_9);
				$result =  mysqli_query($mysqli, 'INSERT INTO `music_tag` (`musicid`,`tagid`,`addeddate`) VALUES ("'.$musicid.'","'.$row_9->tagid.'",CURTIME());');
				echo "<script>window.location.replace('./manage.php?menu=music&albumid=".$albumid."&musicid=".$musicid."');</script>";
			}
		case '10' :
			if ($name_99 != "0"){
				$result_99 = mysqli_query ( $mysqli, 'SELECT * FROM `tag` WHERE `name`="' . $name_99 . '" AND (`type`="99")' );
				$row_99 = mysqli_fetch_object($result_99);
				$result =  mysqli_query($mysqli, 'INSERT INTO `music_tag` (`musicid`,`tagid`,`addeddate`) VALUES ("'.$musicid.'","'.$row_99->tagid.'",CURTIME());');
				echo "<script>window.location.replace('./manage.php?menu=music&albumid=".$albumid."&musicid=".$musicid."');</script>";
			}
	}
}
		
?>