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
	$existID = "(";
	
	if (isset($data['activityname']) && (count($data['activityname'])>0) && isset($data['projectid']) && (count($data['projectid'])>0) && isset($data['phaseid']) && (count($data['phaseid'])>0)) {
		for($a=0; $a<count($data['activityname']); $a++) {
			$v1 = $_db->escape($data['activityname'][$a]);
			$fk1 = $_db->escape($data['projectid']);
			$fk2 = $_db->escape($data['phaseid']) ;
			$activityid = $_db->escape($data['activityid'][$a]);
			$v2 = isset($data['activitydesc'][$a])? $_db->escape($data['activitydesc'][$a]) : '';
			$v3 = isset($data['activitystart'][$a])? $_db->escape($data['activitystart'][$a]) : '';
			$v4 = isset($data['activityend'][$a])? $_db->escape($data['activityend'][$a]) : '';		
			$_db->query("UPDATE " . DB_PREFIX_APP . "activity SET orderid = '".$a."' WHERE activityid = '".$activityid."' AND phaseid = '".$fk2."' AND projectid = '".$fk1."'");
			$existID .= "'".$activityid."',";
			
			if ($data['activityid'][$a]== "new"){
				
				$maxidadd = $_db->query("SELECT IFNULL(MAX(activityid),0)+1 as mx FROM " . DB_PREFIX_APP . "activity WHERE projectid = '". $fk1 ."' AND phaseid = '". $fk2 ."'");	
				$idadd = (int)$maxidadd->row['mx'];	
				
			
				//die("INSERT INTO " . DB_PREFIX_APP . "phase VALUES('".($id+1)."','".$fk1."','".$p1."','".$p2."','".$p3."','1','".$a."','T') ");
				$_db->query("INSERT INTO " . DB_PREFIX_APP . "activity VALUES('".($idadd)."','".$fk1."','".$fk2."','".$v1."','".$v2."','".$v3."','".$v4."','1','".$a."','T') ");
				$existID .= "'".($idadd)."',";
				
			}

		}
		$existID = substr($existID,0,strlen($existID)-1);
		$existID .= ")";			

		//die("INSERT INTO " . DB_PREFIX_APP . "activity VALUES('".($id+$a+1)."','".$fk1."','".$fk2."','".$v1."','".$v2."','".$v3."','".$v4."','1') ");
		//$_db->query("INSERT INTO " . DB_PREFIX_APP . "activity VALUES('".($id+$a+1)."','".$fk1."','".$fk2."','".$v1."','".$v2."','".$v3."','".$v4."','1') ");
		$_db->query(" UPDATE " . DB_PREFIX_APP . "activity SET active = 'F' WHERE activityid NOT IN ".$existID." AND projectid = '".$fk1."' AND phaseid = '".$fk2."' ");
	}		
	return 1;
}

function editActivity($data) {
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
		startdate = '".$_db->escape($data['startdate'])."' 
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



?>