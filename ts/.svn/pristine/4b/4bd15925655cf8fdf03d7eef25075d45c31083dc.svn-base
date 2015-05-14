<?php
// Error Reporting
error_reporting(E_ALL);

// Check Version
if (version_compare(phpversion(), '5.1.0', '<') == true) {
	exit('PHP5.1+ Required');
}

include('config.php');

// Register Globals
/*
if (ini_get('register_globals')) {
	ini_set('session.use_cookies', 'On');
	ini_set('session.use_trans_sid', 'Off');
		
	session_set_cookie_params(0, '/');
	
}
*/
@session_start();

// Magic Quotes Fix
if (ini_get('magic_quotes_gpc')) {
	function clean($data) {
   		if (is_array($data)) {
  			foreach ($data as $key => $value) {
    			$data[clean($key)] = clean($value);
  			}
		} else {
  			$data = stripslashes($data);
		}
	
		return $data;
	}			
	
	$_GET = clean($_GET);
	$_POST = clean($_POST);
	$_REQUEST = clean($_REQUEST);
	$_COOKIE = clean($_COOKIE);
}

if (defined('DEFAULT_TIMEZONE')) {
	date_default_timezone_set(DEFAULT_TIMEZONE);
} else 	if (!ini_get('date.timezone')) {
	date_default_timezone_set('UTC');
}

// Windows IIS Compatibility  
if (!isset($_SERVER['DOCUMENT_ROOT'])) { 
	if (isset($_SERVER['SCRIPT_FILENAME'])) {
		$_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0 - strlen($_SERVER['PHP_SELF'])));
	}
}

if (!isset($_SERVER['DOCUMENT_ROOT'])) {
	if (isset($_SERVER['PATH_TRANSLATED'])) {
		$_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0 - strlen($_SERVER['PHP_SELF'])));
	}
}

if (!isset($_SERVER['REQUEST_URI'])) { 
	$_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 1); 
	
	if (isset($_SERVER['QUERY_STRING'])) { 
		$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING']; 
	} 
}

if (!isset($_SERVER['HTTP_HOST'])) {
	$_SERVER['HTTP_HOST'] = getenv('HTTP_HOST');
}

// Helper
require_once(DIR_SYSTEM . 'helper/json.php'); 
require_once(DIR_SYSTEM . 'helper/utf8.php'); 

// Engine
require_once(DIR_SYSTEM . 'library/model.php');
require_once(DIR_SYSTEM . 'library/registry.php');

// Common
require_once(DIR_SYSTEM . 'library/url.php');
require_once(DIR_SYSTEM . 'library/config.php');
require_once(DIR_SYSTEM . 'library/db.php');
require_once(DIR_SYSTEM . 'library/encryption.php');
require_once(DIR_SYSTEM . 'library/image.php');
require_once(DIR_SYSTEM . 'library/language.php');
require_once(DIR_SYSTEM . 'library/log.php');
require_once(DIR_SYSTEM . 'library/mail.php');
require_once(DIR_SYSTEM . 'library/session.php');
require_once(DIR_SYSTEM . 'library/user.php');
require_once(DIR_SYSTEM . 'library/form.php');
require_once(DIR_SYSTEM . 'library/pagination.php');


////////////////////// INITIALIZATIONS

// Version
define('VERSION', '1.0.0');

// Configuration
if (file_exists('config.php')) {
	require_once('config.php');
}  

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// Config
$_config = new Config();
$registry->set('config', $_config);


// Database 
$_db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DEFAULT_TIMEZONE);
$registry->set('db', $_db);
		
// Settings
$query = $_db->query("SELECT * FROM " . DB_PREFIX . "setting s ORDER BY s.group ASC");

foreach ($query->rows as $setting) {
	if (!$setting['serialized']) {
		$_config->set($setting['key'], $setting['value']);
	} else {
		$_config->set($setting['key'], unserialize($setting['value']));
	}
}


// Url
$_url = new Url($_config->get('config_url'), $_config->get('config_secure') ? $_config->get('config_ssl') : $_config->get('config_url'));	
$registry->set('url', $_url);

// Log 
$_log = new Log($_config->get('config_error_filename'));
$registry->set('log', $_log);

function error_handler($errno, $errstr, $errfile, $errline) {
	global $_log, $_config;
	
	switch ($errno) {
		case E_NOTICE:
		case E_USER_NOTICE:
			$error = 'Notice';
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$error = 'Warning';
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$error = 'Fatal Error';
			break;
		default:
			$error = 'Unknown';
			break;
	}
		
	if ($_config->get('config_error_display')) {
		echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
	}
	
	if ($_config->get('config_error_log')) {
		$_log->write('PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
	}

	return true;
}
	
// Error Handler
set_error_handler('error_handler');

// Request
//$request = new Request();
//$registry->set('request', $request);
 
// Response
//$response = new Response();
//$response->addHeader('Content-Type: text/html; charset=utf-8');
//$response->setCompression($_config->get('config_compression'));
//$registry->set('response', $response); 
		
// Cache
//$cache = new Cache();
//$registry->set('cache', $cache); 

// Session
$_session = new Session();
$registry->set('session', $_session);

// User
$_user = new User($registry);
$registry->set('user', $_user);

// form action
$_act = isset($_GET['act'])? strtolower($_GET['act']) : (isset($_POST['act'])? strtolower($_POST['act']) : '');

// User
$_form = new Form($registry);
$registry->set('form', $_form);

// Pagination
$_pagination = new Pagination();
$_pagination->page = 1;
$_pagination->total = 0;
$_pagination->url = preg_replace('/page=\d+/i','',$_SERVER['REQUEST_URI']).(empty($_SERVER['QUERY_STRING'])? '?page={page}' : '&page={page}');
$_pagination->limit = (int) $_config->get('items_per_page')>0? (int) $_config->get('items_per_page') : 20;


// form response
$_response = array();
$_response['status'] = 1;
$_response['message'] = 0;
$_response['action'] = '';
$_response['redirect'] = '';
$_response['targetClass'] = ''; // hanya digunakan untuk area refresh lookup / popup secara ajax  



?>