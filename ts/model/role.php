<?php
function getRoles($data){
	global $_db, $_config;
	
	// Filters 
	$filter_rolename = isset($data['filter_role']) && !empty($data['filter_role'])? ' WHERE vroledesc LIKE \'%'.$_db->escape($data['filter_role']).'%\' ' : '';
	
	// Paging
	$page = isset($data['page']) && !empty($data['page'])? (int)$data['page'] : 1;
	$max = (int) $_config->get('items_per_page')>0? (int) $_config->get('items_per_page') : 20;
	$limit = "  LIMIT ".(($page-1)*$max).", ".$max;
	
	// Main Query
	$role_query = $_db->query("SELECT vroleid, vroledesc, IF (SYSDATE() BETWEEN dbegineff AND dlasteff, 'active','inactive') AS `status` FROM " . DB_PREFIX . "mstroles ".$filter_rolename.$limit);
	
	if ($role_query->num_rows) { 
		return $role_query->rows;
	} else {
		return false; 
	}
}

function getRoleById($roleid){
	global $_db;
	
	// Main Query
	$role_query = $_db->query("SELECT * FROM " . DB_PREFIX . "mstroles WHERE vroleid = '".(int) $roleid."'");
	
	if ($role_query->num_rows) { 
		return $role_query->row;
	} else {
		return false;
	}
}

function getRolesTotal($data){
	global $_db;
	
	// Filters 
	$filter_rolename = isset($data['filter_role']) && !empty($data['filter_role'])? ' WHERE vroledesc LIKE \'%'.$_db->escape($data['filter_role']).'%\' ' : '';
	
	// Main Query
	$role_query = $_db->query("SELECT COUNT(vroleid) AS total FROM " . DB_PREFIX . "mstroles ".$filter_rolename);
	
	if ($role_query->num_rows) { 
		return $role_query->row['total'];
	} else {
		return 0;
	}
}

function deleteRoles($role_ids){
	global $_db, $_config;
	// Main Query
	$role_query = $_db->query("DELETE FROM " . DB_PREFIX . "mstroles WHERE vroleid IN (".$_db->escape($role_ids).")");
	return 1;
}

function addRole($data) {
	global $_db, $_user;
	$role_query = $_db->query("INSERT INTO " . DB_PREFIX . "mstroles SELECT MAX(vroleid)+1, '".$_db->escape($data['roledesc'])."', '".$_db->escape($data['begineff'])."', '".$_db->escape($data['lasteff'])."', SYSDATE(), '".$_db->escape($_user->getUserName())."', NULL, NULL FROM " . DB_PREFIX . "mstroles ");
	return 1;
}

function editRole($data) {
	global $_db, $_user;
	$role_query = $_db->query("UPDATE " . DB_PREFIX . "mstroles SET  vroledesc = '".$_db->escape($data['roledesc'])."', dbegineff = '".$_db->escape($data['begineff'])."', dlasteff = '".$_db->escape($data['lasteff'])."', dmodi = SYSDATE(), vmodi = '".$_db->escape($_user->getUserName())."' WHERE vroleid = '".(int)$data['roleid']."'");
	return 1;
}

?>