<?php
function reportAttendance($data){
	global $_db, $_config;
	
	
	$today = new DateTime();
	// Filters 
	$tanggalakhir = isset($data['tanggalakhir']) && !empty($data['tanggalakhir'])? $data['tanggalakhir'] : $today->format('Y-m-d');
	$tanggalawal = isset($data['tanggalawal']) && !empty($data['tanggalawal'])? $data['tanggalawal'] : $today->sub(new DateInterval('P30D'))->format('Y-m-d');
	// $filter_username = isset($data['filter_username']) && !empty($data['filter_username'])? ' WHERE vusername LIKE \'%'.$_db->escape($data['filter_username']).'%\' OR vdisplayname LIKE \'%'.$_db->escape($data['filter_username']).'%\' ' : '';
	//die($tanggalawal." | ".$tanggalakhir);
	// Paging
	// $page = isset($data['page']) && !empty($data['page'])? (int)$data['page'] : 1;
	// $max = (int) $_config->get('items_per_page')>0? (int) $_config->get('items_per_page') : 20;
	// $limit = "  LIMIT ".(($page-1)*$max).", ".$max;
	
	// Main Query
	$report_query = $_db->query("SELECT DISTINCT dateloop.dateloop AS tanggal,c.checktime,c.userid,s.name,s.starttime,s.endtime, s.checkintime1,s.checkintime2,s.checkouttime1,s.checkouttime2 FROM  (
	SELECT CAST((SYSDATE()-INTERVAL (H+T+U) DAY) AS DATE) dateloop 
	FROM 
	(  SELECT 0 H
	    UNION ALL SELECT 100 UNION ALL SELECT 200 UNION ALL SELECT 300
	    UNION ALL SELECT 400 UNION ALL SELECT 500 UNION ALL SELECT 600
	) H CROSS JOIN  
	( SELECT 0 T
	    UNION ALL SELECT  10 UNION ALL SELECT  20 UNION ALL SELECT  30
	    UNION ALL SELECT  40 UNION ALL SELECT  50 UNION ALL SELECT  60
	    UNION ALL SELECT  70 UNION ALL SELECT  80 UNION ALL SELECT  90  
	) T  CROSS JOIN
	( SELECT 0 U
	    UNION ALL SELECT   1 UNION ALL SELECT   2 UNION ALL SELECT   3
	    UNION ALL SELECT   4 UNION ALL SELECT   5 UNION ALL SELECT   6
	    UNION ALL SELECT   7 UNION ALL SELECT   8 UNION ALL SELECT   9 
	) U    
	WHERE H+T+U <= DATEDIFF(SYSDATE(),'".$_db->escape($tanggalawal)."')  /*tanggal awal*/
	AND H+T+U >= DATEDIFF(SYSDATE(),'".$_db->escape($tanggalakhir)."')  /*tanggal akhir*/
	ORDER BY H+T+U DESC
) dateloop LEFT JOIN am_ts_checkinout c ON dateloop.dateloop = DATE(c.checktime)
LEFT JOIN amts_v_userschedule s ON c.userid = s.userid
ORDER BY tanggal,userid,checktime;");
	
	// ("SELECT vuserid, vusername, vdisplayname, IF (SYSDATE() BETWEEN dbegineff AND dlasteff, 'active','inactive') AS `status` FROM " . DB_PREFIX . "mstusers ".$filter_username.$limit);
	
	if ($report_query->num_rows) { 
		return $report_query->rows;
	} else {
		return false;
	}
}

function reportAttendanceDateVsUser($data){
	global $_db, $_config;
	
	
	$today = new DateTime();
	// Filters 
	$tanggalakhir = isset($data['tanggalakhir']) && !empty($data['tanggalakhir'])? $data['tanggalakhir'] : $today->format('Y-m-d');
	$tanggalawal = isset($data['tanggalawal']) && !empty($data['tanggalawal'])? $data['tanggalawal'] : $today->sub(new DateInterval('P30D'))->format('Y-m-d');
	// $filter_username = isset($data['filter_username']) && !empty($data['filter_username'])? ' WHERE vusername LIKE \'%'.$_db->escape($data['filter_username']).'%\' OR vdisplayname LIKE \'%'.$_db->escape($data['filter_username']).'%\' ' : '';
	//die($tanggalawal." | ".$tanggalakhir);
	// Paging
	// $page = isset($data['page']) && !empty($data['page'])? (int)$data['page'] : 1;
	// $max = (int) $_config->get('items_per_page')>0? (int) $_config->get('items_per_page') : 20;
	// $limit = "  LIMIT ".(($page-1)*$max).", ".$max;
	
	// Main Query
	$report_query = $_db->query("SELECT DISTINCT dateloop.dateloop AS tanggal,c.userid,s.name,s.starttime,s.endtime, s.checkintime1,s.checkintime2,s.checkouttime1,s.checkouttime2 FROM  (
	SELECT CAST((SYSDATE()-INTERVAL (H+T+U) DAY) AS DATE) dateloop 
	FROM 
	(  SELECT 0 H
	    UNION ALL SELECT 100 UNION ALL SELECT 200 UNION ALL SELECT 300
	    UNION ALL SELECT 400 UNION ALL SELECT 500 UNION ALL SELECT 600
	) H CROSS JOIN  
	( SELECT 0 T
	    UNION ALL SELECT  10 UNION ALL SELECT  20 UNION ALL SELECT  30
	    UNION ALL SELECT  40 UNION ALL SELECT  50 UNION ALL SELECT  60
	    UNION ALL SELECT  70 UNION ALL SELECT  80 UNION ALL SELECT  90  
	) T  CROSS JOIN
	( SELECT 0 U
	    UNION ALL SELECT   1 UNION ALL SELECT   2 UNION ALL SELECT   3
	    UNION ALL SELECT   4 UNION ALL SELECT   5 UNION ALL SELECT   6
	    UNION ALL SELECT   7 UNION ALL SELECT   8 UNION ALL SELECT   9 
	) U    
	WHERE H+T+U <= DATEDIFF(SYSDATE(),'".$_db->escape($tanggalawal)."')  /*tanggal awal*/
	AND H+T+U >= DATEDIFF(SYSDATE(),'".$_db->escape($tanggalakhir)."')  /*tanggal akhir*/
	ORDER BY H+T+U DESC
) dateloop LEFT JOIN am_ts_checkinout c ON dateloop.dateloop = DATE(c.checktime)
LEFT JOIN amts_v_userschedule s ON c.userid = s.userid
ORDER BY tanggal,userid;");
	
	// ("SELECT vuserid, vusername, vdisplayname, IF (SYSDATE() BETWEEN dbegineff AND dlasteff, 'active','inactive') AS `status` FROM " . DB_PREFIX . "mstusers ".$filter_username.$limit);
	
	if ($report_query->num_rows) { 
		return $report_query->rows;
	} else {
		return false;
	}
}

function reportAttendanceSummary($data){
	global $_db, $_config;

	$today = new DateTime();
	// Filters 
	$tanggalakhir = isset($data['tanggalakhir']) && !empty($data['tanggalakhir'])? $data['tanggalakhir'] : $today->format('Y-m-d');
	$tanggalawal = isset($data['tanggalawal']) && !empty($data['tanggalawal'])? $data['tanggalawal'] : $today->sub(new DateInterval('P30D'))->format('Y-m-d');
	$filter = isset($data['filter']) && !empty($data['filter'])? "AND (dateschedule.userid LIKE '%".$data['filter']."%' OR dateschedule.name LIKE '%".$data['filter']."%')"    : ""; 

	$report_query = $_db->query("SELECT dateschedule.tanggal, dateschedule.userid,dateschedule.badgenumber, dateschedule.name,dateschedule.starttime,dateschedule.endtime, 
getcheckin(dateschedule.userid,dateschedule.tanggal) AS getcheckin,
(
	SELECT MAX(cio.checktime) FROM am_ts_checkinout cio 
	WHERE DATE(cio.checktime) = dateschedule.tanggal 
	AND dateschedule.userid = cio.userid
	AND cio.checktime BETWEEN dateschedule.checkintime1 AND dateschedule.checkintime2
) AS checkin,
getcheckout(dateschedule.userid,dateschedule.tanggal) AS getcheckout,
(
	SELECT MIN(cio.checktime) FROM am_ts_checkinout cio 
	WHERE DATE(cio.checktime) = dateschedule.tanggal 
	AND dateschedule.userid = cio.userid
	AND cio.checktime BETWEEN dateschedule.checkouttime1 AND dateschedule.nextcheckintime1
) AS checkout
FROM (
	SELECT DISTINCT dateloop.dateloop AS tanggal,
	c.userid,
	s.name,
	s.badgenumber,
	dateloop.dateloop+ INTERVAL s.starttime HOUR_SECOND AS starttime,
	CASE 
		WHEN endtime > s.starttime THEN dateloop.dateloop+ INTERVAL s.endtime HOUR_SECOND 
		ELSE dateloop.dateloop+ INTERVAL 1 DAY + INTERVAL s.endtime HOUR_SECOND 
	END
	AS endtime,	
	dateloop.dateloop+ INTERVAL s.checkintime1 HOUR_SECOND AS checkintime1,	
	dateloop.dateloop+ INTERVAL s.checkintime2 HOUR_SECOND AS checkintime2,	
	CASE 
		WHEN s.endtime > s.starttime THEN dateloop.dateloop+ INTERVAL s.checkouttime1 HOUR_SECOND 
		ELSE dateloop.dateloop+ INTERVAL 1 DAY + INTERVAL s.checkouttime1 HOUR_SECOND 
	END
	AS checkouttime1,	
	dateloop.dateloop+ INTERVAL s.checkouttime2 HOUR_SECOND AS checkouttime2,	
	dateloop.dateloop+ INTERVAL 1 DAY+ INTERVAL s.checkintime1 HOUR_SECOND AS nextcheckintime1 
	FROM ( 
		SELECT CAST((SYSDATE()-INTERVAL (H+T+U) DAY) AS DATE) dateloop 
		FROM ( 
			SELECT 0 H UNION ALL SELECT 100 UNION ALL SELECT 200 UNION ALL SELECT 300 UNION ALL SELECT 400 UNION ALL SELECT 500 UNION ALL SELECT 600 ) H 
			CROSS JOIN ( SELECT 0 T UNION ALL SELECT 10 UNION ALL SELECT 20 UNION ALL SELECT 30 UNION ALL SELECT 40 UNION ALL SELECT 50 UNION ALL SELECT 60 UNION ALL SELECT 70 UNION ALL SELECT 80 UNION ALL SELECT 90 ) T 
			CROSS JOIN ( SELECT 0 U UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 ) U 
			WHERE H+T+U <= DATEDIFF(SYSDATE(),'".$_db->escape($tanggalawal)."') /*tanggal awal*/ 
			AND H+T+U >= DATEDIFF(SYSDATE(),'".$_db->escape($tanggalakhir)."') /*tanggal akhir*/ 
			ORDER BY H+T+U DESC 
		) dateloop 
	LEFT JOIN am_ts_checkinout c ON dateloop.dateloop = DATE(c.checktime) 
	LEFT JOIN amts_v_userschedule s ON c.userid = s.userid 
	/*order by dateloop.dateloop, s.userid;*/
) dateschedule 
WHERE 1=1
".$filter."
ORDER BY dateschedule.tanggal,dateschedule.userid");
	
	
	if ($report_query->num_rows) { 
		return $report_query->rows;
	} else {
		return false;
	}
	
	
}

function reportTimesheetSummary($data){
	global $_db, $_config;

	$today = new DateTime();
	// Filters 
	// $tanggalakhir = isset($data['tanggalakhir']) && !empty($data['tanggalakhir'])? $data['tanggalakhir'] : $today->format('Y-m-d');
	// $tanggalawal = isset($data['tanggalawal']) && !empty($data['tanggalawal'])? $data['tanggalawal'] : $today->sub(new DateInterval('P30D'))->format('Y-m-d');
	// $filter = isset($data['filter']) && !empty($data['filter'])? "AND (dateschedule.userid LIKE '%".$data['filter']."%' OR dateschedule.name LIKE '%".$data['filter']."%')"    : ""; 

	$report_query = $_db->query("SELECT ph.`PhaseID`, ph.`Name` AS PhaseName,
ta.`EmployeeID`,(SELECT v.name FROM  amts_v_userschedule v WHERE v.UserID = ta.`EmployeeID`)AS EmpName ,
SUM(TIMESTAMPDIFF(MINUTE,(ta.startdate + INTERVAL ta.timefrom HOUR_SECOND ) , ( ta.startdate + INTERVAL ta.timeto HOUR_SECOND)))  AS summinute
FROM amtsphase ph, amtsactivity ac, amtstask ta
WHERE ph.`PhaseID` = ac.`PhaseID`
AND ph.`ProjectID` = '".$_db->escape($data['project'])."'
AND ac.`ActivityID` = ta.`ActivityID`
GROUP BY ph.`PhaseID`, ta.`EmployeeID`
ORDER BY ac.`PhaseID`,ta.`ActivityID`,ta.`EmployeeID`");
	
	
	if ($report_query->num_rows) { 
		return $report_query->rows;
	} else {
		return false;
	}
	
	
}

?>