<?php

class spamProtector
{
	public function isSpam($ip, $body, $subject = null, $author = null, $author_email = null, $author_url = null)
	{
		$author_url = trim($author_url, '/');

		include_once(BASEPATH . 'config/blacklists.php');
		if (in_array($ip, $spam_ips) || in_array($author_email, $spam_emails) || in_array($author_url, $spam_urls))
			return true;
		
		foreach ($spam_emails AS $email) {
			if (strpos($body, $email) !== FALSE)
				return true;
			if ($subject && strpos($subject, $email) !== FALSE)
				return true;
		}
		
		foreach ($spam_urls AS $url) {
			if (strpos($body, $url) !== FALSE)
				return true;
			if ($subject && strpos($subject, $url) !== FALSE)
				return true;
		}
		
		$system_settings = registry::get('system_settings');
		if ($system_settings['mollom_public_key'] && $system_settings['mollom_private_key']) {
			include_once(MODULES_PATH . 'settings/classes/mollom/mollom.php');
			$mollom = new mollom();
			$mollom->setPublicKey($system_settings['mollom_public_key']);
			$mollom->setPrivateKey($system_settings['mollom_private_key']);
			$mollom->setServerList(array('http://xmlrpc.mollom.com'));
			$result = $mollom->checkContent(null, $subject, $body, $author, $author_url, $author_email);
			if ($result['spam'] == 'spam')
				return true;
		}
		return false;
	}
}
?>