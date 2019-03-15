<?php
	session_start();
	if($_SESSION['TYPE'] != 1){
		echo "<script>alert('관리자만 접근 가능합니다.');</script>";
		echo "<script>window.location.replace('../index.php');</script>";
		exit;
	}else{
		include "../conf/dbconf.php";
		include "../conf/Common.php";
		if(!isset($_GET['menu'])){
			$menu = "album";
		}else{
			$menu = $_GET['menu'];
			if(isset($_GET['albumid'])){
				$albumid = $_GET['albumid'];
			}
			if(isset($_GET['albumid'])){
				$musicid = $_GET['musicid'];
			}
		}
	}
?>
<html>
<head>
	<title>관리페이지</title>
	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<link href="../css/mymusic.css" rel="stylesheet">
	<script src="../js/jquery-3.1.1.min.js"></script>
</head>
<body>
<div class="container=fluid">
	<div class="col-lg-2 sidebar">
		<ul class="nav nav-sidebar">
			<?php
			if($menu == "album" || $menu == "music"){
				echo '<li class="active"><a href="./manage.php?menu=album">앨범 관리<span class="sr-only">(current)</span></a></li>';
			}else{
				echo '<li><a href="./manage.php?menu=album">앨범 관리</a></li>';
			}
			if($menu == "albumadd"){
				echo '<li class="active"><a href="./manage.php?menu=albumadd">앨범 등록<span class="sr-only">(current)</span></a></li>';
			}else{
				echo '<li><a href="./manage.php?menu=albumadd">앨범 등록</a></li>';
			}
			if($menu == "tag"){
				echo '<li class="active"><a href="./manage.php?menu=tag">태그 관리<span class="sr-only">(current)</span></a></li>';
			}else{
				echo '<li><a href="./manage.php?menu=tag">태그 관리</a></li>';
			}
			?>
		</ul>
		<ul class="nav nav-sidebar">
			<li><a href="../">메인으로 이동</a></li>
		</ul>
	</div>
	<div class="col-lg-10 col-lg-offset-2 main">
		<?php
		if($menu == "album"){
			if(!isset($albumid)){
				$result = mysqli_query($mysqli, 'SELECT * FROM `album` ORDER BY `albumid` DESC;');
				echo '<h1>앨범 관리</h1>';
				echo '<div class="table-resconsive">';
				echo '<table class="table table-striped">';
				echo '<thead>';
				echo '<tr>';
				echo '<th>앨범 아트</th>';
				echo '<th>ID</th>';
				echo '<th>앨범 명</th>';
				echo '<th>앨범 발매일</th>';
				echo '<th>등록일</th>';
				echo '<th/>';
				echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
				while($row = mysqli_fetch_object($result)){
					echo '<tr>';
					echo '<td><img src="'.$row->albumpath.'" width="200px" height="200px"/></td>';
					echo '<td>'.$row->albumid.'</td>';
					echo '<td>'.$row->name.'</td>';
					echo '<td>'.$row->createdate.'</td>';
					echo '<td>'.$row->registerdate.'</td>';
					echo '<td><button type="button" name="'.$row->albumid.'" class="btn btn-success btn-block"><a href="./manage.php?menu=album&albumid='.$row->albumid.'" style="color: white;">앨범 관리</a></button></td>';
					echo '</tr>';
				}
				echo '</tbody>';
				echo '</table>';
				echo '</div>';
			}else{
				$result = mysqli_query($mysqli, 'SELECT * FROM `album` WHERE `albumid`='.$albumid.';');
				$row = mysqli_fetch_object($result);
				echo '<h1>'.$row->name.' 앨범 관리</h1>';
				$result = mysqli_query($mysqli, 'SELECT * FROM `music` WHERE `albumid`='.$row->albumid.';');
				echo '<h2>음악 추가</h2>';
				echo '<form class="musicaddForm" name="musicaddForm" method="post" enctype="multipart/form-data" action="./musicaddProcess.php">';
					echo '<input class="form-control" type="file" name="path"/>';
					echo '<input class="form-control" type="hidden" name="albumid" value="'.$albumid.'"/>';
					echo '<input class="form-control" type="text" name="name" placeholder="음악 이름" autofocus/>';
					echo '<button class="btn btn-primary btn-lg btn-block" type="submit">추가</button>';
				echo '</form>';
				
				echo '<h2>수록곡 목록</h2>';
				echo '<div class="table-resconsive">';
				echo '<table class="table table-striped">';
				echo '<thead>';
				echo '<tr>';
				echo '<th>ID</th>';
				echo '<th>음악 명</th>';
				echo '<th>음악 경로</th>';
				echo '<th>등록일</th>';
				echo '<th/>';
				echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
				while($row = mysqli_fetch_object($result)){
					echo '<tr>';
					echo '<td>'.$row->musicid.'</td>';
					echo '<td>'.$row->name.'</td>';
					echo '<td><a href="'.$row->songpath.'">'.$row->songpath.'</a></td>';
					echo '<td>'.$row->registerdate.'</td>';
					echo '<td><button type="button" name="'.$row->musicid.'" class="btn btn-success btn-block"><a href="./manage.php?menu=music&albumid='.$albumid.'&musicid='.$row->musicid.'" style="color: white;">음악 관리</a></button></td>';
					echo '</tr>';
				}
				echo '</tbody>';
				echo '</table>';
				echo '</div>';
				echo '<span class="pull-right"><a href="./manage.php?menu=album">앨범 목록으로 되돌아가기</a></span>';
			}
		}else if($menu == "albumadd"){
			echo '<h1>앨범 등록</h1>';
			echo '<form class="albumaddForm" name="albumaddForm" method="post" enctype="multipart/form-data" action="./albumaddProcess.php">';
			echo '<input class="form-control" type="file" name="path"/>';
			echo '<input class="form-control" type="text" name="name" placeholder="앨범 이름" autofocus/>';
			echo '<input class="form-control" type="text" name="albumdate" placeholder="앨범 발매일(YYYYMMDD)"/>';
			echo '<button class="btn btn-primary btn-lg btn-block" type="submit">등록</button>';
			echo '</form>';
		}else if($menu == "music"){
			$result = mysqli_query($mysqli, 'SELECT * FROM `music` WHERE `musicid`='.$musicid.';');
			$row = mysqli_fetch_object($result);
			echo '<h1 style="margin-bottom:25px">'.$row->name.' 음악 관리</h1>';
			echo '<h2>태그 추가</h2>';
			echo '<form class="tagaddForm" name="tagaddForm" method="post" action="./tagaddProcess.php" style="margin-bottom:25px">';
				echo '<input class="form-control" type="hidden" name="albumid" value="'.$albumid.'"/>';
				echo '<input class="form-control" type="hidden" name="musicid" value="'.$musicid.'"/>';
				echo '<input class="form-control" type="text" name="name" placeholder="태그 명" style="width:30%" autofocus/>';
				echo '<button class="btn btn-primary btn-lg btn-block" type="submit" style="width:30%;">태그 추가</button>';
			echo '</form>';
			
			
			echo '<h2>태그 일괄 추가</h2>';
			echo '<form class="tagaddallForm" name="tagaddForm" method="post" action="./tagaddallProcess.php" style="margin-bottom:25px">';
			echo '<input class="form-control" type="hidden" name="albumid" value="'.$albumid.'"/>';
			echo '<input class="form-control" type="hidden" name="musicid" value="'.$musicid.'"/>';
			
			echo '<select name="name_1" style="width:30%;" class="form-control">';
			$result = mysqli_query($mysqli, 'SELECT * FROM `tag` WHERE (`type`= "1") AND `originalid` IS NULL order By name');
			echo '<option value = "0">장르</option>';
			while($row = mysqli_fetch_object($result)){
				echo '<option value = '.$row->name.'>'.$row->name.'</option>';
			}
			echo '</select>';
			
			echo '<select name="name_2" style="width:30%;" class="form-control">';
			$result = mysqli_query($mysqli, 'SELECT * FROM `tag` WHERE (`type`= "2") AND `originalid` IS NULL order By name');
			echo '<option value = "0">분위기</option>';
			while($row = mysqli_fetch_object($result)){
				echo '<option value = '.$row->name.'>'.$row->name.'</option>';
			}
			echo '</select>';
			
			echo '<select name="name_3" style="width:30%;" class="form-control">';
			$result = mysqli_query($mysqli, 'SELECT * FROM `tag` WHERE (`type`= "3") AND `originalid` IS NULL order By name');
			echo '<option value = "0">국가</option>';
			while($row = mysqli_fetch_object($result)){
				echo '<option value = '.$row->name.'>'.$row->name.'</option>';
			}
			echo '</select>';
			
			echo '<select name="name_4" style="width:30%;" class="form-control">';
			$result = mysqli_query($mysqli, 'SELECT * FROM `tag` WHERE (`type`= "4") AND `originalid` IS NULL order By name');
			echo '<option value = "0">가수</option>';
			while($row = mysqli_fetch_object($result)){
				echo '<option value = '.$row->name.'>'.$row->name.'</option>';
			}
			echo '</select>';
			
			echo '<select name="name_5" style="width:30%;" class="form-control">';
			$result = mysqli_query($mysqli, 'SELECT * FROM `tag` WHERE (`type`= "5") AND `originalid` IS NULL order By name');
			echo '<option value = "0">작곡가</option>';
			while($row = mysqli_fetch_object($result)){
				echo '<option value = '.$row->name.'>'.$row->name.'</option>';
			}
			echo '</select>';
			
			echo '<select name="name_6" style="width:30%;" class="form-control">';
			$result = mysqli_query($mysqli, 'SELECT * FROM `tag` WHERE (`type`= "6") AND `originalid` IS NULL order By name');
			echo '<option value = "0">작사가</option>';
			while($row = mysqli_fetch_object($result)){
				echo '<option value = '.$row->name.'>'.$row->name.'</option>';
			}
			echo '</select>';
			
			echo '<select name="name_7" style="width:30%;" class="form-control">';
			$result = mysqli_query($mysqli, 'SELECT * FROM `tag` WHERE (`type`= "7") AND `originalid` IS NULL order By name');
			echo '<option value = "0">피쳐링</option>';
			while($row = mysqli_fetch_object($result)){
				echo '<option value = '.$row->name.'>'.$row->name.'</option>';
			}
			echo '</select>';
			
			echo '<select name="name_8" style="width:30%;" class="form-control">';
			$result = mysqli_query($mysqli, 'SELECT * FROM `tag` WHERE (`type`= "8") AND `originalid` IS NULL order By name');
			echo '<option value = "0">편곡가</option>';
			while($row = mysqli_fetch_object($result)){
				echo '<option value = '.$row->name.'>'.$row->name.'</option>';
			}
			echo '</select>';
			
			echo '<select name="name_9" style="width:30%;" class="form-control">';
			$result = mysqli_query($mysqli, 'SELECT * FROM `tag` WHERE (`type`= "9") AND `originalid` IS NULL order By name');
			echo '<option value = "0">관련 작품</option>';
			while($row = mysqli_fetch_object($result)){
				echo '<option value = '.$row->name.'>'.$row->name.'</option>';
			}
			echo '</select>';
			
			echo '<select name="name_99" style="width:30%;" class="form-control">';
			$result = mysqli_query($mysqli, 'SELECT * FROM `tag` WHERE (`type`= "99") AND `originalid` IS NULL order By name');
			echo '<option value = "0">그 외</option>';
			while($row = mysqli_fetch_object($result)){
				echo '<option value = '.$row->name.'>'.$row->name.'</option>';
			}
			echo '</select>';
			
			echo '<button class="btn btn-primary btn-lg btn-block" type="submit" style="width:30%;">태그 일괄 추가</button>';
			echo '</form>';
			
			
			echo '<h2>적용 태그 목록</h2>';
			$result = mysqli_query($mysqli, 'SELECT * FROM `music_tag` NATURAL JOIN `tag` WHERE `tag`.`originalid` IS NULL AND`music_tag`.`musicid`='.$musicid.';');
			echo '<div class="table-resconsive">';
			echo '<table class="table table-striped">';
			echo '<thead>';
			echo '<tr>';
			echo '<th>ID</th>';
			echo '<th>태그 명</th>';
			echo '<th>태그 타입</th>';
			echo '<th>등록일</th>';
			echo '<th style="width:10%"/>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			while($row = mysqli_fetch_object($result)){
				echo '<tr>';
				echo '<td>'.$row->tagid.'</td>';
				echo '<td>'.$row->name.'</td>';
				echo '<td>'.getTagType($row->type).'</td>';
				echo '<td>'.$row->addeddate.'</td>';
				echo '<td><button type="button" name="'.$row->musicid.'" class="btn btn-danger btn-block"><a href="./tagdelProcess.php?albumid='.$albumid.'&musicid='.$musicid.'&tagid='.$row->tagid.'" style="color: white;">태그 삭제</a></button></td>';
				echo '</tr>';
			}
			echo '</tbody>';
			echo '</table>';
			echo '</div>';
			echo '<span class="pull-right"><a href="./manage.php?menu=album&albumid='.$albumid.'">앨범으로 되돌아가기</a></span>';
		}else if($menu == "tag"){
			$result = mysqli_query($mysqli, 'SELECT `t2`.`tagid` AS `tagid`, `t2`.`name` AS `name`, `t1`.`name` AS `originalname`, `t2`.`type` AS `type` FROM `tag` AS `t1`, `tag` AS `t2` WHERE (`t1`.`tagid` = `t2`.`originalid`) OR (`t1`.`tagid` = `t2`.`tagid` AND `t1`.`originalid` IS NULL) ORDER BY `tagid` DESC');
			echo '<h1 style="margin-bottom:25px">태그 관리</h1>';
			echo '<h2>태그 추가</h2>';
			echo '<form class="tagaddForm" name="tagaddForm" method="post" action="./rawtagaddProcess.php" style="margin-bottom:25px">';
				echo '<select name="type" style="width:30%;" class="form-control">';
				echo '<option value="1">장르</option>';
				echo '<option value="2">분위기</option>';
				echo '<option value="3">국가</option>';
				echo '<option value="4" selected>가수</option>';
				echo '<option value="5">작곡가</option>';
				echo '<option value="6">작사가</option>';
				echo '<option value="7">피처링</option>';
				echo '<option value="8">편곡가</option>';
				echo '<option value="9">관련 작품</option>';
				echo '<option value="99">그 외</option>';
				echo '</select>';
				echo '<input class="form-control" type="text" name="name" placeholder="태그 명" style="width:30%" autofocus/>';
				echo '<input class="form-control" type="text" name="originalname" placeholder="원본 태그명" style="width:30%"/>';
				echo '<button class="btn btn-primary btn-lg btn-block" type="submit" style="width:30%;">태그 추가</button>';
			echo '</form>';
			
			echo '<h2>태그 목록</h2>';
			echo '<div class="table-resconsive">';
			echo '<table class="table table-striped">';
			echo '<thead>';
			echo '<tr>';
			echo '<th>ID</th>';
			echo '<th>태그 명</th>';
			echo '<th>태그 타입</th>';
			echo '<th>원본 태그명</th>';
			echo '<th style="width:10%"/>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			while($row = mysqli_fetch_object($result)){
				echo '<tr>';
				echo '<td>'.$row->tagid.'</td>';
				echo '<td>'.$row->name.'</td>';
				echo '<td>'.getTagType($row->type).'</td>';
				if($row->name != $row->originalname){
					echo '<td>'.$row->originalname.'</td>';
				}else{
					echo '<td/>';
				}
				echo '<td><button type="button" name="'.$row->tagid.'" class="btn btn-danger btn-block"><a href="./rawtagdelProcess.php?tagid='.$row->tagid.'" style="color: white;">태그 삭제</a></button></td>';
				echo '</tr>';
			}
			echo '</tbody>';
			echo '</table>';
			echo '</div>';
		}
		?>
	</div>
</div>
</body>
</html>