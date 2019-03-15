<?php
session_start();
include "./conf/dbconf.php";
include "./conf/Common.php";

$today = date("Y-m-d");
if(!isset($_GET['r'])){
	$ranktype = 1;
}else if($_GET['r'] == 1 || $_GET['r'] == 2 || $_GET['r'] == 3){
	$ranktype = $_GET['r'];
}else{
	$ranktype = 1;
}
if(!isset($_GET['p'])){
	$playlist = 1;
}else if($_GET['p'] == 1 || $_GET['p'] == 2 || $_GET['p'] == 3){
	$playlist = $_GET['p'];
}else{
	$playlist = 1;
}
?>
<html>
<head>
	<title>취향저격</title>
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
				echo '<span class="pull-right">';
				echo '<a href="./login.php">로그인</a>&nbsp;|&nbsp;';
				echo '<a href="./signup.php">회원가입</a>';
				echo '</span>';
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
			$result = mysqli_query($mysqli, 'SELECT * FROM `tag` WHERE (`type`= "1" OR `type`= "2") AND `originalid` IS NULL ORDER BY rand() LIMIT 30;');
			//$result = mysqli_query($mysqli, 'SELECT * FROM `tag` WHERE `originalid` IS NULL ORDER BY rand() LIMIT 30;');
			echo '<div class="row" style="margin-top: 10px; text-align: center;">';
			echo '<div class="col-lg-12">';
			while($row = mysqli_fetch_object($result)){
				echo '<form class="searchForm" name="searchForm" method="get" style="display: inline-block; margin-bottom: 0px" action="./search.php">';
				echo '<input class="form-control" type="hidden" name="type" value="2"/>';
				echo '<button class="btn btn-link btn-block" name="key" id="key" value="'.$row->name.'">'.$row->name.'</button>';
				echo '</form>';
			}
			echo '</div>';
			echo '</div>';
		?>
		<div class="row" style="margin-top: 10px">
			<div class="col-lg-4">
				<span>
				<?php
					echo '<h3 style="width:50%; display: inline-block;">';
					if($ranktype == 2){
						echo '주간 음악 랭킹</h3>';
					}else if($ranktype == 3){
						echo '월간 음악 랭킹</h3>';
					}else{
						echo '일간 음악 랭킹</h3>';
					}
					echo '<a href="./index.php?r=1">일간</a>';
					echo '&nbsp&nbsp';
					echo '<a href="./index.php?r=2">주간</a>';
					echo '&nbsp&nbsp';
					echo '<a href="./index.php?r=3">월간</a>';
					echo '</span>';
					if($ranktype == 1){
						$result = mysqli_query($mysqli, 'SELECT * FROM `ranking` WHERE (`rankingdate` >= CURDATE() - INTERVAL 1 DAY) AND `rankingtype` = 1;');
						if(mysqli_num_rows($result) == 0){
							makeDailyRanking($mysqli);
							$result = mysqli_query($mysqli, 'SELECT * FROM `ranking` WHERE (`rankingdate` >= CURDATE() - INTERVAL 1 DAY) AND `rankingtype` = 1;');
						}
						$row = mysqli_fetch_object($result);
						$rankresult = mysqli_query($mysqli, 'SELECT * FROM `ranking_music` NATURAL JOIN `music` WHERE `rankingid`="'.$row->rankingid.'" ORDER BY `rank` ASC LIMIT 20;');
						echo '<div class="table-resconsive">';
						echo '<table class="table table-striped">';
						echo '<thead>';
						echo '<tr>';
						echo '<th style="width:15%; text-align:center;">순위</th>';
						echo '<th>제목</th>';
						echo '</thead>';
						echo '<tbody>';
						while($rankrow = mysqli_fetch_object($rankresult)){
							echo '<tr>';
							echo '<td style="text-align:center;"><b>'.$rankrow->rank.'</b></td>';
							echo '<td><a href="./player.php?id='.$rankrow->musicid.'" target="_blank">'.$rankrow->name.'</a></td>';
							echo '</tr>';
						}
						echo '</tbody>';
						echo '</table>';
						echo '</div>';
					}else if($ranktype == 2){
						$result = mysqli_query($mysqli, 'SELECT * FROM `ranking` WHERE (`rankingdate` >= CURDATE() - INTERVAL 7 DAY) AND `rankingtype` = 2;');
						if(mysqli_num_rows($result) == 0){
							makeWeeklyRanking($mysqli);
							$result = mysqli_query($mysqli, 'SELECT * FROM `ranking` WHERE (`rankingdate` >= CURDATE() - INTERVAL 7 DAY) AND `rankingtype` = 2;');
						}
						$row = mysqli_fetch_object($result);
						$rankresult = mysqli_query($mysqli, 'SELECT * FROM `ranking_music` NATURAL JOIN `music` WHERE `rankingid`="'.$row->rankingid.'" ORDER BY `rank` ASC LIMIT 20;');
						echo '<div class="table-resconsive">';
						echo '<table class="table table-striped">';
						echo '<thead>';
						echo '<tr>';
						echo '<th style="width:15%; text-align:center;">순위</th>';
						echo '<th>제목</th>';
						echo '</thead>';
						echo '<tbody>';
						while($rankrow = mysqli_fetch_object($rankresult)){
							echo '<tr>';
							echo '<td style="text-align:center;"><b>'.$rankrow->rank.'</b></td>';
							echo '<td><a href="./player.php?id='.$rankrow->musicid.'" target="_blank">'.$rankrow->name.'</a></td>';
							echo '</tr>';
						}
						echo '</tbody>';
						echo '</table>';
						echo '</div>';
					}else if($ranktype == 3){
						$result = mysqli_query($mysqli, 'SELECT * FROM `ranking` WHERE (`rankingdate` >= CURDATE() - INTERVAL 1 MONTH) AND `rankingtype` = 3;');
						if(mysqli_num_rows($result) == 0){
							makeMonthlyRanking($mysqli);
							$result = mysqli_query($mysqli, 'SELECT * FROM `ranking` WHERE (`rankingdate` >= CURDATE() - INTERVAL 1 MONTH) AND `rankingtype` = 3;');
						}
						$row = mysqli_fetch_object($result);
						$rankresult = mysqli_query($mysqli, 'SELECT * FROM `ranking_music` NATURAL JOIN `music` WHERE `rankingid`="'.$row->rankingid.'" ORDER BY `rank` ASC LIMIT 20;');
						echo '<div class="table-resconsive">';
						echo '<table class="table table-striped">';
						echo '<thead>';
						echo '<tr>';
						echo '<th style="width:15%; text-align:center;">순위</th>';
						echo '<th>제목</th>';
						echo '</thead>';
						echo '<tbody>';
						while($rankrow = mysqli_fetch_object($rankresult)){
							echo '<tr>';
							echo '<td style="text-align:center;"><b>'.$rankrow->rank.'</b></td>';
							echo '<td><a href="./player.php?id='.$rankrow->musicid.'" target="_blank">'.$rankrow->name.'</a></td>';
							echo '</tr>';
						}
						echo '</tbody>';
						echo '</table>';
						echo '</div>';
					}
				?>
			</div>
			<div class="col-lg-4">
				<?php
				echo '<h3 style="width:65%; display: inline-block;">추천 음악</h3>';
				if(!isset($_SESSION['USERID'])){
					echo '<h4><a href="./login.php">로그인</a>이 필요합니다!</h4>';
				}else{
					echo '<a href="./makercmdlist.php">추천 음악 재생성</a>';
					$rcmdresult = mysqli_query($mysqli, 'SELECT * FROM `rcmdlist` NATURAL JOIN `music` WHERE `userid`="'.$_SESSION['USERID'].'" AND (`updatetime` >= CURRENT_TIMESTAMP() - INTERVAL 3 HOUR) ORDER BY `rank` ASC LIMIT 20;');
					if(mysqli_num_rows($rcmdresult) == 0){
						makeRcmdList($mysqli, $_SESSION['USERID']);
						$rcmdresult = mysqli_query($mysqli, 'SELECT * FROM `rcmdlist` NATURAL JOIN `music` WHERE `userid`="'.$_SESSION['USERID'].'" AND (`updatetime` >= CURRENT_TIMESTAMP() - INTERVAL 3 HOUR) ORDER BY `rank` ASC LIMIT 20;');
					}
					if(mysqli_num_rows($rcmdresult) == 0){
						echo '<h4>추천해 드릴만한 노래가 아직 없습니다.</h4>';
						echo '<h4>다양한 노래들을 들어보시고 평가하시면 자동으로 추천 목록이 만들어 집니다.</h4>';
					}else{
						echo '<div class="table-resconsive">';
						echo '<table class="table table-striped">';
						echo '<thead>';
						echo '<tr>';
						echo '<th style="width:15%; text-align:center;">순위</th>';
						echo '<th>제목</th>';
						echo '</thead>';
						echo '<tbody>';
						while($rcmdrow = mysqli_fetch_object($rcmdresult)){
							echo '<tr>';
							echo '<td style="text-align:center;"><b>'.$rcmdrow->rank.'</b></td>';
							echo '<td><a href="./player.php?id='.$rcmdrow->musicid.'" target="_blank" >'.$rcmdrow->name.'</a></td>';
							echo '</tr>';
						}
						echo '</tbody>';
						echo '</table>';
						echo '</div>';
					}
				}
				?>
			</div>
			<div class="col-lg-4">
				<?php
				echo '<h3 style="display: inline-block;">즐겨찾기</h3>';
				if(!isset($_SESSION['USERID'])){
					echo '<h4><a href="./login.php">로그인</a>이 필요합니다!</h4>';
				}else{
					$result = mysqli_query($mysqli, 'SELECT * FROM `playlist` WHERE `userid`="'.$_SESSION['USERID'].'" AND `name`="즐겨찾기";');
					$row = mysqli_fetch_object($result);
					$playlistresult = mysqli_query($mysqli, 'SELECT * FROM `playlist_music` NATURAL JOIN `music` WHERE `playlistid` = "'.$row->playlistid.'" LIMIT 20;');
					if(mysqli_num_rows($playlistresult) == 0){
						echo '<h4>즐겨찾기에 아직 노래가 없습니다!</h4>';
						echo '<h4>마음에 드시는 노래를 추가해 주세요.</h4>';
					}else{
						echo '<div class="table-resconsive">';
						echo '<table class="table table-striped">';
						echo '<thead>';
						echo '<tr>';
						echo '<th style="width:15%; text-align:center;">번호</th>';
						echo '<th>제목</th>';
						echo '</thead>';
						echo '<tbody>';
						$i = 1;
						while($playlistrow = mysqli_fetch_object($playlistresult)){
							echo '<tr>';
							echo '<td style="text-align:center;"><b>'.$i++.'</b></td>';
			 				echo '<td><a href="./player.php?id='.$playlistrow->musicid.'" target="_blank">'.$playlistrow->name.'</a></td>';
							echo '</tr>';
						}
						echo '</tbody>';
						echo '</table>';
						echo '</div>';
					}
				}
				?>
			</div>
	</div>
</body>
</html>