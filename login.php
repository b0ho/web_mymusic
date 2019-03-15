<?php
	session_start();
	if(isset($_SESSION['USERID'])){
		echo "<script>alert('이미 로그인 되어 있습니다.');</script>";
		echo "<script>window.location.replace('index.php');</script>";
		exit;
	}
?>
<html>
<head>
	<title>로그인 - 취향저격</title>
	<link href="./css/bootstrap.min.css" rel="stylesheet">
	<link href="./css/mymusic.css" rel="stylesheet">
	<script src="./js/jquery-3.1.1.min.js"></script>
	<script language="javascript">
		function checkLogin(){
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
			form.submit();
		}
	</script>
</head>
<body>
	<div class="container">
		<form class="loginForm" name="loginForm" method='post' action='./loginProcess.php'>
			<h1>로그인</h1>
			<input class="form-control" name="user_id" type='text' maxlength="20" placeholder="아이디"/>
			<input class="form-control" name="user_pw" type='password' maxlength="20" placeholder="패스워드"/>
			<button class="btn btn-primary btn-lg btn-block" type="button" onClick="javascript:checkLogin()">로그인</button>
			<span class="pull-right"><a href="./signup.php">회원가입</a></span>
		</form>
	</div>
</body>
</html>