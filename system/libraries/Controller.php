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
 * CodeIgniter Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/general/controllers.html
 */
class Controller extends CI_Base {

	var $_ci_scaffolding	= FALSE;
	var $_ci_scaff_table	= FALSE;
	
	/**
	 * Constructor
	 *
	 * Calls the initialize() function
	 */
	public function Controller($components)
	{	
		parent::CI_Base();
		$this->_ci_initialize();

		$BM =& load_class('Benchmark');
		$BM->mark('hooks_execution_time_start');

		$hooks = $components->fetch_hooks();
		foreach ($hooks AS $function) {
			self::run_function($function['file'], $function['module_name'], $function['function_name']);
		}
		
		$BM->mark('hooks_execution_time_end');
		
		log_message('debug', "Controller Class Initialized");
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize
	 *
	 * Assigns all the bases classes loaded by the front controller to
	 * variables in this class.  Also calls the autoload routine.
	 *
	 * @access	private
	 * @return	void
	 */
	private function _ci_initialize()
	{
		// Assign all the class objects that were instantiated by the
		// front controller to local class variables so that CI can be
		// run as one big super object.
		$classes = array(
							'config'	=> 'Config',
							'input'		=> 'Input',
							'benchmark'	=> 'Benchmark',
							'uri'		=> 'URI',
							'output'	=> 'Output',
							'load'	    => 'Loader',
							'router'    => 'Router',
							'lang'      => 'Language',
							);
		
		foreach ($classes as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load->_ci_autoloader();
	}
	

	// --------------------------------------------------------------------
	
	/**
	 * Run hook functions and event hook functions
	 *
	 * @param string $file - hook function file
	 * @param string $module_name - the name of module containing proper hook function
	 * @param string $function_name - hook function name
	 * @param array $args
	 */
	public static function run_function($file, $module_name, $function_name, $args = array())
	{
		registry::set('hook_module', $module_name);
		include_once(MODULES_PATH . $module_name . '/controllers/' . $file);
		return $function_name(self::get_instance(), $args);
	}
	
	public function setSuccess($msg)
	{
		$success_msgs = registry::get('success_msgs');
		$success_msgs[] = $msg;
		$this->session->set_flashdata('success_msgs', $success_msgs);
		
		// duplicate messages in registry (for output without redirect)
		registry::set('success_msgs', $success_msgs);
	}
	
	public function setError($msg)
	{
		$error_msgs = registry::get('error_msgs');
		$error_msgs[] = $msg;
		$this->session->set_flashdata('error_msgs', $error_msgs);
		
		// duplicate messages in registry (for output without redirect)
		registry::set('error_msgs', $error_msgs);
	}
}
// END _Controller class

/* End of file Controller.php */
/* Location: ./system/libraries/Controller.php */