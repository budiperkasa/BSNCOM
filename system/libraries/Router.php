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
 * Router Class
 *
 * Parses URIs and determines routing
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @author		ExpressionEngine Dev Team
 * @category	Libraries
 * @link		http://codeigniter.com/user_guide/general/routing.html
 */
class CI_Router {

	var $config;	
	var $routes 		= array();
	var $error_routes	= array();
	var $module			= '';
	var $class			= '';
	var $method			= 'index';
	var $uri_protocol 	= 'auto';
	var $default_controller;
	var $scaffolding_request = FALSE; // Must be set to FALSE
	
	/**
	 * Constructor
	 *
	 * Runs the route mapping function.
	 */
	function CI_Router()
	{
		$this->config = &load_class('Config');
		$this->uri =& load_class('URI');
		$this->_set_routing();
		log_message('debug', "Router Class Initialized");
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Set the route mapping
	 *
	 * This function determines what should be served based on the URI request,
	 * as well as any "routes" that have been set in the routing config file.
	 *
	 * @access	private
	 * @return	void
	 */
	function _set_routing()
	{
		// Are query strings enabled in the config file?
		// If so, we're done since segment based URIs are not used with query strings.
		if ($this->config->item('enable_query_strings') === TRUE AND isset($_GET[$this->config->item('controller_trigger')]))
		{
			$this->set_class(trim($this->uri->_filter_uri($_GET[$this->config->item('controller_trigger')])));

			if (isset($_GET[$this->config->item('function_trigger')]))
			{
				$this->set_method(trim($this->uri->_filter_uri($_GET[$this->config->item('function_trigger')])));
			}
			
			return;
		}
		
		// Load the routes.php file.
		//@include(APPPATH.'config/routes'.EXT);
		$route = registry::get('route');
		
		$this->routes = ( ! isset($route) OR ! is_array($route)) ? array() : $route;
		unset($route);

		// Set the default controller so we can display it in the event
		// the URI doesn't correlated to a valid controller.
		$this->default_controller = ( ! isset($this->routes['default_controller']) OR $this->routes['default_controller'] == '') ? FALSE : strtolower($this->routes['default_controller']);	
		
		// Fetch the complete URI string
		$this->uri->_fetch_uri_string();

		// Do we need to remove the URL suffix?
		$this->uri->_remove_url_suffix();
		
		// Find, save into registry and remove additional segments from URL
		$this->uri->_fetch_url_additional_segments();

		// Compile the segments into an array
		$this->uri->_explode_segments();
		
		// Parse any custom routing that may exist
		$this->_parse_routes();
		
		// Re-index the segment array so that it starts with 1 rather than 0
		$this->uri->_reindex_segments();
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Set the Route
	 *
	 * This function takes an array of URI segments as
	 * input, and sets the current class/method
	 *
	 * @access	private
	 * @param	array
	 * @param	bool
	 * @return	void
	 */
	function _set_request($segments = array(), $args = array())
	{
		$segments = $this->_validate_request($segments);
		
		if (count($segments) == 0)
		{
			return;
		}

		$this->set_module($segments[0]);
		$this->set_class($segments[1]);
		$this->set_method($segments[2]);

		$this->uri->setArgs($args);
		registry::set('current_route_args', $args);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Validates the supplied segments.  Attempts to determine the path to
	 * the controller.
	 *
	 * @access	private
	 * @param	array
	 * @return	array
	 */	
	function _validate_request($segments)
	{
		// Does the requested controller exist in the root folder?
		if (file_exists(MODULES_PATH . $segments[0] . '/controllers/' . $segments[1] . '_controller' . EXT))
		{
			return $segments;
		}

		// Can't find the requested controller...
		log_message('error', 'Can not find the requested controller');
		show_404($segments[0]);
	}

	// --------------------------------------------------------------------

	/**
	 *  Parse Routes
	 *
	 * This function matches any routes that may exist in
	 * the config/routes.php file against the URI to
	 * determine if the class/method need to be remapped.
	 *
	 * @access	private
	 * @return	void
	 */
	function _parse_routes()
	{
		// Remove index.php segment
		if (array_search('index.php', $this->uri->segments) !== FALSE) {
			unset($this->uri->segments[array_search('index.php', $this->uri->segments)]);
		}

		// Turn the segment array into a URI string
		$uri = implode('/', $this->uri->segments) . '/';

		foreach ($this->routes AS $module=>$routes) {
			foreach ($routes AS $route=>$route_attrs) {
				$route = trim($route, '/');
				
				if (isset($route_attrs['title']))
					$route_title = $route_attrs['title'];

				if (isset($route_attrs['controller']))
					$route_controller = $route_attrs['controller'];
				else
					$route_controller = $module;

				if (isset($route_attrs['action']))
					$route_action = $route_attrs['action'];
				else
					$route_action = 'index';

				if (isset($route_attrs['access']))
					$route_access = $route_attrs['access'];

				// Is there a literal match?  If so we're done
				if ($route == $uri)
				{
					$this->_set_request(explode('/', $module . '/' . $route_controller . '/' . $route_action));
					registry::set('controller_attrs', $route_attrs);
					return;
				}
					
				// Convert wild-cards to RegEx
				$route = str_replace(':any', '([\w\.,-]+)', str_replace(':num', '(\d+)', $route));

				// Does the RegEx match?
				if (preg_match('#^'.$route.'\\/*$#', $uri, $matches))
				{	
					// Do we have a back-reference?
					if (strpos($route_action, '$') !== FALSE AND strpos($route, '(') !== FALSE)
					{
						$route_action = preg_replace('#^'.$route.'\\/*$#', $route_action, $uri);
					}

					array_shift($matches);
					$this->_set_request(explode('/', $module . '/' . $route_controller . '/' . $route_action), $matches);
					registry::set('controller_attrs', $route_attrs);
					registry::set('current_route', $route);
					return;
				}
			}
		}

		log_message('error', "URI parse error");
		show_404($uri);

		// If we got this far it means we didn't encounter a
		// matching route so we'll set the site default route
		//$this->_set_request($this->uri->segments);
	}

	// --------------------------------------------------------------------
	
	/**
	 * Set the module name
	 *
	 * @access	public
	 * @param	string
	 * @return	void
	 */	
	function set_module($module)
	{
		$this->module = $module;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Fetch the current module
	 *
	 * @access	public
	 * @return	string
	 */	
	function fetch_module()
	{
		return $this->module;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Set the class name
	 *
	 * @access	public
	 * @param	string
	 * @return	void
	 */	
	function set_class($class)
	{
		$this->class = $class;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Fetch the current class
	 *
	 * @access	public
	 * @return	string
	 */	
	function fetch_class()
	{
		return $this->class;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 *  Set the method name
	 *
	 * @access	public
	 * @param	string
	 * @return	void
	 */	
	function set_method($method)
	{
		$this->method = $method;
	}

	// --------------------------------------------------------------------
	
	/**
	 *  Fetch the current method
	 *
	 * @access	public
	 * @return	string
	 */	
	function fetch_method()
	{
		return $this->method;
	}

}
// END Router Class

/* End of file Router.php */
/* Location: ./system/libraries/Router.php */