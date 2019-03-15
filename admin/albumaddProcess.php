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
//업로드 상대 경로
$uploads_dir = '../img/albumart/';
//등록 가능 확장자
$allowed_ext = array('jpg','jpeg','png','gif', 'JPG', 'JPEG', 'PNG', 'GIF');
//파일 명
$name = $_FILES['path']['name'];
//확장자 분리
$ext_tmp = explode('.', $name);
$ext = array_pop($ext_tmp);
//에러인 경우
$error = $_FILES['path']['error'];
//앨범 명
$albumname = $_POST['name'];
//앨범 발매일
$albumdate = $_POST['albumdate'];

//업로드 에러 처리
if( $error != UPLOAD_ERR_OK ) {
	switch( $error ) {
		case UPLOAD_ERR_INI_SIZE:
		case UPLOAD_ERR_FORM_SIZE:
			echo "파일이 너무 큽니다. ($error)";
			break;
		case UPLOAD_ERR_NO_FILE:
			echo "파일이 첨부되지 않았습니다. ($error)";
			break;
		default:
			echo "파일이 제대로 업로드되지 않았습니다. ($error)";
	}
	exit;
}
//확장자 예외 처리
if( !in_array($ext, $allowed_ext) ) {
	echo "허용되지 않는 확장자입니다.";
	exit;
}

//파일명 중복등의 문제 해결을 위해 파일명은 timestamp로 변경
$imgfilename = time().'.'.$ext;
//DB에 등록될 실제 이미지 경로 입력
$albumpath = '/mymusic/img/albumart/'.$imgfilename;
//파일 업로드 시도 후 DB 등록
if(move_uploaded_file( $_FILES['path']['tmp_name'], $uploads_dir.$imgfilename)){
	$result =  mysqli_query($mysqli, 'INSERT INTO `album` (`name`,`albumpath`,`createdate`, `registerdate`) VALUES ("'.$albumname.'","'.$albumpath.'","'.$albumdate.'",CURTIME());');
	if($result){
		echo "<script>alert('앨범 등록 완료');</script>";
		echo "<script>window.location.replace('manage.php?menu=album');</script>";
	}else{
		echo "<script>alert('앨범 등록에 실패하였습니다.');</script>";
		echo "<script>window.location.replace('manage.php?menu=albumadd');</script>";
	}
}
?>