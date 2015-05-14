<?php
	include '../../startup.php';
	include '../../model/user.php';
	include '../../model/attendance.php';
	
	header('Content-Type: application/json; charset=utf-8');
	
	// get query strings
	$qs = '';
	if (isset($_POST['filter'])) {
		$qs = 'filter='.$_POST['filter'];
	}
	if (isset($_POST['page'])) {
		$qs = empty($qs)? 'page='.$_POST['page'] : '&page='.$_POST['page'];
	}
	
	// handle actions
	switch ($_act) {
		case 'add' :
			if ($_form->userHasFunction('add')) {
				$val = validateInput();
				if ($val=='ok') {
					$res = addUserCheckInOut($_POST);
					$_response['status'] = 1;
					$_response['message'] = sprintf(MSG_EDIT_ITEM_SUCCESS,3);
					$_response['redirect'] = HTTPS_SERVER.'forms/attendance/attlist.php';
					$_response['redirect-time'] = 3000;
				} else {
					$_response['status'] = 0;
					$_response['message'] = MSG_ERROR_FIELDS.$val;
				}
			} else {
					$_response['status'] = 0;
					$_response['message'] = MSG_TASK_NOT_AUTHORIZED;
			}
			break;
		default :
			$_response['status'] = 1;
			$_response['message'] = '';
			$_response['redirect'] = HTTPS_SERVER.'forms/attendance/attlist.php?'.$qs;
			break;
	}
	echo json_encode($_response);

/// SUPPORTING FUNCTIONS
function validateInput(){
	global $_POST;
	$err = array();
	if (!isset($_POST['checkdate']) || empty($_POST['checkdate'])) {
		$err[] = '<li>Check date must be filled</li>';
	}
	if (!isset($_POST['employeeid']) || empty($_POST['employeeid'])) {
		$err[] = '<li>Employee must be filled</li>';
	}
	if (!isset($_POST['checkhour']) || empty($_POST['checkhour'])) {
		$err[] = '<li>Check hour must be filled</li>';
	}
	if (!isset($_POST['checkmin']) || empty($_POST['checkmin'])) {
		$err[] = '<li>Check minute must be filled</li>';
	}
	if (!isset($_POST['checktype']) || empty($_POST['checktype'])) {
		$err[] = '<li>Check type must be filled</li>';
	}
 
	if (count($err)>0) {
		return '<ul>'.implode('',$err).'</ul>';
	} else return 'ok';
	
}
	
?>