<?php
include_once(MODULES_PATH . 'listings/classes/listing_file.class.php');
include_once(MODULES_PATH . 'ajax_files_upload/classes/files_upload.class.php');
include_once(MODULES_PATH . 'acl/classes/content_acl.class.php');

class filesController extends controller
{
	public function files($listing_id)
	{
		$this->load->model('listings');
		$this->load->model('files', null, 'files_model');

		$this->listings->setListingId($listing_id);
		$this->files_model->setListingId($listing_id);

		// we need to know level of this listing
		$level_id = $this->listings->getLevelIdByListingId();
		
		$content_access_obj = contentAcl::getInstance();
		$content_access_obj->checkListingAccess($listing_id, 'Manage all listings');

		$listing = new listing($level_id, $listing_id);
		$listing->setListingFromArray($this->listings->getListingRowById(), $this->listings->getListingCategories(), $this->listings->getListingLocations());

		$files = $this->files_model->selectFilesByListingId();
		
		if (strpos($this->session->userdata('back_page'), 'admin/listings/search/') !== FALSE) {
	    	registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_SEARCH_LISTINGS_TITLE,
	    		'admin/listings/view/' . $listing->id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
	    		LANG_MANAGE_FILES_TITLE,
	    	));
    	} elseif (strpos($this->session->userdata('back_page'), 'admin/listings/my/') !== FALSE) {
    		registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_VIEW_MY_LISTINGS_TITLE,
	    		'admin/listings/view/' . $listing->id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
	    		LANG_MANAGE_FILES_TITLE,
	    	));
    	}
		
		$view = $this->load->view();
		if (count($files) < $listing->level->files_count) {
			$file_to_upload = new filesUpload;
			$file_to_upload->title = LANG_LISTING_FILE;
			$file_to_upload->upload_id = 'listing_file';
			$file_to_upload->after_upload_url = site_url('ajax/listings/' . $listing_id . '/get_file/' . $level_id);
			$file_to_upload->attrs['listing_id'] = $listing_id;
			$file_to_upload->attrs['files_limit'] = $listing->level->files_count;

			$view->addJsFile('ajaxfileupload.js');
			$view->assign('file_upload_block', $file_to_upload);
		}

		$view->assign('files', $files);
		$view->assign('listing', $listing);
		$view->assign('listing_id', $listing_id);
		$view->display('listings/files/admin_listing_files_storage.tpl');
	}
	
	/**
	 * Handle uploaded file and create new record in DB
	 */
	public function get_file($listing_id, $level_id)
    {
    	if ($this->input->post('uploaded_file')) {
    		$uploaded_file = $this->input->post('uploaded_file');

			$this->load->model('listings');
			$this->load->model('files', null, 'files_model');
			
			$this->listings->setListingId($listing_id);
			$this->files_model->setListingId($listing_id);

			$content_access_obj = contentAcl::getInstance();
			$content_access_obj->checkListingAccess($listing_id, 'Manage all listings');

			$listing = new listing($level_id, $listing_id);

			$files = $this->files_model->selectFilesByListingId();

			if (count($files) < $listing->level->files_count) {
				if ($this->input->post('file_title') != '') {
					$file_title = $this->input->post('file_title');
				} else {
					$file_title = LANG_LISTING_ATTACHED_FILE;
				}

				$this->load->helper('number');
				$file_size = byte_format(filesize($uploaded_file));

				$creation_date = date("m/j/y H:i:s");

				$path_info = pathinfo($uploaded_file);
				$file_format = strtolower($path_info['extension']);
				// Error sometimes: sytemException on file_get_contents
				if (!@file_get_contents(registry::get('public_path') . 'images/file_types/' . $file_format . '.png')) {
					$file_format = 'undefined';
				}

				$file_id = $this->files_model->saveFileToStorage($file_title, basename($uploaded_file), $file_size, $file_format);
				// Clean cache
				$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_'.$listing_id));
	
				echo json_encode(array(
					'error_msg' => '',
					'file_title' => _utf8_encode($file_title),
					'file_name' => $uploaded_file,
					'file_size' => $file_size,
					'file_format' => $file_format,
					'creation_date' => $creation_date,
					'file_id' => $file_id,
				));
			} else {
				echo json_encode(array('error_msg' => _utf8_encode(LANG_FILE_COUNT_ERROR)));
				return;
			}
		}
	}
	
	/**
	 * Edit already attached files
	 *
	 */
	public function file_edit($listing_id, $file_id)
	{
		$this->load->model('listings');
		$this->load->model('files', null, 'files_model');
		
		$this->listings->setListingId($listing_id);
		$this->files_model->setListingId($listing_id);

		// we need to know level of this listing
		$level_id = $this->listings->getLevelIdByListingId();

		$listing = new listing($level_id, $listing_id);
		$listing->setListingFromArray($this->listings->getListingRowById(), $this->listings->getListingCategories(), $this->listings->getListingLocations());
		
		$content_access_obj = contentAcl::getInstance();
		$content_access_obj->checkListingAccess($listing_id, 'Manage all listings');
		
		if ($this->input->post('submit')) {
			$this->form_validation->set_rules('title', LANG_FILE_TITLE, 'max_length[255]');

			if ($this->form_validation->run() !== FALSE) {
				if ($this->files_model->saveFileById($file_id, $this->form_validation->result_array())) {
					// Clean cache
					$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_'.$listing_id));

					$this->setSuccess(LANG_FILE_SAVE_SUCCESS);
					redirect('admin/listings/files/' . $listing_id . '/');
				}
			} else {
				$file = $this->files_model->getFileFromForm($file_id, $this->form_validation->result_array());
			}
		} else {
			$file = $this->files_model->getFileById($file_id);
		}
		
		if (strpos($this->session->userdata('back_page'), 'admin/listings/search/') !== FALSE) {
	    	registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_SEARCH_LISTINGS_TITLE,
	    		'admin/listings/view/' . $listing_id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
	    		'admin/listings/files/' . $listing_id => LANG_MANAGE_FILES_TITLE,
	    		LANG_EDIT_FILES_TITLE,
	    	));
    	} elseif (strpos($this->session->userdata('back_page'), 'admin/listings/my/') !== FALSE) {
    		registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_VIEW_MY_LISTINGS_TITLE,
	    		'admin/listings/view/' . $listing_id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
	    		'admin/listings/files/' . $listing_id => LANG_MANAGE_FILES_TITLE,
	    		LANG_EDIT_FILES_TITLE,
	    	));
    	}

		$view = $this->load->view();
		$view->assign('file', $file);
		$view->assign('listing', $listing);
		$view->assign('listing_id', $listing_id);
		$view->display('listings/files/admin_listing_file_details.tpl');
	}
	
	/**
	 * Delete just one file
	 * 
	 */
	public function deleteFile($file_id)
    {
        $this->load->model('listings');
        $this->load->model('files', null, 'files_model');

        if (!$file = $this->files_model->getFileById($file_id)) {
            redirect($this->session->userdata('back_page'));
        }
        
        $listing_id = $file->listing_id;
		
		$content_access_obj = contentAcl::getInstance();
		$content_access_obj->checkFileAccess($file_id, 'Manage all listings');
		
		$this->listings->setListingId($listing_id);
		$this->files_model->setListingId($listing_id);
		
		// we need to know level of this listing
		$level_id = $this->listings->getLevelIdByListingId();

		$listing = new listing($level_id, $listing_id);
		$listing->setListingFromArray($this->listings->getListingRowById(), $this->listings->getListingCategories(), $this->listings->getListingLocations());

		if ($this->input->post('yes')) {
			// Clean cache
			$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_'.$listing_id));

            if ($this->files_model->deleteFileById($file_id)) {
            	$this->setSuccess(LANG_FILE_DELETE_SUCCESS);
                redirect('admin/listings/files/' . $listing_id);
            }
        }

        if ($this->input->post('no')) {
            redirect('admin/listings/files/' . $listing_id);
        }

        if (strpos($this->session->userdata('back_page'), 'admin/listings/search/') !== FALSE) {
	    	registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_SEARCH_LISTINGS_TITLE,
	    		'admin/listings/view/' . $listing_id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
	    		'admin/listings/files/' . $listing_id => LANG_MANAGE_FILES_TITLE,
	    		LANG_DELETE_FILES,
	    	));
    	} elseif (strpos($this->session->userdata('back_page'), 'admin/listings/my/') !== FALSE) {
    		registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_VIEW_MY_LISTINGS_TITLE,
	    		'admin/listings/view/' . $listing_id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
	    		'admin/listings/files/' . $listing_id => LANG_MANAGE_FILES_TITLE,
	    		LANG_DELETE_FILE_TITLE,
	    	));
    	}

		$view  = $this->load->view();
		$view->assign('options', array($file_id => $file->title));
        $view->assign('heading', LANG_DELETE_FILE_TITLE);
        $view->assign('question', LANG_DELETE_FILES_QUEST);
        $view->display('backend/delete_common_item.tpl');
    }
    
    /**
	 * Bulk files delete
	 *
	 */
	public function massFilesDelete()
    {
    	$listing_id = $this->input->post('listing_id');

    	$this->load->model('listings');
		$this->load->model('files', null, 'files_model');
		
		$this->listings->setListingId($listing_id);
		$this->files_model->setListingId($listing_id);

		// we need to know level of this listing
		$level_id = $this->listings->getLevelIdByListingId();

		$listing = new listing($level_id, $listing_id);
		$listing->setListingFromArray($this->listings->getListingRowById(), $this->listings->getListingCategories(), $this->listings->getListingLocations());

		$files_ids = $this->input->searchPostItems('cb_');
		if (empty($files_ids)) {
			$files_ids = $this->input->post('options');
		}
		$files_array = array();
		$content_access_obj = contentAcl::getInstance();
		if (!empty($files_ids)) {
			foreach ($files_ids AS $id) {
				$content_access_obj->checkFileAccess($id, 'Manage all listings');
				$file = $this->files_model->getFileById($id);
				$files_array[$id] = $file->title;
			}
		} else {
			$this->setError(LANG_FILES_SELECT_ERROR);
			redirect('admin/listings/files/' . $listing_id);
		}
		

        if ($this->input->post('yes')) {
        	// Clean cache
			$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_'.$listing_id));

            if ($this->files_model->deleteFiles($files_array)) {
                $this->setSuccess(LANG_FILE_DELETE_SUCCESS);
                redirect('admin/listings/files/' . $listing_id);
            }
        }

        if ($this->input->post('no')) {
            redirect('admin/listings/files/' . $listing_id);
        }
        
        if (strpos($this->session->userdata('back_page'), 'admin/listings/search/') !== FALSE) {
	    	registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_SEARCH_LISTINGS_TITLE,
	    		'admin/listings/view/' . $listing_id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
	    		'admin/listings/files/' . $listing_id => LANG_MANAGE_FILES_TITLE,
	    		LANG_DELETE_FILES,
	    	));
    	} elseif (strpos($this->session->userdata('back_page'), 'admin/listings/my/') !== FALSE) {
    		registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_VIEW_MY_LISTINGS_TITLE,
	    		'admin/listings/view/' . $listing_id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
	    		'admin/listings/files/' . $listing_id => LANG_MANAGE_FILES_TITLE,
	    		LANG_DELETE_FILES,
	    	));
    	}

        $view  = $this->load->view();
		$view->assign('options', $files_array);
		$view->assign('hidden', array('listing_id' => $listing_id));
        $view->assign('heading', LANG_DELETE_FILES);
        $view->assign('question', LANG_DELETE_FILES_QUEST);
        $view->display('backend/delete_common_item.tpl');
    }
}
?>