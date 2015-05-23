<?php
include '../../startup.php';
include '../../model/project.php';

header('Content-Type: application/json; charset=utf-8');

// handle actions
switch ($_act) {
	case 'add' :
		if ($_form->userHasFunction('add')) {
			$val = validateInput();
			if ($val=='ok') {
				$res = addproject($_POST);
				$_response['status'] = 1;
				$_response['message'] = sprintf(msg_add_item_success,3);
				$_response['redirect'] = https_server.'forms/amts/projectlist.php';
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
}

echo json_encode($_response);


/// SUPPORTING FUNCTIONS
function validateInput(){
	global $_POST;
	$err = array();
	if (!isset($_POST['project_number']) || empty($_POST['project_number'])) {
		$err[] = '<li>Project number must be filled</li>';
	}
	if (!isset($_POST['project_name']) || empty($_POST['project_name'])) {
		$err[] = '<li>Project name must be filled</li>';
	}
	if (!isset($_POST['project_manager']) || empty($_POST['project_manager'])) {
		$err[] = '<li>Project manager must be filled</li>';
	}
	if (!isset($_POST['startdate']) || empty($_POST['startdate'])) {
		$err[] = '<li>Start date must be filled</li>';
	}
	if (!isset($_POST['estimatedmanhour']) || empty($_POST['estimatedmanhour'])) {
		$err[] = '<li>Estimated man hour must be filled</li>';
	}
	
	if (count($err)>0) {
		return '<ul>'.implode('',$err).'</ul>';
	} else return 'ok';
	
}

?>