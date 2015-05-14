<?php
class Form {
	private $user_id;
	private $username;
	private $ip;
	private $txt_roles;
	private $session;
	private $is_authorized;
	private $form_id;
	private $form_name;
	private $form_desc;
	private $form_url;
	private $form_menu_path;
  	private $functions = array();
  	private $public_forms = array('home.php','header.php','footer.php','login.php','menu.php','index.php','denied.php','404.php');

  	public function __construct($registry) {
		global $_SERVER, $_SESSION, $_PAGE_TITLE, $_PAGE_CLASSES;
		$_PAGE_CLASSES = 'base-page';
		$_PAGE_TITLE = '';
		
		$this->db = $registry->get('db');
		$this->session = $registry->get('session');
		$_usr = $registry->get('user');
		$this->is_authorized = false;
		$_log = $registry->get('log');
		$vroles = array('0');
		$this->txt_roles = '';
		
    	if (isset($this->session->data['user_id'])) {
			if (isset($this->session->data['user_name']) && isset($this->session->data['user_ip'])) {
				$this->user_id = $this->session->data['user_id'];
				$this->username = $this->session->data['user_name'];
				$this->ip = $this->session->data['user_ip'];
				$this->roles = $this->session->data['user_roles'];
				
				foreach ($this->roles as $key=>$val) {
					$vroles[] = $this->db->escape($key);
				}
				$this->txt_roles = "'".implode("','",$vroles)."'";

				// get form id 
				$is_global_form = false;
				foreach($this->public_forms as $form) {
					if(substr($_SERVER['SCRIPT_NAME'], -strlen($form)) === $form) {
						$is_global_form = true;
						break;
					}
				}

				if (!$is_global_form) {
					$form_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "mstforms WHERE '".$this->db->escape($_SERVER['SCRIPT_NAME'])."' LIKE CONCAT('%',vformurl) AND nenable='1' LIMIT 0,1 ");
					if ($form_query->num_rows) {
						$this->form_id = $form_query->row['vformid'];
						$this->form_name = $form_query->row['vformname'];
						$this->form_desc = $form_query->row['vformdesc'];
						$this->form_url = $form_query->row['vformurl'];
						$this->form_menu_path = $form_query->row['vmenupath'];
						
						$_PAGE_TITLE = $this->form_name;
						
						// get form functions
						$func_query = $this->db->query("SELECT r.vformid, r.vfunctionid, f.vfunctionname, f.vfunctiondesc FROM " . DB_PREFIX . "mstformroles r LEFT JOIN " . DB_PREFIX . "mstfunctions f ON f.vfunctionid=r.vfunctionid WHERE r.vroleid IN (".$this->txt_roles.") AND r.vformid = '".$this->db->escape($form_query->row['vformid'])."' ");
						if ($func_query->num_rows) {
							foreach($func_query->rows as $row) {
								$this->functions[$row['vfunctionid']] = array(
									'name' =>  $row['vfunctionname'],
									'desc' =>  $row['vfunctiondesc']
								);
							}
							$this->is_authorized = true;
						} else {
							$this->is_authorized = false;
							$this->deniedAndRedirect();
						}
					} else {
						$this->is_authorized = false;
						$this->deniedAndRedirect();
					}
				} else {
					$this->is_authorized = true; 
				}
			} else {
				$this->is_authorized = false;
				$_usr->logoutAndRedirect();
			}
    	} else {
			$_usr->logoutAndRedirect();
			
		}
  	}
	
	
  	public function deniedAndRedirect() {
    	if(substr($_SERVER['SCRIPT_NAME'], -strlen('denied.php')) !== 'denied.php') header("Location: ".HTTPS_SERVER."forms/denied.php");
  	}

  	public function getAuthorizedFunctions() {
		return $this->functions;
  	}

  	public function userHasFunction($funcname, $formurl='') {
		if ($formurl!='') {
			$frm = $this->db->query("SELECT 1  FROM phpfw_mstformroles fr, phpfw_mstforms f, phpfw_mstfunctions c WHERE f.vformid=fr.vformid AND fr.vfunctionid = c.vfunctionid AND vroleid IN (".$this->txt_roles.") AND vformurl = '".$this->db->escape($formurl)."' AND vfunctionname = '".$this->db->escape($funcname)."' LIMIT 0,1");
			if ($frm->num_rows) return true;
		} else {
			$found = false;
			foreach ($this->functions as $key=>$val) {
				if ($val['name']==$funcname) return true;
			}
		}
		return false;
  	}
	
	public function userHasAccess($formurl) {
		$frm = $this->db->query("SELECT 1 AS `status` FROM phpfw_mstformroles fr, phpfw_mstforms f WHERE f.vformid=fr.vformid AND vroleid IN (".$this->txt_roles.") AND vformurl = '".$this->db->escape($formurl)."' LIMIT 0,1");
		if ($frm->num_rows) return true;
		else return false;
	}

  	public function getFormId() {
		return $this->form_id;
  	}

  	public function getFormName() {
		return $this->form_name;
  	}

  	public function getFormDesc() {
		return $this->form_desc;
  	}

  	public function isFormAuthorized() {
    	return $this->is_authorized;
  	}
  
	public function getHtmlMenu(){
		if (!$this->is_authorized) return '';
		$arpath = $this->getMenuPaths();
		$txtmenu = '<!--[[ROOT]]-->';
		//print_r($arpath);
		foreach($arpath as $menu) {
			$arpath = explode('>',$menu['vmenupath']);
			$ppath = '';
			for($a=0; $a<count($arpath); $a++) {
				$link = '#';
				if ($a==count($arpath)-1) $link = HTTPS_SERVER.'forms/'.$menu['vformurl'];
				if ($a==0) {
					if ((strpos($txtmenu,'<!--[[MENU:'.$arpath[$a].']]-->')===false) && (strpos($txtmenu,'<!--[[SUBMENU:'.$arpath[$a].']]-->')===false)) {
						$txtmenu = str_replace('<!--[[ROOT]]-->','<div class="element"><a class="dropdown-toggle" href="'.$link.'">'.$arpath[$a].'</a><!--[[MENU:'.$arpath[$a].']]--></div><!--[[ROOT]]-->',$txtmenu);
					}
				} else {
					$drole = $a<2? ' data-role="dropdown"' : '';
					$dlink = $a==count($arpath)-1? '' : ' class="dropdown-toggle"';
					if ((strpos($txtmenu,'<!--[[MENU:'.$arpath[$a].']]-->')===false) && (strpos($txtmenu,'<!--[[MENU:'.$ppath.']]-->')!==false)) {
						$txtmenu = str_replace('<!--[[MENU:'.$ppath.']]-->','<ul class="dropdown-menu" '.$drole.'><li><a href="'.$link.'" '.$dlink.'>'.$arpath[$a].'</a><!--[[MENU:'.$arpath[$a].']]--></li><!--[[SUBMENU:'.$ppath.']]--></ul>',$txtmenu);
					} else if ((strpos($txtmenu,'<!--[[SUBMENU:'.$arpath[$a].']]-->')===false ) && (strpos($txtmenu,'<!--[[SUBMENU:'.$ppath.']]-->')!==false )) {
						$txtmenu = str_replace('<!--[[SUBMENU:'.$ppath.']]-->','<li><a '.$dlink.' href="'.$link.'">'.$arpath[$a].'</a><!--[[MENU:'.$arpath[$a].']]--></li><!--[[SUBMENU:'.$ppath.']]-->',$txtmenu);
					}
				}
				$ppath = $arpath[$a];
			}
		}
		return $txtmenu;
	}
	
	public function getMenuPaths(){
		if (!$this->is_authorized) return array();
		$vroles = array('0');
		$this->txt_roles = '';
		foreach ($this->roles as $key=>$val) {
			$vroles[] = $this->db->escape($key);
		}
		$this->txt_roles = "'".implode("','",$vroles)."'";

		$menu_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "mstforms WHERE vformid IN ( SELECT vformid FROM " . DB_PREFIX . "mstformroles WHERE vroleid IN (".$this->txt_roles.") ) AND vmenupath != '' AND vmenupath IS NOT NULL");
		if ($menu_query->num_rows) {
			return $menu_query->rows;
		} else {
			return array();
		}
	}
}
?>