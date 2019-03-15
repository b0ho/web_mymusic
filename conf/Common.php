<?php
//문자열 공백 제거
function removeSpace($str){
	return preg_replace("/\s+/", "", $str);
}

//아이디, 비밀번호 정보에 한글,공백등 허가되지 않은 문자가 있거나 입력 길이(4~20)외의 길이인 문자열인지 체크
function chkAccount($str){
	if(strcmp($str, removeSpace($str))){
		return false;
	}
	if(preg_match("/[\xA1-\xFE\xA1-\xFE]/",$str)){
		return false;
	}
	if(preg_match("/[%^&*()?+=\/\'\"|]/",$obj)){
		return false;
	}
	$len = strlen($str);
	if($len < 4 || $len > 20){
		return false;
	}
	return true;
}

//문자열이 허용되는 길이인지 체크
function chkLength($str, $min, $max){
	$len = strlen($str);
	if($len < $min || $len > $max){
		return false;
	}
	return true;
}

function getTagType($type){
	switch ($type){
		case 1: return '장르';
		case 2: return '분위기';
		case 3: return '국가';
		case 4: return '가수';
		case 5: return '작곡가';
		case 6: return '작사가';
		case 7: return '피처링';
		case 8: return '편곡가';
		case 9: return '관련 작품';
		case 99: return '그 외';
		case '장르': return 1;
		case '분위기': return 2;
		case '국가': return 3;
		case '가수': return 4;
		case '작곡가': return 5;
		case '작사가': return 6;
		case '피처링': return 7;
		case '편곡가': return 8;
		case '관련 작품': return 9;
		case '그 외': return 99;
	}
}

function makeDailyRanking($mysqli){
	$result = mysqli_query($mysqli, 'INSERT INTO `ranking` (`rankingdate`, `rankingtype`) VALUES (CURDATE() - INTERVAL 1 DAY, 1);');
	if($result){
		$rankid = mysqli_insert_id($mysqli);
		$rank = 1;
		$result = mysqli_query($mysqli, 'SELECT `musicid`, COUNT(*) AS count FROM `b_view` WHERE `viewdate` >= CURDATE() - INTERVAL 1 DAY GROUP BY `musicid` ORDER BY `count` DESC LIMIT 30;');
		while($row = mysqli_fetch_object($result)){
			$rankresult = mysqli_query($mysqli, 'INSERT INTO `ranking_music` (`rankingid`, `musicid`, `rank`) VALUES ("'.$rankid.'", "'.$row->musicid.'", "'.$rank.'");');
			if($rankresult){
				$rank++;
			}
		}
	}else{
		echo '<p>랭킹 정보를 받아올 수 없습니다.</p>';
	}
}
function makeWeeklyRanking($mysqli){
	$result = mysqli_query($mysqli, 'INSERT INTO `ranking` (`rankingdate`, `rankingtype`) VALUES (CURDATE() - INTERVAL 7 DAY, 2);');
	if($result){
		$rankid = mysqli_insert_id($mysqli);
		$rank = 1;
		$result = mysqli_query($mysqli, 'SELECT `musicid`, COUNT(*) AS count FROM `b_view` WHERE `viewdate` >= CURDATE() - INTERVAL 7 DAY GROUP BY `musicid` ORDER BY `count` DESC LIMIT 30;');
		while($row = mysqli_fetch_object($result)){
			$rankresult = mysqli_query($mysqli, 'INSERT INTO `ranking_music` (`rankingid`, `musicid`, `rank`) VALUES ("'.$rankid.'", "'.$row->musicid.'", "'.$rank.'");');
			if($rankresult){
				$rank++;
			}
		}
	}else{
		echo '<p>랭킹 정보를 받아올 수 없습니다.</p>';
	}
}
function makeMonthlyRanking($mysqli){
	$result = mysqli_query($mysqli, 'INSERT INTO `ranking` (`rankingdate`, `rankingtype`) VALUES (CURDATE() - INTERVAL 1 MONTH, 3);');
	if($result){
		$rankid = mysqli_insert_id($mysqli);
		$rank = 1;
		$result = mysqli_query($mysqli, 'SELECT `musicid`, COUNT(*) AS count FROM `b_view` WHERE `viewdate` >= CURDATE() - INTERVAL 1 MONTH GROUP BY `musicid` ORDER BY `count` DESC LIMIT 30;');
		while($row = mysqli_fetch_object($result)){
			$rankresult = mysqli_query($mysqli, 'INSERT INTO `ranking_music` (`rankingid`, `musicid`, `rank`) VALUES ("'.$rankid.'", "'.$row->musicid.'", "'.$rank.'");');
			if($rankresult){
				$rank++;
			}
		}
	}else{
		echo '<p>랭킹 정보를 받아올 수 없습니다.</p>';
	}
}

function makeRcmdList($mysqli, $userid){
	$result = mysqli_query($mysqli, 'DELETE FROM `rcmdlist` WHERE `userid`="'.$userid.'";');
	$searchResult = mysqli_query($mysqli, 'SELECT `result`.*, `tag`.`type`, `tag`.`name` FROM (SELECT `t1`.*, COUNT(*) AS `cnt` FROM (SELECT `userid`, `tagid` FROM `b_view` NATURAL JOIN `music_tag` WHERE `viewdate` >= CURDATE() - INTERVAL 1 MONTH AND `userid`="'.$userid.'") AS `t1` GROUP BY `t1`.`tagid`) AS `result` NATURAL JOIN `tag`'); //검색 내역 쿼리
	$viewResult = mysqli_query($mysqli, 'SELECT `result`.*, `tag`.`type`, `tag`.`name` FROM (SELECT `t1`.*, COUNT(*) AS `cnt` FROM (SELECT `userid`, `tagid` FROM `b_search` WHERE `searchdate` >= CURDATE() - INTERVAL 1 MONTH AND `userid`="'.$userid.'") AS `t1` GROUP BY `t1`.`tagid`) AS `result` NATURAL JOIN `tag`'); //조회 목록 쿼리
	$gradePosResult = mysqli_query($mysqli, 'SELECT `result`.*, `tag`.`type`, `tag`.`name` FROM (SELECT `t1`.*, COUNT(*) AS `cnt` FROM (SELECT `userid`, `tagid`, `grade` FROM `b_grade` NATURAL JOIN `music_tag` WHERE `userid`="'.$userid.'" AND `grade`="1") AS `t1` GROUP BY `t1`.`tagid`, `grade`) AS `result` NATURAL JOIN `tag`'); //긍정평가 쿼리
	$gradeNegResult = mysqli_query($mysqli, 'SELECT `result`.*, `tag`.`type`, `tag`.`name` FROM (SELECT `t1`.*, COUNT(*) AS `cnt` FROM (SELECT `userid`, `tagid`, `grade` FROM `b_grade` NATURAL JOIN `music_tag` WHERE `userid`="'.$userid.'" AND `grade`="-1") AS `t1` GROUP BY `t1`.`tagid`, `grade`) AS `result` NATURAL JOIN `tag`'); //부정평가 쿼리
	
	$map = array();
	while($searchRow = mysqli_fetch_object($searchResult)){
		$id = $searchRow->tagid;
		$cnt = $searchRow->cnt;
		$type = $searchRow->type;
		if(array_key_exists($id, $map)){
			$weight = $map[$id][0];
		}else{
			$weight = 0;
		}
		//태그 타입에 따른 가중치 변경이 있을 수 있음
		$map[$id] = array($weight + ($cnt * 13), $type);
	}
	while($viewRow = mysqli_fetch_object($viewResult)){
		$id = $viewRow->tagid;
		$cnt = $viewRow->cnt;
		$type = $viewRow->type;
		if(array_key_exists($id, $map)){
			$weight = $map[$id][0];
		}else{
			$weight = 0;
		}
		//태그 타입에 따른 가중치 변경이 있을 수 있음
		$map[$id] = array($weight + ($cnt * 8), $type);
	}
	while($gradePosRow = mysqli_fetch_object($gradePosResult)){
		$id = $gradePosRow->tagid;
		$cnt = $gradePosRow->cnt;
		$type = $gradePosRow->type;
		if(array_key_exists($id, $map)){
			$weight = $map[$id][0];
		}else{
			$weight = 0;
		}
		//태그 타입에 따른 가중치 변경이 있을 수 있음
		$map[$id] = array($weight + ($cnt * 25), $type);
	}
	while($gradeNegRow = mysqli_fetch_object($gradeNegResult)){
		$id = $gradeNegRow->tagid;
		$cnt = $gradeNegRow->cnt;
		$type = $gradeNegRow->type;
		if(array_key_exists($id, $map)){
			$weight = $map[$id][0];
		}else{
			$weight = 0;
		}
		//태그 타입에 따른 가중치 변경이 있을 수 있음
		$map[$id] = array($weight + ($cnt * -40), $type);
	}
	if(sizeof($map) < 10){
		return;
	}
	arsort($map); //내림차순 정렬
	//장르와 분위기 타입에서 가중치 상위 10개 태그를 가지는 노래 목록 가져오기
	$query = 'SELECT * FROM `music_tag` WHERE ';
	$i = 0;
	foreach($map AS $k => $v){
		if(($v[1] == 1 || $v[1] == 2) && $i < 10){
			if($i < 9){
				$query = $query.'`tagid`="'.$k.'" OR ';
			}else{
				$query = $query.'`tagid`="'.$k.'" GROUP BY `musicid`';
			}
			$i++;
		}
	}
	//장르와 분위기의 태그 갯수가 10개가 안될경우 추천목록을 만들지 않음
	if($i < 10){
		return;
	}
	reset($map);
	$musicResult = mysqli_query($mysqli, $query);
	$musicMap = array();
	//각 음악별 태그 목록을 가져와 가중치 합산
	while($musicRow = mysqli_fetch_object($musicResult)){
		$tagListResult = mysqli_query($mysqli, 'SELECT * FROM `music_tag` WHERE `musicid`="'.$musicRow->musicid.'"');
		while($tagListRow = mysqli_fetch_object($tagListResult)){
			$tagid = $tagListRow->tagid;
			$musicid = $tagListRow->musicid;
			if(array_key_exists($tagid, $map)){
				//음악에 적용된 태그 갯수에 따른 추가 보정이 필요할 수 있음.
				if(isset($musicMap[$musicid])){
					$musicMap[$musicid][0] = $musicMap[$musicid][0] + $map[$tagid][0];
					$musicMap[$musicid][1]++;
				}else{
					$musicMap[$musicid] = array($map[$tagid][0], 1);
				}
			}
		}
	}
	//플레이 리스트에 등록된 노래는 추천 목록에서 제외
	$haveplaylistResult = mysqli_query($mysqli, 'SELECT `playlistid`, `musicid` FROM `playlist` NATURAL JOIN `playlist_music` WHERE `userid`="'.$userid.'";');
	while($haveplaylistRow = mysqli_fetch_object($haveplaylistResult)){
		if(isset($musicMap[$haveplaylistRow->musicid])){
			$musicMap[$haveplaylistRow->musicid][0] = 0;
		}
	}
	arsort($musicMap);
	$i = 1;
	foreach($musicMap AS $k => $v){
		if($i > 30) break;
		if($v[0] >= 20){
			$makeRcmdResult = mysqli_query($mysqli, 'INSERT INTO `rcmdlist` (`userid`,`musicid`,`updatetime`, `rank`) VALUES ("'.$userid.'","'.$k.'",CURRENT_TIMESTAMP(),"'.$i.'");');
			if($makeRcmdResult){
				$i++;
			}
		}
	}
}
?>