<?php
// GLOBALS
// setting khusus localhost / github base
define('HTTP_SERVER', 'http://localhost:88/Andramatin/ts/');
define('HTTPS_SERVER', 'http://localhost:88/Andramatin/ts/');

// setting khusus untuk andramatin
//define('HTTP_SERVER', 'http://192.168.0.120:81/phpfw/');
//define('HTTPS_SERVER', 'http://192.168.0.120:81/phpfw/');

define('DEFAULT_TIMEZONE', 'Asia/Jakarta');
define('DATEPICKER_FORMAT', 'yyyy-mm-dd');

// DIR khusus andramatin
// define('DIR_APPLICATION', 'C:\wamp\www\ts/');
// define('DIR_SYSTEM', 'C:\wamp\www\ts/');
// define('DIR_DATABASE', 'C:\wamp\www\ts/database/');
// define('DIR_IMAGE', 'C:\wamp\www\ts/image/');
// define('DIR_LOGS', 'C:\wamp\www\ts/logs/');

// DIR
define('DIR_APPLICATION', 'D:\wampserver\www\Andramatin\ts/');
define('DIR_SYSTEM', 'D:\wampserver\www\Andramatin\ts/');
define('DIR_DATABASE', 'D:\wampserver\www\Andramatin\ts/database/');
define('DIR_IMAGE', 'D:\wampserver\www\Andramatin\ts/image/');
define('DIR_LOGS', 'D:\wampserver\www\Andramatin\ts/logs/');

// DB
define('DB_DRIVER', 'mysql');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'langit_php');
define('DB_PREFIX', 'phpfw_');
define('DB_PREFIX_APP', 'amts');
define('DB_PREFIX_ABSEN', 'am_ts_');

// COMMON MESSAGES
define('MSG_TASK_NOT_AUTHORIZED', 'You are not authorized to do this task.');
define('MSG_ERROR_OCCURED', 'An error has occured. Please try again later.');
define('MSG_SELECT_ITEM', 'Please select one or more items.');
define('MSG_ADD_ITEM_SUCCESS', "Items added successfully. <br/>Refreshing data in %d seconds, or hit browser's refresh button...");
define('MSG_EDIT_ITEM_SUCCESS', "Items updated successfully. <br/>Refreshing data in %d seconds, or hit browser's refresh button...");
define('MSG_DELETE_ITEM_SUCCESS', "Items deleted successfully. <br/>Refreshing data in %d seconds, or hit browser's refresh button...");
define('MSG_ERROR_FIELDS', "The following error has occured.");


?>