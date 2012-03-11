<?php
include_once(MODULES_PATH . 'listings/classes/listing_file.class.php');

class filesModel extends model
{
    private $_listing_id;
    
    public function setListingId($listing_id)
    {
    	$this->_listing_id = $listing_id;
    }

    /**
     * Select all files that were attached to this listing
     *
     * @return array
     */
    public function selectFilesByListingId()
    {
    	$this->db->select();
    	$this->db->from('files');
    	$this->db->where('listing_id', $this->_listing_id);
    	$this->db->order_by('creation_date');
		$query = $this->db->get();

    	$files = array();
    	foreach ($query->result_array() AS $row) {
	        $file = new listingFile;
	        
    		$file->setFileFromArray($row);
    		$files[] = $file;
    	}
    	return $files;
    }
    
    /**
     * save file attributes into DB
     *
     * @param string $file_title
     * @param string $file_name
     * @param string $file_size
     * @param string $file_format
     */
    public function saveFileToStorage($file_title, $file_name, $file_size, $file_format)
    {
    	$this->db->set('last_modified_date', date("Y-m-d H:i:s"));
    	$this->db->where('id', $this->_listing_id);
    	$this->db->update('listings');

    	$this->db->set('listing_id', $this->_listing_id);
    	$this->db->set('title', $file_title);
    	$this->db->set('file', $file_name);
    	$this->db->set('creation_date', date("Y-m-d H:i:s"));
    	$this->db->set('file_size', $file_size);
    	$this->db->set('file_format', $file_format);
    	$this->db->insert('files');
    	$file_id = $this->db->insert_id();

    	$system_settings = registry::get('system_settings');
        if (isset($system_settings['language_areas_enabled']) && $system_settings['language_areas_enabled']) {
        	translations::saveTranslations(array('files', 'title', $file_id));
        }

        return $file_id;
    }
    
    public function deleteFiles($files_array)
    {
    	if (count($files_array)) {
	    	foreach ($files_array AS $id=>$val) {
		    	$this->deleteFileById($id);
	    	}
	    	return true;
    	} else 
    		return false;
    }
    
    public function deleteFileById($file_id)
    {
    	$this->db->set('last_modified_date', date("Y-m-d H:i:s"));
    	$this->db->where('id', $this->_listing_id);
    	$this->db->update('listings');
    	
    	$file = $this->getFileById($file_id);
    	
    	// We will check if exist both: absolute path and $file->file
    	/*$users_content_server_path = $this->config->item('users_content_server_path');
		$users_content = $this->config->item('listing_file', 'users_content');
		if (@is_file($users_content_server_path . $users_content['upload_to'] . $file->file))
			@unlink($users_content_server_path . $users_content['upload_to'] . $file->file);
		else
			@unlink($file->file);*/
		
		return $this->db->delete('files', array('id' => $file_id));
    }
    
    public function getFileFromForm($file_id, $form)
    {
    	$file = $this->getFileById($file_id);
    	$file->setFileFromArray($form);
    	return $file;
    }

    public function getFileById($file_id)
	{
    	$this->db->select();
    	$this->db->from('files');
    	$this->db->where('id', $file_id);
    	$query = $this->db->get();

    	$file = new listingFile;
    	$file->setFileFromArray($query->row_array());
		return $file;
	}
	
	public function saveFileById($file_id, $form)
    {
    	$this->db->set('last_modified_date', date("Y-m-d H:i:s"));
    	$this->db->where('id', $this->_listing_id);
    	$this->db->update('listings');

    	if ($form['title'] != '') {
    		$title = $form['title'];
    	} else {
    		$title = 'attached file';
    	}
    	
    	$this->db->set('title', $title);
    	$this->db->where('id', $file_id);
    	return $this->db->update('files');
    }
}
?>