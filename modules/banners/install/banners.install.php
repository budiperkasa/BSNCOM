<?php

// Create 'banners' folder in 'users_content'
$CI = &get_instance();
$CI->config->load(MODULES_PATH . 'banners'.DIRECTORY_SEPARATOR.'banners.config.php');

$users_content_server_path = $CI->config->item('users_content_server_path');
$users_content_array = $CI->config->item('users_content');
$banner_image_path_to_upload = $users_content_array['banner_file']['upload_to'];
@mkdir($users_content_server_path . $banner_image_path_to_upload, 0777);
?>