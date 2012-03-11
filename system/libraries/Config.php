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
 * CodeIgniter Config Class
 *
 * This class contains functions that enable config files to be managed
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/config.html
 */
class CI_Config {

	var $config = array();
	var $is_loaded = array();

	/**
	 * Constructor
	 *
	 * Sets the $config data from the primary config.php file as a class variable
	 *
	 * @access   public
	 * @param   string	the config file name
	 * @param   boolean  if configuration values should be loaded into their own section
	 * @param   boolean  true if errors should just return false, false if an error message should be displayed
	 * @return  boolean  if the file was successfully loaded or not
	 */
	function CI_Config()
	{
		$this->config =& get_config();
		log_message('debug', "Config Class Initialized");
	}
  	
	// --------------------------------------------------------------------

	/**
	 * Load Config File,
	 * File may be in config folder - just set its name
	 * or you may pass any file name - set its full path
	 *
	 * @access	public
	 * @param	string	the config file name
	 * @return	boolean	if the file was loaded correctly
	 */	
	function load($file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
	{
		$file = ($file == '') ? 'config' : str_replace(EXT, '', $file);
	
		if (in_array($file, $this->is_loaded, TRUE))
		{
			return TRUE;
		}

		if (!file_exists(BASEPATH.'config/'.$file.EXT) && !file_exists($file.EXT))
		{
			if ($fail_gracefully === TRUE)
			{
				return FALSE;
			}
			show_error('The configuration file '.$file.EXT.' does not exist.');
		}

		if (file_exists(BASEPATH.'config/'.$file.EXT))
			include(BASEPATH.'config/'.$file.EXT);
			
		if (file_exists($file.EXT))
			include($file.EXT);

		if ( ! isset($config) OR ! is_array($config))
		{
			if ($fail_gracefully === TRUE)
			{
				return FALSE;
			}
			show_error('Your '.$file.EXT.' file does not appear to contain a valid configuration array.');
		}

		if ($use_sections === TRUE)
		{
			if (isset($this->config[$file]))
			{
				$this->config[$file] = array_merge($this->config[$file], $config);
			}
			else
			{
				$this->config[$file] = $config;
			}
		}
		else
		{
			$this->config = array_merge_recursive($this->config, $config);
		}

		$this->is_loaded[] = $file;
		unset($config);

		log_message('debug', 'Config file loaded: config/'.$file.EXT);
		return TRUE;
	}
  	
	// --------------------------------------------------------------------

	/**
	 * Fetch a config file item
	 *
	 *
	 * @access	public
	 * @param	string	the config item name
	 * @param	string	the index name
	 * @param	bool
	 * @return	string
	 */
	function item($item, $index = '')
	{	
		if ($index == '')
		{	
			if ( ! isset($this->config[$item]))
			{
				return FALSE;
			}

			$pref = $this->config[$item];
		}
		else
		{
			if ( ! isset($this->config[$index]))
			{
				return FALSE;
			}

			if ( ! isset($this->config[$index][$item]))
			{
				return FALSE;
			}

			$pref = $this->config[$index][$item];
		}

		return $pref;
	}
  	
  	// --------------------------------------------------------------------

	/**
	 * Fetch a config file item - adds slash after item
	 *
	 * The second parameter allows a slash to be added to the end of
	 * the item, in the case of a path.
	 *
	 * @access	public
	 * @param	string	the config item name
	 * @param	bool
	 * @return	string
	 */
	function slash_item($item)
	{
		if ( ! isset($this->config[$item]))
		{
			return FALSE;
		}

		$pref = $this->config[$item];

		if ($pref != '' && substr($pref, -1) != '/')
		{	
			$pref .= '/';
		}

		return $pref;
	}
  	
	// --------------------------------------------------------------------

	/**
	 * Site URL
	 *
	 * @access	public
	 * @param	string	the URI string
	 * @return	string
	 */
	function site_url($uri = '')
	{
		if (is_array($uri))
		{
			$uri = implode('/', $uri);
		}
		
		$add_segments = '';
		if ($url_additional_segments_matches = registry::get('url_additional_segments_matches')) {
			foreach ($url_additional_segments_matches AS $segment=>$value) {
				$add_segments .= $segment . '/' . $value;
			}
			$add_segments .= '/';
		}

		if ($uri == '')
		{
			if ($this->item('enable_query_strings') && $add_segments)
				return $this->slash_item('base_url').$this->slash_item('subdirectory').$this->slash_item('index_page').'?route=/'.$add_segments;
			else
				return $this->slash_item('base_url').$this->slash_item('subdirectory').$this->slash_item('index_page').$add_segments;
		}
		else
		{
			$suffix = ($this->item('url_suffix') == FALSE) ? '' : $this->item('url_suffix');
			$uri = preg_replace("|^/*(.+?)/*$|", "\\1", $uri);
			if ($suffix == '') {
				$uri .= '/';
			}
			if ($this->item('enable_query_strings'))
				return $this->slash_item('base_url').$this->slash_item('subdirectory').$this->slash_item('index_page').'?route=/'.$add_segments.$uri.$suffix;
			else
				return $this->slash_item('base_url').$this->slash_item('subdirectory').$this->slash_item('index_page').$add_segments.$uri.$suffix;
		}
	}
	
	// --------------------------------------------------------------------

	/**
	 * System URL
	 *
	 * @access	public
	 * @return	string
	 */
	function system_url()
	{
		$x = explode("/", preg_replace("|/*(.+?)/*$|", "\\1", BASEPATH));
		return $this->slash_item('base_url').$CI->config->slash_item('subdirectory').end($x).'/';
	}
  	
	// --------------------------------------------------------------------

	/**
	 * Set a config file item
	 *
	 * @access	public
	 * @param	string	the config item key
	 * @param	string	the config item value
	 * @return	void
	 */
	function set_item($item, $value)
	{
		$this->config[$item] = $value;
	}

}

// END CI_Config class

/* End of file Config.php */
/* Location: ./system/libraries/Config.php */