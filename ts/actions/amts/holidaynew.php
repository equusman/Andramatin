<?php
include '../../startup.php';
include '../../model/project.php';
include '../../model/task.php';

header('Content-Type: application/json; charset=utf-8');

// handle actions
switch ($_act) {
	case 'add' :
		if ($_form->userHasFunction('add')) {
			$val = validateInput();
			if ($val=='ok') {
				$res = addHoliday($_POST);
				$_response['status'] = 1;
				$_response['message'] = sprintf(MSG_ADD_ITEM_SUCCESS,3);
				$_response['redirect'] = HTTPS_SERVER.'forms/amts/holiday.php';
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
	if (!isset($_POST['holiday']) || empty($_POST['holiday'])) {
		$err[] = '<li>Holiday date must be filled</li>';
	}
	if (!isset($_POST['description']) || empty($_POST['description'])) {
		$err[] = '<li>Description must be filled</li>';
	}
	
	if (count($err)>0) {
		return '<ul>'.implode('',$err).'</ul>';
	} else return 'ok';
	
}

?>