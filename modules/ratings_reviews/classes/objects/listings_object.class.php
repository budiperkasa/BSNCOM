<?php
include_once(MODULES_PATH . 'ratings_reviews/classes/objects/default_object.class.php');

/**
 * ratings and reviews attached to objects,
 * listings may be such objects.
 * Here are some common methods for objects behaviour
 *
 */
class listingsObject extends ratingsReviewsDefaultObject
{
	public $listing_id;
	public $listing;

	public function __construct($listing_id)
	{
		$this->listing_id = $listing_id;

		$CI = &get_instance();
		$CI->load->model('listings', 'listings');
		$CI->listings->setListingId($listing_id);
		$this->listing = $CI->listings->getListingById();
	}
	
	/**
	 * Check is object assigned with this review exists right now
	 *
	 * @return bool
	 */
	public function isObject()
	{
		return (bool)$this->listing;
	}
	
	/**
	 * Get owner of the object
	 *
	 * @return user object
	 */
	public function getOwner()
	{
		return $this->listing->user;
	}
	
	/**
	 * is review body may be edited in richtext editor?
	 *
	 * @return bool
	 */
	public function isRichtext()
	{
		if ($this->isObject())
			return $this->listing->level->reviews_richtext_enabled;
		else 
			return false;
	}
	
	/**
	 * Check if user permitted to work with this review
	 *
	 * @param string $user_access
	 * @param string $admin_access
	 */
	public function checkAccess($user_access, $admin_access)
	{
		$content_access_object = contentAcl::getInstance();
		if (!$content_access_object->isPermission($admin_access))
			if (!$content_access_object->isPermission($user_access) || !$content_access_object->checkListingAccess($this->listing_id))
				show_error('401 Access denied!', '');
	}
	
	public function cleanCache()
	{
		// Clean cache
		$CI = &get_instance();
		$CI->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_' . $this->listing_id));
	}
	
	/**
	 * URL to see all reviews assigned with this object
	 *
	 * @return url
	 */
	public function getObjectReviewsUrl()
	{
		return site_url("admin/reviews/listings/" . $this->listing->id);
	}
	
	/**
	 * URL to this object at the backend
	 *
	 * @return url
	 */
	public function getObjectUrl()
	{
		return site_url("admin/listings/view/" . $this->listing->id);
	}
	
	/**
	 * URL to this object at the frontend
	 *
	 * @return url
	 */
	public function getObjectFrontUrl()
	{
		return site_url($this->listing->url());
	}
	
	public function getObjectTitle()
	{
		return $this->listing->title();
	}
	
	public function setBreadcrumbs($last_crumb)
	{
		$CI = &get_instance();

		if (strpos($CI->session->userdata('back_page'), 'admin/listings/search/') !== FALSE) {
	    	registry::set('breadcrumbs', array(
	    		$CI->session->userdata('back_page') => LANG_SEARCH_LISTINGS_TITLE,
	    		"admin/listings/view/" . $this->listing->id => LANG_VIEW_LISTING . ' "' . $this->listing->title() . '"',
	    		"admin/reviews/listings/" . $this->listing->id => LANG_ADMIN_REVIEWS_TITLE,
	    		$last_crumb
	    	));
    	} elseif (strpos($CI->session->userdata('back_page'), 'admin/listings/my/') !== FALSE) {
    		registry::set('breadcrumbs', array(
	    		$CI->session->userdata('back_page') => LANG_VIEW_MY_LISTINGS_TITLE,
	    		"admin/listings/view/" . $this->listing->id => LANG_VIEW_LISTING . ' "' . $this->listing->title() . '"',
	    		"admin/reviews/listings/" . $this->listing->id => LANG_ADMIN_REVIEWS_TITLE,
	    		$last_crumb
	    	));
    	} elseif (strpos($CI->session->userdata('reviews_back_page'), 'admin/reviews/listings/search/') !== FALSE) {
    		registry::set('breadcrumbs', array(
	    		$CI->session->userdata('reviews_back_page') => LANG_SEARCH_REVIEWS_TITLE,
	    		$last_crumb
	    	));
    	}
	}
	
	public function isRatings()
	{
		return (bool)$this->listing->level->ratings_enabled;
	}
	
	public function checkIsInLocation($search_location)
	{
		if ($search_location) {
			$CI = &get_instance();
			
			$CI->load->model('locations', 'locations_predefined');
    		if (is_numeric($search_location)) {
    			// By ID
    			$search_location = $CI->locations->getLocationById($search_location);
    		} elseif (is_string($search_location)) {
    			// By seo name
    			$search_location = $CI->locations->getLocationBySeoName($search_location);
    		}
    		$locations_children_objs = $CI->locations->getAllChildrenOfLocation($search_location);
    		$locations_children_objs[] = $search_location;
    		$search_locations_ids = array();
    		foreach ($locations_children_objs AS $location_obj) {
    			$search_locations_ids[] = $location_obj->id;
    		}
			
			$CI->db->select('l.id');
			$CI->db->from('listings as l');
			$CI->db->join('levels as lev', 'lev.id=l.level_id', 'left');
    		$CI->db->join('types as t', 't.id=lev.type_id', 'left');
			$CI->db->where('l.id', $this->listing_id);
    		$CI->db->join('listings_in_locations AS lil', 'lil.listing_id=l.id', 'left');
    		$where_sql = '';
    		if ($search_location->geocoded_name)
    			$where_sql = '(lil.geocoded_name LIKE "%' . $search_location->geocoded_name . '" AND t.locations_enabled=1 AND lev.locations_number>0) OR ';
    		$CI->db->where('(' . $where_sql . 'lil.predefined_location_id IN (' . implode(',', $search_locations_ids) . '))', null, false);
    		return $CI->db->get()->num_rows();
    	}
	}
}
?>