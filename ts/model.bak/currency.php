<?php
function getCurrencies($data){
	global $_db, $_config;
	
	// Filters 
	$filter_currencyname = isset($data['filter_currencyname']) && !empty($data['filter_currencyname'])? ' WHERE idcurrency LIKE \'%'.$_db->escape($data['filter_currencyname']).'%\' OR desc LIKE \'%'.$_db->escape($data['filter_currencyname']).'%\' ' : '';
	
	// Paging
	$page = isset($data['page']) && !empty($data['page'])? (int)$data['page'] : 1;
	$max = (int) $_config->get('items_per_page')>0? (int) $_config->get('items_per_page') : 20;
	$limit = "  LIMIT ".(($page-1)*$max).", ".$max;
	
	// Main Query
	$currency_query = $_db->query("SELECT * FROM " . DB_PREFIX_APP . "currencies c ".$filter_currencyname.$limit);
	if ($currency_query->num_rows) { 
		return $currency_query->rows;
	} else {
		return false;
	}
}

function getCurrencyById($currencyid){
	global $_db;
	
	// Main Query
	$currency_query = $_db->query("SELECT * FROM " . DB_PREFIX_APP . "mstcurrencies WHERE vcurrencyid = '".(int) $currencyid."'");
	
	if ($currency_query->num_rows) { 
		return $currency_query->row;
	} else {
		return false;
	}
}

function getCurrenciesTotal($data){
	global $_db, $_config;
	
	// Filters 
	$filter_currencyname = isset($data['filter_currencyname']) && !empty($data['filter_currencyname'])? ' WHERE vcurrencyname LIKE \'%'.$_db->escape($data['filter_currencyname']).'%\' OR vdisplayname LIKE \'%'.$_db->escape($data['filter_currencyname']).'%\' ' : '';
	
	// Main Query
	$currency_query = $_db->query("SELECT COUNT(vcurrencyid) AS total FROM " . DB_PREFIX_APP . "mstcurrencies ".$filter_currencyname);
	
	if ($currency_query->num_rows) { 
		return $currency_query->row['total'];
	} else {
		return 0;
	}
}

function deleteCurrencies($currency_ids){
	global $_db, $_config;
	// Main Query
	$currency_query = $_db->query("DELETE FROM " . DB_PREFIX_APP . "mstcurrencies WHERE vcurrencyid IN (".$_db->escape($currency_ids).")");
	return 1;
}

function addCurrency($data) {
	global $_db, $_currency;
	$currency_query = $_db->query("INSERT INTO " . DB_PREFIX_APP . "mstcurrencies SELECT MAX(vcurrencyID)+1, SHA1('".$_db->escape($data['password'])."'), '".$_db->escape($data['currencyname'])."', '".$_db->escape($data['displayname'])."', '".$_db->escape($data['begineff'])."', '".$_db->escape($data['lasteff'])."', SYSDATE(), '".$_db->escape($_currency->getCurrencyName())."', NULL, NULL FROM " . DB_PREFIX_APP . "mstcurrencies ");
	return 1;
}

function editCurrency($data) {
	global $_db, $_currency;
	$qpasswd = '';
	if (!empty($data['password'])) $qpasswd = " vpassword = SHA1('".$_db->escape($data['password'])."'), ";
	$currency_query = $_db->query("UPDATE " . DB_PREFIX_APP . "mstcurrencies SET  vcurrencyname = '".$_db->escape($data['currencyname'])."', vdisplayname = '".$_db->escape($data['displayname'])."', dbegineff = '".$_db->escape($data['begineff'])."', dlasteff = '".$_db->escape($data['lasteff'])."', dmodi = SYSDATE(), vmodi = '".$_db->escape($_currency->getCurrencyName())."' WHERE vcurrencyID = '".(int)$data['currencyid']."'");
	return 1;
}

function getCurrencyRoles($currencyid){
	global $_db;
	
	// Main Query
	$currency_query = $_db->query("SELECT * FROM " . DB_PREFIX_APP . "mstcurrencyroles WHERE vcurrencyid = '".(int) $currencyid."'");
	
	if ($currency_query->num_rows) { 
		$arRoles = array();
		foreach($currency_query->rows as $role) {
			$arRoles[] = $role['vRoleid'];
		}
		return $arRoles;
	} else {
		return array();
	}
}

function assignCurrency($data) {
	global $_db, $_config, $_currency;
	// Main Query
	$currency_query = $_db->query("DELETE FROM " . DB_PREFIX_APP . "mstcurrencyroles WHERE vcurrencyid = '".(int)$data['currencyid']."' ");
	if (isset($data['role']) && (count($data['role'])>0)) {
		$qs = array();
		foreach ($data['role'] as $role) {
			$qs[] = "(".(int)$role.", ".(int)$data['currencyid'].", SYSDATE(), '".$_db->escape($_currency->getCurrencyName())."', NULL, NULL )";
		}
		if (count($qs)>0) {
			$_db->query("INSERT INTO " . DB_PREFIX_APP . "mstcurrencyroles VALUES ".implode(',',$qs));
		}
	}
	return 1;
}

?>