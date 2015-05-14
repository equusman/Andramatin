<?php
class User {
	private $user_id;
	private $username;
	private $displayname;
	private $ip;
	private $session;
  	private $roles = array();
  	private $permission = array();

  	public function __construct($registry) {
		global $_SERVER, $_SESSION;
		$this->db = $registry->get('db');
		//$this->request = $registry->get('request');
		$this->session = $registry->get('session');
		//print_r($this->session);
		//print_r($_SESSION);
		
    	if (isset($this->session->data['user_id'])) {
			if (isset($this->session->data['user_name']) && isset($this->session->data['user_ip'])) {
				$this->user_id = $this->session->data['user_id'];
				$this->username = $this->session->data['user_name'];
				$this->displayname = $this->session->data['display_name'];
				$this->ip = $this->session->data['user_ip'];
				$this->roles = $this->session->data['user_roles'];
			} else {
				$user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "mstusers WHERE vuserID = '" . (int)$this->session->data['user_id'] . "' AND DATE(SYSDATE()) BETWEEN dbegineff AND dlasteff ");
				
				if ($user_query->num_rows) {
					$this->user_id = $user_query->row['vuserID'];
					$this->username = $user_query->row['vusername'];
					$this->displayname = $user_query->row['vdisplayname'];
					$this->ip = $_SERVER['REMOTE_ADDR'];
					
					$user_group_query = $this->db->query("SELECT r.vroleid, r.vroledesc FROM " . DB_PREFIX . "mstuserroles ur, " . DB_PREFIX . "mstroles r 
					WHERE r.vRoleid=ur.vroleid AND DATE(SYSDATE()) BETWEEN r.dbegineff AND r.dlasteff AND ur.vUserid = '" . (int)$this->session->data['user_id'] . "'");
					
					if (isset($user_group_query->num_rows)) {
						foreach ($user_group_query->rows as $row) {
							$this->roles[$row['vroleid']] = $row['vroledesc'];
						}
						$this->session->data['user_roles'] = $this->roles;
					}
				} else {
					$this->logout();
				}
			}
    	}
  	}
		
  	public function login($username, $password) {
		global $_SERVER, $_SESSION;
    	$user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "mstusers WHERE vusername = '" . $this->db->escape($username) . "' AND vpassword = SHA1('" . $this->db->escape($password) . "') AND DATE(SYSDATE()) BETWEEN dbegineff AND dlasteff ");

    	if ($user_query->num_rows) {
			//print_r($user_query);
			$this->session->data['user_id'] = $user_query->row['vuserID'];
			$this->session->data['user_name'] = $user_query->row['vusername'];
			$this->session->data['display_name'] = $user_query->row['vdisplayname'];
			$this->session->data['user_ip'] = $_SERVER['REMOTE_ADDR'];
			$this->session->data['user_roles'] = array();
			
			$this->user_id = $user_query->row['vuserID'];
			$this->username = $user_query->row['vusername'];
			$this->displayname = $user_query->row['vdisplayname'];
			$this->ip = $_SERVER['REMOTE_ADDR'];

			$user_group_query = $this->db->query("SELECT r.vroleid, r.vroledesc FROM " . DB_PREFIX . "mstuserroles ur, " . DB_PREFIX . "mstroles r 
			WHERE r.vRoleid=ur.vroleid AND DATE(SYSDATE()) BETWEEN r.dbegineff AND r.dlasteff AND ur.vUserid = '" . (int)$this->session->data['user_id'] . "'");
			
			if (isset($user_group_query->num_rows)) {
				foreach ($user_group_query->rows as $row) {
					$this->roles[$row['vroleid']] = $row['vroledesc'];
				}
				$this->session->data['user_roles'] = $this->roles;
				//$_SESSION['data'] = $this->session->data;
				return true;
			} else {
				$_SESSION['data'] = $this->session->data;
				return false;
			}	
			
		} else {
      		return false;
    	}
  	}

  	public function logout() {
		unset($this->session->data['user_id']);
		unset($this->session->data['user_name']);
		unset($this->session->data['display_name']);
		unset($this->session->data['user_ip']);
		unset($this->session->data['user_roles']);
	
		$this->user_id = '';
		$this->username = '';
		$this->ip = '';
		$this->roles = array();
		
		session_destroy();
  	}

  	public function logoutAndRedirect() {
    	if(substr($_SERVER['SCRIPT_NAME'], -strlen('login.php')) !== 'login.php') header("Location: ".HTTPS_SERVER."forms/login.php");
  	}
  
  	public function hasRole($key) {
    	return isset($this->roles[$key]);
  	}
  
  	public function isLogged() {
    	return (isset($this->user_id) && !empty($this->user_id) && ($this->user_id!=null));
  	}
  
  	public function getId() {
    	return $this->user_id;
  	}
	
  	public function getUserName() {
    	return $this->username;
  	}	

  	public function getUserDisplayName() {
    	return $this->displayname;
  	}	

  	public function getUserIp() {
    	return $this->user_ip;
  	}	

  	public function getUserRoles() {
    	return $this->roles;
  	}	

}
?>