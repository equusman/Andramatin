<?php
include '../../startup.php';
include '../../model/user.php';

header('Content-Type: application/json; charset=utf-8');

// handle actions
switch ($_act) {
	case 'edit' :
		if ($_form->userHasFunction('edit')) {
			$val = validateInput();
			if ($val=='ok') {
				$res = resetPassword($_POST);
				$_response['status'] = 1;
				$_response['message'] = sprintf(MSG_ADD_ITEM_SUCCESS,3);
				$_response['redirect'] = HTTPS_SERVER.'forms/admin/user_list.php';
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
	if (!isset($_POST['userid']) || empty($_POST['userid'])) {
		$err[] = '<li>Invalid User ID</li>';
	}
	// if (!isset($_POST['oldpassword']) || empty($_POST['oldpassword'])) {
		// $err[] = '<li>Current password must be filled</li>';
	// }
	if (!isset($_POST['newpassword']) || empty($_POST['newpassword'])) {
		$err[] = '<li>New password must be filled</li>';
	}
	if (!isset($_POST['confirmpassword']) || empty($_POST['confirmpassword'])) {
		$err[] = '<li>Confirm Password must be filled</li>';
	}
	if (isset($_POST['newpassword']) && !empty($_POST['newpassword'])) {
		if (isset($_POST['confirmpassword']) && !empty($_POST['confirmpassword'])) {
			if ($_POST['confirmpassword'] != $_POST['newpassword']) {
				$err[] = '<li>New password adn Confirm password not match</li>';
			}
		}
	}	
	if (count($err)>0) {
		return '<ul>'.implode('',$err).'</ul>';
	} else return 'ok';
}

?>