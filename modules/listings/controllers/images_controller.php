<?php
include_once(MODULES_PATH . 'listings/classes/listing_image.class.php');
include_once(MODULES_PATH . 'ajax_files_upload/classes/files_upload.class.php');
include_once(MODULES_PATH . 'acl/classes/content_acl.class.php');

class imagesController extends controller
{
	public function images($listing_id)
	{
		$this->load->model('listings');
		$this->load->model('images', null, 'images_model');
		
		$this->listings->setListingId($listing_id);
		$this->images_model->setListingId($listing_id);

		// we need to know level of this listing
		$level_id = $this->listings->getLevelIdByListingId();
		
		$content_access_obj = contentAcl::getInstance();
		$content_access_obj->checkListingAccess($listing_id, 'Manage all listings');

		$listing = new listing($level_id, $listing_id);
		$listing->setListingFromArray($this->listings->getListingRowById(), $this->listings->getListingCategories(), $this->listings->getListingLocations());

		$images = $this->images_model->selectImagesByListingId();
		
		if (strpos($this->session->userdata('back_page'), 'admin/listings/search/') !== FALSE) {
	    	registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_SEARCH_LISTINGS_TITLE,
	    		'admin/listings/view/' . $listing->id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
	    		LANG_MANAGE_LISTING_IMAGES_TITLE,
	    	));
    	} elseif (strpos($this->session->userdata('back_page'), 'admin/listings/my/') !== FALSE) {
    		registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_VIEW_MY_LISTINGS_TITLE,
	    		'admin/listings/view/' . $listing->id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
	    		LANG_MANAGE_LISTING_IMAGES_TITLE,
	    	));
    	}
		
		$view = $this->load->view();
		if (count($images) < $listing->level->images_count) {
			$file_to_upload = new filesUpload;
			$file_to_upload->title = LANG_LISTING_IMAGE;
			$file_to_upload->upload_id = 'listing_image';
			$file_to_upload->after_upload_url = site_url('ajax/listings/' . $listing_id . '/get_image/' . $level_id);
			$file_to_upload->attrs['width'] = $listing->level->explodeSize('images_size', 'width');
			$file_to_upload->attrs['height'] = $listing->level->explodeSize('images_size', 'height');
			$file_to_upload->attrs['listing_id'] = $listing_id;
			$file_to_upload->attrs['images_limit'] = $listing->level->images_count;

			$view->addJsFile('ajaxfileupload.js');
			$view->assign('image_upload_block', $file_to_upload);
		}

		$view->assign('images', $images);
		$view->assign('listing', $listing);
		$view->assign('listing_id', $listing_id);
		$view->display('listings/images/admin_listing_image_gallery.tpl');
	}
	
	/**
	 * Handle uploaded image file and process it through image resize functions
	 */
	public function get_image($listing_id, $level_id)
    {
    	if ($this->input->post('uploaded_file')) {
    		$uploaded_file = $this->input->post('uploaded_file');
			$users_content_server_path = $this->config->item('users_content_server_path');
			$users_content_settings = $this->config->item('users_content');
			
			$this->load->model('listings');
			$this->load->model('images', null, 'images_model');
			
			$this->listings->setListingId($listing_id);
			$this->images_model->setListingId($listing_id);
			
			$content_access_obj = contentAcl::getInstance();
			$content_access_obj->checkListingAccess($listing_id, 'Manage all listings');

			$listing = new listing($level_id, $listing_id);
			
			$images = $this->images_model->selectImagesByListingId();
			
			if (count($images) < $listing->level->images_count) {
				$this->load->library('image_lib');

				// Process all available thumbnails
				foreach ($users_content_settings['listing_image']['thumbnails'] AS $thmb) {
					if (isset($thmb['size']))
						$destImageSize[] = $thmb['size'];
					else
						$destImageSize[] = $listing->level->images_thumbnail_size;
					$destImageFolder[] = $users_content_server_path . $thmb['upload_to'];
					if (isset($thmb['crop']))
						$destImageCrop[] = $thmb['crop'];
					else 
						$destImageCrop[] = false;
				}

				$destImageSize[] = $listing->level->images_size;
				$destImageFolder[] = $users_content_server_path . $users_content_settings['listing_image']['upload_to'];
				$destImageCrop[] = false;
				
				$destImageSize[] = $listing->level->logo_size;
				$destImageFolder[] = $users_content_server_path . $users_content_settings['listing_logo_image']['upload_to'];
				$destImageCrop[] = false;
			} else {
				echo json_encode(array('error_msg' => _utf8_encode(LANG_IMAGES_COUNT_ERROR)));
				return;
			}
			
			if ($this->image_lib->process_images('resize', $uploaded_file, $destImageFolder, $destImageSize, $destImageCrop)) {
				$image_title = $this->input->post('image_title');
				$uploaded_file = basename($uploaded_file);
				$creation_date = date("m/j/y H:i:s");

				$image_id = $this->images_model->saveImageToGallery($image_title, $uploaded_file);
				// Clean cache
				$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_'.$listing_id));

				echo json_encode(array(
					'error_msg' => '',
					'file_name' => $uploaded_file,
					'image_title' => _utf8_encode($image_title),
					'creation_date' => $creation_date,
					'image_width' => $listing->level->explodeSize('images_thumbnail_size', 'width'),
					'image_height' => $listing->level->explodeSize('images_thumbnail_size', 'height'),
					'image_id' => $image_id,
				));
			} else {
				echo json_encode(array('error_msg' => _utf8_encode($this->image_lib->display_errors()), 'file_name' => ''));
			}
			return;
		}
	}

	public function imageEdit($listing_id, $image_id)
	{
		$this->load->model('listings');
		$this->load->model('images', null, 'images_model');
		
		$this->listings->setListingId($listing_id);
		$this->images_model->setListingId($listing_id);

		// we need to know level of this listing
		$level_id = $this->listings->getLevelIdByListingId();
		
		$content_access_obj = contentAcl::getInstance();
		$content_access_obj->checkListingAccess($listing_id, 'Manage all listings');

		$listing = new listing($level_id, $listing_id);
		$listing->setListingFromArray($this->listings->getListingRowById(), $this->listings->getListingCategories(), $this->listings->getListingLocations());
		
		if ($this->input->post('submit')) {
			$this->form_validation->set_rules('title', LANG_IMAGE_TITLE, 'max_length[255]');

			if ($this->form_validation->run() !== FALSE) {
				if ($this->images_model->saveImageById($image_id, $this->form_validation->result_array())) {
					// Clean cache
					$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_'.$listing_id));

					$this->setSuccess(LANG_IMAGE_SAVE_SUCCESS);
					redirect('admin/listings/images/' . $listing_id . '/');
				}
			} else {
				$image = $this->images_model->setImageFromArray($image_id, $this->form_validation->result_array());
			}
		} else {
			$image = $this->images_model->getImageById($image_id);
		}
		
		if (strpos($this->session->userdata('back_page'), 'admin/listings/search/') !== FALSE) {
	    	registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_SEARCH_LISTINGS_TITLE,
	    		'admin/listings/view/' . $listing_id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
	    		'admin/listings/images/' . $listing_id => LANG_MANAGE_LISTING_IMAGES_TITLE,
	    		LANG_MANAGE_IMAGE_TITLE,
	    	));
    	} elseif (strpos($this->session->userdata('back_page'), 'admin/listings/my/') !== FALSE) {
    		registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_VIEW_MY_LISTINGS_TITLE,
	    		'admin/listings/view/' . $listing_id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
	    		'admin/listings/images/' . $listing_id => LANG_MANAGE_LISTING_IMAGES_TITLE,
	    		LANG_MANAGE_IMAGE_TITLE,
	    	));
    	}

		$view = $this->load->view();
		$view->assign('listing', $listing);
		$view->assign('image', $image);
		$view->display('listings/images/admin_listing_image_details.tpl');
	}
	
	/**
	 * Delete just one image
	 * 
	 */
	public function deleteImage($image_id)
    {
        $this->load->model('listings');
		$this->load->model('images', null, 'images_model');

        if ( !$image = $this->images_model->getImageById($image_id)) {
            redirect($this->session->userdata('back_page'));
        }
        
        $listing_id = $image->listing_id;
		
		$content_access_obj = contentAcl::getInstance();
		$content_access_obj->checkImageAccess($image_id, 'Manage all listings');
		
		$this->listings->setListingId($listing_id);
		$this->images_model->setListingId($listing_id);
		
		// we need to know level of this listing
		$level_id = $this->listings->getLevelIdByListingId();

		$listing = new listing($level_id, $listing_id);
		$listing->setListingFromArray($this->listings->getListingRowById(), $this->listings->getListingCategories(), $this->listings->getListingLocations());

		if ($this->input->post('yes')) {
			// Clean cache
			$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_'.$listing_id));

            if ($this->images_model->deleteImageById($image_id)) {
            	$this->setSuccess(LANG_IMAGES_DELETE_SUCCESS);
                redirect('admin/listings/images/' . $listing_id . '/');
            }
        }

        if ($this->input->post('no')) {
            redirect('admin/listings/images/' . $listing_id . '/');
        }

        
        if (strpos($this->session->userdata('back_page'), 'admin/listings/search/') !== FALSE) {
	    	registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_SEARCH_LISTINGS_TITLE,
	    		'admin/listings/view/' . $listing_id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
	    		'admin/listings/images/' . $listing_id => LANG_MANAGE_LISTING_IMAGES_TITLE,
	    		LANG_DELETE_IMAGES,
	    	));
    	} elseif (strpos($this->session->userdata('back_page'), 'admin/listings/my/') !== FALSE) {
    		registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_VIEW_MY_LISTINGS_TITLE,
	    		'admin/listings/view/' . $listing_id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
	    		'admin/listings/images/' . $listing_id => LANG_MANAGE_LISTING_IMAGES_TITLE,
	    		LANG_DELETE_IMAGES,
	    	));
    	}

		$view  = $this->load->view();
		$view->assign('options', array($image_id => $image->title));
        $view->assign('heading', LANG_DELETE_IMAGES);
        $view->assign('question', LANG_DELETE_IMAGES_QUEST);
        $view->display('backend/delete_common_item.tpl');
    }
    
    /**
	 * Bulk images delete
	 *
	 */
	public function massImagesDelete()
    {
    	$listing_id = $this->input->post('listing_id');

    	$this->load->model('listings');
		$this->load->model('images', null, 'images_model');
    	
		$this->listings->setListingId($listing_id);
		$this->images_model->setListingId($listing_id);
		
		// we need to know level of this listing
		$level_id = $this->listings->getLevelIdByListingId();

		$listing = new listing($level_id, $listing_id);
		$listing->setListingFromArray($this->listings->getListingRowById(), $this->listings->getListingCategories(), $this->listings->getListingLocations());

		$images_ids = $this->input->searchPostItems('cb_');
		if (empty($images_ids)) {
			$images_ids = $this->input->post('options');
		}
		$images_array = array();
		$content_access_obj = contentAcl::getInstance();
		if (!empty($images_ids)) {
			foreach ($images_ids AS $id) {
				$content_access_obj->checkImageAccess($id, 'Manage all listings');
				$image = $this->images_model->getImageById($id);
				$images_array[$id] = $image->title;
			}
		} else {
			$this->setError(LANG_IMAGES_SELECT_ERROR);
			redirect('admin/listings/images/' . $listing_id);
		}

        if ($this->input->post('yes')) {
        	// Clean cache
			$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_'.$listing_id));

            if ($this->images_model->deleteImages($images_array)) {
                $this->setSuccess(LANG_IMAGES_DELETE_SUCCESS);
                redirect('admin/listings/images/' . $listing_id);
            }
        }

        if ($this->input->post('no')) {
        	redirect('admin/listings/images/' . $listing_id);
        }
        
        if (strpos($this->session->userdata('back_page'), 'admin/listings/search/') !== FALSE) {
	    	registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_SEARCH_LISTINGS_TITLE,
	    		'admin/listings/view/' . $listing_id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
	    		'admin/listings/images/' . $listing_id => LANG_MANAGE_LISTING_IMAGES_TITLE,
	    		LANG_DELETE_IMAGES,
	    	));
    	} elseif (strpos($this->session->userdata('back_page'), 'admin/listings/my/') !== FALSE) {
    		registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_VIEW_MY_LISTINGS_TITLE,
	    		'admin/listings/view/' . $listing_id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
	    		'admin/listings/images/' . $listing_id => LANG_MANAGE_LISTING_IMAGES_TITLE,
	    		LANG_DELETE_IMAGES,
	    	));
    	}

        $view  = $this->load->view();
		$view->assign('options', $images_array);
		$view->assign('hidden', array('listing_id' => $listing_id));
        $view->assign('heading', LANG_DELETE_IMAGES);
        $view->assign('question', LANG_DELETE_IMAGES_QUEST);
        $view->display('backend/delete_common_item.tpl');
    }
}
?>