<?php
function getApplicationSetting($data){
	global $_db, $_config, $_user;

	// Filters 
	$filter_setting = isset($data['filter_setting']) && !empty($data['filter_setting'])? ' WHERE setting.key LIKE \'%'.$_db->escape($data['filter_setting']).'%\' OR setting.value LIKE \'%'.$_db->escape($data['filter_setting']).'%\' OR setting.group LIKE \'%'.$_db->escape($data['filter_setting']).'%\' ' : '';
	
	// Paging
	$page = isset($data['page']) && !empty($data['page'])? (int)$data['page'] : 1;
	$max = (int) $_config->get('items_per_page')>0? (int) $_config->get('items_per_page') : 20;
	$limit = "  LIMIT ".(($page-1)*$max).", ".$max;
	
	// Main Query
	$setting_query = $_db->query("SELECT * FROM " . DB_PREFIX . "setting as setting ".$filter_setting.$limit);
 
	
	if ($setting_query->num_rows) { 
		return $setting_query->rows;
	} else {
		return false;
	}
}	

function getApplicationSettingTotal($data){
	global $_db, $_config, $_user;

	// Filters 
	$filter_setting = isset($data['filter_setting']) && !empty($data['filter_setting'])? ' WHERE setting.key LIKE \'%'.$_db->escape($data['filter_setting']).'%\' OR setting.value LIKE \'%'.$_db->escape($data['filter_setting']).'%\' OR setting.group LIKE \'%'.$_db->escape($data['filter_setting']).'%\' ' : '';
	
	// Paging
	$page = isset($data['page']) && !empty($data['page'])? (int)$data['page'] : 1;
	$max = (int) $_config->get('items_per_page')>0? (int) $_config->get('items_per_page') : 20;
	$limit = "  LIMIT ".(($page-1)*$max).", ".$max;
	
	// Main Query
	$setting_query = $_db->query("SELECT COUNT(setting_id) AS total FROM " . DB_PREFIX . "setting as setting ".$filter_setting.$limit);
 
	
	if ($setting_query->num_rows) { 
		return $setting_query->row['total'];
	} else {
		return 0;
	}
}

function getSettingById($data){
	global $_db, $_config;
	
	//die($_db->escape($data['setting_id']));
	
		// Main Query
	$opentask_query = $_db->query("SELECT * FROM " . DB_PREFIX . "setting AS setting WHERE setting.setting_id = ".$_db->escape($data['setting_id'])." LIMIT 1");
 
	
	if ($opentask_query->num_rows) { 
		return $opentask_query->row;
	} else {
		return false;
	}
}

function editSetting($data) {
	global $_db;


	$task_exist_query = $_db->query("SELECT COUNT(setting_id) AS ada FROM " . DB_PREFIX . "setting WHERE setting_id = ".$_db->escape($data['setting_id']));
	$isExist = false;
	
	if ($task_exist_query->num_rows){
		if ($task_exist_query->row['ada']=='1'){
			$isExist = true;
		}
	}
	
	
	if ($isExist)
	{
		//die("die here");
		$task_update_query = $_db->query("UPDATE " . DB_PREFIX . "setting s SET s.group = '".$_db->escape($data['group'])."',
		s.key = '".$_db->escape($data['key'])."', s.value = '".$_db->escape($data['value'])."' 
		WHERE s.setting_id = ".$_db->escape($data['setting_id']) );
		return 1;
	}
	else
	{
		return 0;
	}
	
}

function getSettingGroupKey($data) {
	global $_db;
	
	$settingquery = $_db->query("select * from ".DB_PREFIX."setting s where s.key = '".$_db->escape($data['key'])."' and s.group = '".$_db->escape($data['group'])."' ");

	if ($settingquery->num_rows) { 
		return $settingquery->row['value'];
	} else {
		return false;
	}

}


?>