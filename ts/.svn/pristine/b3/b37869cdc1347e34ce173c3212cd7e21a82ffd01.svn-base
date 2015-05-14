<?php
	include '../startup.php';
	
	header('Content-Type: application/json; charset=utf-8');
	
	switch ($_act) {
		case 'login' :
			validateLogin();
			break;
		case 'logout' :
			$_user->logout();
			$_response['status'] = 1;
			$_response['message'] = 'Successfully logged out.';
			$_response['redirect'] = HTTPS_SERVER.'forms/login.php';
			break;
	}

	echo json_encode($_response);



	/**********************************
	 * Helper functions
	 **********************************/	
	function validateLogin(){
		global $_POST, $_response, $_user;
		if (!isset($_POST['username']) || empty($_POST['username'])) {
			$_response['status'] = 0;
			$_response['message'] = 'Username must be filled.';
			
		} else if (!isset($_POST['password']) || empty($_POST['password'])) {
			$_response['status'] = 0;
			$_response['message'] = 'Password must be filled.';
		} else {
			if ($_user->login($_POST['username'], $_POST['password'])) {
				$_response['status'] = 1;
				$_response['message'] = 'Redirecting...';
				$_response['redirect'] = 'home.php';
			} else {
				$_response['status'] = 0;
				$_response['message'] = 'Invalid username / password.';
			}
		}
		
	}
?>