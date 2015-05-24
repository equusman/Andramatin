<?php
function getProject($data){
	global $_db, $_config;
	
	// Filters 
	$filter_username = isset($data['filter_username']) && !empty($data['filter_username'])? ' WHERE vusername LIKE \'%'.$_db->escape($data['filter_username']).'%\' OR vdisplayname LIKE \'%'.$_db->escape($data['filter_username']).'%\' ' : '';
	
	// Paging
	$page = isset($data['page']) && !empty($data['page'])? (int)$data['page'] : 1;
	$max = (int) $_config->get('items_per_page')>0? (int) $_config->get('items_per_page') : 20;
	$limit = "  LIMIT ".(($page-1)*$max).", ".$max;
	
	// Main Query
	$user_query = $_db->query("SELECT vuserid, vusername, vdisplayname, IF (SYSDATE() BETWEEN dbegineff AND dlasteff, 'active','inactive') AS `status` FROM " . DB_PREFIX . "mstusers ".$filter_username.$limit);
	
	if ($user_query->num_rows) { 
		return $user_query->rows;
	} else {
		return false;
	}
}

function getProjectActivity($id){
	global $_db;
	//die("SELECT * FROM " . DB_PREFIX_APP . "activity ac WHERE ac.projectid = '".(int) $id['project']."' AND ac.phaseid = '".(int) $id['phase']."'");
	// Main Query
	$user_query = $_db->query("SELECT ActivityID,ProjectID,PhaseID,Name,Description,
ScheduleStart,ScheduleEnd,MultipleTask,(
SELECT IFNULL(SUM(TIMESTAMPDIFF(HOUR,STR_TO_DATE(CONCAT(t.startdate, ' ', t.timefrom),'%Y-%m-%d %H:%i:%s'),
STR_TO_DATE(CONCAT(t.startdate, ' ', IFNULL(t.timeto,NOW())), '%Y-%m-%d %H:%i:%s'))),0) 
FROM " . DB_PREFIX_APP . "task t WHERE t.activityid = ac.activityid) AS actualhour
FROM " . DB_PREFIX_APP . "activity ac WHERE ac.projectid = '".(int) $id['project']."' AND ac.phaseid = '".(int) $id['phase']."'
AND ac.Active = 'T'
ORDER BY ac.orderid");
		if ($user_query->num_rows) { 
		return $user_query->rows;
	} else {
		return false;
	}
}

function getProjectById($id){
	global $_db;
	// Main Query
	$user_query = $_db->query("SELECT 
		pr.projectid,pr.projectnumber,pr.name prname,
		pr.description prdesc,pr.projectmanager,pr.startdate,
		pr.estimatedmanhour,pr.status,pr.published,pr.version,pr.baseline,
		pr.contractamount,pr.idcurrency,ph.phaseid,ph.name phname,
		ph.description phdesc,ph.estimatedmanhour phestimatedmanhour,ph.status phstatus, ph.orderid phorder, ph.active phactive
		FROM " . DB_PREFIX_APP . "project pr, " . DB_PREFIX_APP . "phase ph WHERE pr.projectid = ph.projectid AND pr.projectid = '".(int) $id['project']."' AND ph.phaseid = '".(int) $id['phase']."'");
	
	if ($user_query->num_rows) { 
		return $user_query->row;
	} else {
		return false;
	}
}

function deleteProject($proj_id){
	global $_db, $_config;
	// Main Query
	$user_query = $_db->query("DELETE FROM " . DB_PREFIX_APP . "project WHERE projectid IN (".$_db->escape($proj_id).")");
	return 1;
}

function addProject($data) {
	global $_db, $_user;
	$pub = isset($data['published']) && ($data['published']=='1') ? '1' : '0';
//	die("INSERT INTO " . DB_PREFIX_APP . "project SELECT MAX(ProjectID)+1, 
		// '".$_db->escape($data['project_number'])."', 
		// '".$_db->escape($data['project_name'])."',
		// '".$_db->escape($data['project_desc'])."', 
		// '".$_db->escape($data['project_manager'])."', 
		// '".$_db->escape($data['startdate'])."', 
		// '".$_db->escape($data['enddate'])."', 
		// '".$_db->escape($data['estimatedmanhour'])."', 
		// '1', 
		// ".$pub.", 
		// '1',  
		// '1', 
		// '".$_db->escape($data['contractamount'])."', 
		// '".$_db->escape($data['currency'])."'  FROM " . DB_PREFIX_APP . "project ");
		
	$user_query = 
		$_db->query("INSERT INTO " . DB_PREFIX_APP . "project SELECT MAX(ProjectID)+1, 
		'".$_db->escape($data['project_number'])."', 
		'".$_db->escape($data['project_name'])."',
		'".$_db->escape($data['project_desc'])."', 
		'".$_db->escape($data['project_manager'])."', 
		'".$_db->escape($data['startdate'])."', 
		'".$_db->escape($data['enddate'])."', 
		'".$_db->escape($data['estimatedmanhour'])."', 
		'1', 
		".$pub.", 
		'1',  
		'1', 
		'".$_db->escape($data['contractamount'])."', 
		'".$_db->escape($data['currency'])."'  FROM " . DB_PREFIX_APP . "project ");

	$pmx_query = $_db->query("SELECT MAX(ProjectID) as mx FROM " . DB_PREFIX_APP . "project");	
	$id = (int)$pmx_query->row['mx'];	
	if (isset($data['member']) && (count($data['member'])>0)) {
		foreach($data['member'] as $item) {
			$_db->query("INSERT INTO " . DB_PREFIX_APP . "projectmembers VALUES('".$id."','".$item."') ");
		}
	}	
	
	if (isset($data['phasename']) && (count($data['phasename'])>0)) {
		for($a=0; $a<count($data['phasename']); $a++) {
			$v1 = $_db->escape($data['phasename'][$a]);
			$v2 = isset($data['phasedesc'][$a])? $_db->escape($data['phasedesc'][$a]) : '';
			$v3 = isset($data['phasemd'][$a])? $_db->escape($data['phasemd'][$a]) : '';
			$_db->query("INSERT INTO " . DB_PREFIX_APP . "phase VALUES('".($a+1)."','".$id."','".$v1."','".$v2."','".$v3."','1','".($a)."','T') ");
		}
	}	
	
	return 1;
}

function hasTask($data) {
	global $_db, $_user;

	// die("SELECT COUNT(t.taskid) AS task FROM " . DB_PREFIX_APP . "task t
// WHERE t.activityid IN (
	// SELECT ac.activityid FROM " . DB_PREFIX_APP . "activity ac
	// WHERE ac.phaseid = '".$_db->escape($data['phase_id'])."', 
	// AND ac.projectid = '".$_db->escape($data['project_id'])."', 
// )");
	
	$task_query = $_db->query("SELECT COUNT(t.taskid) AS task FROM " . DB_PREFIX_APP . "task t
WHERE t.activityid IN (
	SELECT ac.activityid FROM " . DB_PREFIX_APP . "activity ac
	WHERE ac.phaseid = '".$_db->escape($data['phase_id'])."'
	AND ac.projectid = '".$_db->escape($data['project_id'])."' 
)");

	$countask = (int)$task_query->row['task'];
	return $countask;
		

}

function editProject($data) {
	global $_db, $_user;

	// $qpasswd = '';
	// if (!empty($data['password'])) $qpasswd = " vpassword = SHA1('".$_db->escape($data['password'])."'), ";
	
	// save project header  -->OK FIX
	$pub = isset($data['published']) && ($data['published']=='1') ? '1' : '0';

	$user_query = $_db->query("UPDATE " . DB_PREFIX_APP . "project SET  
		projectnumber = '".$_db->escape($data['project_number'])."', 
		name = '".$_db->escape($data['project_name'])."', 
		description = '".$_db->escape($data['project_desc'])."', 
		projectmanager = '".$_db->escape($data['project_manager'])."', 
		estimatedmanhour = '".$_db->escape($data['estimatedmanhour'])."', 
		contractamount = '".$_db->escape($data['contractamount'])."', 
		published = '".$pub."',
		idcurrency = '".$_db->escape($data['currency'])."', 
		startdate = '".$_db->escape($data['startdate'])."', 
		enddate = '".$_db->escape($data['enddate'])."' 		
		WHERE projectid = '".(int)$data['project_id']."'");


	// select max phase from database --> CHECK LAGI PERLUNYA
		$fk1 = $_db->escape($data['project_id']);
	$maxphase = $_db->query("SELECT IFNULL(MAX(phaseid),0) as mx FROM " . DB_PREFIX_APP . "phase WHERE projectid = '". $fk1 ."'");	
	$id = (int)$maxphase->row['mx'];	

	
	// delete project member, karena akan di insert ulang semuanya --> OK FIX
	$_db->query("DELETE FROM " . DB_PREFIX_APP . "projectmembers WHERE projectid =  '". $fk1 ."' ");
	//$_db->query("DELETE FROM " . DB_PREFIX_APP . "projectmembers WHERE projectid =  '". $fk1 ."' ");  //harusnya delete phase
		
	//insert data member semuanya --> OK FIX	
	if (isset($data['member']) && (count($data['member'])>0)) {
		for($a=0; $a<count($data['member']); $a++) {
			$v1 = $_db->escape($data['member'][$a]);
			$_db->query("INSERT INTO " . DB_PREFIX_APP . "projectmembers VALUES('".$fk1."','".$v1."') ");
		}
	}			
	
	//update phase id yang di delete menjadi active = F, insert yang new
	if (isset($data['phasename']) && (count($data['phasename'])>0)) {
		$existID = "(";
		for($a=0; $a<count($data['phasename']); $a++) {
			$p1 = $_db->escape($data['phasename'][$a]);
			$p2 = $_db->escape($data['phasedesc'][$a]);
			$p3 = $_db->escape($data['phasemd'][$a]);
			$phaseid = $_db->escape($data['phaseid'][$a]);
			$projectid = $_db->escape($data['projectid'][$a]);
			$_db->query("UPDATE " . DB_PREFIX_APP . "phase SET orderid = '".$a."' WHERE phaseid = '".$phaseid."' AND projectid = '".$projectid."'");
			$existID .= "'".$phaseid."',";
			if ($data['newphase'][$a]== "1"){
				
			$maxphaseadd = $_db->query("SELECT IFNULL(MAX(phaseid),0)+1 as mx FROM " . DB_PREFIX_APP . "phase WHERE projectid = '". $fk1 ."'");	
			$idadd = (int)$maxphaseadd->row['mx'];	
				
			
				//die("INSERT INTO " . DB_PREFIX_APP . "phase VALUES('".($id+1)."','".$fk1."','".$p1."','".$p2."','".$p3."','1','".$a."','T') ");
				$_db->query("INSERT INTO " . DB_PREFIX_APP . "phase VALUES('".($idadd)."','".$fk1."','".$p1."','".$p2."','".$p3."','1','".$a."','T') ");
				$existID .= "'".($idadd)."',";
				
			}
		}
		$existID = substr($existID,0,strlen($existID)-1);
		$existID .= ")";
		//die(" UPDATE " . DB_PREFIX_APP . "phase SET ACTIVE = 'F' WHERE phaseid NOT IN ".$existID." AND projectid = '".$fk1."' ");
		$_db->query(" UPDATE " . DB_PREFIX_APP . "phase SET ACTIVE = 'F' WHERE phaseid NOT IN ".$existID." AND projectid = '".$fk1."' ");
	}			

		
	return 1;
}

// function getProjectTotal($data){
	// global $_db, $_config;
	
	// // Filters 
	// $filter_project = isset($data['filter_project']) && !empty($data['filter_project'])? ' AND name LIKE \'%'.$_db->escape($data['filter_project']).'%\' OR description LIKE \'%'.$_db->escape($data['filter_project']).'%\' OR projectnumber LIKE \'%'.$_db->escape($data['filter_project']).'%\' ' : '';
	// $filter_status = 'AND STATUS < 3';
	// if (isset($data['status_filter']) && !empty($data['status_filter'])) {
		// if  ($data['status_filter']=='all') {
			// $filter_status = 'AND STATUS < 3';
		// }
	// }
	// //die("SELECT COUNT(projectid) AS total FROM " . DB_PREFIX_APP . "project WHERE 1=1 ".$filter_project." ".$filter_status);
	// // Main Query
	// $project_query = $_db->query("SELECT COUNT(projectid) AS total FROM " . DB_PREFIX_APP . "project WHERE 1=1 ".$filter_project." ".$filter_status);
	
	// if ($project_query->num_rows) { 
		// return $project_query->row['total'];
	// } else {
		// return 0;
	// }
// }

function getProjectTotal($data){
	global $_db, $_config;

	// Filters
	$filter_project = isset($data['filter_project']) && !empty($data['filter_project'])? 'WHERE prj.projectname LIKE \'%'.$_db->escape($data['filter_project']).'%\' OR prj.description LIKE \'%'.$_db->escape($data['filter_project']).'%\' OR prj.projectnumber LIKE \'%'.$_db->escape($data['filter_project']).'%\' ' : ' ';
	$filter_status = 'AND pr.STATUS < 3';
	if (isset($data['status_filter']) && !empty($data['status_filter'])) {
		if  ($data['status_filter']=='all') {
			$filter_status = 'AND pr.STATUS < 3';
		}
	}
	
	// Main Query
	$user_query = $_db->query("SELECT COUNT(projectid) as total FROM (
		SELECT 1 AS tag, pr.projectid, 'X' AS phaseid, pr.projectnumber, pr.name AS projectname,pr.description, ' ' AS phase, 		
		pr.estimatedmanhour AS est, (SELECT IFNULL(SUM(TIMESTAMPDIFF(DAY,amt.schedulestart,amt.scheduleend))+1,0) 		
		FROM " . DB_PREFIX_APP . "activity amt WHERE amt.phaseid IN (SELECT pha.phaseid FROM " . DB_PREFIX_APP . "phase pha 
		WHERE pha.projectid = pr.projectid) AND amt.projectid = pr.projectid) AS actual, pr.status AS status 
		, ' ' as phaseorderid
		FROM " . DB_PREFIX_APP . "project AS pr WHERE ProjectNumber IS NOT NULL " . $filter_status. "		
		UNION 		
		SELECT 2 AS tag, pr.projectid, ph.phaseid, pr.projectnumber, pr.name AS projectname,pr.description, ph.name AS phase, ph.estimatedmanhour AS est, 		
		(SELECT IFNULL(SUM(TIMESTAMPDIFF(DAY,amt.schedulestart,amt.scheduleend))+1,0) FROM " . DB_PREFIX_APP . "activity amt WHERE amt.phaseid = ph.phaseid 		
		AND amt.projectid = pr.projectid) AS actual, (SELECT pp.status from ".DB_PREFIX_APP."phase pp where pp.projectid = pr.projectid AND pp.phaseid = ph.phaseid) AS status 	
		, ph.orderid as phaseorderid		
		FROM " . DB_PREFIX_APP . "project AS pr, " . DB_PREFIX_APP . "phase AS ph 
		WHERE ProjectNumber IS NOT NULL " . $filter_status. "
		AND pr.projectid = ph.projectid 
		AND ph.active = 'T'
		ORDER BY projectnumber desc, tag	, phaseorderid,phaseid
		) AS prj ".$filter_project);
	
	if ($user_query->num_rows) { 
		return $user_query->row['total'];
	} else {
		return false;
	}
}

function getProjectList($data){
	global $_db, $_config;

	// Filters
	$filter_project = isset($data['filter_project']) && !empty($data['filter_project'])? 'WHERE prj.projectname LIKE \'%'.$_db->escape($data['filter_project']).'%\' OR prj.description LIKE \'%'.$_db->escape($data['filter_project']).'%\' OR prj.projectnumber LIKE \'%'.$_db->escape($data['filter_project']).'%\' ' : ' ';
	$filter_status = 'AND pr.STATUS < 3';
	if (isset($data['status_filter']) && !empty($data['status_filter'])) {
		if  ($data['status_filter']=='all') {
			$filter_status = 'AND pr.STATUS < 3';
		}
	}
	
	// Paging
	$page = isset($data['page']) && !empty($data['page'])? (int)$data['page'] : 1;
	$max = (int) $_config->get('items_per_page')>0? (int) $_config->get('items_per_page') : 20;
	$limit = "  LIMIT ".(($page-1)*$max).", ".$max;

	
	// Main Query
	$user_query = $_db->query("SELECT * FROM (
		SELECT 1 AS tag, pr.projectid, 'X' AS phaseid, pr.projectnumber, pr.name AS projectname,pr.description, ' ' AS phase, 		
		pr.estimatedmanhour AS est, (SELECT IFNULL(SUM(TIMESTAMPDIFF(DAY,amt.schedulestart,amt.scheduleend))+1,0) 		
		FROM " . DB_PREFIX_APP . "activity amt WHERE amt.phaseid IN (SELECT pha.phaseid FROM " . DB_PREFIX_APP . "phase pha 
		WHERE pha.projectid = pr.projectid) AND amt.projectid = pr.projectid) AS actual, pr.status AS status 
		, ' ' as phaseorderid
		FROM " . DB_PREFIX_APP . "project AS pr WHERE ProjectNumber IS NOT NULL " . $filter_status. "		
		UNION 		
		SELECT 2 AS tag, pr.projectid, ph.phaseid, pr.projectnumber, pr.name AS projectname,pr.description, ph.name AS phase, ph.estimatedmanhour AS est, 		
		(SELECT IFNULL(SUM(TIMESTAMPDIFF(DAY,amt.schedulestart,amt.scheduleend))+1,0) FROM " . DB_PREFIX_APP . "activity amt WHERE amt.phaseid = ph.phaseid 		
		AND amt.projectid = pr.projectid) AS actual, (SELECT pp.status from ".DB_PREFIX_APP."phase pp where pp.projectid = pr.projectid AND pp.phaseid = ph.phaseid) AS status 	
		, ph.orderid as phaseorderid		
		FROM " . DB_PREFIX_APP . "project AS pr, " . DB_PREFIX_APP . "phase AS ph 
		WHERE ProjectNumber IS NOT NULL " . $filter_status. "
		AND pr.projectid = ph.projectid 
		AND ph.active = 'T'
		ORDER BY projectnumber desc, tag	, phaseorderid,phaseid
		) AS prj ".$filter_project." ".$limit);
	
	if ($user_query->num_rows) { 
		return $user_query->rows;
	} else {
		return false;
	}
}

function getProjectDetailByID($data){
	global $_db;
	
	// Main Query
	$user_query = $_db->query("SELECT 	pr.ProjectID, 
	pr.ProjectNumber, 
	pr.Name, 
	pr.Description, 
	pr.ProjectManager, 
	pr.StartDate, 
	pr.EndDate,
	pr.EstimatedManHour, 
	pr.Status, 
	pr.Published, 
	pr.version, 
	pr.baseline, 
	pr.ContractAmount, 
	pr.idcurrency
	FROM 
	" . DB_PREFIX_APP . "project pr
	WHERE projectID = ".$_db->escape($data['project']));
	
	if ($user_query->num_rows) { 
		return $user_query->row;
	} else {
		return false;
	}
}

function getProjectPhaseByID($data){
	global $_db;
	
	// Main Query
	$user_query = $_db->query("SELECT 	ph.PhaseID, 
	ph.ProjectID, 
	ph.NAME, 
	ph.Description, 
	ph.EstimatedManHour, 
	ph.STATUS,
	ph.orderid,
	ph.active
	FROM 
	" . DB_PREFIX_APP . "phase ph 
	WHERE ProjectID = ".$_db->escape($data['project'])." AND ph.Active = 'T' ORDER BY orderid");
	
	if ($user_query->num_rows) { 
		return $user_query->rows;
	} else {
		return false;
	}
}

function getPhaseDetail($data){
	global $_db;
	
	// Main Query
	$user_query = $_db->query("SELECT 	ph.PhaseID, 
	ph.ProjectID, 
	ph.NAME, 
	ph.Description, 
	ph.EstimatedManHour, 
	ph.STATUS
	FROM 
	" . DB_PREFIX_APP . "phase ph 
	WHERE ProjectID = ".$_db->escape($data['project'])."
	AND PhaseID = ".$_db->escape($data['phase']));
	
	
	if ($user_query->num_rows) { 
		return $user_query->row;
	} else {
		return false;
	}
}

function getProjectMemberByID($data){
	global $_db;
	
	// Main Query
	$user_query = $_db->query("SELECT pm.projectid,pm.memberid,u.vuserid,u.vusername,u.vdisplayname  FROM " . DB_PREFIX_APP . "projectmembers pm
LEFT JOIN " . DB_PREFIX . "mstusers u ON pm.memberid=u.vuserid
WHERE pm.projectid =  ".$_db->escape($data['project']));
	
	if ($user_query->num_rows) { 
		return $user_query->rows;
	} else {
		return false;
	}
}

function isProjectActive($data) {
	global $_db, $_user;
	
	$project_query = $_db->query("SELECT IFNULL(STATUS,'X') AS ada FROM " . DB_PREFIX_APP ."project WHERE projectid = '".(int)$data['project']."' AND status < 3");

	if ($project_query->num_rows) { 
		if ($project_query->row['ada']=='X') {return 0;}else{return 1;}
	} else {
		return 0;
	}
	
}

function phaseLeft($data) {
	global $_db, $_user;
	
	$phase_query = $_db->query("SELECT IFNULL(STATUS,'X') AS ada FROM " . DB_PREFIX_APP ."phase WHERE projectid = '".(int)$data['project_id']."' AND phaseid = '".(int)$data['phase_id']."' AND status < 3");

	if ($phase_query->num_rows) { 
		if ($phase_query->row['ada']=='X') {return 0;}else{return 1;}
	} else {
		return 0;
	}
}

function stopProject($data) { //masih belum final
	global $_db, $_user;
	
	//pastikan project ini ada dan active
	$project_query = $_db->query("SELECT IFNULL(STATUS,'X') AS ada FROM " . DB_PREFIX_APP ."project WHERE projectid = '".(int)$data['project_id']."' AND status < 3");

	//jika memang project masih aktif
	if ($project_query->num_rows) { 
		if ($project_query->row['ada']=='X') {
			return 0;
		}else{
			//update phase menjadi complete --> status = 3
			$phase_query = $_db->query("UPDATE " . DB_PREFIX_APP ."phase SET STATUS = 3 WHERE  projectid = '".(int)$data['project_id']."' AND phaseid = '".(int)$data['phase_id']."'");
			
			$hasActivity = $_db->query("SELECT COUNT(phaseid) AS ada FROM " . DB_PREFIX_APP ."phase WHERE  projectid = '".(int)$data['project_id']."' AND status < 3");
			if ($hasActivity->num_rows) {
				if ((int)$hasActivity->row['ada']==0) {
						$project_query = $_db->query("UPDATE " . DB_PREFIX_APP ."project SET STATUS = 3 WHERE  projectid = '".(int)$data['project_id']."'");
				
				}
			}
		}
	} else {
		return 0;
	}
	
	return 1;
	
}

function getStatus($data) {

	switch ($data) {
		case 0:
			echo "Active";
			break;
		case 1:
		case 2:
			echo "In Progress";
			break;
		case 3:
			echo "Not Active";
			break;
		default:
			echo "Unknown Status";
	}
}

function projectPhaseActual($data) {
    global $_db;
    
    if (isset($data['ProjectID'])){

        $actual_query = $_db->query("SELECT ph.`PhaseID`,ph.`ProjectID`,ph.`Name`,ph.`Description`,ph.`EstimatedManHour`,ph.`OrderID`,
        (
                SELECT SUM(TIMESTAMPDIFF(MINUTE,startdate + INTERVAL timefrom HOUR_SECOND, startdate + INTERVAL timeto HOUR_SECOND )) AS _interval  
                FROM " . DB_PREFIX_APP ."task ts RIGHT JOIN " . DB_PREFIX_APP ."activity ac ON ts.activityid = ac.activityid
                RIGHT JOIN " . DB_PREFIX_APP ."phase pha ON pha.`PhaseID` = ac.phaseid
                WHERE pha.`ProjectID` = ph.`ProjectID`
                AND pha.`PhaseID` = ph.`PhaseID`
        ) AS subtotals_in_minutes
        FROM " . DB_PREFIX_APP ."phase ph
        WHERE ph.projectid = ".$_db->escape($data['ProjectID'])." 
        AND ph.active = 'T'
        ORDER BY ph.orderid, ph.phaseid ;");

	if ($actual_query->num_rows) { 
		return $actual_query->rows;
	} else {
		return 0;
	}
    
    }else{
        return 0;
    }
}




?>