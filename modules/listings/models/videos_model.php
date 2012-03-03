<?php
include_once(MODULES_PATH . 'listings/classes/youtube_video_process.class.php');
include_once(MODULES_PATH . 'listings/classes/listing_video.class.php');

class videosModel extends model
{
	private $_listing_id;
	
    public function setListingId($listing_id)
    {
    	$this->_listing_id = $listing_id;
    }

    public function setVideoRecordStatus($id, $status)
    {
    	// Clean cache
		$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_' . $id));

    	$this->db->set('last_modified_date', date("Y-m-d H:i:s"));
    	$this->db->where('id', $this->_listing_id);
    	$this->db->update('listings');

    	$this->db->set('status', $status['status']);
    	$this->db->set('error_code', $status['error_code']);
    	$this->db->where('id', $id);
    	return $this->db->update('videos');
    }

	/**
     * Select all videos that were attached to this listing
     *
     * @return array
     */
    public function selectVideosByListingId()
    {
    	$this->db->select();
    	$this->db->from('videos');
    	$this->db->where('listing_id', $this->_listing_id);
    	$this->db->where('video_code !=', '');
    	$this->db->order_by('creation_date');
    	$array = $this->db->get()->result_array();

    	$videos = array();
    	foreach ($array AS $row) {
    		if ($row['mode'] == 'attached')
	        	$video = new listingVideoAttached;
	        if ($row['mode'] == 'uploaded')
	        	$video = new listingVideoUploaded;

    		$video->setVideoFromArray($row);
    		$videos[] = $video;
    	}
    	return $videos;
    }
    
    /**
     * Select all videos that were attached to this listing
     *
     * @return array
     */
    public function selectActiveVideosByListingId()
    {
    	$this->db->select();
    	$this->db->from('videos');
    	$this->db->where('listing_id', $this->_listing_id);
    	$this->db->where('status', 'success');
    	$this->db->where('video_code !=', '');
    	$this->db->order_by('creation_date');
    	$array = $this->db->get()->result_array();

    	$videos = array();
    	foreach ($array AS $row) {
    		if ($row['mode'] == 'attached')
	        	$video = new listingVideoAttached;
	        if ($row['mode'] == 'uploaded')
	        	$video = new listingVideoUploaded;

    		$video->setVideoFromArray($row);
    		$videos[] = $video;
    	}
    	return $videos;
    }
    
    /**
     * create record for attached youtube video
     *
     * @param object $form
     */
    public function saveAttachedVideo($title, $video_code)
    {
    	$video_processing = new videoProcess;
    	$status = $video_processing->getVideoStatus($video_code);
    	
    	$this->db->set('last_modified_date', date("Y-m-d H:i:s"));
    	$this->db->where('id', $this->_listing_id);
    	$this->db->update('listings');

    	$this->db->set('mode', 'attached');
    	$this->db->set('status', $status['status']);
    	$this->db->set('error_code', $status['error_code']);
    	$this->db->set('listing_id', $this->_listing_id);
    	$this->db->set('title', $title);
    	$this->db->set('video_code', $video_code);
    	$this->db->set('creation_date', date("Y-m-d H:i:s"));
    	$this->db->insert('videos');
    	$video_id = $this->db->insert_id();

    	$system_settings = registry::get('system_settings');
        if (isset($system_settings['language_areas_enabled']) && $system_settings['language_areas_enabled']) {
        	translations::saveTranslations(array('videos', 'title', $video_id));
        }

        return $video_id;
    }

    public function getVideoFromForm($video, $form)
    {
    	$video->setVideoFromArray($form);
        return $video;
    }
    
    public function getNewVideo($mode = null)
    {
    	if ($mode == 'attached' || is_null($mode))
        	return new listingVideoAttached;
        if ($mode == 'uploaded')
        	return new listingVideoUploaded;
    }
    
    public function getAttachedVideoById($video_id)
    {
    	$this->db->select();
    	$this->db->from('videos');
    	$this->db->where('id', $video_id);
    	$query = $this->db->get();
    	$row = $query->row_array();
    	
    	if ($row['mode'] == 'attached')
        	$video = new listingVideoAttached;
        if ($row['mode'] == 'uploaded')
        	$video = new listingVideoUploaded;

		$video->setVideoFromArray($row);
        return $video;
    }
    
    public function saveVideoById($video, $form)
    {
    	$this->db->set('last_modified_date', date("Y-m-d H:i:s"));
    	$this->db->where('id', $this->_listing_id);
    	$this->db->update('listings');
    	
    	return $video->saveVideoById($video, $form);
    }
    
    public function deleteVideos($videos_array)
    {
    	if (count($videos_array)) {
	    	foreach ($videos_array AS $id=>$val) {
		    	$this->deleteVideoById($id);
	    	}
	    	return true;
    	} else 
    		return false;
    }
    
    public function deleteVideoById($video_id)
    {
    	$this->db->set('last_modified_date', date("Y-m-d H:i:s"));
    	$this->db->where('id', $this->_listing_id);
    	$this->db->update('listings');

    	$video = $this->getAttachedVideoById($video_id);
    	if ($video->mode == 'uploaded') {
    		$system_settings = registry::get('system_settings');

			$youtube = new videoProcess;
			$youtube->setSettings($system_settings['youtube_username'], $system_settings['youtube_password'], $system_settings['youtube_product_name'], $system_settings['youtube_key']);
			$youtube->getToken();
			if ($youtube->getLastError()) {
				$this->setError(LANG_VIDEO_ERROR . ' : ' . $youtube->getLastError());
				redirect('admin/listings/videos/' . $this->_listing_id . '/');
			}
    		$youtube->deleteVideo($video->video_code);
    	}
    	
	    return $this->db->delete('videos', array('id' => $video_id));
    }
    
    /**
     * insert record before video file will be uploaded to youtube
     *
     * @param string $title
     * @return int
     */
    public function createVideoRecord($title)
    {
    	$this->db->set('listing_id', $this->_listing_id);
    	$this->db->set('title', $title);
    	$this->db->set('mode', 'uploaded');
    	$this->db->set('creation_date', date("Y-m-d H:i:s"));
    	$this->db->insert('videos');
    	$video_id = $this->db->insert_id();

    	$system_settings = registry::get('system_settings');
        if (isset($system_settings['language_areas_enabled']) && $system_settings['language_areas_enabled']) {
        	translations::saveTranslations(array('videos', 'title', $video_id));
        }

        return $video_id;
    }
    
    /**
     * update video record after video file was succesfully uploaded to youtube
     *
     * @param int $video_record_id
     * @param string $video_youtube_id
     * @return bool
     */
    public function completeVideoRecord($video_record_id, $video_youtube_code)
    {
    	$this->db->set('video_code', $video_youtube_code);
    	$this->db->where('id', $video_record_id);
    	return $this->db->update('videos');
    }
}
?>