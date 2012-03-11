<?php

class listingVideoAttached extends listingVideo
{
    public $mode = 'attached';

    public function setValidation($form_validation) {
    	$form_validation->set_rules('video_code', LANG_YOUTUBE_VIDEO_CODE, 'required|alpha_numeric|max_length[25]');
		$form_validation->set_rules('title', LANG_VIDEO_TITLE, 'max_length[255]');
    }
    
    public function saveVideoById($video, $form)
    {
    	$video_processing = new videoProcess;
    	$status = $video_processing->getVideoStatus($form['video_code']);

    	$CI = &get_instance();
    	$CI->videos_model->db->set('title', $form['title']);
    	$CI->videos_model->db->set('video_code', $form['video_code']);
    	$CI->videos_model->db->set('status', $status['status']);
    	$CI->videos_model->db->set('error_code', $status['error_code']);
    	$CI->videos_model->db->where('id', $video->id);
    	return $CI->videos_model->db->update('videos');
    }
}
?>