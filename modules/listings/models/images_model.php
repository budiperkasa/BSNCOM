<?php
include_once(MODULES_PATH . 'listings/classes/listing_image.class.php');

class imagesModel extends Model 
{
	private $_listing_id;
	
	public function setListingId($listing_id)
    {
    	$this->_listing_id = $listing_id;
    }
	
	/**
     * Select all images that were attached to this listing
     *
     * @return array
     */
    public function selectImagesByListingId()
    {
    	$this->db->select();
    	$this->db->from('images');
    	$this->db->where('listing_id', $this->_listing_id);
    	$this->db->order_by('creation_date');
    	$query = $this->db->get();

    	$images = array();
    	foreach ($query->result_array() AS $row) {
	        $image = new listingImage;
	        
    		$image->setImageFromArray($row);
    		$images[] = $image;
    	}
    	return $images;
    }
    
    /**
     * save image attributes into DB
     *
     */
    public function saveImageToGallery($image_title, $file_name)
    {
    	$this->db->set('last_modified_date', date("Y-m-d H:i:s"));
    	$this->db->where('id', $this->_listing_id);
    	$this->db->update('listings');

    	$this->db->set('listing_id', $this->_listing_id);
    	$this->db->set('title', $image_title);
    	$this->db->set('file', $file_name);
    	$this->db->set('creation_date', date("Y-m-d H:i:s"));
    	$this->db->insert('images');
    	$image_id = $this->db->insert_id();

    	$system_settings = registry::get('system_settings');
        if (isset($system_settings['language_areas_enabled']) && $system_settings['language_areas_enabled']) {
        	translations::saveTranslations(array('images', 'title', $image_id));
        }

        return $image_id;
    }
    
    public function getImageById($image_id)
    {
    	$this->db->select();
    	$this->db->from('images');
    	$this->db->where('id', $image_id);
    	$query = $this->db->get();

    	$image = new listingImage;
		$image->setImageFromArray($query->row_array());
		return $image;
    }
    
    public function getImageFromForm($image_id, $form)
    {
    	$image = $this->getImageById($image_id);

		$image->setImageFromArray($form);
		return $image;
    }
    
    public function saveImageById($image_id, $form)
    {
    	$this->db->set('last_modified_date', date("Y-m-d H:i:s"));
    	$this->db->where('id', $this->_listing_id);
    	$this->db->update('listings');

    	$this->db->set('title', $form['title']);
    	$this->db->where('id', $image_id);
    	return $this->db->update('images');
    }
    
    public function deleteImages($images_array)
    {
    	if (count($images_array)) {
	    	foreach ($images_array AS $id=>$val) {
		    	$this->deleteImageById($id);
	    	}
	    	return true;
    	} else 
    		return false;
    }
    
    public function deleteImageById($image_id)
    {
    	$this->db->set('last_modified_date', date("Y-m-d H:i:s"));
    	$this->db->where('id', $this->_listing_id);
    	$this->db->update('listings');
    	
    	$image = $this->getImageById($image_id);
    	
    	/*$users_content_server_path = $this->config->item('users_content_server_path');
    	$users_content_array = $this->config->item('users_content');
    	$image_path_to_upload = $users_content_array['listing_image']['upload_to'];
		$image_thmb_path_to_upload = $users_content_array['listing_image_thmb']['upload_to'];

    	@unlink($users_content_server_path . $image_path_to_upload . $image->file);
    	@unlink($users_content_server_path . $image_thmb_path_to_upload . $image->file);*/

	    return $this->db->delete('images', array('id' => $image_id));
    }
}
?>