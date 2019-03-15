<?php
session_start();
if(isset($_SESSION['USERID'])){
	echo "<script>alert('로그인 상태에선 회원가입을 할 수 없습니다.');</script>";
	echo "<script>window.location.replace('index.php');</script>";
	exit;
}
?>
<html>
<head>
	<title>회원가입 - 취향저격</title>
	<link href="./css/bootstrap.min.css" rel="stylesheet">
	<link href="./css/mymusic.css" rel="stylesheet">
	<script src="./js/jquery-3.1.1.min.js"></script>
	<script language="javascript">
		function checkForm(){
			var form = document.loginForm;
			if(!form.user_id.value){
				alert("ID가 입력되지 않았습니다.");
				form.user_id.focus();
				return;
			}
			if(!form.user_pw.value){
				alert("비밀번호가 입력되지 않았습니다.");
				form.user_pw.focus();
				return;
			}
			if(!form.user_name.value){
				alert("닉네임이 입력되지 않았습니다.");
				form.user_name.focus();
				return;
			}
			if(!form.email.value){
				alert("이메일 주소가 입력되지 않았습니다.");
				form.email.focus();
				return;
			}
			if(form.user_pw.value != form.user_repw.value){
				alert("재입력 비밀번호가 일치하지 않습니다.");
				form.user_pw.focus();
				return;
			}
			form.submit();
		}
	</script>
</head>
<body>
	<div class="container">
		<form class="loginForm" name="loginForm" method='post' action='./signupProcess.php'>
			<h1>회원가입</h1>
			<input class="form-control" name="user_id" type='text' maxlength="20" placeholder="아이디 (4~20자)"/>
			<input class="form-control" name="user_pw" type='password' maxlength="20" placeholder="패스워드 (4~20자)"/>
			<input class="form-control" name="user_repw" type='password' maxlength="20" placeholder="패스워드 재입력"/>
			<input class="form-control" name="user_name" type='text' maxlength="20" placeholder="닉네임 (최대 20자)"/>
			<input class="form-control" name="email" type='text' placeholder="이메일 주소"/>
			<button class="btn btn-primary btn-lg btn-block" type="button" onClick="javascript:checkForm()">회원가입</button>
		</form>
	</div>
</body>
</html>