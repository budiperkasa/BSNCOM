<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(BASEPATH . 'libraries/Events.php');

class CI_Components_loader
{
	private $_hook_functions = array();

	public function __construct()
	{
		require_once(BASEPATH . 'libraries/Registry' . EXT);
		include_once(BASEPATH . 'view/view' . EXT);

		$this->load       = load_class('Loader');
		$this->config     = load_class('Config');
		$this->uri        = load_class('URI');
		$this->db         = $this->load->database('', TRUE);
		
		$this->_load_components();
		
		$this->_sort_hook_functions();
	}
	
	private function _load_components()
	{
		$this->load->helper('directory');
		
		// --------------------------------------------------------------------------------------------
		// Clear database.
		// needed for "blind" installation (DO NOT REMOVE)
		// --------------------------------------------------------------------------------------------
		/*$tables = $this->db->list_tables();
		foreach ($tables AS $table) {
			$this->db->query("DROP TABLE IF EXISTS ".$table);
		}*/
		// --------------------------------------------------------------------------------------------

		// Is modules table already installed?
		if (!$this->db->table_exists('modules')) {
			// No
			
			// Process pre-installation tests
			include_once(MODULES_PATH . 'install/process_tests.php');
			
			// Scan modules dir
			$this->load->plugin('sqlDumpParser');
			$modules_dir = directory_map(MODULES_PATH);
			
			$modules = array();
			$lang_files = array();
			
			// Install each module
			foreach ($modules_dir AS $module_dir=>$array) {
				if (is_file(MODULES_PATH . $module_dir . '/' . $module_dir . '.module.php')) {
					include_once(MODULES_PATH . $module_dir . '/' . $module_dir . '.module' . EXT);
					$module_class = $module_dir . 'Module';
					$module_instance = new $module_class;
					// Install just core modules
					if (key_exists('type', get_object_vars($module_instance)) && $module_instance->type == 'core') {
						$modules[$module_dir] = $module_instance->title;
						$install_path = MODULES_PATH . $module_dir . '/install/';
						if (is_dir($install_path)) {
							$install_php_file = $module_dir . '.install.php';
							$install_sql_file = $module_dir . '.install.sql';
							if (is_file($install_path . $install_php_file)) {
								include($install_path . $install_php_file);
							}
							if (is_file($install_path . $install_sql_file)) {
								$queries = getQueriesFromFile($install_path . $install_sql_file);
								foreach ($queries AS $query) {
									$this->db->query($query);
								}
							}
						}
						if (isset($module_instance->lang_files)) {
							if (!is_array($module_instance->lang_files)) {
								$module_instance->lang_files = array($module_instance->lang_files);
							}
							foreach ($module_instance->lang_files AS $file) {
								$lang_files[$module_instance->title][] = $file;
							}
						}
					}
				}
			}
			// Set modules rows into the DB
			foreach ($modules AS $module_dir=>$title) {
				$this->db->insert('modules', array('dir' => $module_dir, 'name' => $title));
			}
			// Set language files rows into the DB
			foreach ($lang_files AS $module_title=>$row) {
				foreach ($row AS $file) {
					$this->db->insert('language_files', array('module' => $module_title, 'file' => $file));
				}
			}

			//header('Location: ' . $this->config->site_url('install'));
			exit("
<font color='green'>
<b>Pre-Installation tests passed successfully!</b><br />
<b>Database installed!</b><br />
<a href='" . $this->config->site_url('install') . "'>Continue installation</a>
</font>");
		} else {
			// Yes

			// Load system settings
			$this->_load_settings();
			$system_settings = registry::get('system_settings');

			// Get modules list from the DB
			$this->db->select();
			$this->db->from('modules');
			$this->db->order_by('id');
			$query = $this->db->get();
			foreach ($query->result() as $row) {
				$modules[$row->dir] = $row->name;
			}
			
			// Get themes list from the DB
			/*$themes = array();
			$this->db->select();
			$this->db->from('themes');
			$this->db->where('installed', 1);
			$this->db->order_by('id');
			$query = $this->db->get();
			foreach ($query->result() as $row) {
				$themes[$row->dir] = $row->name;
			}*/

			// Get langauge files list from the DB (modules and themes lang files)
			$theme_name = $system_settings['design_theme'];
			$this->db->select();
			$this->db->from('language_files');
			$this->db->order_by('id');
			$query = $this->db->get();
			foreach ($query->result() as $row) {
				if ($row->module)
					$lang_files['modules'][$row->module][] = $row->file;
				if ($row->theme == $theme_name)
					$lang_files['themes'][$row->theme][] = $row->file;
			}

			if (!isset($system_settings['completed_installation']) && strpos($this->uri->uri_string, 'install') === FALSE) {
				header('Location: ' . $this->config->site_url('install'));
				exit();
			}
		}
		// Save installed modules in the registry
		registry::set('modules_array', $modules);

		// Save installed language files in the registry
		registry::set('language_files_array', $lang_files);

		// Select loading language code and Load language files
		$url_additional_segments_matches = registry::get('url_additional_segments_matches');
    	if ($url_additional_segments_matches && key_exists('lang', $url_additional_segments_matches)) {
    		$load_language = $url_additional_segments_matches['lang'];
    	} else {
			$system_settings = registry::get('system_settings');
			$load_language = $system_settings['default_language'];
			/*if (isset($system_settings['default_language'])) {
				$load_language = $system_settings['default_language'];
			} else {
				$load_language = $this->config->item('default_language');
			}*/
    	}
		$this->_load_language($load_language);

		// Let load routes, hooks and menu items of each module
		$route = array();
		$hook = array();
		$menu = array();
		$permissions = array();
		foreach ($modules AS $module_dir=>$modules_name) {
			include_once(MODULES_PATH . $module_dir . '/' . $module_dir . '.module' . EXT);
			$module_class = $module_dir . 'Module';
			$module_instance = new $module_class;
			// Collects routes of each active module into $route array
			if (method_exists($module_instance, 'routes'))
				$route[$module_dir] = $module_instance->routes();
			// Collects hooks of each active module into $hook array
			if (method_exists($module_instance, 'hooks')) {
				$hook[$module_dir] = $module_instance->hooks();
			}
			$hooks_file = MODULES_PATH . $module_dir . '/controllers/' . $module_dir . '_hook' . EXT;
			if (is_file($hooks_file)) {
				include_once($hooks_file);
			}
			// Collects menu items of each active module into $menu array
			if (method_exists($module_instance, 'menu')) {
				$module_menu_list = $module_instance->menu();
				foreach ($module_menu_list AS $key=>$item) {
					$module_menu_list[$key]['module'] = $module_dir;
					if (isset($item['children'])) {
						foreach ($item['children'] AS $subkey=>$subitem) {
							$module_menu_list[$key]['children'][$subkey]['module'] = $module_dir;
						}
					}
				}
				$menu = $this->_my_array_merge($menu, $module_menu_list);
			}
			if (property_exists($module_instance, 'permissions')) {
				if (is_array($module_instance->permissions))
					if (isset($permissions[$module_dir]))
						$permissions[$module_dir] = array_merge($permissions[$module_dir], $module_instance->permissions);
					else 
						$permissions[$module_dir] = $module_instance->permissions;
				else 
					$permissions[$module_dir][] = $module_instance->permissions;
			}
		}
		
		/*foreach ($themes AS $theme_dir=>$theme_name) {
			include_once(THEMES_PATH . $theme_dir . '/' . $theme_dir . '.theme' . EXT);
			$theme_class = $theme_dir . 'Theme';
			$theme_instance = new $theme_class;
			// Collects hooks of each theme into $hook array
			if (method_exists($theme_instance, 'hooks')) {
				$hook[$theme_dir] = $theme_instance->hooks();
			}
		}*/

		// Save modules info into the registry
		registry::set('route', $route);
		registry::set('hook', $hook);
		registry::set('menu', $menu);
		registry::set('permissions', $permissions);
		
		$this->_hook_functions = $hook;

		log_message('debug', "Modules were loaded");
	}
	
	private function _my_array_merge($arr, $ins)
	{
		if(is_array($arr)) {
			if(is_array($ins)) 
				foreach($ins as $k=>$v) {
					if(isset($arr[$k]) && is_array($v) && is_array($arr[$k])) {
						$arr[$k] = $this->_my_array_merge($arr[$k], $v);
					} else 
						$arr[$k] = $v;
				}
		} elseif(!is_array($arr) && (strlen($arr) == 0 || $arr == 0)) {
			$arr = $ins;
		}
		return($arr);
	}
	
	private function _load_settings()
	{
		// --------------------------------------------------------------------------------------------
		// Select system settings
		// --------------------------------------------------------------------------------------------
		$query = $this->db->get('system_settings');
		$settings = array();
		foreach ($query->result() as $row) {
			$settings[$row->name] = $row->value;
		}
		registry::set('system_settings', $settings);

		// --------------------------------------------------------------------------------------------
		// Select secret word (first 5 chars from the hash of admin's password)
		// --------------------------------------------------------------------------------------------
		$this->db->select('password AS secret', false);
		$this->db->from('users');
		$this->db->where('id', 1);
		$query = $this->db->get();
		$row = $query->row_array();
		if (isset($row['secret']))
			registry::set('secret', substr($row['secret'], 0, 5));

		log_message('debug', "Settings were loaded");
	}
	
	/**
	 * Include languages files, from 'languages' and themes folders
	 *
	 * @param string $loading_language - language code
	 */
	private function _load_language($loading_language = null)
	{
		if (is_dir(LANGPATH . $loading_language)) {
			$language = array();
			$language_files_array = registry::get('language_files_array');

			// Scan languages directory
			$languages_map = directory_map(LANGPATH . $loading_language);
			foreach ($languages_map AS $file) {
				// Include modules lang files
				foreach ($language_files_array['modules'] AS $module_title=>$row) {
					foreach ($row AS $db_file) {
						// Is there file such as in the DB
						if ($db_file == $file) {
							if (is_file(LANGPATH . $loading_language . '/' . $file)) {
								include_once(LANGPATH . $loading_language . '/' . $file);
							}
						}
					}
				}

				// Include themes lang files
				if (isset($language_files_array['themes']))
					foreach ($language_files_array['themes'] AS $db_theme_name=>$row) {
						foreach ($row AS $db_file) {
							// Is there file such as in the DB
							if ($db_file == $file) {
								if (is_file(LANGPATH . $loading_language . '/' . $file)) {
									include_once(LANGPATH . $loading_language . '/' . $file);
								}
							}
						}
					}
			}

			// --------------------------------------------------------------------------------------------
			// If translation mode enabled - reverce language vars with its keys
			// --------------------------------------------------------------------------------------------
			if ($this->config->item('translation_mode')) {
				foreach ($language AS $var=>$string)
					$language[$var] = $var;
			}
			
			// --------------------------------------------------------------------------------------------
			// Define lang constants
			// --------------------------------------------------------------------------------------------
			foreach ($language AS $key=>$var) {
				define($key, $var);
			}

			// --------------------------------------------------------------------------------------------
			// Load langauge constants into views environment
			// --------------------------------------------------------------------------------------------
			view::setLanguageVars($language);

			// Save in the registry
			registry::set('current_language', $loading_language);

			log_message('debug', "Language file was loaded");
		} else {
			show_error('Language file was not found in the language directory');
		}
	}
	
	private function _sort_hook_functions()
	{
		// Sort hook functions by weight
		$hook_functions = array();
		foreach ($this->_hook_functions AS $module_name=>$module_hooks) {
			foreach ($module_hooks AS $function_name=>$function_attrs) {
				$function_attrs['module_name'] = $module_name;

				// Set hook events
				if (!isset($function_attrs['events']))
					$hook_functions[$function_name] = $function_attrs;
				else {
					if (!is_array($function_attrs['events'])) {
						$function_attrs['events'] = array($function_attrs['events']);
					}
					foreach ($function_attrs['events'] AS $event) {
						if (isset($function_attrs['file'])) {
							$file = $function_attrs['file'];
						} else {
							$file = $module_name . '_hook' . EXT;
						}
						events::setEvent($event, $file, $module_name, $function_name);
					}
				}
			}
		}
		$sortAux = array();
		$count = 1000;
    	foreach($hook_functions as $key=>$function_item) {
    		if (!isset($function_item['weight']) || !is_numeric($function_item['weight'])) {
    			$function_item['weight'] = $count;
    			$count++;
    			$hook_functions[$key]['weight'] = $function_item['weight'];
    		}
    		$sortAux[] = $function_item['weight'];
    	}
     	if (!empty($sortAux))
     		array_multisort($sortAux, SORT_ASC, SORT_NUMERIC, $hook_functions);
     		
     	$this->_hook_functions = $hook_functions;
	}
	
	public function fetch_hooks()
	{
		$this->uri = load_class('URI');

		$functions_to_process = array();

		foreach ($this->_hook_functions AS $function_name=>$function_attrs) {
			$module_name = $function_attrs['module_name'];
			$run = true;
				
			// search inclusion or exclusion condition routes
			$inc_exc_order = array();
			foreach ($function_attrs AS $search_key=>$search_item) {
				if ($search_key == 'inclusions' || $search_key == 'exclusions')
					$inc_exc_order[] = $search_key;
			}
	
			// if inclusion or exclusion condition routes were found - match them with current route and 
			// determine what to do: run hook function or not
			if (!empty($inc_exc_order)) {
				// if inclusions were declared first - then hook function doesn't run by default,
				// else it will run
				if (reset($inc_exc_order) == 'inclusions') {
					$run = false;
				} else {
					$run = true;
				}
				foreach ($inc_exc_order AS $action) {
					foreach ($function_attrs[$action] AS $action_route) {
						$action_route = trim($action_route, '/') . '/';

						// when current route from front controller satisfies at least one inclusion route condition - hook function runs,
						// but then it may satisfies exclusion condition, so it won't run 
						// and vice versa,
						// it depends on the conditions order
						$uri = implode('/', $this->uri->segments) . '/';

						if ($uri == $action_route) {
							if ($action == 'inclusions')
								$run = true;
							if ($action == 'exclusions')
								$run = false;
						} else {
							$action_route = trim($action_route, '/');

							// Convert wild-cards to RegEx
							$action_route = str_replace(':any', '.*', str_replace(':num', '[0-9]+', $action_route));

							// Does the RegEx match?
							if (preg_match('#^'.$action_route.'\\/*$#', $uri))
							{			
								if ($action == 'inclusions')
									$run = true;
								if ($action == 'exclusions')
									$run = false;
							}
						}
					}
				}
			}
	
			if ($run) {
				if (isset($function_attrs['file'])) {
					$file = $function_attrs['file'];
				} else {
					$file = $module_name . '_hook' . EXT;
				}
				// right now will be processed only functions those haven't event or view trigger attributes
				if (!isset($function_attrs['events']) && !isset($function_attrs['viewTrigger'])) {
					// select functions that will be processed after all events will be set
					$functions_to_process[] = array('file' => $file, 'module_name' => $module_name, 'function_name' => $function_name);
				} else {
					// Set view triggers
					if (isset($function_attrs['viewTrigger'])) {
						$triggers = $function_attrs['viewTrigger'];
						if (countdim($triggers) > 1) {
							foreach($triggers AS $trigger) {
								if (isset($trigger[2])) {
									$child = $trigger[2];
								} else {
									$child = null;
								}
								view::setViewTrigger($trigger[0], $trigger[1], $file, $module_name, $function_name);
							}
						} else {
							if (isset($triggers[2])) {
								$child = $triggers[2];
							} else {
								$child = null;
							}
							view::setViewTrigger($triggers[0], $triggers[1], $file, $module_name, $function_name);
						}
					}
				}
			}
		}
		
		return $functions_to_process;
	}
}

/**
 * return number of array dimensions
 *
 * @param input array $array
 * @return int
 */
function countdim($array)
{
	if (is_array(reset($array))) 
		$return = countdim(reset($array)) + 1;
	else
		$return = 1;

	return $return;
}