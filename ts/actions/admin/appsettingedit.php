<?php
include '../../startup.php';
include '../../model/properties.php';

header('Content-Type: application/json; charset=utf-8');

// handle actions
switch ($_act) {
	case 'edit' :
		if ($_form->userHasFunction('edit')) {
			$val = validateInput();
			if ($val=='ok') {
				$res = editSetting($_POST);
				$_response['status'] = 1;
				$_response['message'] = sprintf(MSG_EDIT_ITEM_SUCCESS,3);
				$_response['redirect'] = HTTPS_SERVER.'forms/admin/appsetting.php';
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
	if (!isset($_POST['setting_id']) || empty($_POST['setting_id'])) {
		$err[] = '<li>Missing Setting ID</li>';
	} else {
		if (!isset($_POST['group']) || empty($_POST['group'])) {
			$err[] = '<li>Group must be filled</li>';
		}
		if (!isset($_POST['key']) || empty($_POST['key'])) {
			$err[] = '<li>Setting Key must be filled</li>';
		}
		if (!isset($_POST['value']) || empty($_POST['value'])) {
			$err[] = '<li>Value must be filled</li>';
		}
	}
	
	if (count($err)>0) {
		return '<ul>'.implode('',$err).'</ul>';
	} else return 'ok';
	
}

?>