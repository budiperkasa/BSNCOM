<?php
include_once(MODULES_PATH . 'acl/classes/content_acl.class.php');
include_once(MODULES_PATH . 'content_fields/classes/field.class.php');
include_once(MODULES_PATH . 'content_fields/classes/content_fields.class.php');
include_once(MODULES_PATH . 'content_fields/classes/search_content_fields.class.php');

class contactusController extends controller
{
    public function index()
    {
    	$this->load->model('content_pages', 'content_pages');
		$this->load->model('types', 'types_levels');
		$view = $this->load->view();

		// --------------------------------------------------------------------------------------------
		// Select types and levels in hierarchical array
		// --------------------------------------------------------------------------------------------
		$types = $this->types->getTypesLevels();
		$view->assign('types', $types);
		// --------------------------------------------------------------------------------------------
		
		// --------------------------------------------------------------------------------------------
		// Search block settings
		// --------------------------------------------------------------------------------------------
		$search_fields = new searchContentFields(GLOBAL_SEARCH_GROUP_CUSTOM_NAME, 0);
		$advanced_search_fields = new searchContentFields(GLOBAL_SEARCH_GROUP_CUSTOM_NAME, 0, 'advanced');

		$view->assign('search_fields', $search_fields);
		$view->assign('advanced_search_fields', $advanced_search_fields);
		// --------------------------------------------------------------------------------------------
		
		$content_fields = new contentFields(CONTACT_US_PAGE_GROUP_CUSTOM_NAME, 0);

    	if ($this->input->post('submit')) {
    		$this->form_validation->set_rules('captcha', LANG_CAPTCHA, 'callback_check_captcha');
            $this->form_validation->set_rules('name', LANG_CONTACTUS_NAME, 'required|max_length[255]');
            $this->form_validation->set_rules('email', LANG_CONTACTUS_EMAIL, 'required|valid_email');
            $this->form_validation->set_rules('subject', LANG_CONTACTUS_SUBJECT, 'required|max_length[255]');
            $this->form_validation->set_rules('message', LANG_CONTACTUS_BODY, 'required|max_length[1500]');
            
            $content_fields->validate($this->form_validation);

    		if ($this->form_validation->run() !== FALSE) {
    			$form = $this->form_validation->result_array();
    			
    			$system_settings = registry::get('system_settings');
    			$site_settings = registry::get('site_settings');

				$this->load->library('email');
    			if ($this->config->item('use_smtp_mail') && $this->config->item('smtp_host') && $this->config->item('smtp_port')) {
    				$config = array(
    					'protocol' => 'smtp',
    					'smtp_host' => $this->config->item('smtp_host'),
    					'smtp_port' => $this->config->item('smtp_port'),
    					'smtp_user' => $this->config->item('smtp_username'),
    					'smtp_pass' => $this->config->item('smtp_password'),
    				);
    				$this->email->initialize($config);
    			}
    			$this->email->from($form['email'], $form['name']);
			   	$this->email->to($system_settings['website_email'], $site_settings['website_title']);
			   	$this->email->reply_to($form['email'], $form['name']);
			   	$this->email->subject($form['subject']);
			   	$this->email->message($form['message']);

			   	// Check this message for spam
    			$sprotector = new spamProtector();
    			if (!$sprotector->isSpam($this->input->ip_address(), $form['message'], $form['subject'], $form['name'], $form['email'])) {
    				// Attach information from content fields
    				$message = $form['message'];
    				$content_fields->getValuesFromForm($form);
    				$fields = $content_fields->getFieldsObjects();
    				foreach ($fields AS $seo_name=>$content_field) {
    					$field_name = $content_field->field->name;
    					$field_value = $content_fields->getFieldValueAsString($seo_name);
    					if ($field_value)
    						$message .= '
' . $field_name . ':
' . $field_value;
    				}
    				$this->email->message($message);
			   		$this->email->send();
    			}
			   	$this->setSuccess(LANG_CONTACTUS_SUCCESS);
			   	redirect('contactus/');
			} else {
				$form = $this->form_validation->result_array();

				$view->assign('name', $form['name']);
				$view->assign('email', $form['email']);
				$view->assign('subject', $form['subject']);
				$view->assign('message', $form['message']);
			}
    	}

    	$this->load->plugin('captcha');
		$captcha = create_captcha($this);
		$view->assign('content_fields', $content_fields);
		$view->assign('captcha', $captcha);
    	$view->display('frontend/contactus_page.tpl');
    }
    
    /**
	 * validation function
	 *
	 * @param string $email
	 * @return bool
	 */
	public function check_captcha($captcha)
	{
		if ($this->session->userdata('captcha_word') != $captcha) {
			$this->form_validation->set_message('captcha');
			return FALSE;
		} else {
			return TRUE;
		}
	}
}
?>