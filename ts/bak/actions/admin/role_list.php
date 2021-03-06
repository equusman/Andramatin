<?php
	include '../../startup.php';
	include '../../model/role.php';
	
	header('Content-Type: application/json; charset=utf-8');
	
	// get query strings
	$qs = '';
	if (isset($_POST['filter_role'])) {
		$qs = 'filter_role='.$_POST['filter_role'];
	}
	if (isset($_POST['page'])) {
		$qs = empty($qs)? 'page='.$_POST['page'] : '&page='.$_POST['page'];
	}
	
	// handle actions
	switch ($_act) {
		case 'delete' :
			if ($_form->userHasFunction('delete')) {
				if (isset($_POST['ids'])) {
					if (deleteRoles($_POST['ids'])) {
						$_response['status'] = 1;
						$_response['message'] = sprintf(MSG_DELETE_ITEM_SUCCESS,3);
						$_response['redirect'] = HTTPS_SERVER.'forms/admin/role_list.php?'.$qs;
						$_response['redirect-time'] = 3000;
					} else {
						$_response['status'] = 0;
						$_response['message'] = MSG_ERROR_OCCURED;
					}
					
				} else {
					$_response['status'] = 0;
					$_response['message'] = MSG_SELECT_ITEM;
				}
			} else {
					$_response['status'] = 0;
					$_response['message'] = MSG_TASK_NOT_AUTHORIZED;
			}
			break;
		default :
			$_response['status'] = 1;
			$_response['message'] = '';
			$_response['redirect'] = HTTPS_SERVER.'forms/admin/role_list.php?'.$qs;
			break;
	}

	echo json_encode($_response);

?>