<?php
function getUsers($data){
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

function getAllUsers($data){
	global $_db, $_config;
	
	// Filters 
	$filter_username = isset($data['filter_username']) && !empty($data['filter_username'])? ' WHERE vusername LIKE \'%'.$_db->escape($data['filter_username']).'%\' OR vdisplayname LIKE \'%'.$_db->escape($data['filter_username']).'%\' ' : '';
	
	// Paging
	$page = isset($data['page']) && !empty($data['page'])? (int)$data['page'] : 1;
	$max = (int) $_config->get('items_per_page')>0? (int) $_config->get('items_per_page') : 20;
	
	// Main Query
	$user_query = $_db->query("SELECT vuserid, vusername, vdisplayname, IF (SYSDATE() BETWEEN dbegineff AND dlasteff, 'active','inactive') AS `status` FROM " . DB_PREFIX . "mstusers ".$filter_username);
	
	if ($user_query->num_rows) { 
		return $user_query->rows;
	} else {
		return false;
	}
}


function getFingerprintUsers(){
	global $_db, $_config;
	
	
	// Main Query
	$user_query = $_db->query("SELECT userid,name FROM " . DB_PREFIX_ABSEN . "userinfo WHERE userid not in (SELECT vuserid FROM ". DB_PREFIX."mstusers)");
	
	if ($user_query->num_rows) { 
		return $user_query->rows;
	} else {
		return false;
	}
}


function getUserById($userid){
	global $_db;
	
	// Main Query
	$user_query = $_db->query("SELECT * FROM " . DB_PREFIX . "mstusers WHERE vuserid = '".(int) $userid."'");
	
	if ($user_query->num_rows) { 
		return $user_query->row;
	} else {
		return false;
	}
}

function getIndependentUserId(){
	global $_db;
	
	// Main Query
	$user_query = $_db->query("SELECT value FROM " . DB_PREFIX . "setting WHERE group = 'user' AND key = 'independent_user'");
	
	if ($user_query->num_rows) { 
		return $user_query->row['value'];
	} else {
		return false;
	}
}


function getUsersTotal($data){
	global $_db, $_config;
	
	// Filters 
	$filter_username = isset($data['filter_username']) && !empty($data['filter_username'])? ' WHERE vusername LIKE \'%'.$_db->escape($data['filter_username']).'%\' OR vdisplayname LIKE \'%'.$_db->escape($data['filter_username']).'%\' ' : '';
	
	// Main Query
	$user_query = $_db->query("SELECT COUNT(vuserid) AS total FROM " . DB_PREFIX . "mstusers ".$filter_username);
	
	if ($user_query->num_rows) { 
		return $user_query->row['total'];
	} else {
		return 0;
	}
}

function deleteUsers($user_ids){
	global $_db, $_config;
	// Main Query
	$user_query = $_db->query("DELETE FROM " . DB_PREFIX . "mstusers WHERE vuserid IN (".$_db->escape($user_ids).")");
	return 1;
}

function addUserRelated($data) {
	global $_db, $_user;
	// die ("INSERT INTO " . DB_PREFIX . "mstusers  
			// SELECT '".$_db->escape($data['relatedusername'])."' ,
			// SHA1('".$_db->escape($data['password'])."'),
			// '".$_db->escape($data['username'])."', '".$_db->escape($data['relatedusername'])."',  '".$_db->escape($data['begineff'])."', '".$_db->escape($data['lasteff'])."', SYSDATE(),
			// '".$_db->escape($_user->getUserName())."', NULL, NULL FROM " . DB_PREFIX . "mstusers ");
	$user_query = $_db->query("INSERT INTO " . DB_PREFIX . "mstusers  
			SELECT '".$_db->escape($data['relatedusername'])."' ,
			SHA1('".$_db->escape($data['password'])."'),
			'".$_db->escape($data['username'])."', 
			(SELECT u.name FROM am_ts_userinfo u WHERE u.userid = '".$_db->escape($data['relatedusername'])."'),
			'".$_db->escape($data['begineff'])."', '".$_db->escape($data['lasteff'])."', SYSDATE(),
			'".$_db->escape($_user->getUserName())."', NULL, NULL FROM " . DB_PREFIX . "mstusers LIMIT 1 ");

	return 1;
}

function addUserIndie($data) {
	global $_db, $_user;
	$user_query = $_db->query("INSERT INTO " . DB_PREFIX . "mstusers SELECT IFNULL(MAX(vuserid)+1,(SELECT `value` FROM " . DB_PREFIX . "setting WHERE `group` ='user' AND `key` = 'independent_user')),SHA1('".$_db->escape($data['password'])."'), '".$_db->escape($data['username'])."', '".$_db->escape($data['displayname'])."', '".$_db->escape($data['begineff'])."', '".$_db->escape($data['lasteff'])."', SYSDATE(), '".$_db->escape($_user->getUserName())."', NULL, NULL  FROM " . DB_PREFIX . "mstusers WHERE vuserid>=(SELECT `value` FROM " . DB_PREFIX . "setting WHERE `group` ='user' AND `key` = 'independent_user')");
	return 1;
}


function editUser($data) {
	global $_db, $_user;
	$qpasswd = '';
	if (!empty($data['password'])) $qpasswd = " vpassword = SHA1('".$_db->escape($data['password'])."'), ";
	$user_query = $_db->query("UPDATE " . DB_PREFIX . "mstusers SET  vusername = '".$_db->escape($data['username'])."', vdisplayname = '".$_db->escape($data['displayname'])."', dbegineff = '".$_db->escape($data['begineff'])."', dlasteff = '".$_db->escape($data['lasteff'])."', dmodi = SYSDATE(), vmodi = '".$_db->escape($_user->getUserName())."' WHERE vuserID = '".(int)$data['userid']."'");
	return 1;
}

function resetPassword($data) {
	global $_db, $_user;
	$qpasswd = '';

	//check old password
	//$check_query = $_db->query("SELECT vpassword AS pass FROM " . DB_PREFIX . "mstusers WHERE vuserID = '".(int)$data['userid']."'");
	
	//if ($check_query->num_rows) { 
	//	if ($user_query->row['pass'] == $data['oldpassword']) {
			if (!empty($data['newpassword'])) $qpasswd = " SHA1('".$_db->escape($data['newpassword'])."') ";
			//die("UPDATE " . DB_PREFIX . "mstusers SET  vpassword = ".$qpasswd.", dmodi = SYSDATE(), vmodi = '".$_db->escape($_user->getUserName())."' WHERE vuserID = '".(int)$data['userid']."'");
			$user_query = $_db->query("UPDATE " . DB_PREFIX . "mstusers SET  vpassword = ".$qpasswd.", dmodi = SYSDATE(), vmodi = '".$_db->escape($_user->getUserName())."' WHERE vuserID = '".(int)$data['userid']."'");
	//	}
	//}
	return 1;
}



function getUserRoles($userid){
	global $_db;
	
	// Main Query
	$user_query = $_db->query("SELECT * FROM " . DB_PREFIX . "mstuserroles WHERE vuserid = '".(int) $userid."'");
	
	if ($user_query->num_rows) { 
		$arRoles = array();
		foreach($user_query->rows as $role) {
			$arRoles[] = $role['vRoleid'];
		}
		return $arRoles;
	} else {
		return array();
	}
}

function assignUser($data) {
	global $_db, $_config, $_user;
	// Main Query
	$user_query = $_db->query("DELETE FROM " . DB_PREFIX . "mstuserroles WHERE vuserid = '".(int)$data['userid']."' ");
	if (isset($data['role']) && (count($data['role'])>0)) {
		$qs = array();
		foreach ($data['role'] as $role) {
			$qs[] = "(".(int)$role.", ".(int)$data['userid'].", SYSDATE(), '".$_db->escape($_user->getUserName())."', NULL, NULL )";
		}
		if (count($qs)>0) {
			$_db->query("INSERT INTO " . DB_PREFIX . "mstuserroles VALUES ".implode(',',$qs));
		}
	}
	return 1;
}

?>