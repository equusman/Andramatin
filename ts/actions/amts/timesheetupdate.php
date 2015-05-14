<?php
	include '../../startup.php';
	include '../../model/user.php';
	include '../../model/task.php';
	
	header('Content-Type: application/json; charset=utf-8');
	
	// get query strings
	$qs = '';
	if (isset($_POST['filter_task'])) {
		$qs = 'filter_task='.$_POST['filter_task'];
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
					$res = addTaskAdmin($_POST);
					$_response['status'] = 1;
					$_response['message'] = sprintf(MSG_EDIT_ITEM_SUCCESS,3);
					$_response['redirect'] = HTTPS_SERVER.'forms/amts/taskclose.php';
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
			$_response['redirect'] = HTTPS_SERVER.'forms/amts/taskclose.php?'.$qs;
			break;
	}
	echo json_encode($_response);

/// SUPPORTING FUNCTIONS
function validateInput(){
	global $_POST;
	$err = array();
	if (!isset($_POST['taskdate']) || empty($_POST['taskdate'])) {
		$err[] = '<li>Task date must be filled</li>';
	}
	if (!isset($_POST['activity_id']) || empty($_POST['activity_id'])) {
		$err[] = '<li>Activity ID must be filled</li>';
	}
	if (!isset($_POST['employeeid']) || empty($_POST['employeeid'])) {
		$err[] = '<li>Employee must be filled</li>';
	}
	if (!isset($_POST['start_hour']) || empty($_POST['start_hour'])) {
		$err[] = '<li>Start hour must be filled</li>';
	}
	if (!isset($_POST['start_min']) || empty($_POST['start_min'])) {
		$err[] = '<li>Start minute must be filled</li>';
	}
	if (!isset($_POST['end_hour']) || empty($_POST['end_hour'])) {
		$err[] = '<li>End hour must be filled</li>';
	}
	if (!isset($_POST['end_min']) || empty($_POST['end_min'])) {
		$err[] = '<li>End minute must be filled</li>';
	}
	if (!isset($_POST['task_result']) || empty($_POST['task_result'])) {
		$err[] = '<li>Result must be filled</li>';
	}

	$overlap = checkOverlap($_POST);
	if ($overlap!="") {
		 $err[] = '<li>'.$overlap.'</li>';
	 }
	

//ditutup sementara untuk checking overlaping task
	$cekin = checkCheckInAdmin($_POST);
	if ($cekin!="") {
		 $err[] = '<li>'.$cekin.'</li>';
	 }

	$cekout = checkCheckOutAdmin($_POST);
	if ($cekout!="") {
		 $err[] = '<li>'.$cekout.'</li>';
	 }
//end ditutup sementara`
	 
	if (count($err)>0) {
		return '<ul>'.implode('',$err).'</ul>';
	} else return 'ok';
	
}
	
?>