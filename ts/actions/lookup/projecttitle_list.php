<?php
	include '../../startup.php';
	include '../../model/user.php';
	
	header('Content-Type: application/json; charset=utf-8');
	
	// get query strings
	$qs = '';
	if (isset($_POST['filter_activity'])) {
		$qs = 'filter_activity='.$_POST['filter_activity'];
	}
	if (isset($_POST['page'])) {
		$qs = empty($qs)? 'page='.$_POST['page'] : '&page='.$_POST['page'];
	}
	
	// handle actions
	switch ($_act) {
		default :
			$_response['status'] = 1;
			$_response['message'] = '';
			$_response['redirect'] = HTTPS_SERVER.'forms/lookup/projecttitle_list.php?'.$qs;
			$_response['targetClass'] = $_POST['targetClass'];
			break;
	}

	echo json_encode($_response);

?>