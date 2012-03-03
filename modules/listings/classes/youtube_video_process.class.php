<?php

class videoProcess
{
	private $AUTH_TOKEN = null;
	private $last_error = null;

	private $username;
	private $password;
	private $product_name;
	private $developers_key;
	
	public $post_url = null;
	public $token;
	
	public function setSettings($username, $password, $product_name, $developers_key)
	{
		$this->username = $username;
		$this->password = $password;
		$this->product_name = $product_name;
		$this->developers_key = $developers_key;
	}
	
	public function getVideoStatus($video_code)
    {
    	$success = array('status' => 'success', 'error_code' => '');
    	
    	$fullUrl = sprintf("http://gdata.youtube.com/feeds/api/videos/%s", $video_code);

    	$curl_handle=curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $fullUrl);
		//curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($curl_handle);
		curl_close($curl_handle);
		
		if ($response == 'Invalid id' || $response == 'Video not found') {
			return array('status' => 'error', 'error_code' => $response);
		}
		
		$yt_xml = @simplexml_load_string($response);
		if ($yt_xml) {
			if ($state = @$yt_xml->children('http://purl.org/atom/app#')->control->children('http://gdata.youtube.com/schemas/2007')->state) {
				$attrs = $state->attributes();
				return array(
					'status' => (string) $attrs['name'],
					'error_code' => (string) $state
				);
			}
		} else 
			return array('status' => 'error', 'error_code' => 'Unknown Error');
			
		return $success;
    }
	
	public function getLastError()
	{
		return $this->last_error;
	}
	
	public function getToken()
	{
		$fullUrl = "https://www.google.com/youtube/accounts/ClientLogin";
		$eq = 'accountType=GOOGLE&Email=' . urlencode($this->username) . '&Passwd=' . urlencode($this->password) . '&service=youtube&source=' . urlencode($this->product_name);

    	$curl_handle=curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $fullUrl);
		curl_setopt($curl_handle, CURLOPT_HEADER, 0);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, 2);
    	curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, 0);
    	curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl_handle, CURLOPT_POST, TRUE);
		curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $eq);
		curl_setopt($curl_handle, CURLOPT_REFERER, "http://".$_SERVER['HTTP_HOST']);
		curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', 'X-GData-Key: key=' . $this->developers_key));
		$response = curl_exec($curl_handle);

		if ($response) {
			preg_match("!(.*?)Auth=(.*?)\n!si", $response, $ok);
			if (count($ok) >= 3) {
				$this->AUTH_TOKEN = $ok[2];
			} else {
				$this->last_error = $response;
			}
		} else {
			$this->last_error = curl_error($curl_handle);
		}
		curl_close($curl_handle);
	}

	public function getUploadFormValues($data)
	{
		$fullUrl = "http://gdata.youtube.com/action/GetUploadToken";

    	$curl_handle=curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $fullUrl);
		curl_setopt($curl_handle, CURLOPT_HEADER, 1);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_handle, CURLOPT_POST, TRUE);
		curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl_handle, CURLOPT_REFERER, "http://".$_SERVER['HTTP_HOST']);
		curl_setopt($curl_handle, CURLOPT_TIMEOUT, 1000);
		curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array(
			'POST /action/GetUploadToken HTTP/1.1',
			'Host: gdata.youtube.com',
			'Content-Type: application/atom+xml; charset=UTF-8',
			'Content-Length: ' . strlen($data),
			'Authorization: GoogleLogin auth=' . $this->AUTH_TOKEN,
			'X-GData-Key: key=' . $this->developers_key));
		$response = curl_exec($curl_handle);

		if ($response) {
			if (preg_match("/HTTP\/1.1 200/", $response)) {
				if (preg_match("/<url>(.+)<\/url>/is", $response, $url) && preg_match("/<token>(.+)<\/token>/is", $response, $token)) {
					$this->post_url = $url[1];
					$this->token = $token[1];
					curl_close($curl_handle);
					return true;
				}
			}
			$this->last_error = $response;
		} else {
			$this->last_error = curl_error($curl_handle);
		}
		curl_close($curl_handle);
		return false;
	}
	
	public function deleteVideo($video_code)
	{
		$fullUrl = "http://gdata.youtube.com/feeds/api/users/" . $this->username . "/uploads/" . $video_code;

    	$curl_handle=curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $fullUrl);
		curl_setopt($curl_handle, CURLOPT_HEADER, 1);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_handle, CURLOPT_REFERER, "http://".$_SERVER['HTTP_HOST']);
		curl_setopt($curl_handle, CURLOPT_TIMEOUT, 1000);
		curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array(
			'DELETE /feeds/api/users/' . $this->username . '/uploads/' . $video_code . ' HTTP/1.1',
			'Host: gdata.youtube.com',
			'Content-Type: application/atom+xml; charset=UTF-8',
			'Authorization: GoogleLogin auth=' . $this->AUTH_TOKEN,
			'X-GData-Key: key=' . $this->developers_key));
		$response = curl_exec($curl_handle);
		
		if ($response) {
			curl_close($curl_handle);
			return true;
		} else {
			$this->last_error = curl_error($curl_handle);
		}
		curl_close($curl_handle);
		return false;
	}
}
?>