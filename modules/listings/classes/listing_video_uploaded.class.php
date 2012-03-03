<?php

class listingVideoUploaded extends listingVideo
{
    public $mode = 'attached';

    public function setValidation($form_validation) {
		$form_validation->set_rules('title', LANG_VIDEO_TITLE, 'max_length[255]');
    }
    
    public function saveVideoById($video, $form)
    {
    	$CI = &get_instance();
    	$CI->videos_model->db->set('title', $form['title']);
    	$CI->videos_model->db->where('id', $video->id);
    	return $CI->videos_model->db->update('videos');
    }
}
?>