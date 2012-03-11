<?php
class filesUpload
{
	public $title;
	public $attrs = array();
	public $upload_id;
	public $upload_to;
	public $after_upload_url;
	public $current_file = null;
	public $server_path;
	public $max_upload_filesize;
	
	// CI base super object
	protected $CI;
	
	public function __construct()
	{
		$this->CI = &get_instance();
		$this->max_upload_filesize = $this->CI->config->item('max_upload_filesize');
	}

	public function setUploadBlock($template)
	{
		$users_content = $this->CI->config->item($this->upload_id, 'users_content');
		$upload_to = trim($this->CI->config->item('users_content_http_path'), '/') . '/' . trim($users_content['upload_to'], '/') . '/';
		$allowed_types = $users_content['allowed_types'];

		$view = $this->CI->load->view();
        $view->assign('title', $this->title);
		$view->assign('attrs', $this->attrs);
		$view->assign('max_upload_filesize', $this->max_upload_filesize);
		$view->assign('upload_id', $this->upload_id);
		$view->assign('upload_to', $upload_to);

		if (!is_null($this->current_file))
			$view->assign('current_file', $this->current_file);

		$view->assign('after_upload_url', $this->after_upload_url);
		$view->assign('allowed_types', $allowed_types);
		$view->assign('error_file_choose', 'Select file to upload first!');
		return $view->fetch($template);
	}
}
?>