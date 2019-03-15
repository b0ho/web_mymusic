<?php
	session_start();
	include "./conf/dbconf.php";
	include "./conf/Common.php";
	if(!isset($_GET['menu'])){
		$menu = "playlist";
	}else{
		$menu = $_GET['menu'];
		if(isset($_GET['playlistid'])){
			$playlistid = $_GET['playlistid'];
		}
		if(isset($_GET['playlistid'])){
			$musicid = $_GET['musicid'];
		}
		if(isset($_GET['playlistid'])){
			$playlistname = $_GET['playlistname'];
		}
	}
?>
<html>
<head>
	<title>마이페이지</title>
	<link href="./css/bootstrap.min.css" rel="stylesheet">
	<link href="./css/mymusic.css" rel="stylesheet">
	<script src="./js/jquery-3.1.1.min.js"></script>
</head>
<body>
<div class="container=fluid">
	<div class="col-lg-2 sidebar">
		<ul class="nav nav-sidebar">
			<?php
			if($menu == "playlist" || $menu == "music"){
				echo '<li class="active"><a href="./mypage.php?menu=playlist">목록 관리<span class="sr-only">(current)</span></a></li>';
			}else{
				echo '<li><a href="./mypage.php?menu=playlist">목록 관리</a></li>';
			}
			if($menu == "playlistadd"){
				echo '<li class="active"><a href="./mypage.php?menu=playlistadd">목록 등록<span class="sr-only">(current)</span></a></li>';
			}else{
				echo '<li><a href="./mypage.php?menu=playlistadd">목록 등록</a></li>';
			}
			?>
		</ul>
		<ul class="nav nav-sidebar">
			<li><a href="./">메인으로 이동</a></li>
		</ul>
	</div>
	<div class="col-lg-10 col-lg-offset-2 main">
		<?php
		if($menu == "playlist"){
			if(!isset($playlistid)){
				$result = mysqli_query($mysqli, 'SELECT * FROM `playlist` where `userid` = "'.$_SESSION['USERID'].'" ORDER BY `playlistid` DESC;');
				echo '<h1>목록 관리</h1>';
				echo '<div class="table-resconsive">';
				echo '<table class="table table-striped">';
				echo '<thead>';
				echo '<tr>';
				echo '<th>목록 명</th>';
				echo '<th/>';
				echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
				while($row = mysqli_fetch_object($result)){
					echo '<tr>';
					echo '<td>'.$row->name.'</td>';
					echo '<td><button type="button" name="'.$row->playlistid.'" class="btn btn-success btn-block"><a href="./mypage.php?menu=playlist&playlistid='.$row->playlistid.'&playlistname='.$row->name.'" style="color: white;">목록 관리</a></button></td>';
					if($row->name != '즐겨찾기'){
						echo '<td><button type="button" name="'.$row->playlistid.'" class="btn btn-danger btn-block"><a href="./playlistdelProcess.php?playlistid='.$row->playlistid.'&playlistname='.$row->name.'" style="color: white;">목록 삭제</a></button></td>';
					}
					echo '</tr>';
				}
				echo '</tbody>';
				echo '</table>';
				echo '</div>';
			}else{
				$result = mysqli_query($mysqli, 'SELECT * FROM `playlist_music` WHERE `playlistid` ='.$playlistid.';');
				$row = mysqli_fetch_object($result);
				$result = mysqli_query($mysqli, 'SELECT * FROM `playlist_music` NATURAL JOIN `music` WHERE `playlistid` ='.$playlistid.';');
				
				echo '<h1>'.$playlistname.' 목록 관리</h1>';
				if($row == 0) {
					echo "<script>alert('목록이 비어 있습니다. 추가해 주세요.');</script>";
					echo "<script>window.location.replace('mypage.php?menu=playlist');</script>";
				}else {
					echo '<h2>음악 목록</h2>';
					echo '<div class="table-resconsive">';
					echo '<table class="table table-striped">';
					echo '<thead>';
					echo '<tr>';
					echo '<th>음악 명</th>';;
					echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
					while($row = mysqli_fetch_object($result)){
						echo '<tr>';
						echo '<td>'.$row->name.'</td>';
						echo '<td><button type="button" name="'.$row->musicid.'" class="btn btn-danger btn-block"><a href="./playlistmusicdelProcess.php?playlistid='.$playlistid.'&playlistname='.$playlistname.'&musicid='.$row->musicid.'" style="color: white;">음악 삭제</a></button></td>';
						echo '</tr>';
					}
					echo '</tbody>';
					echo '</table>';
					echo '</div>';
					echo '<span class="pull-right"><a href="./mypage.php?menu=playlist">목록으로 되돌아가기</a></span>';
				}
			}
		}else if($menu == "playlistadd"){
			echo '<h1>목록 등록</h1>';
			echo '<form class="playlistaddForm" name="playlistaddForm" method="post" enctype="multipart/form-data" action="./playlistaddProcess.php">';
			echo '<input class="form-control" type="text" name="list_name" placeholder="목록 이름" autofocus/>';
			echo '<button class="btn btn-primary btn-lg btn-block" type="submit">등록</button>';
			echo '</form>';
		}
		?>
	</div>
</div>
</body>
</html>