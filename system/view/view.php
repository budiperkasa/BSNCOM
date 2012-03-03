<?php
include_once(BASEPATH . 'view/smarty/libs/Smarty.class' . EXT);
include_once(BASEPATH . 'view/phpQuery/phpQuery' . EXT);

/**
 * helps to run other helpers functions inside smarty teplates
 * using $VH assigned object
 * 
 * Example: use $VH->anchor() 
 * instead of anchor()
 *
 */
class view_helper
{
	public function __call($name, $args)
	{
		return call_user_func_array($name, $args);
	}
}


class view extends Smarty
{
    private static $_css_files = array();
    private static $_js_scripts = array();
    private static $_ex_js_scripts = array();
    private static $_ex_css_files = array();
    
    private static $_system_settings = null;
    private static $_site_settings = null;

    private static $_triggers = array();
    private $triggers_enabled = true;
    private static $lang_vars = array();

    private $curr_module;
    
    private $_theme;
    private $_default_theme = 'default';

    public function __construct($register_triggers = true)
    {
    	if (is_null(self::$_system_settings) && is_null(self::$_site_settings))
    	{
    		self::$_system_settings = registry::get('system_settings');
    		self::$_site_settings = registry::get('site_settings');
    	}

        //      Initialize Smarty
        parent::__construct();
        
        $CI = &get_instance();
        if ($CI->load->is_module_loaded("i18n")) {
        	// Load custom theme of current language
        	$current_language_db_obj = registry::get('current_language_db_obj');
        	$this->_theme = $current_language_db_obj->custom_theme;
        } else {
        	$this->_theme = self::$_system_settings['design_theme'];
        }

        // --------------------------------------------------------------------------------------------
        // Check selected theme, if available - smarty will look for templates in that folder initially,
        // also set default theme - smarty will look for templates in that folder in the second order
        $themes = array();
        if (is_dir(THEMES_PATH . $this->_theme . DIRECTORY_SEPARATOR . 'views'))
        	$themes[] = THEMES_PATH . $this->_theme . DIRECTORY_SEPARATOR . 'views';
        $themes[] = THEMES_PATH . $this->_default_theme . DIRECTORY_SEPARATOR . 'views';
        $this->template_dir  = $themes;
        // --------------------------------------------------------------------------------------------

        // --------------------------------------------------------------------------------------------
        // Smarty requires different compile directories for each theme
        $c_dir = BASEPATH . 'view'.DIRECTORY_SEPARATOR.'smarty'.DIRECTORY_SEPARATOR.'templates_c'.DIRECTORY_SEPARATOR.$this->_theme;
        if (!is_dir($c_dir))
        	@mkdir($c_dir, 0777);
        //mkdir(BASEPATH . 'view'.DIRECTORY_SEPARATOR.'smarty'.DIRECTORY_SEPARATOR.'templates_c'.DIRECTORY_SEPARATOR.$this->_theme, 0777);
        //$c_dir = BASEPATH . 'view'.DIRECTORY_SEPARATOR.'smarty'.DIRECTORY_SEPARATOR.'templates_c';
        // --------------------------------------------------------------------------------------------

        $this->compile_dir   = $c_dir;
        $this->config_dir    = BASEPATH . 'view'.DIRECTORY_SEPARATOR.'smarty'.DIRECTORY_SEPARATOR.'configs';

        // Initialize system vars - if they weren't assigned yet
        if (!isset($this->tpl_vars['VH'])) {
        	// Assign base object
        	$this->compile_check = $CI->config->item('smarty_compile_check');
        	$this->assign('CI', $CI);
        	
        	// Assign content ACL object
        	$content_access_obj = contentAcl::getInstance();
        	$this->assign('content_access_obj', $content_access_obj);
        	
        	// Assign system and site settings
        	$this->assign('system_settings', self::$_system_settings);
        	$this->assign('site_settings', self::$_site_settings);
        	
        	// Assign current language code
        	$this->assign('current_language', registry::get('current_language'));

        	// Assign view helper
			$VH = new view_helper;
			$this->assign('VH', $VH);
			
			// Assign language strings
        	$this->assign(self::$lang_vars);
        	
        	// Assign path to public content (js, css, images)
        	$path = base_url() . 'themes/' . $this->_theme . '/';
	        $this->assign('public_path', $path);
	        registry::set('public_path', $path);

        	// Assign users upload public path
        	$users_content_path = $CI->config->item('users_content_http_path');
        	$this->assign('users_content', trim($users_content_path, '/') . '/');
        	
        	// Assign current uri
        	$this->assign('route', implode('/', $CI->router->uri->segments));
        	
        	// Assign 1st part of title tag, extracted from controller attributes
        	$controller_attrs = registry::get('controller_attrs');
        	if (isset($controller_attrs['title']))
        		$this->assign('title', $controller_attrs['title']);

        	// Assign decimals and thousands separator from language settings
        	// or default from config.php
        	if ($CI->load->is_module_loaded("i18n")) {
        		$this->assign('decimals_separator', $current_language_db_obj->decimals_separator);
        		$this->assign('thousands_separator', $current_language_db_obj->thousands_separator);
        	} else {
        		$this->assign('decimals_separator', $CI->config->item('decimals_separator'));
        		$this->assign('thousands_separator', $CI->config->item('thousands_separator'));
        	}
        	
        	// Assign session user id
        	$this->assign_by_ref('session_user_id', $CI->session->userdata('user_id'));

        	// Assign current location/type/category/listing/user/serach params from registry
        	$this->assign('current_location', registry::get('current_location'));
        	$this->assign('current_type', registry::get('current_type'));
        	$this->assign('current_category', registry::get('current_category'));
        	$this->assign('current_listing', registry::get('current_listing'));
        	$this->assign('current_user', registry::get('current_user'));
        	$this->assign('current_search_params_url', registry::get('current_search_params'));
        	
        	// Assign self view object
        	$this->assign_by_ref('smarty_obj', $this);
        }
        $this->assign_by_ref('css_files', self::$_css_files);
        $this->assign_by_ref('js_scripts', self::$_js_scripts);
        $this->assign_by_ref('ex_js_scripts', self::$_ex_js_scripts);
    }
    
    public function getFileInTheme($file_name, $with_base_url = true)
    {
    	if (is_file(THEMES_PATH . $this->_theme . DIRECTORY_SEPARATOR . $file_name)) {
    		return ($with_base_url ? base_url() : '') . 'themes/' . $this->_theme . '/' . $file_name;
    	} else {
    		return ($with_base_url ? base_url() : '') . 'themes/' . $this->_default_theme . '/' . $file_name;
    	}
    }

    public function addCssFile($file, $media = 'screen')
    {
    	$file_path = $this->getFileInTheme('css/' . $file);

    	if (!array_key_exists($file_path, array_keys(self::$_css_files))) {
        	self::$_css_files[$file_path] = $media;
    	}
    }
    public function addExternalCssFile($file)
    {
    	if (!in_array($file, self::$_ex_css_files))
        	self::$_ex_css_files[] = $file;
    }

    public function addJsFile($file)
    {
    	if (!in_array($file, self::$_js_scripts)) {
    		$file_path = $this->getFileInTheme('js/' . $file, false);
    		
        	self::$_js_scripts[] = $file_path;
    	}
    }
    public function addExternalJsFile($file)
    {
    	if (!in_array($file, self::$_ex_js_scripts))
        	self::$_ex_js_scripts[] = $file;
    }
    
    public function removeJsFile($file)
    {
    	$file_path = base_url() . 'themes/' . $this->_theme . '/js/' . $file;
    	if (($key = array_search($file, self::$_js_scripts)) !== false)
        	unset(self::$_js_scripts[$key]);
        	
		$file_path = base_url() . 'themes/' . $this->_default_theme . '/js/' . $file;
		if (($key = array_search($file, self::$_js_scripts)) !== false)
        	unset(self::$_js_scripts[$key]);
    }
    
    public function clearCssJs()
    {
    	self::$_css_files = array();
    	self::$_js_scripts = array();
    	self::$_ex_js_scripts = array();
    }

    public function display($template = null, $cache_id = null, $compile_id = null)
    {
    	parent::register_outputfilter(array($this, 'runTriggers'));
    	
        if (empty($template)) {
	    	$template = $this->template;
    	}

	    $CI = &get_instance();
    	$CI->events->callEvent('onDisplay', $template, $this->curr_module);
        $output = parent::fetch($template);

        echo $output;
        return $output;
    }
    
    public function fetch($template = null, $cache_id = null, $compile_id = null, $display = false)
    {
    	if (empty($template)) {
	    	$template = $this->template;
    	}
    	
    	$CI = &get_instance();
    	$CI->events->callEvent('onFetch', $template, $this->curr_module);
        return parent::fetch($template);
    }

    public static function setLanguageVars($vars)
    {
    	self::$lang_vars = $vars;
    }

    /**
     * Call view trigger function $function_name, that placed in the $file of $module_name
     *
     * @param str $mode
     * @param str $selector
     * @param str $file
     * @param str $module_name
     * @param str $function_name
     */
    public static function setViewTrigger($mode, $selector, $file, $module_name, $function_name, $html = '', $args = array())
    {
	    self::$_triggers[] = array(
	    	'mode' => $mode,
	    	'selector' => $selector,
	    	'file' => $file,
	    	'module_name' => $module_name,
	    	'function_name' => $function_name,
	    	'trigger_html' => $html,
	    	'args' => $args,
	    );
    }
    
    public function runTriggers($output, &$smarty)
	{
		if (!empty(self::$_triggers) && $this->triggers_enabled) {
			$BM =& load_class('Benchmark');
			$BM->mark('view_triggers_execution_time_start');

			$pq = phpQuery::newDocumentHTML($output);
			$triggers = self::$_triggers;
			$i = 0;
			foreach ($triggers AS $i=>$trigger) {
				$trigger_html = '';
					if ($found_elements = $pq->find($trigger['selector'])) {

					if (!$trigger['file'] || !$trigger['module_name'] || !$trigger['function_name']) {
						$trigger_html = $trigger['trigger_html'];
					} elseif (empty($trigger_html)) {
						$file = $trigger['file'];
						$module_name = $trigger['module_name'];
						$function = $trigger['function_name'];
						$args = $trigger['args'];
						$trigger_html = Controller::run_function($file, $module_name, $function, $args);
					}

					foreach($found_elements as $element) {
						switch ($trigger['mode']) {
							case 'pre':
								pq($element)->prepend($trigger_html);
								break;
							case 'preouter':
								pq($element)->before($trigger_html);
								break;
							case 'post':
								pq($element)->append($trigger_html);
								break;
							case 'postouter':
								pq($element)->after($trigger_html);
								break;
							case 'replace':
								pq($element)->html($trigger_html);
								break;
							case 'replaceouter':
								pq($element)->replaceWith($trigger_html);
								break;
						}
					}
				}
			}
			
			$BM->mark('view_triggers_execution_time_end');

			return $pq->htmlOuter();
		} else {
			return $output;
		}
	}
}
?>