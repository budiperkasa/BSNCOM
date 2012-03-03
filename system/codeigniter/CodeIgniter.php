<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * System Front Controller
 *
 * Loads the base classes and executes the request.
 *
 * @package		CodeIgniter
 * @subpackage	codeigniter
 * @category	Front-controller
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/
 */

// CI Version
define('CI_VERSION',	'1.7.1');

/*
 * ------------------------------------------------------
 *  Load the global functions
 * ------------------------------------------------------
 */
require(BASEPATH.'codeigniter/Common'.EXT);

/*
 * ------------------------------------------------------
 *  Load the compatibility override functions
 * ------------------------------------------------------
 */
require(BASEPATH.'codeigniter/Compat'.EXT);

/*
 * ------------------------------------------------------
 *  Load the framework constants
 * ------------------------------------------------------
 */
require(BASEPATH.'config/constants'.EXT);

/*
 * ------------------------------------------------------
 *  Define a custom error handler so we can log PHP errors
 * ------------------------------------------------------
 */
set_error_handler('_exception_handler');
if (version_compare(PHP_VERSION, '5.3.0', '<'))
	set_magic_quotes_runtime(0); // Kill magic quotes

/*
 * ------------------------------------------------------
 *  Start the timer... tick tock tick tock...
 * ------------------------------------------------------
 */

$BM =& load_class('Benchmark');
$BM->mark('total_execution_time_start');
$BM->mark('loading_time_base_classes_start');


require(BASEPATH.'codeigniter/Base5'.EXT);

// Load the base controller class
load_class('Controller', FALSE);

// Load hook functions, system settings, site settings, laguage constants.
$components = load_class('Components_loader');

/*
 * ------------------------------------------------------
 *  Instantiate the base classes
 * ------------------------------------------------------
 */

$CFG =& load_class('Config');
$URI =& load_class('URI');
$RTR =& load_class('Router');
$OUT =& load_class('Output');

// Set a mark point for benchmarking
$BM->mark('loading_time_base_classes_end');


/*
 * ------------------------------------------------------
 *  Security check
 * ------------------------------------------------------
 *
 *  None of the functions in the app controller or the
 *  loader class can be called via the URI, nor can
 *  controller functions that begin with an underscore
 */
$module  = $RTR->fetch_module();
$class  = $RTR->fetch_class() . 'Controller';
$method = $RTR->fetch_method();

include(MODULES_PATH . $RTR->fetch_module() . '/controllers/' . $RTR->fetch_class() . '_controller' .EXT);

if ( ! class_exists($class)
	OR $method == 'controller'
	OR strncmp($method, '_', 1) == 0
	OR in_array(strtolower($method), array_map('strtolower', get_class_methods('Controller')))
	)
{
	log_message('error', 'Can not find the requested class');
	show_404("{$class}/{$method}");
}

/*
 * ------------------------------------------------------
 *  Instantiate the controller and call requested method
 * ------------------------------------------------------
 */
$CI = new $class($components);

if ( ! in_array(strtolower($method), array_map('strtolower', get_class_methods($CI)))) {
	log_message('error', 'Can not find the requested class');
	show_404("{$class}/{$method}");
}

// Mark a start point so we can benchmark the controller
$BM->mark('controller_execution_time_( '.$class.' / '.$method.' )_start');

// Call the requested method.
// Any URI segments present (besides the class/function) will be passed to the method for convenience
call_user_func_array(array(&$CI, $method), $URI->args);


// Mark a benchmark end point
$BM->mark('controller_execution_time_( '.$class.' / '.$method.' )_end');


/*
 * ------------------------------------------------------
 *  Send the final rendered output to the browser
 * ------------------------------------------------------
 */
$OUT->_display();


/*
 * ------------------------------------------------------
 *  Close the DB connection if one exists
 * ------------------------------------------------------
 */
if (class_exists('CI_DB') AND isset($CI->db))
{
	$CI->db->close();
}

/*echo '
<br /><br /><i>Total execution time: ' . $BM->elapsed_time('total_execution_time_start') . '</i>';*/


/* End of file CodeIgniter.php */
/* Location: ./system/codeigniter/CodeIgniter.php */