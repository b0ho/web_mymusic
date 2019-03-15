<?php
session_start();
include "./conf/dbconf.php";
include "./conf/Common.php";
$key = $_GET['key'];
$type = $_GET['type'];
?>
<html>
<head>
	<title><?php echo $key;?> 검색 결과 - 취향저격</title>
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
						<?php
							echo '<option value="1" '.($type==1?'selected="selected"':'').'>제목</option>';
							echo '<option value="2" '.($type==2?'selected="selected"':'').'>태그</option>';
							echo '<option value="3" '.($type==3?'selected="selected"':'').'>가수</option>';
						?>
						</select>
					</span>
				</div>
				<div class="col-lg-8">
					<span>
						<input type="text" class="form-control" name="key" id="key" value=<?php echo '"'.$key.'"';?> style="height: 50px;">
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
		<?php
			if($key == ""){
				echo '<h2>검색 값이 올바르지 않습니다.</h2>';
				exit;
			}
			//검색 결과 화면
			echo '<div class="row" style="margin-top: 10px;">';
			echo '<h2> '.$key.' 의 검색결과</h2>';
			if($type == 1){
				$songresult = mysqli_query($mysqli, 'SELECT * FROM `music` WHERE `name` LIKE "%'.$key.'%";');
				if(mysqli_num_rows($songresult) == 0){
					echo '<h3> '.$key.' 곡의 검색 결과가 없습니다.</h3>';
					exit;
				}
				echo '<div class="table-resconsive">';
				echo '<table class="table table-striped">';
				echo '<thead>';
				echo '<tr>';
				echo '<th style="width:20%; text-align:center;">앨범 아트</th>';
				echo '<th style="width:25%;">앨범 명</th>';
				echo '<th>음악 명</th>';
				echo '<th style="width:25%">가수</th>';
				echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
				while($songrow = mysqli_fetch_object($songresult)){
					echo '<tr>';
					$albumresult = mysqli_query($mysqli, 'SELECT * FROM `album` WHERE `albumid`='.$songrow->albumid.';');
					$albumrow = mysqli_fetch_object($albumresult);
					echo '<td><img src="'.$albumrow->albumpath.'" width=200, height=200/></td>';
					echo '<td>'.$albumrow->name.'</td>';
					echo '<td><a href="./player.php?id='.$songrow->musicid.'" target="_blank">'.$songrow->name.'</a></td>';
					echo '<td>';
					$tagresult = mysqli_query($mysqli, 'SELECT * FROM `music_tag` NATURAL JOIN `tag` WHERE `musicid`='.$songrow->musicid.';');
					while($tagrow = mysqli_fetch_object($tagresult)){
						if($tagrow->type == 4){
							echo '<a href="./search.php?type=3&key='.$tagrow->name.'">'.$tagrow->name.'</a>  ';
						}
					}
					echo '</td>';
					echo '</tr>';
				}
				echo '</tbody>';
				echo '</table>';
				echo '</div>';
			}else{
				$result = mysqli_query($mysqli, 'SELECT * FROM `tag` WHERE `name`="'.$key.'";');
				try{
					$row = mysqli_fetch_object($result);
				}catch (Exception $e){
					echo '<h3> '.$key.' 태그를 찾을 수 없습니다. 태그를 정확히 입력 해 주세요.</h3>';
					exit;
				}
				if(isset($row)){
					if($row->originalid != NULL){
						$result = mysqli_query($mysqli, 'SELECT * FROM `tag` WHERE `tagid`='.$row->originalid.';');
						try{
							$row = mysqli_fetch_object($result);
						}catch (Exception $e){
							echo '<h3> '.$key.' 태그를 찾을 수 없습니다. 태그를 정확히 입력 해 주세요.</h3>';
							exit;
						}
					}
				}else{
					echo '<h3> '.$key.' 태그를 찾을 수 없습니다. 태그를 정확히 입력 해 주세요.</h3>';
					exit;
				}
				//SELECT * FROM `music` NATURAL JOIN `music_tag` WHERE `tagid`=22
				$songresult = mysqli_query($mysqli, 'SELECT * FROM `music` NATURAL JOIN `music_tag` WHERE `tagid`='.$row->tagid.';');
				if(mysqli_num_rows($songresult) == 0){
					echo '<h3> '.$key.' 태그가 등록된 노래가 없습니다!</h3>';
					exit;
				}
				echo '<div class="table-resconsive">';
				echo '<table class="table table-striped">';
				echo '<thead>';
				echo '<tr>';
				echo '<th style="width:20%; text-align:center;">앨범 아트</th>';
				echo '<th style="width:25%;">앨범 명</th>';
				echo '<th>음악 명</th>';
				echo '<th style="width:25%">가수</th>';
				echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
				while($songrow = mysqli_fetch_object($songresult)){
					echo '<tr>';
					$albumresult = mysqli_query($mysqli, 'SELECT * FROM `album` WHERE `albumid`='.$songrow->albumid.';');
					$albumrow = mysqli_fetch_object($albumresult);
					echo '<td><img src="'.$albumrow->albumpath.'" width=200, height=200/></td>';
					echo '<td>'.$albumrow->name.'</td>';
					echo '<td><a href="./player.php?id='.$songrow->musicid.'" target="_blank">'.$songrow->name.'</a></td>';
					echo '<td>';
					$tagresult = mysqli_query($mysqli, 'SELECT * FROM `music_tag` NATURAL JOIN `tag` WHERE `musicid`='.$songrow->musicid.';');
					while($tagrow = mysqli_fetch_object($tagresult)){
						if($tagrow->type == 4){
							echo '<a href="./search.php?type=3&key='.$tagrow->name.'">'.$tagrow->name.'</a>  ';
						}
					}
					echo '</td>';
					echo '</tr>';
				}
				echo '</tbody>';
				echo '</table>';
				echo '</div>';
				if(isset($_SESSION['USERID'])){
					$result = mysqli_query($mysqli, 'INSERT INTO `b_search` (`userid`,`tagid`,`searchdate`) VALUES ("'.$_SESSION['USERID'].'","'.$row->tagid.'",CURRENT_TIMESTAMP());');
				}
			}
		?>
	</div>
</body>
</html>