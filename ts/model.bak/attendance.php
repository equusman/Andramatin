<?php
function getAttendance($data){
	global $_db, $_config;
	
	$currentmonth = new DateTime('today');
	//$currentmonth = $currentmonth->format('Y-m-d');
	
	$startdate = isset($data['startdate']) && !empty($data['startdate'])? DateTime::createFromFormat('Y-m-d H:i:s',$data['startdate']." 01:00:00" )->format('Y-m-d H:i:s'):$currentmonth->sub(new DateInterval('P30D'))->format('Y-m-d H:i:s');
	$enddate = isset($data['enddate']) && !empty($data['enddate'])? DateTime::createFromFormat('Y-m-d H:i:s', $data['enddate']." 23:59:59" )->format('Y-m-d H:i:s'):$currentmonth->format('Y-m-d H:i:s') ;
	
	//die("currentmonth : ".$currentmonth->format('Y-m-d H:i:s')." |start: ".$startdate." |end: ".$enddate);
	
	// Filters 
	$filter = isset($data['filter']) && !empty($data['filter'])? ' WHERE tanggal LIKE \'%'.$_db->escape($data['filter']).'%\' OR name LIKE \'%'.$_db->escape($data['filter']).'%\' ' : '';
	
	// Paging
	$page = isset($data['page']) && !empty($data['page'])? (int)$data['page'] : 1;
	$max = (int) $_config->get('items_per_page')>0? (int) $_config->get('items_per_page') : 20;
	$limit = "  LIMIT ".(($page-1)*$max).", ".$max;
	//die ("CALL p_datebetween('".$startdate." 08:00:00', '".$enddate." 08:00:00', 'DAY', 1);");
	$proc = $_db->query("CALL p_datebetween('".$startdate." 08:00:00', '".$enddate." 08:00:00', 'DAY', 1);");
	//die("CALL p_datebetween('".$startdate." 08:00:00', '".$enddate." 08:00:00', 'DAY', 1);");
	// Main Query
	 //die("SELECT l.tanggal,l.userid,l.name,getCheckIn(l.userid,l.tanggal) AS checkin,getCheckOut(l.userid,l.tanggal) AS checkout FROM 
				// (
					// SELECT DISTINCT d._date AS tanggal,c.userid,s.name FROM t_datebetween d LEFT JOIN " . DB_PREFIX_ABSEN . "checkinout c ON d._date = DATE(c.checktime)
					// LEFT JOIN " . DB_PREFIX_APP . "_v_userschedule s ON c.userid = s.userid
				// ) AS l ".$filter.$limit);
	$user_query = $_db->query("SELECT l.tanggal,l.userid,l.name,getCheckIn(l.userid,l.tanggal) AS checkin,getCheckOut(l.userid,l.tanggal) AS checkout FROM 
				(
					SELECT DISTINCT d._date AS tanggal,c.userid,s.name FROM t_datebetween d LEFT JOIN " . DB_PREFIX_ABSEN . "checkinout c ON d._date = DATE(c.checktime)
					LEFT JOIN " . DB_PREFIX_APP . "_v_userschedule s ON c.userid = s.userid
				) AS l ");
	
	if ($user_query->num_rows) { 
		return $user_query->rows;
	} else {
		return false;
	}
}

function getAttendanceTotal($data){
	global $_db;
	
	// Main Query
	// $user_query = $_db->query("SELECT * FROM " . DB_PREFIX_APP . "activity ac WHERE ac.projectid = '".(int) $id['project']."' AND ac.phaseid = '".(int) $id['phase']."'");
	
	// if ($user_query->num_rows) { 
		// return $user_query->rows;
	// } else {
		// return false;
	// }
}

function addUserCheckInOut($data) {
	global $_db, $_user;
	
	$now = new DateTime();
	$username = $_user->getUsername();
	$userid =$_user->getId();
	$check_query = 
		$_db->query("INSERT INTO " . DB_PREFIX_ABSEN . "checkinout (userid,checktime,checktype,verifycode,sensorid,workcode,sn) VALUES ( 
		'".$_db->escape($data['employeeid'])."', 
		'".$_db->escape( $data['checkdate']." ".$data['checkhour'].":".$data['checkmin'].":00")."',
		'".$_db->escape($data['checktype'])."',   
		'".$_db->escape($userid)."',   
		'X', 
		'0', 
		'".$_db->escape($now->format('Y-m-d H:i:s'))."' )");
	
	return 1;


}




?>