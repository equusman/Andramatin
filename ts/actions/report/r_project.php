<?php
	include '../../startup.php';
	include '../../model/user.php';
	
	header('Content-Type: application/json; charset=utf-8');
	
	// get query strings
	$qs = '';
	if (isset($_POST['filter'])) {
		$qs = empty($qs)? 'filter='.$_POST['filter'] : '&filter='.$_POST['filter'];
	}
	if (isset($_POST['page'])) {
		$qs = empty($qs)? 'page='.$_POST['page'] : $qs.'&page='.$_POST['page'];
	}
	if (isset($_POST['act']) && ($_POST['act']=='export')) {
		$qs = empty($qs)? 'export=1' : $qs.'&export=1';
	}
	
	// handle actions
	// switch ($_act) {
		// case 'delete' :
			// if ($_form->userHasFunction('delete')) {
				// if (isset($_POST['ids'])) {
					// // if (deleteUsers($_POST['ids'])) {
						// // $_response['status'] = 1;
						// // $_response['message'] = sprintf(MSG_DELETE_ITEM_SUCCESS,3);
						// // $_response['redirect'] = HTTPS_SERVER.'forms/amts/projectlist.php?'.$qs;
						// // $_response['redirect-time'] = 3000;
					// // } else {
						// // $_response['status'] = 0;
						// // $_response['message'] = MSG_ERROR_OCCURED;
					// // }
					// $_1=1;
				// } else {
					// $_response['status'] = 0;
					// $_response['message'] = MSG_SELECT_ITEM;
				// }
			// } else {
					// $_response['status'] = 0;
					// $_response['message'] = MSG_TASK_NOT_AUTHORIZED;
			// }
			// break;
		// default :
			// break;
	// }
	if (isset($_POST['act']) && ($_POST['act']=='detail')) {
		include '../../model/project.php';

		$params['project'] = $_POST['pid'];
		$project = getProjectDetailByID($params);
		$members = getProjectMemberByID($params);
		$phase = getProjectPhaseByID($params);
		
		//echo 'test html_response';
		/*
?>




<?php		
*/
	} else {
			$_response['status'] = 1;
			$_response['message'] = '';//HTTPS_SERVER.'forms/amts/projectlist.php?'.$qs;
			$_response['redirect'] = HTTPS_SERVER.'forms/report/r_project.php?'.$qs;
			echo json_encode($_response);
	
		// $_response['status'] = 1;
		// $_response['message'] = '';
		// $_response['redirect'] = HTTPS_SERVER.'forms/report/r_project.php?'.$qs;
		// echo json_encode($_response);
	}

?>