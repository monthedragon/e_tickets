<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');



/*
|--------------------------------------------------------------------------
| 
|--------------------------------------------------------------------------
/**Create list of define here for specific project**/
define('PROJECT_NAME','BANKARD SYSTEM'); //set this based on the project name
define('CLIENT_NAME','');


/*
|--------------------------------------------------------------------------
| 
|--------------------------------------------------------------------------
|
| CALLRESULT TAGGING
|
*/

define('CB_TAG','CB');
define('NI_TAG','NI');
define('AG_TAG','AG');


//if you wnat not to see the page controller and action add here!!
define('DEBUG_URI_EXEMPT','security/do_login' );
define('DEBUG',FALSE);
define('MAIN_DB','template_db');


define('SEND_EMAIL',FALSE);
define('ITEM_PER_PAGE',20);
define('LIMIT',50);
define('VIRGIN_CODE','V');
define('ADMIN_CODE','admin');
define('CONTACT_LIST','contact_list');
define('CONTACT_LIST_XLS','Contact List');
define('CR_WITH_SUB_CR','NI,CB'); //list of callresult with sub_callresult (CSV)
define('CC_FIELD','card_number'); //if this field is shown in "field display" then the CC display will be the last four digits only






/* End of file constants.php */
/* Location: ./application/config/constants.php */