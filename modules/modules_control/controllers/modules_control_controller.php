<?php

class modules_controlController extends Controller
{
    public function index()
    {
		$modules_array = registry::get('modules_array');
		$this->load->model('modules_control');
		$modules = $this->modules_control->getModulesInfo($modules_array);
		
		if ($this->input->post('submit')) {
			// Clean cache
			$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);

			$this->load->plugin('sqlDumpParser');

			foreach ($_POST AS $key=>$post_item) {
				// Search through post vars, are there checked checkboxes?
				if (strpos($key, "install_") !== false) {
					$module_dir = str_replace("install_", "", $key);
					if (!array_key_exists('installed_' . $module_dir, $_POST)) {
						// Checkbox checked, but module haven't installed yet
						if (!$modules[$module_dir]->active) {
							// so, install module
							$this->modules_control->installModule($module_dir);
						}
					}
				} else {
					// Search through post vars, are there unchecked checkboxes?
					if (strpos($key, "installed_") !== false) {
						$module_dir = str_replace("installed_", "", $key);
						if (!array_key_exists('install_' . $module_dir, $_POST)) {
							// Checkbox unchecked, and module have been already installed
							if ($modules[$module_dir]->active) {
								// so, uninstall module
								$this->modules_control->uninstallModule($module_dir);
							}
						}
					}
				}
			}
			events::callEvent('Modules list rebuild');
			$this->setSuccess(LANG_MODULES_SAVE_SUCCESS);
			
			redirect('admin/modules/');
		}

        $view  = $this->load->view();
        $view->assign('modules', $modules);
        $view->display('modules_control/admin_modules_control.tpl');
    }
}
?>