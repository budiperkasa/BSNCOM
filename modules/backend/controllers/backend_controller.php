<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class backendController extends Controller
{
    public function index()
    {
    	// Only logged in users allowed
    	if (!$this->session->userdata('user_id')) {
    		exit('Log in first!');
    	}
    	
    	$this->load->model('backend');
    	$content_access_obj = contentAcl::getInstance();
    	$view  = $this->load->view();
    	
    	if ($content_access_obj->isPermission('Manage all listings')) {
    		$listings_status_count = $this->backend->getListingsSummary();
    		$view->assign('listings_status_count', $listings_status_count);
    	}
    	if ($content_access_obj->isPermission('Manage users')) {
	    	$users_status_count = $this->backend->getUsersSummary();
	    	$users_in_groups_count = $this->backend->getUsersGroupsSummary();
	    	$view->assign('users_status_count', $users_status_count);
			$view->assign('users_in_groups_count', $users_in_groups_count);
    	}
    	if ($content_access_obj->isPermission('Manage all listings') && $content_access_obj->isPermission('Manage ability to claim')) {
    		$unapproved_claims_count = $this->backend->getClaimsUnapproved();
    		$view->assign('unapproved_claims_count', $unapproved_claims_count);
    	}

    	if ($this->load->is_module_loaded('banners') && $content_access_obj->isPermission('Manage all banners')) {
	    	$this->load->model('banners', 'banners');
			$banners_status_count = $this->banners->getBannersSummary();
			$view->assign('banners_status_count', $banners_status_count);
    	}

    	if ($this->load->is_module_loaded('payment') && $content_access_obj->isPermission('View all invoices') && $content_access_obj->isPermission('View all transactions')) {
			$this->load->model('payment', 'payment');
			$invoices_status_count = $this->payment->getInvoicesSummary();
			$transactions_count = $this->payment->getTransactionsCount();
			$transactions_summary = $this->payment->getTransactionsSummary();
			$view->assign('invoices_status_count', $invoices_status_count);
			$view->assign('transactions_count', $transactions_count);
			$view->assign('transactions_summary', $transactions_summary);
    	}
    	
    	// --------------------------------------------------------------------------------------------
    	if ($content_access_obj->isPermission('Manage self listings')) {
    		$my_listings_status_count = $this->backend->getMyListingsSummary();
    		$view->assign('my_listings_status_count', $my_listings_status_count);
    	}

    	if ($this->load->is_module_loaded('banners') && $content_access_obj->isPermission('Manage self banners')) {
	    	$this->load->model('banners', 'banners');
			$my_banners_status_count = $this->banners->getMyBannersSummary();
			$view->assign('my_banners_status_count', $my_banners_status_count);
    	}
		
    	if ($this->load->is_module_loaded('payment') && $content_access_obj->isPermission('View self invoices') && $content_access_obj->isPermission('View self transactions')) {
			$this->load->model('payment', 'payment');
			$my_invoices_status_count = $this->payment->getMyInvoicesSummary();
			$my_transactions_count = $this->payment->getMyTransactionsCount();
			$my_transactions_summary = $this->payment->getMyTransactionsSummary();
			$view->assign('my_invoices_status_count', $my_invoices_status_count);
			$view->assign('my_transactions_count', $my_transactions_count);
			$view->assign('my_transactions_summary', $my_transactions_summary);
    	}

        $view->display('backend/admin_index.tpl');
    }
    
    public function refresh_captcha()
    {
    	$this->load->plugin('captcha');
		$captcha = create_captcha($this);

		echo $captcha->image;
    }

    public function update_langs($updater_string)
    {
    	error_reporting(E_ALL);

    	$udir = ROOT . 'updates/' . $updater_string . '/';
    	if (is_dir($udir)) {
    		if (preg_match("/^update_v([0-9])([0-9x])([0-9x])_to_v([0-9])([0-9])([0-9])$/", $updater_string, $matches)) {
    			$uversion = $matches[4].'.'.$matches[5].'.'.$matches[6];
    			
    			// Clean cache
				$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);

	    		// Translate new language constants
	    		$this->load->model('backend');
	    		$this->backend->languagesFilesUpdate($uversion, $udir);

				events::callEvent('Modules list rebuild');
	    		
	    		exit("Languages update to ".$uversion." version completed!");
    		} else {
    			exit("'" . $updater_string . "' update url doesn't match format");
    		}
    	} else {
    		exit("'" . $updater_string . "' update folder doesn't exist!");
    	}
    }
    
    public function update($updater_string)
    {
    	error_reporting(E_ALL);

    	$udir = ROOT . 'updates/' . $updater_string . '/';
    	if (is_dir($udir)) {
    		if (preg_match("/^update_v([0-9])([0-9x])([0-9x])_to_v([0-9])([0-9])([0-9])$/", $updater_string, $matches)) {
    			$uversion = $matches[4].'.'.$matches[5].'.'.$matches[6];
    			
    			echo "Upgrade started, it may take up to some minutes, please, be patient<br /><br />";
    			
    			// Clean cache
				$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);
				
				// Switch off lang areas
				if ($this->load->is_module_loaded('i18n')) {
		    		$this->load->model('languages', 'i18n');
		    		$this->languages->langAreasSwitchOff();
				}

    			// SQL queries execution
    			$update_sql_file = $udir . $updater_string . '.sql';
	    		if (is_file($update_sql_file)) {
	    			$this->load->plugin('sqlDumpParser');
	    			$queries = getQueriesFromFile($update_sql_file);
					foreach ($queries AS $query) {
						$this->db->query($query);
					}
	    		}
	    		
	    		// PHP instructions execution
    			$update_php_file = $udir . $updater_string . '.php';
	    		if (is_file($update_php_file)) {
	    			include_once($update_php_file);
	    		}
	    		
	    		// Switch on lang areas
				if ($this->load->is_module_loaded('i18n')) {
		    		$this->languages->langAreasSwitchOn();
				}
	    		
	    		// Translate new language constants
	    		$this->load->model('backend');
	    		$this->backend->languagesFilesUpdate($uversion, $udir);

				events::callEvent('Modules list rebuild');
	    		
	    		exit("Update to ".$uversion." version completed!");
    		} else {
    			exit("'" . $updater_string . "' update url doesn't match format");
    		}
    	} else {
    		exit("'" . $updater_string . "' update folder doesn't exist!");
    	}
    }
}
?>