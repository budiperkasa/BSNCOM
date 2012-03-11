<?php

/**
 * realize contentAcl object as singleton:
 * - one entry point;
 * - one object in the whole system
 * - object allowed from modules and views
 *
 */
class contentAcl
{
	private static $instance = null;
	
	private function __construct()
	{
	}
	
	public static function getInstance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new contentAcl;
			return self::$instance;
		} else {
			return self::$instance;
		}
	}
	
	/**
	 * Checks user access to the access item
	 *
	 * @param string $access_item
	 * @return bool
	 */
	public function isPermission($access_item)
	{
		$CI = &get_instance();
		$CI->load->model('acl', 'acl');

		return $CI->acl->checkAccess($CI->session->userdata('user_group_id'), $access_item);
	}
	
	/**
	 * Checks user access to the level
	 *
	 * @param int $level_id
	 * @return bool
	 */
	public function isContentPermission($object_name, $object_id)
	{
		$CI = &get_instance();
		$CI->load->model('acl', 'acl');

		return $CI->acl->checkContentAccess($CI->session->userdata('user_group_id'), $object_name, $object_id);
	}
	
	/**
	 * Checks if admin access listing or just a user wants to edit self listing
	 *
	 * @param int $listing_id
	 * @param string $admin_access
	 */
	public function checkListingAccess($listing_id, $admin_access = null)
	{
		if (!$this->getListingAccess($listing_id, $admin_access)) {
			show_error('401 Access denied!', '');
		} else {
			return true;
		}
	}
	
	public function getListingAccess($listing_id, $admin_access)
	{
		$CI = &get_instance();
		$CI->load->model('acl', 'acl');

		if (is_null($admin_access) || !$CI->acl->checkAccess($CI->session->userdata('user_group_id'), $admin_access)) {
			$CI->db->select('owner_id');
			$CI->db->from('listings');
			$CI->db->where('id', $listing_id);
			$CI->db->where('owner_id', $CI->session->userdata('user_id'));
			$query = $CI->db->get();

			return $query->num_rows();
		} else {
			return true;
		}
	}
	
	/**
	 * Checks if admin access image or just a user wants to edit self image
	 *
	 * @param int $image_id
	 * @param string $admin_access
	 */
	public function checkImageAccess($image_id, $admin_access = null)
	{
		if (!$this->getImageAccess($image_id, $admin_access)) {
			show_error('401 Access denied!', '');
		} else {
			return true;
		}
	}
	
	public function getImageAccess($image_id, $admin_access)
	{
		$CI = &get_instance();
		$CI->load->model('acl', 'acl');

		if (is_null($admin_access) || !$CI->acl->checkAccess($CI->session->userdata('user_group_id'), $admin_access)) {
			$CI->db->select('l.owner_id');
			$CI->db->from('images AS i');
			$CI->db->join('listings AS l', 'l.id=i.listing_id', 'left');
			$CI->db->where('i.id', $image_id);
			$CI->db->where('l.owner_id', $CI->session->userdata('user_id'));
			$query = $CI->db->get();

			return $query->num_rows();
		} else {
			return true;
		}
	}
	
	/**
	 * Checks if admin access file or just a user wants to edit self file
	 *
	 * @param int $file_id
	 * @param string $admin_access
	 */
	public function checkFileAccess($file_id, $admin_access = null)
	{
		if (!$this->getImageAccess($file_id, $admin_access)) {
			show_error('401 Access denied!', '');
		} else {
			return true;
		}
	}
	
	public function getFileAccess($file_id, $admin_access)
	{
		$CI = &get_instance();
		$CI->load->model('acl', 'acl');

		if (is_null($admin_access) || !$CI->acl->checkAccess($CI->session->userdata('user_group_id'), $admin_access)) {
			$CI->db->select('l.owner_id');
			$CI->db->from('files AS f');
			$CI->db->join('listings AS l', 'l.id=f.listing_id', 'left');
			$CI->db->where('f.id', $image_id);
			$CI->db->where('l.owner_id', $CI->session->userdata('user_id'));
			$query = $CI->db->get();

			return $query->num_rows();
		} else {
			return true;
		}
	}
	
	/**
	 * Checks if admin access video or just a user wants to edit self video
	 *
	 * @param int $file_id
	 * @param string $admin_access
	 */
	public function checkVideoAccess($video_id, $admin_access = null)
	{
		if (!$this->getVideoAccess($video_id, $admin_access)) {
			show_error('401 Access denied!', '');
		} else {
			return true;
		}
	}
	
	public function getVideoAccess($video_id, $admin_access)
	{
		$CI = &get_instance();
		$CI->load->model('acl', 'acl');

		if (is_null($admin_access) || !$CI->acl->checkAccess($CI->session->userdata('user_group_id'), $admin_access)) {
			$CI->db->select('l.owner_id');
			$CI->db->from('videos AS v');
			$CI->db->join('listings AS l', 'l.id=v.listing_id', 'left');
			$CI->db->where('v.id', $video_id);
			$CI->db->where('l.owner_id', $CI->session->userdata('user_id'));
			$query = $CI->db->get();

			return $query->num_rows();
		} else {
			return true;
		}
	}
	
	/**
	 * Checks if admin access user or just a user wants to edit self profile
	 *
	 * @param int $user_id
	 * @param string $admin_access
	 */
	public function checkUserAccess($user_id, $admin_access = null)
	{
		if (!$this->getUserAccess($user_id, $admin_access)) {
			show_error('401 Access denied!', '');
		} else {
			return true;
		}
	}

	public function getUserAccess($user_id, $admin_access)
	{
		$CI = &get_instance();
		$CI->load->model('acl', 'acl');

		if (is_null($admin_access) || !$CI->acl->checkAccess($CI->session->userdata('user_group_id'), $admin_access)) {
			if ($user_id == $CI->session->userdata('user_id')) {
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}
}
?>