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
		case 'assign' :
			if ($_form->userHasFunction('assign')) {
				$val = validateInput();
				if ($val=='ok') {
					$res = getMyOpenActivity($_POST);
					$_response['status'] = 1;
					$_response['message'] = sprintf(MSG_EDIT_ITEM_SUCCESS,3);
					//$_response['redirect'] = HTTPS_SERVER.'forms/amts/projectnew.php';
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
		default :
			$_response['status'] = 1;
			$_response['message'] = '';
			$_response['redirect'] = HTTPS_SERVER.'forms/amts/taskpick.php?'.$qs;
			break;
	}
	echo json_encode($_response);

/// SUPPORTING FUNCTIONS
function validateInput(){
	global $_POST;
	$err = array();
	// if (!isset($_POST['project_number']) || empty($_POST['project_number'])) {
		// $err[] = '<li>Project number must be filled</li>';
	// }
	// if (!isset($_POST['project_name']) || empty($_POST['project_name'])) {
		// $err[] = '<li>Project name must be filled</li>';
	// }
	// if (!isset($_POST['project_manager']) || empty($_POST['project_manager'])) {
		// $err[] = '<li>Project manager must be filled</li>';
	// }
	// if (!isset($_POST['startdate']) || empty($_POST['startdate'])) {
		// $err[] = '<li>Start date must be filled</li>';
	// }
	// if (!isset($_POST['estimatedmanhour']) || empty($_POST['estimatedmanhour'])) {
		// $err[] = '<li>Estimated man hour must be filled</li>';
	// }
	
	if (count($err)>0) {
		return '<ul>'.implode('',$err).'</ul>';
	} else return 'ok';
	
}
	
?>