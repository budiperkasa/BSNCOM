<?php

class email_senderController extends controller
{
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
	
    public function send_listing($listing_id, $target)
    {
    	$this->load->model('listings', 'listings');
    	if (!$listing = $this->listings->getListingById($listing_id))
    		exit();

    	if ($this->input->post('submit')) {
    		$this->form_validation->set_rules('captcha', LANG_CAPTCHA, 'callback_check_captcha');
    		$this->form_validation->set_rules('subject', LANG_SUBJECT, 'required|max_length[100]');
    		$this->form_validation->set_rules('body', LANG_MESSAGE, 'required|max_length[1000]');
    		if (!$this->session->userdata('user_login')) {
	    		$this->form_validation->set_rules('sender_name', LANG_YOUR_NAME, 'max_length[100]');
	    		$this->form_validation->set_rules('sender_email', LANG_YOUR_EMAIL, 'required|valid_email');
    		}
    		if ($target == 'friend') {
    			$this->form_validation->set_rules('recipient_name', LANG_RECIPIENT_NAME, 'max_length[100]');
    			$this->form_validation->set_rules('recipient_email', LANG_RECIPIENT_EMAIL, 'required|valid_email');
    		}
    		
    		if ($this->form_validation->run() !== FALSE) {
    			if ($this->session->userdata('user_login')) {
    				$sender_name = $this->session->userdata('user_login');
	    			$sender_email = $this->session->userdata('user_email');
    			} else {
	    			$sender_name = $this->form_validation->set_value('sender_name');
	    			$sender_email = $this->form_validation->set_value('sender_email');
    			}
    			if ($target == 'friend') {
	    			$recipient_name = $this->form_validation->set_value('recipient_name');
	    			$recipient_email = $this->form_validation->set_value('recipient_email');
    			} elseif ($target == 'owner') {
    				$recipient_name = $listing->user->login;
    				$recipient_email = $listing->user->email;
    			} elseif ($target == 'report') {
		    		$system_settings = registry::get('system_settings');
		    		$site_settings = registry::get('site_settings');
		    		$recipient_name = $site_settings['website_title'];
		    		$recipient_email = $system_settings['website_email'];
		    	}
    			$subj = $this->form_validation->set_value('subject');
    			$body = $this->form_validation->set_value('body');

    			$this->load->library('email');
    			$system_settings = registry::get('system_settings');
    			$site_settings = registry::get('site_settings');
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
    			// Attach sender name and email to the subject
    			$subject = 'From ' . $sender_name . ' <' . $sender_email . '>: ' . $subj;
    			
    			$this->email->from($system_settings['website_email'], $site_settings['website_title']);
    			$this->email->to($recipient_email, $recipient_name);
    			$this->email->reply_to($sender_email, $sender_name);
    			$this->email->subject($subject);
    			$this->email->message($body . '

' . $site_settings['signature_in_emails']  . '

' . $this->input->ip_address());
    			
    			// Check this message for spam
    			$sprotector = new spamProtector();
    			if (!$sprotector->isSpam($this->input->ip_address(), $body, $subject, $sender_name, $sender_email)) {
	    			$this->email->send();
    			}

	    		$this->setSuccess(LANG_SEND_SUCCESS);
    		} else {
    			$sender_name = $this->form_validation->set_value('sender_name');
	    		$sender_email = $this->form_validation->set_value('sender_email');
    			$recipient_name = $this->form_validation->set_value('recipient_name');
    			$recipient_email = $this->form_validation->set_value('recipient_email');
    			$subj = $this->form_validation->set_value('subject');
    			$body = $this->form_validation->set_value('body');
    		}
    	} else {
    		if ($this->session->userdata('user_login')) {
	    		$sender_name = $this->session->userdata('user_login');
	    		$sender_email = $this->session->userdata('user_email');
	    	} else {
	    		$sender_name = '';
	    		$sender_email = '';
	    	}
	    	if ($target == 'friend') {
	    		$recipient_name = '';
	    		$recipient_email = '';
	    		$subj = LANG_EMAIL_FRIEND_SUBJ . " '" . $listing->title() . "'";
	    		$body = site_url('listings/' . $listing->getUniqueId() . '/') . '
';
	    	} elseif ($target == 'owner') {
	    		$recipient_name = $listing->user->login;
	    		$recipient_email = $listing->user->email;
	    		$subj= LANG_EMAIL_OWNER_SUBJ . " '" . $listing->title() . "'";
	    		$body = '';
	    	} elseif ($target == 'report') {
	    		$system_settings = registry::get('system_settings');
	    		$site_settings = registry::get('site_settings');
	    		$recipient_name = $site_settings['website_title'];
	    		$recipient_email = $system_settings['website_email'];
	    		$subj= LANG_EMAIL_REPORT_SUBJ . " '" . $listing->title() . "'";
	    		$body = '';
	    	}
    	}
    	
    	$this->load->plugin('captcha');
		$captcha = create_captcha($this);

    	$view = $this->load->view();
	    $view->assign('target', $target);
	    $view->assign('sender_name', $sender_name);
	    $view->assign('sender_email', $sender_email);
	    $view->assign('recipient_name', $recipient_name);
	    $view->assign('recipient_email', $recipient_email);
	    $view->assign('subject', $subj);
	    $view->assign('body', $body);
	    $view->assign('captcha', $captcha);
	    $view->assign('sender_url', site_url("email/send/listing_id/".$listing_id."/target/".$target."/"));
    	$view->display('email_sender/user_send.tpl');
    }
    
    public function send_user($user_id)
    {
    	$this->load->model('listings', 'listings');
    	$this->load->model('users', 'users');
    	$this->users->setUserId($user_id);
    	$user = $this->users->getUserById();

    	if ($this->input->post('submit')) {
    		$this->form_validation->set_rules('captcha', LANG_CAPTCHA, 'callback_check_captcha');
    		$this->form_validation->set_rules('subject', LANG_SUBJECT, 'required|max_length[100]');
    		$this->form_validation->set_rules('body', LANG_MESSAGE, 'required|max_length[1000]');
   			if (!$this->session->userdata('user_login')) {
	    		$this->form_validation->set_rules('sender_name', LANG_YOUR_NAME, 'max_length[100]');
    			$this->form_validation->set_rules('sender_email', LANG_YOUR_EMAIL, 'required|valid_email');
   			}

    		if ($this->form_validation->run() !== FALSE) {
    			if (!$this->session->userdata('user_login')) {
	    			$sender_name = $this->form_validation->set_value('user_login');
		    		$sender_email = $this->form_validation->set_value('user_email');
    			} else {
    				$sender_name = $this->session->userdata('user_login');
	    			$sender_email = $this->session->userdata('user_email');
    			}
    			$recipient_email = $user->email;
    			$recipient_name = $user->login;
    			$subj = $this->form_validation->set_value('subject');
    			$body = $this->form_validation->set_value('body');

    			$this->load->library('email');
    			$system_settings = registry::get('system_settings');
    			$site_settings = registry::get('site_settings');
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
    			$this->email->from($system_settings['website_email'], $site_settings['website_title']);
    			$this->email->to($recipient_email, $recipient_name);
    			$this->email->reply_to($sender_email, $sender_name);
    			$this->email->subject($subj);
    			$this->email->message($body . '

' . $site_settings['signature_in_emails']  . '

' . $this->input->ip_address());
    			
    			// Check this message for spam
    			$sprotector = new spamProtector();
    			if (!$sprotector->isSpam($this->input->ip_address(), $body, $subj, $sender_name, $sender_email))
	    			$this->email->send();
    			$this->setSuccess(LANG_SEND_SUCCESS);
    		} else {
    			if (!$this->session->userdata('user_login')) {
	    			$sender_name = $this->form_validation->set_value('sender_name');
		    		$sender_email = $this->form_validation->set_value('sender_email');
    			} else {
    				$sender_name = '';
    				$sender_email = '';
    			}
    			$subj = $this->form_validation->set_value('subject');
    			$body = $this->form_validation->set_value('body');
    		}
    	} else {
    		$sender_name = '';
	    	$sender_email = '';
	    	$subj = '';
	    	$body = '';
    	}
    	
    	$this->load->plugin('captcha');
		$captcha = create_captcha($this);

    	$view = $this->load->view();
	    $view->assign('subject', $subj);
	    $view->assign('sender_name', $sender_name);
	    $view->assign('sender_email', $sender_email);
	    $view->assign('body', $body);
	    $view->assign('captcha', $captcha);
	    $view->assign('sender_url', site_url("email/send/user_id/".$user_id));
    	$view->display('email_sender/user_send.tpl');
    }
}
?>