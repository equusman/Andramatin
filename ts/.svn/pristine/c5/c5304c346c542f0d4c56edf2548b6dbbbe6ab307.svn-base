<?php
include '../../startup.php';
include '../../model/role.php';

header('Content-Type: application/json; charset=utf-8');

// handle actions
switch ($_act) {
	case 'edit' :
		if ($_form->userHasFunction('edit')) {
			$val = validateInput();
			if ($val=='ok') {
				$res = editRole($_POST);
				$_response['status'] = 1;
				$_response['message'] = sprintf(MSG_EDIT_ITEM_SUCCESS,3);
				$_response['redirect'] = HTTPS_SERVER.'forms/admin/role_list.php';
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
	if (!isset($_POST['roleid']) || empty($_POST['roleid'])) {
		$err[] = '<li>Missing Role ID</li>';
	} else {
		if (!isset($_POST['roledesc']) || empty($_POST['roledesc'])) {
			$err[] = '<li>Role description must be filled</li>';
		}
		if (!isset($_POST['begineff']) || empty($_POST['begineff'])) {
			$err[] = '<li>Begin effective date must be filled</li>';
		}
		if (!isset($_POST['lasteff']) || empty($_POST['lasteff'])) {
			$err[] = '<li>Last effective date must be filled</li>';
		}
	}
	
	if (count($err)>0) {
		return '<ul>'.implode('',$err).'</ul>';
	} else return 'ok';
	
}

?>