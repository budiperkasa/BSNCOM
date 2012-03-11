<?php
class ajax_files_uploadController extends controller
{
	public function index($upload_id)
	{
		$upload_field = $upload_id . "_browse";
		
		$users_content = $this->config->item($upload_id, 'users_content');
		$server_path = $this->config->item('users_content_server_path') . trim($users_content['upload_to'], '/') . '/';
		$http_path = trim($this->config->item('users_content_http_path'), '/') . '/' . trim($users_content['upload_to'], '/') . '/';
		$allowed_types = $users_content['allowed_types'];

    	$this->load->library('upload', array(
    		'upload_path' => $server_path, 
    		'allowed_types' => $allowed_types, 
    		'encrypt_name' => TRUE,
    	));

    	if ($this->upload->do_upload($upload_field)) {
    		$file_attrs = $this->upload->data();
    		$file = $file_attrs['full_path'];
    		@chmod($file, 0777);
    		$error = '';
    	} else {
    		$error = $this->upload->display_errors('', '');
			$file = '';
    	}

		// JQuery .post needs JSON response
		echo json_encode(array('error_msg' => _utf8_encode($error), 'uploaded_file' => $file));
	}
}
?>