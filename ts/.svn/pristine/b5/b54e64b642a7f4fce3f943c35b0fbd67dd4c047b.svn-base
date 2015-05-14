<?php
	include '../../startup.php';
	include '../../model/project.php';
	include '../../model/task.php';
	
	header('Content-Type: application/json; charset=utf-8');
	
	
	// handle actions
	switch ($_act) {
		case 'edit' :
			if ($_form->userHasFunction('edit')) {
				$val = validateInput();
				if ($val=='ok') {
					//die("asdjfaskdjfhasjkdfhaksjdf");
					$res = stopTask($_POST);
					//die($res);
					$_response['status'] = 1;
					$_response['message'] = sprintf(MSG_EDIT_ITEM_SUCCESS,3);
					//$_response['redirect'] = HTTPS_SERVER.'forms/amts/taskclose.php';
					//$_response['redirect-time'] = 3000;
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
	if (!isset($_POST['task_result']) || empty($_POST['task_result'])) {
		$err[] = '<li>Task result must be filled</li>';
	}
	if (!isset($_POST['end_hour']) || empty($_POST['end_hour'])) {
		$err[] = '<li>Task end date must be filled</li>';
	}
	if (!isset($_POST['end_min']) || empty($_POST['end_min'])) {
		$err[] = '<li>Task end date must be filled</li>';
	}
	
	if (count($err)>0) {
		return '<ul>'.implode('',$err).'</ul>';
	} else return 'ok';
	
}
	
?>