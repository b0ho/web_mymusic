<?php
session_start();
if(isset($_SESSION['USERID'])){
	echo "<script>alert('로그인 상태에선 회원가입을 할 수 없습니다.');</script>";
	echo "<script>window.location.replace('index.php');</script>";
	exit;
}
//DB 정보 및 함수를 가져옴
include "./conf/dbconf.php";
include "./conf/Common.php";

//POST로 들어온 데이터가 NULL인 경우 잘못된 접근이므로 회원가입 프로세스 종료
if(!isset($_POST['user_id']) || !isset($_POST['user_pw']) || !isset($_POST['user_name']) || !isset($_POST['email'])) exit;

$user_id = $_POST['user_id'];
$user_pw = $_POST['user_pw'];
$user_name = $_POST['user_name'];
$email = $_POST['email'];

//정상 문자열 검사를 진행해서 올바른 문자열인 경우만 쿼리 실행
if(chkAccount($user_id) && chkAccount($user_pw) && chkLength($user_name,0,20)){
	//아이디 중복 검사를 위한 쿼리
	$overlap = mysqli_query($mysqli, 'SELECT `userid` FROM `user` WHERE `userid`="'.$user_id.'";');
	//아이디 결과값이 없을때 (중복이 아닐때)만 삽입 쿼리 실행
	if(mysqli_num_rows($overlap) == 0){
		$result = mysqli_query($mysqli, 'INSERT INTO `user` (`userid`,`password`,`name`,`type`,`joindate`,`email`) VALUES ("'.$user_id.'","'.$user_pw.'","'.$user_name.'",0,CURRENT_TIMESTAMP(),"'.$email.'");');
		if($result){
			$result = mysqli_query($mysqli, 'INSERT INTO `playlist` (`userid`,`name`,`createdate`) VALUES ("'.$user_id.'","즐겨찾기",CURRENT_TIMESTAMP());');
			echo "<script>alert('회원가입 완료. 로그인 페이지로 이동합니다.');</script>";
			echo "<script>window.location.replace('login.php');</script>";
		}else{
			echo "<script>alert('회원가입이 제대로 완료되지 않았습니다. 다시 시도해 주세요.');</script>";
			echo "<script>window.location.replace('signup.php');</script>";
		}
	}else{
		echo "<script>alert('이미 존재하는 아이디 입니다.');</script>";
		echo "<script>window.location.replace('signup.php');</script>";
	}
}else{
	echo "<script>alert('회원가입 정보가 올바르지 않습니다.');</script>";
	echo "<script>window.location.replace('signup.php');</script>";
}
mysqli_close($mysqli);
?>