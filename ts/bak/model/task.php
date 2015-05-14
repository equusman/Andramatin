<?php

function checkOverlap($data){
	global $_db, $_user;

	$currentuserid = $_user->getId();
	
	$taskquery= $_db->query("
	
SELECT * FROM " . DB_PREFIX_APP . "task
WHERE employeeid = ".$currentuserid."
AND startdate = '".$_db->escape($data['taskdate'])."'
AND timefrom < '".$_db->escape($data['start_hour']).":".$_db->escape($data['start_min']).":00' AND timeto > '".$_db->escape($data['end_hour']).":".$_db->escape($data['end_min']).":00' 
UNION
SELECT * FROM " . DB_PREFIX_APP . "task
WHERE employeeid = ".$currentuserid."
AND startdate = '".$_db->escape($data['taskdate'])."'
AND timefrom BETWEEN '".$_db->escape($data['start_hour']).":".$_db->escape($data['start_min']).":00' AND '".$_db->escape($data['end_hour']).":".$_db->escape($data['end_min']).":00'
UNION
SELECT * FROM " . DB_PREFIX_APP . "task
WHERE employeeid = ".$currentuserid."
AND startdate = '".$_db->escape($data['taskdate'])."'
AND timeto BETWEEN '".$_db->escape($data['start_hour']).":".$_db->escape($data['start_min']).":00' AND '".$_db->escape($data['end_hour']).":".$_db->escape($data['end_min']).":00'
	");
	
	if ($taskquery->num_rows) { 
		return "This activity is overlaping with your others activity.";
	} else {
		return "";
	}

}

function isHoliday($tanggal){ //dalam format object DateTime
	global $_db;
	
	$holiday = false;
	$holidayselect = $_db->query("SELECT holiday,comment,dayid FROM " . DB_PREFIX_APP . "holiday WHERE holiday = '".$tanggal->format('Y-m-d')."' AND active = 1");
	if ($holidayselect->num_rows) { 
		$holiday = true;
	} else {
		$holiday = false;
	}
	return $holiday;
}

function getAvailableTaskEntryDate($data){
	global $_db, $_config, $_user;
	
	$da = $data['dayweek'];//"1000001";
	$daysback = $data['dayback'];//2	;
	$dcount = 1;
	
	$date = new DateTime();
	
	// echo "<pre>";
	// print_r($date);
	// echo "</pre>";

	while ($daysback >= $dcount) {
		if ($data['weekendskip']=='1') {
			
			if (($da[$date->format('w')]=='0') && (!isHoliday($date)))
			{
				$dreturn[] =  $date->format('Y-m-d')."|".$date->format('l, j F Y') ;
				$dcount++;
			}else{
				$dreturn[] =  $date->format('Y-m-d')."|".$date->format('l, j F Y') ;
			}
		} else {
				$dreturn[] =  $date->format('Y-m-d')."|".$date->format('l, j F Y') ;
				$dcount++;
		}
		$date->sub(new DateInterval('P1D'));
	}
	
	return $dreturn;
}	


function addHoliday($data) {
	global $_db, $_user;
	
	$username = $_user->getUsername();
	$userid =$_user->getId();
	// die("INSERT INTO " . DB_PREFIX_APP . "holiday SELECT  
		// '".$_db->escape($data['holiday'])."', 
		// '".$_db->escape($data['active'])."',
		// '".$_db->escape($data['description'])."',
		// MAX(dayid)+1 FROM " . DB_PREFIX_APP . "holiday ");
		
	$task_query = 
		$_db->query("INSERT INTO " . DB_PREFIX_APP . "holiday SELECT  
		'".$_db->escape($data['holiday'])."', 
		'".$_db->escape($data['active'])."',
		'".$_db->escape($data['description'])."',
		MAX(dayid)+1 FROM " . DB_PREFIX_APP . "holiday ");
	
	return 1;
}


function getHoliday($data) {
	global $_db;
	
	//startdate dan end date dalam bentuk yyyy-mm-dd
	
	$currentyear = new DateTime();
	$currentyear = $currentyear->format('Y');
	
	$startdate = isset($data['startdate']) && !empty($data['startdate'])? DateTime::createFromFormat('Y-m-d',$data['startdate'] )->format('Y-m-d'):DateTime::createFromFormat('Y-m-d',$currentyear."-01-01")->format('Y-m-d');
	$enddate = isset($data['enddate']) && !empty($data['enddate'])? DateTime::createFromFormat('Y-m-d', $data['enddate'] )->format('Y-m-d'):DateTime::createFromFormat('Y-m-d', $currentyear."-12-31")->format('Y-m-d');
	//die("select * from " . DB_PREFIX_APP . "holiday where holiday between '".$startdate."' and '".$enddate."'");
	$holiday_query= $_db->query("select * from " . DB_PREFIX_APP . "holiday where holiday between '".$startdate."' and '".$enddate."'");
	
	// $ret['startdate']= $startdate;
	// $ret['enddate']=$enddate;
	if ($holiday_query->num_rows) { 
		return $holiday_query->rows;
	} else {
		return false;
	}
}


function getHolidayTotal($data) {
	global $_db;
	
	//startdate dan end date dalam bentuk yyyy-mm-dd
	
	$currentyear = new DateTime();
	$currentyear = $currentyear->format('Y');
	$startdate = isset($data['startdate']) && !empty($data['startdate'])? DateTime::createFromFormat('Y-m-d', $currentyear."-01-01")->format('Y-m-d'):DateTime::createFromFormat('Y-m-d', $data['startdate'])->format('Y-m-d');
	$enddate = isset($data['enddate']) && !empty($data['enddate'])? DateTime::createFromFormat('Y-m-d', $currentyear."-12-31")->format('Y-m-d'):DateTime::createFromFormat('Y-m-d', $data['enddate'])->format('Y-m-d');
	// die("select * from " . DB_PREFIX_APP . "holiday where holiday between '".$startdate."' and '".$enddate."'");
	$holiday_query= $_db->query("select count(dayid) as total from " . DB_PREFIX_APP . "holiday where holiday between '".$startdate."' and '".$enddate."'");
	
	$ret['startdate']= $startdate;
	$ret['enddate']=$enddate;
	
	if ($holiday_query->num_rows) { 
		return $holiday_query->row['total'];
	} else {
		return false;
	}
}



function checkCheckIn($data){
	global $_db, $_user;
	
	$currentuserid = $_user->getId();
	$taskin = DateTime::createFromFormat('H:i:s', $data['start_hour'].":".$data['start_min'].":00")->format('H:i:s');
	$taskout = DateTime::createFromFormat('H:i:s', $data['end_hour'].":".$data['end_min'].":00")->format('H:i:s');
    
	$message = "";
	$check_query = $_db->query("SELECT l.tanggal,l.userid,l.name,getCheckIn(l.userid,l.tanggal) AS checkin,getCheckOut(l.userid,l.tanggal) AS checkout FROM 
	(
		SELECT DISTINCT d._date AS tanggal,c.userid,s.name FROM (SELECT DATE('".$_db->escape(date("Y-m-d H:i:s", strtotime($data['taskdate'])))."') AS _date) d LEFT JOIN am_ts_checkinout c ON d._date = DATE(c.checktime)
		LEFT JOIN amts_v_userschedule s ON c.userid = s.userid
	) AS l 
	where l.userid = ".$currentuserid."  LIMIT 1");
	
	if ($check_query->num_rows) { 
		if (is_null($check_query->row['checkin'])){ 
			$message .= "Check in data is not available.";
		}else{
			if ($taskin < DateTime::createFromFormat('Y-m-d H:i:s', $check_query->row['checkin'])->format('H:i:s'))
			{
				$message .= "Task start time is before check in.";
			}
		}
		
		if ($message=="")
		{
			return "";
		}
	} else {
		$message .= "Checkin data is not available";
	}
	return $message;	
}

function checkCheckInAdmin($data){
	global $_db, $_user;
	
	$currentuserid = $_user->getId();
	$taskin = DateTime::createFromFormat('H:i:s', $data['start_hour'].":".$data['start_min'].":00")->format('H:i:s');
	$taskout = DateTime::createFromFormat('H:i:s', $data['end_hour'].":".$data['end_min'].":00")->format('H:i:s');
    
	$message = "";
	$check_query = $_db->query("SELECT l.tanggal,l.userid,l.name,getCheckIn(l.userid,l.tanggal) AS checkin,getCheckOut(l.userid,l.tanggal) AS checkout FROM 
	(
		SELECT DISTINCT d._date AS tanggal,c.userid,s.name FROM (SELECT DATE('".$_db->escape(date("Y-m-d H:i:s", strtotime($data['taskdate'])))."') AS _date) d LEFT JOIN am_ts_checkinout c ON d._date = DATE(c.checktime)
		LEFT JOIN amts_v_userschedule s ON c.userid = s.userid
	) AS l 
	where l.userid = ".$_db->escape($data['employeeid'])."  LIMIT 1");
	
	if ($check_query->num_rows) { 
		if (is_null($check_query->row['checkin'])){ 
			$message .= "Check in data is not available.";
		}else{
			if ($taskin < DateTime::createFromFormat('Y-m-d H:i:s', $check_query->row['checkin'])->format('H:i:s'))
			{
				$message .= "Task start time is before check in.";
			}
		}
		
		if ($message=="")
		{
			return "";
		}
	} else {
		$message .= "Checkin data is not available";
	}
	return $message;	
}


function checkCheckOut($data){
	global $_db, $_user;
	//print_r($data);
	
	$currentuserid = $_user->getId();
	$taskin = DateTime::createFromFormat('Y-m-d H:i:s', $data['taskdate']." ".$data['start_hour'].":".$data['start_min'].":00")->format('H:i:s');
	$taskout = DateTime::createFromFormat('Y-m-d H:i:s', $data['taskdate']." ".$data['end_hour'].":".$data['end_min'].":00")->format('H:i:s');
    
	$message = "";
	$check_query = $_db->query("SELECT l.tanggal,l.userid,l.name,getCheckIn(l.userid,l.tanggal) AS checkin,getCheckOut(l.userid,l.tanggal) AS checkout, now() as _now FROM 
	(
		SELECT DISTINCT d._date AS tanggal,c.userid,s.name FROM (SELECT DATE('".$_db->escape(date("Y-m-d H:i:s", strtotime($data['taskdate'])))."') AS _date) d LEFT JOIN am_ts_checkinout c ON d._date = DATE(c.checktime)
		LEFT JOIN amts_v_userschedule s ON c.userid = s.userid
	) AS l 
	where l.userid = ".$currentuserid."  LIMIT 1");

	$now =new  DateTime('now');
	
	
	if ($check_query->num_rows) { 
		if (is_null($check_query->row['checkin'])){ 
			$message .= "Check in data is not available.";
		}else{
			if (is_null($check_query->row['checkout'])){
				if ($taskout>$now)
				{
					$message .= "Task end time cannot overlap current time.";
				}
			}
			else
			{
				if ($taskout > DateTime::createFromFormat('Y-m-d H:i:s', $check_query->row['checkout'])->format('H:i:s'))
				{
					$message .= "Task end time is after check out.";
				}					
			}
			
		}
		
		if ($message=="")
		{
			return "";
		}
	} else {
		$message .= "Check data is not available";
	}
	return $message;	
}

function checkCheckOutAdmin($data){
	global $_db, $_user;
	//print_r($data);
	
	$currentuserid = $_user->getId();
	$taskin = DateTime::createFromFormat('Y-m-d H:i:s', $data['taskdate']." ".$data['start_hour'].":".$data['start_min'].":00")->format('H:i:s');
	$taskout = DateTime::createFromFormat('Y-m-d H:i:s', $data['taskdate']." ".$data['end_hour'].":".$data['end_min'].":00")->format('H:i:s');
    
	$message = "";
	$check_query = $_db->query("SELECT l.tanggal,l.userid,l.name,getCheckIn(l.userid,l.tanggal) AS checkin,getCheckOut(l.userid,l.tanggal) AS checkout, now() as _now FROM 
	(
		SELECT DISTINCT d._date AS tanggal,c.userid,s.name FROM (SELECT DATE('".$_db->escape(date("Y-m-d H:i:s", strtotime($data['taskdate'])))."') AS _date) d LEFT JOIN am_ts_checkinout c ON d._date = DATE(c.checktime)
		LEFT JOIN amts_v_userschedule s ON c.userid = s.userid
	) AS l 
	where l.userid = ".$_db->escape($data['employeeid'])."  LIMIT 1");

	$now =new  DateTime('now');
	
	
	if ($check_query->num_rows) { 
		if (is_null($check_query->row['checkin'])){ 
			$message .= "Check in data is not available.";
		}else{
			if (is_null($check_query->row['checkout'])){
				if ($taskout>$now)
				{
					$message .= "Task end time cannot overlap current time.";
				}
			}
			else
			{
				if ($taskout > DateTime::createFromFormat('Y-m-d H:i:s', $check_query->row['checkout'])->format('H:i:s'))
				{
					$message .= "Task end time is after check out.";
				}					
			}
			
		}
		
		if ($message=="")
		{
			return "";
		}
	} else {
		$message .= "Check data is not available";
	}
	return $message;	
}



function getMyTaskListDateView($data){
	global $_db, $_config, $_user;
	
	// Filters 
	$filter_activity = isset($data['filter_activity']) && !empty($data['filter_activity'])? ' WHERE tasklist.name LIKE \'%'.$_db->escape($data['filter_activity']).'%\' OR tasklist.description LIKE \'%'.$_db->escape($data['filter_activity']).'%\' ' : '';
	
	// Paging
	$page = isset($data['page']) && !empty($data['page'])? (int)$data['page'] : 1;
	$max = (int) $_config->get('items_per_page')>0? (int) $_config->get('items_per_page') : 20;
	$limit = "  LIMIT ".(($page-1)*$max).", ".$max;
	
	//get user id
	$currentuserid = $_user->getId();

	// Main Query
	$user_query = $_db->query("	 select * from (
	SELECT a.activityid,a.phaseid,
	(select IFNULL(ph.name,'') from " . DB_PREFIX_APP . "phase ph where a.phaseid = ph.phaseid and ph.projectid = a.projectid) as phasename,
	a.projectid,(select IFNULL(pr.name,'') from " . DB_PREFIX_APP . "project pr where pr.projectid=a.projectid) as projectname,
	(select IFNULL(pr.projectnumber,'') from " . DB_PREFIX_APP . "project pr where pr.projectid=a.projectid) as projectnumber,
	a.name,a.description,a.schedulestart,a.scheduleend,a.multipletask,
	(select IFNULL(ph.estimatedmanhour,0) from " . DB_PREFIX_APP . "phase ph where a.phaseid = ph.phaseid and ph.projectid = a.projectid) as estimated,
		(SELECT IFNULL(SUM(TIMESTAMPDIFF(HOUR,STR_TO_DATE(CONCAT(t.startdate, ' ', t.timefrom),'%Y-%m-%d %H:%i:%s'),
		STR_TO_DATE(CONCAT(t.startdate, ' ', IFNULL(t.timeto,NOW())), '%Y-%m-%d %H:%i:%s'))),0) 
		FROM " . DB_PREFIX_APP . "task t WHERE t.activityid = a.activityid) AS actualhour
	FROM " . DB_PREFIX_APP . "activity a WHERE a.projectid IN 
	(
		SELECT DISTINCT p.projectid FROM (
			SELECT p1.projectID FROM amtsproject p1
			WHERE p1.projectID IN (SELECT pm.projectID FROM " . DB_PREFIX_APP . "projectmembers pm WHERE pm.memberID = '". $currentuserid ."') 
			AND p1.status < 3
			UNION
			SELECT p2.projectid FROM " . DB_PREFIX_APP . "project p2
			WHERE p2.published = '1'
			AND p2.status < 3
		) AS p ORDER BY projectid
	)) as tasklist
		".$filter_activity.$limit);
	
	if ($user_query->num_rows) { 
		return $user_query->rows;
	} else {
		return false;
	}
}



function getMyOpenActivity($data){
	global $_db, $_config, $_user;
	
	// Filters 
	$filter_activity = isset($data['filter_activity']) && !empty($data['filter_activity'])? ' WHERE tasklist.name LIKE \'%'.$_db->escape($data['filter_activity']).'%\' OR tasklist.description LIKE \'%'.$_db->escape($data['filter_activity']).'%\' ' : '';
	
	// Paging
	$page = isset($data['page']) && !empty($data['page'])? (int)$data['page'] : 1;
	$max = (int) $_config->get('items_per_page')>0? (int) $_config->get('items_per_page') : 20;
	$limit = "  LIMIT ".(($page-1)*$max).", ".$max;
	
	//get user id
	$currentuserid = $_user->getId();

	// Main Query
	$user_query = $_db->query("	 select * from (
	SELECT a.activityid,a.phaseid,
	(select IFNULL(ph.name,'') from " . DB_PREFIX_APP . "phase ph where a.phaseid = ph.phaseid and ph.projectid = a.projectid) as phasename,
	a.projectid,(select IFNULL(pr.name,'') from " . DB_PREFIX_APP . "project pr where pr.projectid=a.projectid) as projectname,
	(select IFNULL(pr.projectnumber,'') from " . DB_PREFIX_APP . "project pr where pr.projectid=a.projectid) as projectnumber,
	a.name,a.description,a.schedulestart,a.scheduleend,a.multipletask,
	(select IFNULL(ph.estimatedmanhour,0) from " . DB_PREFIX_APP . "phase ph where a.phaseid = ph.phaseid and ph.projectid = a.projectid) as estimated,
		(SELECT IFNULL(SUM(TIMESTAMPDIFF(HOUR,STR_TO_DATE(CONCAT(t.startdate, ' ', t.timefrom),'%Y-%m-%d %H:%i:%s'),
		STR_TO_DATE(CONCAT(t.startdate, ' ', IFNULL(t.timeto,NOW())), '%Y-%m-%d %H:%i:%s'))),0) 
		FROM " . DB_PREFIX_APP . "task t WHERE t.activityid = a.activityid) AS actualhour
	FROM " . DB_PREFIX_APP . "activity a WHERE a.projectid IN 
	(
		SELECT DISTINCT p.projectid FROM (
			SELECT p1.projectID FROM amtsproject p1
			WHERE p1.projectID IN (SELECT pm.projectID FROM " . DB_PREFIX_APP . "projectmembers pm WHERE pm.memberID = '". $currentuserid ."') 
			AND p1.status < 3
			UNION
			SELECT p2.projectid FROM " . DB_PREFIX_APP . "project p2
			WHERE p2.published = '1'
			AND p2.status < 3
		) AS p ORDER BY projectid
	)) as tasklist
		".$filter_activity.$limit);
	
	if ($user_query->num_rows) { 
		return $user_query->rows;
	} else {
		return false;
	}
}


function getMyOpenActivityTotal($data){
	global $_db, $_config, $_user ; 
	
	// Filters 
	$filter_activity = isset($data['filter_activity']) && !empty($data['filter_activity'])? ' AND a.name LIKE \'%'.$_db->escape($data['filter_activity']).'%\' OR a.description LIKE \'%'.$_db->escape($data['filter_activity']).'%\' ' : '';

	//get user id
	$currentuserid = $_user->getId();
	
	// Main Query
	$opentask_query = $_db->query("SELECT COUNT(a.activityid) as total
	FROM " . DB_PREFIX_APP . "activity a WHERE a.projectid IN 
	(
		SELECT DISTINCT p.projectid FROM (
			SELECT p1.projectID FROM amtsproject p1
			WHERE p1.projectID IN (SELECT pm.projectID FROM " . DB_PREFIX_APP . "projectmembers pm WHERE pm.memberID = '". $currentuserid ."') 
			AND p1.status < 3
			UNION
			SELECT p2.projectid FROM " . DB_PREFIX_APP . "project p2
			WHERE p2.published = '1'
			AND p2.status < 3
		) AS p ORDER BY projectid
	) ".$filter_activity);
	
	if ($opentask_query->num_rows) { 
		return $opentask_query->row['total'];
	} else {
		return 0;
	}
}


function getActivityById($data){
	global $_db, $_config, $_user ; 

	//get user id
	$currentuserid = $_user->getId();

	// Main Query
	$activity_query = $_db->query("	 select * from (
	SELECT a.activityid,a.phaseid,
	(select IFNULL(ph.name,'') from " . DB_PREFIX_APP . "phase ph where a.phaseid = ph.phaseid and ph.projectid = a.projectid) as phasename,
	a.projectid,(select IFNULL(pr.name,'') from " . DB_PREFIX_APP . "project pr where pr.projectid=a.projectid) as projectname,
	(select IFNULL(pr.projectnumber,'') from " . DB_PREFIX_APP . "project pr where pr.projectid=a.projectid) as projectnumber,
	a.name,a.description,a.schedulestart,a.scheduleend,a.multipletask,
	(select IFNULL(ph.estimatedmanhour,0) from " . DB_PREFIX_APP . "phase ph where a.phaseid = ph.phaseid and ph.projectid = a.projectid) as estimated,
		(SELECT IFNULL(SUM(TIMESTAMPDIFF(HOUR,STR_TO_DATE(CONCAT(t.startdate, ' ', t.timefrom),'%Y-%m-%d %H:%i:%s'),
		STR_TO_DATE(CONCAT(t.startdate, ' ', IFNULL(t.timeto,NOW())), '%Y-%m-%d %H:%i:%s'))),0) 
		FROM " . DB_PREFIX_APP . "task t WHERE t.activityid = a.activityid) AS actualhour
	FROM " . DB_PREFIX_APP . "activity a 	WHERE a.activityid = ".$_db->escape($data['activity']).") 
	AS tasklist");

	if ($activity_query->num_rows) { 
		return $activity_query->row;
	} else {
		return false;
	}
	
}

function addTaskAdmin($data) {
	global $_db, $_user;
	
	$username = $_user->getUsername();
	$userid =$_user->getId();
	// die("INSERT INTO " . DB_PREFIX_APP . "task SELECT MAX(TaskId)+1, 
		// '".$_db->escape($data['activity_id'])."', 
		// '".$_db->escape($data['taskdate'])."',
		// '".$_db->escape(date("Y-m-d H:m:s", mktime($data['start_hour'], $data['start_min'], 0, 0, 0, 0)))."',   
		// '".$_db->escape(date("Y-m-d H:m:s", mktime($data['end_hour'], $data['end_min'], 0, 0, 0, 0)))."',   
		// '".$_db->escape($data['task_result'])."', 
		// '".$_db->escape($userid)."', 
		// '1', 
		// '".$_db->escape($userid)."',   
		// '".date('Y-m-d H:m:s')."' FROM " . DB_PREFIX_APP . "task ");
	$task_query = 
		$_db->query("INSERT INTO " . DB_PREFIX_APP . "task SELECT MAX(TaskId)+1, 
		'".$_db->escape($data['activity_id'])."', 
		'".$_db->escape($data['taskdate'])."',
		'".$_db->escape(date("Y-m-d H:i:s", mktime($data['start_hour'], $data['start_min'], 0, 0, 0, 0)))."',   
		'".$_db->escape(date("Y-m-d H:i:s", mktime($data['end_hour'], $data['end_min'], 0, 0, 0, 0)))."',   
		'".$_db->escape($data['task_result'])."', 
		'".$_db->escape($data['employeeid'])."', 
		'3', 
		'".$_db->escape($userid)."',   
		'".date('Y-m-d H:i:s')."' FROM " . DB_PREFIX_APP . "task ");
	
	return 1;
}


function addTask($data) {
	global $_db, $_user;
	
	$username = $_user->getUsername();
	$userid =$_user->getId();
	// die("INSERT INTO " . DB_PREFIX_APP . "task SELECT MAX(TaskId)+1, 
		// '".$_db->escape($data['activity_id'])."', 
		// '".$_db->escape($data['taskdate'])."',
		// '".$_db->escape(date("Y-m-d H:m:s", mktime($data['start_hour'], $data['start_min'], 0, 0, 0, 0)))."',   
		// '".$_db->escape(date("Y-m-d H:m:s", mktime($data['end_hour'], $data['end_min'], 0, 0, 0, 0)))."',   
		// '".$_db->escape($data['task_result'])."', 
		// '".$_db->escape($userid)."', 
		// '1', 
		// '".$_db->escape($userid)."',   
		// '".date('Y-m-d H:m:s')."' FROM " . DB_PREFIX_APP . "task ");
	$task_query = 
		$_db->query("INSERT INTO " . DB_PREFIX_APP . "task SELECT MAX(TaskId)+1, 
		'".$_db->escape($data['activity_id'])."', 
		'".$_db->escape($data['taskdate'])."',
		'".$_db->escape(date("Y-m-d H:i:s", mktime($data['start_hour'], $data['start_min'], 0, 0, 0, 0)))."',   
		'".$_db->escape(date("Y-m-d H:i:s", mktime($data['end_hour'], $data['end_min'], 0, 0, 0, 0)))."',   
		'".$_db->escape($data['task_result'])."', 
		'".$_db->escape($userid)."', 
		'3', 
		'".$_db->escape($userid)."',   
		'".date('Y-m-d H:i:s')."' FROM " . DB_PREFIX_APP . "task ");
	
	return 1;
}

function getMyTaskList($data){
	global $_db, $_config, $_user;
	
	// Filters 
	$filter_task1 = isset($data['filter_task']) && !empty($data['filter_task'])? ' where xxx.projectname LIKE \'%'.$_db->escape($data['filter_task']).'%\' OR xxx.projectnumber LIKE \'%'.$_db->escape($data['filter_task']).'%\' OR xxx.phasename LIKE \'%'.$_db->escape($data['filter_task']).'%\' OR xxx.activityname LIKE \'%'.$_db->escape($data['filter_task']).'%\' ' : '';
	
	$filter_task2 = isset($data['filter_task']) && !empty($data['filter_task'])? ' where xax.projectname LIKE \'%'.$_db->escape($data['filter_task']).'%\' OR xax.projectnumber LIKE \'%'.$_db->escape($data['filter_task']).'%\' OR xax.phasename LIKE \'%'.$_db->escape($data['filter_task']).'%\' OR xax.activityname LIKE \'%'.$_db->escape($data['filter_task']).'%\' ' : '';

	
	// Paging
	$page = isset($data['page']) && !empty($data['page'])? (int)$data['page'] : 1;
	$max = (int) $_config->get('items_per_page')>0? (int) $_config->get('items_per_page') : 20;
	$limit = "  LIMIT ".(($page-1)*$max).", ".$max;
	
	//get user id
	$currentuserid = $_user->getId();

		// Main Query
	$tasklist_query = $_db->query("SELECT * FROM 
	(
		SELECT xxx.* FROM (SELECT 1 AS tag, p.phaseid,p.projectid,t.taskid, t.activityid, IFNULL(t.startdate,'') AS startdate, 
		IFNULL(t.timefrom,'') AS timefrom,IFNULL(t.timeto,'') AS timeto, t.result, t.employeeid, 
		t.status, IFNULL(t.vcrea,'') AS vcrea,IFNULL(t.dcrea,'') AS dcrea, 
		(SELECT NAME FROM " . DB_PREFIX_APP . "project j WHERE j.projectid =p.projectid )AS projectname, 
		(SELECT projectnumber FROM " . DB_PREFIX_APP . "project j WHERE j.projectid = p.projectid)AS projectnumber, 
		(SELECT NAME FROM " . DB_PREFIX_APP . "phase j WHERE j.phaseid = p.phaseid AND j.projectid = p.projectid)AS phasename, a.name AS activityname 
		FROM " . DB_PREFIX_APP . "task t, " . DB_PREFIX_APP . "activity a, " . DB_PREFIX_APP . "phase p 
		WHERE employeeid = '".$currentuserid."' 
		AND a.activityid = t.activityid 
		AND a.phaseid = p.phaseid 
		AND a.projectid = p.projectid 
		".$limit.") xxx ".$filter_task1."
		UNION
		SELECT DISTINCT 0 AS tag, '','','', '', IFNULL(xax.startdate,'') AS startdate, 
		'','', '', '', 
		'', '','','','','','' 
		FROM 	(SELECT 1 AS tag, p.phaseid,p.projectid,t.taskid, t.activityid, IFNULL(t.startdate,'') AS startdate, 
		IFNULL(t.timefrom,'') AS timefrom,IFNULL(t.timeto,'') AS timeto, t.result, t.employeeid, 
		t.status, IFNULL(t.vcrea,'') AS vcrea,IFNULL(t.dcrea,'') AS dcrea, 
		(SELECT NAME FROM " . DB_PREFIX_APP . "project j WHERE j.projectid =p.projectid )AS projectname, 
		(SELECT projectnumber FROM " . DB_PREFIX_APP . "project j WHERE j.projectid = p.projectid)AS projectnumber, 
		(SELECT NAME FROM " . DB_PREFIX_APP . "phase j WHERE j.phaseid = p.phaseid AND j.projectid = p.projectid)AS phasename, a.name AS activityname 
		FROM " . DB_PREFIX_APP . "task t, " . DB_PREFIX_APP . "activity a, " . DB_PREFIX_APP . "phase p 
		WHERE employeeid = '".$currentuserid."' 
		AND a.activityid = t.activityid 
		AND a.phaseid = p.phaseid 
		AND a.projectid = p.projectid 
		".$limit.") AS xax ".$filter_task2."
	) AS alltask 
	ORDER BY alltask.startdate, alltask.tag");

	if ($tasklist_query->num_rows) { 
		return $tasklist_query->rows;
	} else {
		return false;
	}
	
}

function getMyTaskListTotal($data){
	global $_db, $_config, $_user;
	
	// Filters 
	$filter_task1 = isset($data['filter_task']) && !empty($data['filter_task'])? ' where xxx.projectname LIKE \'%'.$_db->escape($data['filter_task']).'%\' OR xxx.projectnumber LIKE \'%'.$_db->escape($data['filter_task']).'%\' OR xxx.phasename LIKE \'%'.$_db->escape($data['filter_task']).'%\' OR xxx.activityname LIKE \'%'.$_db->escape($data['filter_task']).'%\' ' : '';
	
	//get user id
	$currentuserid = $_user->getId();

		// Main Query
	$tasklist_query = $_db->query("		SELECT count(xxx.taskid) as total FROM (SELECT 1 AS tag, p.phaseid,p.projectid,t.taskid, t.activityid, IFNULL(t.startdate,'') AS startdate, 
		IFNULL(t.timefrom,'') AS timefrom,IFNULL(t.timeto,'') AS timeto, t.result, t.employeeid, 
		t.status, IFNULL(t.vcrea,'') AS vcrea,IFNULL(t.dcrea,'') AS dcrea, 
		(SELECT NAME FROM " . DB_PREFIX_APP . "project j WHERE j.projectid =p.projectid )AS projectname, 
		(SELECT projectnumber FROM " . DB_PREFIX_APP . "project j WHERE j.projectid = p.projectid)AS projectnumber, 
		(SELECT NAME FROM " . DB_PREFIX_APP . "phase j WHERE j.phaseid = p.phaseid AND j.projectid = p.projectid)AS phasename, a.name AS activityname 
		FROM " . DB_PREFIX_APP . "task t, " . DB_PREFIX_APP . "activity a, " . DB_PREFIX_APP . "phase p 
		WHERE employeeid = '".$currentuserid."' 
		AND a.activityid = t.activityid 
		AND a.phaseid = p.phaseid 
		AND a.projectid = p.projectid 
		) xxx ".$filter_task1);

	if ($tasklist_query->num_rows) { 
		return $tasklist_query->row['total'];
	} else {
		return 0;
	}
	
}



function getMyOpenTask($data){
	global $_db, $_config, $_user;
	
	// Filters 
	$filter_task = isset($data['filter_task']) && !empty($data['filter_task'])? ' where tasklist.projectname LIKE \'%'.$_db->escape($data['filter_task']).'%\' OR tasklist.projectnumber LIKE \'%'.$_db->escape($data['filter_task']).'%\' OR tasklist.phasename LIKE \'%'.$_db->escape($data['filter_task']).'%\' OR tasklist.activityname LIKE \'%'.$_db->escape($data['filter_task']).'%\' ' : '';
	
	// Paging
	$page = isset($data['page']) && !empty($data['page'])? (int)$data['page'] : 1;
	$max = (int) $_config->get('items_per_page')>0? (int) $_config->get('items_per_page') : 20;
	$limit = "  LIMIT ".(($page-1)*$max).", ".$max;
	
	//get user id
	$currentuserid = $_user->getId();

		// Main Query
	$opentask_query = $_db->query("select * from (
SELECT p.phaseid,p.projectid,t.taskid, t.activityid, ifnull(t.startdate,'') as startdate, ifnull(t.timefrom,'') as timefrom,ifnull(t.timeto,'') as timeto, t.result, t.employeeid, t.status, ifnull(t.vcrea,'') as vcrea,ifnull(t.dcrea,'') as dcrea,
(SELECT NAME FROM " . DB_PREFIX_APP . "project j WHERE j.projectid =p.projectid )AS projectname,
(SELECT projectnumber FROM " . DB_PREFIX_APP . "project j WHERE j.projectid = p.projectid)AS projectnumber,
(SELECT NAME FROM " . DB_PREFIX_APP . "phase j WHERE j.phaseid = p.phaseid AND j.projectid = p.projectid)AS phasename,
a.name as activityname
 FROM " . DB_PREFIX_APP . "task t, " . DB_PREFIX_APP . "activity a, " . DB_PREFIX_APP . "phase p
 WHERE employeeid = ".$currentuserid."
 AND a.activityid = t.activityid
 AND a.phaseid = p.phaseid AND a.projectid = p.projectid
 AND t.status=1 ) as tasklist
 ".$filter_task.$limit);
 
	
	if ($opentask_query->num_rows) { 
		return $opentask_query->rows;
	} else {
		return false;
	}
}

function getMyOpenTaskById($data){
	global $_db, $_config, $_user;
	
	// Filters 
	$filter_activity = isset($data['filter_activity']) && !empty($data['filter_activity'])? ' WHERE tasklist.name LIKE \'%'.$_db->escape($data['filter_activity']).'%\' OR tasklist.description LIKE \'%'.$_db->escape($data['filter_activity']).'%\' ' : '';
	
	// Paging
	$page = isset($data['page']) && !empty($data['page'])? (int)$data['page'] : 1;
	$max = (int) $_config->get('items_per_page')>0? (int) $_config->get('items_per_page') : 20;
	//$limit = "  LIMIT ".(($page-1)*$max).", ".$max;
	
	//get user id
	$currentuserid = $_user->getId();

		// Main Query
	$opentask_query = $_db->query("
SELECT p.phaseid,p.projectid,t.taskid, t.activityid, ifnull(t.startdate,'') as startdate, ifnull(t.timefrom,'') as timefrom,ifnull(t.timeto,'') as timeto, t.result, t.employeeid, t.status, ifnull(t.vcrea,'') as vcrea,ifnull(t.dcrea,'') as dcrea,
(SELECT NAME FROM " . DB_PREFIX_APP . "project j WHERE j.projectid =p.projectid )AS projectname,
(SELECT projectnumber FROM " . DB_PREFIX_APP . "project j WHERE j.projectid = p.projectid)AS projectnumber,
(SELECT NAME FROM " . DB_PREFIX_APP . "phase j WHERE j.phaseid = p.phaseid AND j.projectid = p.projectid)AS phasename,
a.name as activityname
 FROM " . DB_PREFIX_APP . "task t, " . DB_PREFIX_APP . "activity a, " . DB_PREFIX_APP . "phase p
 WHERE 1 = '".$currentuserid."'
 AND a.activityid = t.activityid
 AND a.phaseid = p.phaseid AND a.projectid = p.projectid
 AND t.status=1
 and t.taskid = ".$data['taskid']."
 ".$filter_activity." LIMIT 1");
 
	
	if ($opentask_query->num_rows) { 
		return $opentask_query->row;
	} else {
		return false;
	}
}

function getMyOpenTaskTotal($data){
	global $_db, $_config, $_user ; 
	
	// Filters 
	//$filter_activity = isset($data['filter_activity']) && !empty($data['filter_activity'])? ' AND a.name LIKE \'%'.$_db->escape($data['filter_activity']).'%\' OR a.description LIKE \'%'.$_db->escape($data['filter_activity']).'%\' ' : '';
	$filter_task = isset($data['filter_task']) && !empty($data['filter_task'])? ' where tasklist.projectname LIKE \'%'.$_db->escape($data['filter_task']).'%\' OR tasklist.projectnumber LIKE \'%'.$_db->escape($data['filter_task']).'%\' OR tasklist.phasename LIKE \'%'.$_db->escape($data['filter_task']).'%\' OR tasklist.activityname LIKE \'%'.$_db->escape($data['filter_task']).'%\' ' : '';

	//get user id
	$currentuserid = $_user->getId();
	
	// Main Query
	$opentask_query = $_db->query("
	select count(taskid) as total from (
SELECT p.phaseid,p.projectid,t.taskid, t.activityid, ifnull(t.startdate,'') as startdate, ifnull(t.timefrom,'') as timefrom,ifnull(t.timeto,'') as timeto, t.result, t.employeeid, t.status, ifnull(t.vcrea,'') as vcrea,ifnull(t.dcrea,'') as dcrea,
(SELECT NAME FROM " . DB_PREFIX_APP . "project j WHERE j.projectid =p.projectid )AS projectname,
(SELECT projectnumber FROM " . DB_PREFIX_APP . "project j WHERE j.projectid = p.projectid)AS projectnumber,
(SELECT NAME FROM " . DB_PREFIX_APP . "phase j WHERE j.phaseid = p.phaseid AND j.projectid = p.projectid)AS phasename,
a.name as activityname
 FROM " . DB_PREFIX_APP . "task t, " . DB_PREFIX_APP . "activity a, " . DB_PREFIX_APP . "phase p
 WHERE employeeid = ".$currentuserid."
 AND a.activityid = t.activityid
 AND a.phaseid = p.phaseid AND a.projectid = p.projectid
 AND t.status=1 ) as tasklist
		 ".$filter_task);
	

	
	if ($opentask_query->num_rows) { 
		return $opentask_query->row['total'];
	} else {
		return 0;
	}
}

function stopTask($data) {
	global $_db, $_user;

	$username = $_user->getUsername();
	$userid =$_user->getId();
	
	$task_exist_query = $_db->query("SELECT COUNT(taskid) AS ada FROM " . DB_PREFIX_APP . "task WHERE taskid = ".$_db->escape($data['task_id'])." AND employeeid = ".$_db->escape($userid)."");
	$isExist = false;
	
	if ($task_exist_query->num_rows){
		if ($task_exist_query->row['ada']=='1'){
			$isExist = true;
		}
	}
	
	
	if ($isExist)
	{
		//die("die here");
		$task_update_query = $_db->query("UPDATE " . DB_PREFIX_APP . "task SET timeto = '".$_db->escape(date("Y-m-d H:i:s", mktime($data['end_hour'], $data['end_min'], 0, 0, 0, 0)))."',
		result = '".$_db->escape($data['task_result'])."', status = 3 WHERE taskid = ".$_db->escape($data['task_id'])." AND employeeid = ".$_db->escape($userid)."");
		return 1;
	}
	else
	{
		return 0;
	}
	
}

?>