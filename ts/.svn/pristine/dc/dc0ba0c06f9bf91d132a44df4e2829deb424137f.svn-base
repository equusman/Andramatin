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
	
	// Main Query
	$user_query = $_db->query("SELECT * FROM " . DB_PREFIX_APP . "activity ac WHERE ac.projectid = '".(int) $id['project']."' AND ac.phaseid = '".(int) $id['phase']."'");
	
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
		ph.description phdesc,ph.estimatedmanhour phestimatedmanhour,ph.status phstatus
		FROM " . DB_PREFIX_APP . "project pr, " . DB_PREFIX_APP . "phase ph WHERE pr.projectid = ph.phaseid AND pr.projectid = '".(int) $id['project']."' AND ph.phaseid = '".(int) $id['phase']."'");
	
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

function addActivity($data) {
	global $_db, $_user;
	
//	$pmx_query = $_db->query("SELECT IFNULL(MAX(ActivityID),0) as mx FROM " . DB_PREFIX_APP . "activity where projectid = ". $_db->escape($data['projectid']) ." and ". $_db->escape($data['projectid']) .";");	
	$pmx_query = $_db->query("SELECT IFNULL(MAX(ActivityID),0) as mx FROM " . DB_PREFIX_APP . "activity ;");		
	$id = (int)$pmx_query->row['mx'];	
	
	if (isset($data['activityname']) && (count($data['activityname'])>0) && isset($data['projectid']) && (count($data['projectid'])>0) && isset($data['phaseid']) && (count($data['phaseid'])>0)) {
		for($a=0; $a<count($data['activityname']); $a++) {
			$v1 = $_db->escape($data['activityname'][$a]);
			$fk1 = $_db->escape($data['projectid']);
			$fk2 = $_db->escape($data['phaseid']) ;
			$v2 = isset($data['activitydesc'][$a])? $_db->escape($data['activitydesc'][$a]) : '';
			$v3 = isset($data['activitystart'][$a])? $_db->escape($data['activitystart'][$a]) : '';
			$v4 = isset($data['activityend'][$a])? $_db->escape($data['activityend'][$a]) : '';			
			//die("INSERT INTO " . DB_PREFIX_APP . "activity VALUES('".($id+$a+1)."','".$fk1."','".$fk2."','".$v1."','".$v2."','".$v3."','".$v4."','1') ");
			$_db->query("INSERT INTO " . DB_PREFIX_APP . "activity VALUES('".($id+$a+1)."','".$fk1."','".$fk2."','".$v1."','".$v2."','".$v3."','".$v4."','1') ");
		}
	}		
	return 1;
}





?>