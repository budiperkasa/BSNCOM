<?php

// We work only with PHP5
// If PHP4, then exit
if (version_compare(PHP_VERSION, '5.0.0', '<'))
	exit('Web 2.0 Directory requires PHP5! You have PHP4, upgrade please.');

/*
|---------------------------------------------------------------
| define web 2.0 directory version
|---------------------------------------------------------------
*/
define('W2D_VERSION', '3.1.0');

/*
|---------------------------------------------------------------
| PHP ERROR REPORTING LEVEL
|---------------------------------------------------------------
|
| By default CI runs with error reporting set to ALL.  For security
| reasons you are encouraged to change this when your site goes live.
| For more info visit:  http://www.php.net/error_reporting
|
*/
	error_reporting(E_ALL);

/*
|---------------------------------------------------------------
| SYSTEM FOLDER NAME
|---------------------------------------------------------------
|
| This variable must contain the name of your "system" folder.
| Include the path if the folder is not in the same  directory
| as this file.
|
| NO TRAILING SLASH!
|
*/
	$system_folder = "system";

/*
|---------------------------------------------------------------
| SET THE SERVER PATH
|---------------------------------------------------------------
|
| Let's attempt to determine the full-server path to the "system"
| folder in order to reduce the possibility of path problems.
| Note: We only attempt this if the user hasn't specified a 
| full server path.
|
*/
if (strpos($system_folder, '/') === FALSE)
{
	if (function_exists('realpath') AND @realpath(dirname(__FILE__)) !== FALSE)
	{
		$system_folder = realpath(dirname(__FILE__)) . '/' . $system_folder;
	}
}
else
{
	// Swap directory separators to Unix style for consistency
	$system_folder = str_replace("\\", "/", $system_folder); 
}

/*
|---------------------------------------------------------------
| DEFINE APPLICATION CONSTANTS
|---------------------------------------------------------------
|
| EXT		- The file extension.  Typically ".php"
| FCPATH	- The full server path to THIS file
| SELF		- The name of THIS file (typically "index.php")
| BASEPATH	- The full server path to the "system" folder
|
*/
define('EXT', '.'.pathinfo(__FILE__, PATHINFO_EXTENSION));
define('FCPATH', __FILE__);
define('ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('MODULES_PATH', ROOT . 'modules' . DIRECTORY_SEPARATOR);
define('THEMES_PATH', ROOT . 'themes' . DIRECTORY_SEPARATOR);
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('BASEPATH', $system_folder . DIRECTORY_SEPARATOR);
define('LANGPATH', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR);

date_default_timezone_set('UTC');


/*
|---------------------------------------------------------------
| Set utf-8 charset header
|---------------------------------------------------------------
|
|
*/
header('Content-Type: text/html; charset=utf-8');

/*
|---------------------------------------------------------------
| LOAD THE FRONT CONTROLLER
|---------------------------------------------------------------
|
| And away we go...
|
*/
require_once BASEPATH.'codeigniter/CodeIgniter'.EXT;

/* End of file index.php */
/* Location: ./index.php */