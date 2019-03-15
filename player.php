<?php
session_start();
include "./conf/dbconf.php";
include "./conf/Common.php";
$musicid = $_GET['id'];
$b = $_GET['b'];
?>
<html>
<head>
	<!-- 타이틀이 나중에 수정될수 있도록 함. -->
	<title>플레이어 - 취향저격</title>
	<link href="./css/bootstrap.min.css" rel="stylesheet">
	<link href="./css/mymusic.css" rel="stylesheet">
	<script src="./js/jquery-3.1.1.min.js"></script>
</head>
<body>
	<div class="container">
		<div class="row" style="margin-top: 5px">
			<div class="col-lg-12">
			<?php
			if(!isset($_SESSION['USERID'])){
				echo "<script>alert('로그인이 필요합니다.');</script>";
				echo "<script>close();</script>";
			}else{
				if($_SESSION['TYPE'] == 0){
					echo '<span class="pull-right">';
					echo $_SESSION['NAME'].'님 환영합니다!&nbsp;|&nbsp;';
					echo '<a href="#">정보수정</a>&nbsp;|&nbsp;';
					echo '<a href="./mypage.php">마이페이지</a>&nbsp;|&nbsp;';
					echo '<a href="./logout.php">로그아웃</a>';
					echo '</span>';
				}else{
					echo '<span class="pull-right">';
					echo '[관리자] '.$_SESSION['NAME'].'님 환영합니다!&nbsp;|&nbsp;';
					echo '<a href="#">정보수정</a>&nbsp;|&nbsp;';
					echo '<a href="./mypage.php">마이페이지</a>&nbsp;|&nbsp;';
					echo '<a href="./admin/manage.php">관리페이지</a>&nbsp;|&nbsp;';
					echo '<a href="./logout.php">로그아웃</a>';
					echo '</span>';
				}
				if($b != 1){
					$insertresult = mysqli_query($mysqli, 'INSERT INTO `b_view` (`userid`,`musicid`,`viewdate`) VALUES ("'.$_SESSION['USERID'].'","'.$musicid.'",CURRENT_TIMESTAMP());');
				}
			}
			?>
			</div>
		</div>
		<div class="row" style="margin-top: 10px">
			<div class="col-lg-2">
				<div class="logo"><a href="./"><img src="./img/logo.png"/></a></div>
			</div>
			<form class="searchForm" name="searchForm" method='get' action='./search.php'>
				<div class="col-lg-1">
					<span class="input-group-btn">
						<select name="type" style="width:95px; height:50px;" class="form-control">
							<option value="1">제목</option>
							<option value="2">태그</option>
							<option value="3">가수</option>
						</select>
					</span>
				</div>
				<div class="col-lg-8">
					<span>
						<input type="text" class="form-control" name="key" id="key" value="" style="height: 50px;">
					</span>
				</div>
				<div class="col-lg-1">
					<span class="input-group-btn_M">
						<button class="btn btn-default" type="submit" style="height: 50px;"><span class="glyphicon glyphicon-search"></span> 검색</button>
					</span>
				</div>
			</form>
		</div>
		<?php
			$songresult = mysqli_query($mysqli, 'SELECT * FROM `music` WHERE `musicid`="'.$musicid.'";');
			$songrow = mysqli_fetch_object($songresult);
			$albumresult = mysqli_query($mysqli, 'SELECT * FROM `album` WHERE `albumid`="'.$songrow->albumid.'";');
			$tagresult = mysqli_query($mysqli, 'SELECT * FROM `tag` NATURAL JOIN `music_tag` WHERE `musicid`="'.$musicid.'" ORDER BY `type`;');
			$albumrow = mysqli_fetch_object($albumresult);
			echo '<div class="row" style="margin-top: 30px;">';
			echo '<div class="col-lg-4" style="text-align: center;">';
			echo '<img src="'.$albumrow->albumpath.'" style="width:300px; height:300px;"/>';
			echo '</div>';
			echo '<div class="col-lg-6">';
			echo '<h2><b>'.$songrow->name.'</b></h2>';
			echo '<h4 style="margin-bottom:20px"><b>'.$albumrow->name.'</b></h4>';
			$tag_use_singer = false;
			$tag_use_jaksa = false;
			$tag_use_jakgok = false;
			$tag_use_feat = false;
			$tag_use_remix = false;

			while($tagrow = mysqli_fetch_object($tagresult)){
				if($tagrow->type == 4){
					if(!$tag_use_singer){
						echo '<h4>가수 : ';
						$tag_use_singer = true;
					}
					echo '<a href="./search.php?type=3&key='.$tagrow->name.'" target="_blank">'.$tagrow->name.'</a>  ';
				}
			}
			if($tag_use_singer){echo '</h4>';}

			mysqli_data_seek($tagresult, 0);
			while($tagrow = mysqli_fetch_object($tagresult)){
				if($tagrow->type == 7){
					if(!$tag_use_feat){
						echo '<h4>피처링 : ';
						$tag_use_feat = true;
					}
					echo '<a href="./search.php?type=2&key='.$tagrow->name.'" target="_blank">'.$tagrow->name.'</a>  ';
				}
			}
			if($tag_use_feat){echo '</h4>';}

			mysqli_data_seek($tagresult, 0);
			while($tagrow = mysqli_fetch_object($tagresult)){
				if($tagrow->type == 5){
					if(!$tag_use_jakgok){
						echo '<h4>작곡 : ';
						$tag_use_jakgok = true;
					}
					echo '<a href="./search.php?type=2&key='.$tagrow->name.'" target="_blank">'.$tagrow->name.'</a>  ';
				}
			}
			if($tag_use_jakgok){echo '</h4>';}

			mysqli_data_seek($tagresult, 0);
			while($tagrow = mysqli_fetch_object($tagresult)){
				if($tagrow->type == 6){
					if(!$tag_use_jaksa){
						echo '<h4>작사 : ';
						$tag_use_jaksa = true;
					}
					echo '<a href="./search.php?type=2&key='.$tagrow->name.'" target="_blank">'.$tagrow->name.'</a>  ';
				}
			}
			if($tag_use_jaksa){echo '</h4>';}

			mysqli_data_seek($tagresult, 0);
			while($tagrow = mysqli_fetch_object($tagresult)){
				if($tagrow->type == 8){
					if(!$tag_use_remix){
						echo '<h4>편곡 : ';
						$tag_use_remix = true;
					}
					echo '<a href="./search.php?type=2&key='.$tagrow->name.'" target="_blank">'.$tagrow->name.'</a>  ';
				}
			}
			if($tag_use_remix){echo '</h4>';}

			echo '</div>';
			echo '<div class="col-lg-2">';
			$graderesult = mysqli_query($mysqli, 'SELECT COUNT(*) AS `cnt`, `musicgrade`.* FROM (SELECT `musicid`, `grade` FROM `b_grade` WHERE `musicid`="'.$musicid.'") AS `musicgrade` GROUP BY `grade`');
			$gradeuserresult = mysqli_query($mysqli, 'SELECT `grade` FROM `b_grade` WHERE `musicid`="'.$musicid.'" AND `userid`="'.$_SESSION['USERID'].'";');
			if(mysqli_num_rows($gradeuserresult) == 0){
				echo '<form class="gradeForm" name="gradeForm" method="post" action="./gradeProcess.php">';
					echo '<input class="form-control" type="hidden" name="musicid" value="'.$musicid.'"/>';
					echo '<input class="form-control" type="hidden" name="grade" value="1"/>';
					if(mysqli_num_rows($graderesult) == 0){
						echo '<button class="btn btn-primary btn-lg btn-block" type="submit">좋아요</button>';
					}else{
						while($graderow = mysqli_fetch_object($graderesult)){
							if(mysqli_num_rows($graderesult) == 1 && $graderow->grade == -1){
								echo '<button class="btn btn-primary btn-lg btn-block" type="submit">좋아요</button>';
							}
							if($graderow->grade == 1){
								echo '<button class="btn btn-primary btn-lg btn-block" type="submit">좋아요 ('.$graderow->cnt.')</button>';
							}
						}
					}
				echo '</form>';
				echo '<form class="gradeForm" name="gradeForm" method="post" action="./gradeProcess.php">';
					echo '<input class="form-control" type="hidden" name="musicid" value="'.$musicid.'"/>';
					echo '<input class="form-control" type="hidden" name="grade" value="-1"/>';
					mysqli_data_seek($graderesult, 0);
					if(mysqli_num_rows($graderesult) == 0){
						echo '<button class="btn btn-danger btn-lg btn-block" type="submit">싫어요</button>';
					}else{
						while($graderow = mysqli_fetch_object($graderesult)){
							if(mysqli_num_rows($graderesult) == 1 && $graderow->grade == 1){
								echo '<button class="btn btn-danger btn-lg btn-block" type="submit">싫어요</button>';
							}
							if($graderow->grade == -1){
								echo '<button class="btn btn-danger btn-lg btn-block" type="submit">싫어요 ('.$graderow->cnt.')</button>';
							}
						}
					}
				echo '</form>';
			}else{
				$gradeuserrow = mysqli_fetch_object($gradeuserresult);
				if($gradeuserrow->grade == 1){
					echo '<form class="gradeForm" name="gradeForm" method="post" action="./gradeProcess.php">';
						echo '<input class="form-control" type="hidden" name="musicid" value="'.$musicid.'"/>';
						echo '<input class="form-control" type="hidden" name="grade" value="0"/>';
						echo '<button class="btn btn-default btn-lg btn-block" type="submit">좋아요 취소</button>';
					echo '</form>';
				}else{
					echo '<form class="gradeForm" name="gradeForm" method="post" action="./gradeProcess.php">';
						echo '<input class="form-control" type="hidden" name="musicid" value="'.$musicid.'"/>';
						echo '<input class="form-control" type="hidden" name="grade" value="0"/>';
						echo '<button class="btn btn-default btn-lg btn-block" type="submit">싫어요 취소</button>';
					echo '</form>';
				}
			}
			echo '<form class="playlistaddForm" name="playlistaddForm" method="post" action="./addPlayListProcess.php" style="margin-bottom:25px; margin-top:25px">';
				echo '<select name="playlist" class="form-control">';
				$playlistresult = mysqli_query($mysqli, 'SELECT * FROM `playlist` WHERE `userid` = "'.$_SESSION['USERID'].'";');
				while($playlistrow = mysqli_fetch_object($playlistresult)){
					echo '<option value = '.$playlistrow->playlistid.'>'.$playlistrow->name.'</option>';
				}
				echo '</select>';
				echo '<input class="form-control" type="hidden" name="musicid" value="'.$musicid.'"/>';
				echo '<button class="btn btn-link btn-lg btn-block">재생목록에 추가</button>';
			echo '</form>';
			echo '</div>';
			echo '</div>';

			echo '<div class="row" style="margin-top: 30px;">';
			echo '<div class="col-lg-12" style="text-align: center;">';
			echo '<audio id="audio" preload="auto" controls="" autoplay="" style="width:95%" loop>';
			echo '<source type="audio/mp3" src="'.$songrow->songpath.'"></audio>';
			echo '</div>';
			echo '</div>';

			
			echo '<div class="row" style="margin-top: 30px;">';
			echo '<div class="col-lg-12" style="text-align:center;">';
			echo '<h4><b>최근 재생 목록</b></h4>';
			$lastviewresult = mysqli_query($mysqli, 'SELECT `result`.*, `album`.`name` AS `albumname` FROM (SELECT `viewlist`.*, `music`.`albumid`, `music`.`name` AS `musicname` FROM ( SELECT `b_view`.* FROM `b_view` INNER JOIN ( SELECT max(`viewdate`) AS `maxviewdate`, `musicid` FROM `b_view` WHERE `userid` = "'.$_SESSION['USERID'].'" GROUP BY `musicid` ) AS `t2` ON `b_view`.`viewdate` = `t2`.`maxviewdate`) AS `viewlist` NATURAL JOIN `music` ORDER BY `viewlist`.`viewdate` DESC LIMIT 10 ) AS `result` INNER JOIN  `album`  WHERE  `result`.`albumid` = `album`.`albumid`');
			echo '<div class="table-resconsive">';
			echo '<table class="table table-striped">';
			echo '<thead>';
			echo '<tr>';
			echo '<th style="width:25%;">앨범 명</th>';
			echo '<th>음악 명</th>';
			echo '<th style="width:25%">들은 날짜</th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			while($lastviewrow = mysqli_fetch_object($lastviewresult)){
				echo '<tr>';
				echo '<td>'.$lastviewrow->albumname.'</td>';
				echo '<td><a href="./player.php?id='.$lastviewrow->musicid.'">'.$lastviewrow->musicname.'</a></td>';
				echo '<td>'.$lastviewrow->viewdate.'</td>';
				echo '</tr>';
			}
			echo '</tbody>';
			echo '</table>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
			echo '<div class="row" style="margin-top: 30px;">';
			echo '<div class="col-lg-12" style="text-align:center;">';
			echo '<h4><b>노래 태그</b></h4>';
			mysqli_data_seek($tagresult, 0);
			while($tagrow = mysqli_fetch_object($tagresult)){
				echo '<a href="./search.php?type=2&key='.$tagrow->name.'" style="margin-right:10px;" target="_blank">'.getTagType($tagrow->type).':'.$tagrow->name.'</a>';
			}
			echo '</div>';
			echo '</div>';
		?>
	</div>
</body>
</html>