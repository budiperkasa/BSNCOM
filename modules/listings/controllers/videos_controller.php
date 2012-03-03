<?php
include_once(MODULES_PATH . 'listings/classes/listing_video.class.php');
include_once(MODULES_PATH . 'listings/classes/youtube_video_process.class.php');
include_once(MODULES_PATH . 'acl/classes/content_acl.class.php');

class videosController extends controller
{
	public function videos($listing_id)
	{
		$this->load->model('listings');
		$this->load->model('videos', null, 'videos_model');
		
		$this->listings->setListingId($listing_id);
		$this->videos_model->setListingId($listing_id);

		// we need to know level of this listing
		$level_id = $this->listings->getLevelIdByListingId();
		
		$content_access_obj = contentAcl::getInstance();
		$content_access_obj->checkListingAccess($listing_id, 'Manage all listings');

		$listing = new listing($level_id, $listing_id);
		$listing->setListingFromArray($this->listings->getListingRowById(), $this->listings->getListingCategories(), $this->listings->getListingLocations());

		// --------------------------------------------------------------------------------------------
		// Check all videos' status
		$videos = $this->videos_model->selectVideosByListingId();
		$video_processing = new videoProcess;
		foreach ($videos AS $video) {
			$this->videos_model->setVideoRecordStatus($video->id, $video_processing->getVideoStatus($video->video_code));
		}
		$videos = $this->videos_model->selectVideosByListingId();
		// --------------------------------------------------------------------------------------------
		
		if (strpos($this->session->userdata('back_page'), 'admin/listings/search/') !== FALSE) {
	    	registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_SEARCH_LISTINGS_TITLE,
	    		'admin/listings/view/' . $listing->id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
	    		LANG_MANAGE_VIDEOS_TITLE,
	    	));
    	} elseif (strpos($this->session->userdata('back_page'), 'admin/listings/my/') !== FALSE) {
    		registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_VIEW_MY_LISTINGS_TITLE,
	    		'admin/listings/view/' . $listing->id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
	    		LANG_MANAGE_VIDEOS_TITLE,
	    	));
    	}

		$view = $this->load->view();
		$view->assign('videos', $videos);
		$view->assign('listing', $listing);
		$view->assign('listing_id', $listing_id);
		$view->display('listings/videos/admin_listing_videos_storage.tpl');
	}

	/**
	 * Attach already uploaded video to the listing
	 *
	 */
	public function videos_attach($listing_id)
	{
		$this->load->model('listings');
		$this->load->model('videos', null, 'videos_model');
		
		$this->listings->setListingId($listing_id);
		$this->videos_model->setListingId($listing_id);

		// we need to know level of this listing
		$level_id = $this->listings->getLevelIdByListingId();
		
		$content_access_obj = contentAcl::getInstance();
		$content_access_obj->checkListingAccess($listing_id, 'Manage all listings');

		$listing = new listing($level_id, $listing_id);
		$listing->setListingFromArray($this->listings->getListingRowById(), $this->listings->getListingCategories(), $this->listings->getListingLocations());

		$videos = $this->videos_model->selectVideosByListingId();
		
		$video = $this->videos_model->getNewVideo();

		if (count($videos) < $listing->level->video_count) {
			if ($this->input->post('submit')) {
				$video->setValidation($this->form_validation);

				if ($this->form_validation->run() !== FALSE) {
					if ($this->videos_model->saveAttachedVideo($this->form_validation->set_value('title'), $this->form_validation->set_value('video_code'))) {
						// Clean cache
						$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_'.$listing_id));

						$this->setSuccess(LANG_VIDEO_ATTACH_SUCCESS);
						redirect('admin/listings/videos/' . $listing_id . '/');
					}
				} else {
					$video = $this->videos_model->getVideoFromForm($video, $this->form_validation->result_array());
					$this->setError('msg');
					$this->setError('msg2');
				}
			}
			
			if (strpos($this->session->userdata('back_page'), 'admin/listings/search/') !== FALSE) {
		    	registry::set('breadcrumbs', array(
		    		$this->session->userdata('back_page') => LANG_SEARCH_LISTINGS_TITLE,
		    		'admin/listings/view/' . $listing_id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
		    		'admin/listings/videos/' . $listing_id => LANG_MANAGE_VIDEOS_TITLE,
		    		LANG_ATTACH_VIDEO_TITLE,
		    	));
	    	} elseif (strpos($this->session->userdata('back_page'), 'admin/listings/my/') !== FALSE) {
	    		registry::set('breadcrumbs', array(
		    		$this->session->userdata('back_page') => LANG_VIEW_MY_LISTINGS_TITLE,
		    		'admin/listings/view/' . $listing_id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
		    		'admin/listings/videos/' . $listing_id => LANG_MANAGE_VIDEOS_TITLE,
		    		LANG_ATTACH_VIDEO_TITLE,
		    	));
	    	}
			
			$view = $this->load->view();
			$view->assign('video', $video);
			$view->assign('listing', $listing);
			$view->assign('listing_id', $listing_id);
			$view->display('listings/videos/admin_listing_video_details.tpl');
		}
	}
	
	/**
	 * Edit already attached video
	 *
	 */
	public function videos_edit($listing_id, $video_id)
	{
		$this->load->model('listings');
		$this->load->model('videos', null, 'videos_model');
		
		$this->listings->setListingId($listing_id);
		$this->videos_model->setListingId($listing_id);

		// we need to know level of this listing
		$level_id = $this->listings->getLevelIdByListingId();

		$content_access_obj = contentAcl::getInstance();
		$content_access_obj->checkListingAccess($listing_id, 'Manage all listings');

		//$listing_settings = $this->listings->getListingSettings();

		$listing = new listing($level_id, $listing_id);
		$listing->setListingFromArray($this->listings->getListingRowById(), $this->listings->getListingCategories(), $this->listings->getListingLocations());

		$video = $this->videos_model->getAttachedVideoById($video_id);

		if ($this->input->post('submit')) {
			$video->setValidation($this->form_validation);

			if ($this->form_validation->run() !== FALSE) {
				if ($this->videos_model->saveVideoById($video, $this->form_validation->result_array())) {
					// Clean cache
					$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_'.$listing_id));

					$this->setSuccess(LANG_VIDEO_SAVE_SUCCESS);
					redirect('admin/listings/videos/' . $listing_id . '/');
				}
			} else {
				$video = $this->videos_model->getVideoFromForm($video, $this->form_validation->result_array());
			}
		}
		
		if (strpos($this->session->userdata('back_page'), 'admin/listings/search/') !== FALSE) {
		    registry::set('breadcrumbs', array(
		    	$this->session->userdata('back_page') => LANG_SEARCH_LISTINGS_TITLE,
		    	'admin/listings/view/' . $listing_id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
		    	'admin/listings/videos/' . $listing_id => LANG_MANAGE_VIDEOS_TITLE,
		    	LANG_EDIT_VIDEO_TITLE,
		    ));
	    } elseif (strpos($this->session->userdata('back_page'), 'admin/listings/my/') !== FALSE) {
	    	registry::set('breadcrumbs', array(
		   		$this->session->userdata('back_page') => LANG_VIEW_MY_LISTINGS_TITLE,
		   		'admin/listings/view/' . $listing_id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
		   		'admin/listings/videos/' . $listing_id => LANG_MANAGE_VIDEOS_TITLE,
		   		LANG_EDIT_VIDEO_TITLE,
		   	));
	    }

		$view = $this->load->view();
		$view->assign('video', $video);
		//$view->assign('listing_settings', $listing_settings);
		$view->assign('listing', $listing);
		$view->assign('listing_id', $listing_id);
		$view->display('listings/videos/admin_listing_video_details.tpl');
	}
	
	/**
	 * Delete just one video
	 * 
	 */
	public function deleteVideo($video_id)
    {
        $this->load->model('listings');
		$this->load->model('videos', null, 'videos_model');

        if ( !$video = $this->videos_model->getAttachedVideoById($video_id)) {
            redirect($this->session->userdata('back_page'));
        }

		$content_access_obj = contentAcl::getInstance();
		$content_access_obj->checkVideoAccess($video_id, 'Manage all listings');
		
		$listing_id = $video->listing_id;
		
		$this->listings->setListingId($listing_id);
		$this->videos_model->setListingId($listing_id);

		// we need to know level of this listing
		$level_id = $this->listings->getLevelIdByListingId(); 

		//$listing_settings = $this->listings->getListingSettings();
		
		$listing = new listing($level_id, $listing_id);
		$listing->setListingFromArray($this->listings->getListingRowById(), $this->listings->getListingCategories(), $this->listings->getListingLocations());

		if ($this->input->post('yes')) {
			// Clean cache
			$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_'.$listing_id));

            if ($this->videos_model->deleteVideoById($video_id)) {
            	$this->setSuccess(LANG_VIDEO_DELETE_SUCCESS);
                redirect('admin/listings/videos/' . $listing_id);
            }
        }

        if ($this->input->post('no')) {
            redirect('admin/listings/videos/' . $listing_id);
        }

		if (strpos($this->session->userdata('back_page'), 'admin/listings/search/') !== FALSE) {
		    registry::set('breadcrumbs', array(
		    	$this->session->userdata('back_page') => LANG_SEARCH_LISTINGS_TITLE,
		    	'admin/listings/view/' . $listing_id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
		    	'admin/listings/videos/' . $listing_id => LANG_MANAGE_VIDEOS_TITLE,
		    	LANG_DELETE_VIDEOS,
		    ));
	    } elseif (strpos($this->session->userdata('back_page'), 'admin/listings/my/') !== FALSE) {
	    	registry::set('breadcrumbs', array(
		   		$this->session->userdata('back_page') => LANG_VIEW_MY_LISTINGS_TITLE,
		   		'admin/listings/view/' . $listing_id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
		   		'admin/listings/videos/' . $listing_id => LANG_MANAGE_VIDEOS_TITLE,
		   		LANG_DELETE_VIDEOS,
		   	));
	    }

		$view  = $this->load->view();
		$view->assign('options', array($video_id => $video->title));
        $view->assign('heading', LANG_DELETE_VIDEOS);
        $view->assign('question', LANG_DELETE_VIDEOS_QUEST);
        $view->display('backend/delete_common_item.tpl');
    }
    
    /**
	 * Bulk videos delete
	 *
	 */
	public function massVideosDelete()
    {
    	$listing_id = $this->input->post('listing_id');

    	$this->load->model('listings');
		$this->load->model('videos', null, 'videos_model');
		
    	$this->listings->setListingId($listing_id);
    	$this->videos_model->setListingId($listing_id);
    	
		// we need to know level of this listing
		$level_id = $this->listings->getLevelIdByListingId();

		//$listing_settings = $this->listings->getListingSettings();
		
		$listing = new listing($level_id, $listing_id);
		$listing->setListingFromArray($this->listings->getListingRowById(), $this->listings->getListingCategories(), $this->listings->getListingLocations());

		$videos_ids = $this->input->searchPostItems('cb_');
		if (empty($videos_ids)) {
			$videos_ids = $this->input->post('options');
		}
		$videos_array = array();
		$content_access_obj = contentAcl::getInstance();
		if (!empty($videos_ids)) {
			foreach ($videos_ids AS $id) {
				$content_access_obj->checkVideoAccess($id, 'Manage all listings');
				$video = $this->videos_model->getAttachedVideoById($id);
				$videos_array[$id] = $video->title;
			}
		} else {
			$this->setError(LANG_VIDEOS_SELECT_ERROR);
			redirect('admin/listings/videos/' . $listing_id);
		}

        if ($this->input->post('yes')) {
        	// Clean cache
			$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_'.$listing_id));

            if ($this->videos_model->deleteVideos($videos_array)) {
                $this->setSuccess(LANG_VIDEOS_DELETE_SUCCESS);
                redirect('admin/listings/videos/' . $listing_id);
            }
        }

        if ($this->input->post('no')) {
            redirect('admin/listings/videos/' . $listing_id);
        }
        
        if (strpos($this->session->userdata('back_page'), 'admin/listings/search/') !== FALSE) {
		    registry::set('breadcrumbs', array(
		    	$this->session->userdata('back_page') => LANG_SEARCH_LISTINGS_TITLE,
		    	'admin/listings/view/' . $listing_id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
		    	'admin/listings/videos/' . $listing_id => LANG_MANAGE_VIDEOS_TITLE,
		    	LANG_EDIT_VIDEO_TITLE,
		    ));
	    } elseif (strpos($this->session->userdata('back_page'), 'admin/listings/my/') !== FALSE) {
	    	registry::set('breadcrumbs', array(
		   		$this->session->userdata('back_page') => LANG_VIEW_MY_LISTINGS_TITLE,
		   		'admin/listings/view/' . $listing_id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
		   		'admin/listings/videos/' . $listing_id => LANG_MANAGE_VIDEOS_TITLE,
		   		LANG_EDIT_VIDEO_TITLE,
		   	));
	    }

        $view  = $this->load->view();
		$view->assign('options', $videos_array);
		$view->assign('hidden', array('listing_id' => $listing_id));
        $view->assign('heading', LANG_DELETE_VIDEOS);
        $view->assign('question', LANG_DELETE_VIDEOS_QUEST);
        $view->display('backend/delete_common_item.tpl');
    }
	
	/**
	 * Upload video file to youtube in 2 steps
	 * 1st step: enter video details
	 * 2nd step: upload video file
	 *
	 */
	public function videos_upload($listing_id)
	{
		$this->load->model('listings');
		$this->load->model('videos', null, 'videos_model');
		
		$this->listings->setListingId($listing_id);
		$this->videos_model->setListingId($listing_id);

		// we need to know level of this listing
		$level_id = $this->listings->getLevelIdByListingId();
		
		$content_access_obj = contentAcl::getInstance();
		$content_access_obj->checkListingAccess($listing_id, 'Manage all listings');

		//$listing_settings = $this->listings->getListingSettings();
		
		$listing = new listing($level_id, $listing_id);
		$listing->setListingFromArray($this->listings->getListingRowById(), $this->listings->getListingCategories(), $this->listings->getListingLocations());

		$videos = $this->videos_model->selectVideosByListingId();
		
		if (strpos($this->session->userdata('back_page'), 'admin/listings/search/') !== FALSE) {
			registry::set('breadcrumbs', array(
				$this->session->userdata('back_page') => LANG_SEARCH_LISTINGS_TITLE,
				'admin/listings/view/' . $listing_id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
				'admin/listings/videos/' . $listing_id => LANG_MANAGE_VIDEOS_TITLE,
				LANG_UPLOAD_VIDEO_TITLE,
			));
		} elseif (strpos($this->session->userdata('back_page'), 'admin/listings/my/') !== FALSE) {
		   	registry::set('breadcrumbs', array(
		   		$this->session->userdata('back_page') => LANG_VIEW_MY_LISTINGS_TITLE,
		  		'admin/listings/view/' . $listing_id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
		   		'admin/listings/videos/' . $listing_id => LANG_MANAGE_VIDEOS_TITLE,
		   		LANG_UPLOAD_VIDEO_TITLE,
		   	));
		}

		if (count($videos) < $listing->level->video_count) {
			$system_settings = registry::get('system_settings');
			if ($system_settings['youtube_key'] && $system_settings['youtube_username'] && $system_settings['youtube_password'] && $system_settings['youtube_product_name']) {
				$youtube_username = $system_settings['youtube_username'];
				$youtube_password = $system_settings['youtube_password'];
				$youtube_product_name = $system_settings['youtube_product_name'];
				$youtube_key = $system_settings['youtube_key'];

				if ($this->input->post('details')) {
			        // --------------------------------------------------------------------------------------------
					// 2nd step
					// --------------------------------------------------------------------------------------------
					$this->form_validation->set_rules('title', LANG_VIDEO_TITLE, 'required|max_length[60]');
					$this->form_validation->set_rules('description', LANG_VIDEO_DESCRIPTION, 'max_length[5000]');
					$this->form_validation->set_rules('category', LANG_VIDEO_CATEGORY, 'is_selected');
					$this->form_validation->set_rules('tags', LANG_VIDEO_TAGS, 'required|max_length[255]');
	
					if ($this->form_validation->run() !== FALSE) {
						$form = $this->form_validation->result_array();

						// --------------------------------------------------------------------------------------------
						// Connect to YouTube
						// --------------------------------------------------------------------------------------------
						$system_settings = registry::get('system_settings');
						$youtube = new videoProcess;
						$youtube->setSettings($youtube_username, $youtube_password, $youtube_product_name, $youtube_key);
						$youtube->getToken();
						if ($youtube->getLastError()) {
							$this->setError(LANG_VIDEO_ERROR . ' : ' . $youtube->getLastError());
							redirect('admin/listings/videos/' . $listing_id . '/');
						}
						// --------------------------------------------------------------------------------------------
	
						// --------------------------------------------------------------------------------------------
						// Build uploading XML form
						// --------------------------------------------------------------------------------------------
						$xml_view = $this->load->view();
						$xml_view->assign('title', $form['title']);
						$xml_view->assign('description', $form['description']);
						$xml_view->assign('category', $form['category']);
						$xml_view->assign('tags', $form['tags']);
						$data = $xml_view->fetch('listings/videos/youtube_xml/step_1.xml');
						// --------------------------------------------------------------------------------------------
						
						// Send XML data to YouTube
						if (!$youtube->getUploadFormValues($data)) {
							$this->setError(LANG_VIDEO_UPLOAD_ERROR . ' :' . $youtube->getLastError());
							redirect('admin/listings/videos/' . $listing_id . '/');
						}
						
						$this->setSuccess(LANG_VIDEO_STEP1_SUCCESS);
	
						// Create new record in DB with empty video_code column
						$video_record_id = $this->videos_model->createVideoRecord($form['title']);
	
						$nextUrl = site_url('admin/listings/' . $listing_id . '/get_video/' . $video_record_id);
	
						$view = new view;
						$view->assign('video_title', $form['title']);
						
						$view->assign('nextUrl', $nextUrl);
						$view->assign('tokenValue', $youtube->token);
						$view->assign('postUrl', $youtube->post_url);
						$view->display('listings/videos/admin_listing_video_upload_uploading.tpl');
						exit();
					}
		        }
			} else {
				$this->setError(LANG_VIDEO_YOUTUBE_SETTINGS_ERROR);
			}
		} else {
			exit();
		}
		
		// --------------------------------------------------------------------------------------------
		// 1st step
		// --------------------------------------------------------------------------------------------
		$view = new view;
		$view->display('listings/videos/admin_listing_video_upload_details.tpl');
	}
	
	/**
	 * After video file was uploaded to youtube, user will be redirected to this page
	 * with status code, video code and video record id arguments
	 *
	 */
	public function get_video($listing_id, $video_record_id, $query_string)
	{
		if (preg_match("/status=([^&]+)&id=([^\/]+)/", $query_string, $matches)) {
			$status = $matches[1];
			$video_youtube_code = trim($matches[2], '/');
		}
		
		$this->load->model('listings');
		$this->load->model('videos', null, 'videos_model');
		
		$this->listings->setListingId($listing_id);
		$this->videos_model->setListingId($listing_id);

		// we need to know level of this listing
		$level_id = $this->listings->getLevelIdByListingId();
		
		$content_access_obj = contentAcl::getInstance();
		$content_access_obj->checkListingAccess($listing_id, 'Manage all listings');

		//$listing_settings = $this->listings->getListingSettings();
		
		$listing = new listing($level_id, $listing_id);
		$listing->setListingFromArray($this->listings->getListingRowById(), $this->listings->getListingCategories(), $this->listings->getListingLocations());

		$videos = $this->videos_model->selectVideosByListingId();

		if ($status == 200 && $video_youtube_code) {
			if ($this->videos_model->completeVideoRecord($video_record_id, $video_youtube_code)) {
				// Clean cache
				$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_'.$listing_id));

				$this->setSuccess(LANG_VIDEO_UPLOAD_SUCCESS);
				redirect('admin/listings/videos/' . $listing_id . '/');
			}
		}
		// Show error message
		$this->setError(LANG_VIDEO_UPLOAD_ERROR);
		redirect('admin/listings/videos/' . $listing_id . '/');
	}
}
?>