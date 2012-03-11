<?php
abstract class ratingsReviewsDefaultObject
{
	/**
	 * Is there object at all
	 */
	public function isObject() { }
	
	/**
	 * Get owner of the object
	 *
	 * @return user object
	 */
	public function getOwner()  { }

	/**
	 * is review body may be edited in richtext editor?
	 *
	 * @return bool
	 */
	public function isRichtext()  { }
	
	/**
	 * Check if user permitted to work with this review
	 *
	 * @param string $user_access
	 * @param string $admin_access
	 */
	public function checkAccess($user_access, $admin_access)  { }

	/**
	 * clear cache of the object
	 */
	public function cleanCache()  { }
	
	/**
	 * URL to see all reviews assigned with this object
	 *
	 * @return url
	 */
	public function getObjectReviewsUrl() { }

	/**
	 * URL to this object at the backend
	 *
	 * @return url
	 */
	public function getObjectUrl() { }
	
	/**
	 * URL to this object at the frontend
	 *
	 * @return url
	 */
	public function getObjectFrontUrl() { }

	/**
	 * the title of the object, if possible
	 */
	public function getObjectTitle() { }

	/**
	 * breadcrumbs to this object, if possible
	 * @param string $last_crumb
	 */
	public function setBreadcrumbs($last_crumb) { }

	/**
	 * are there ratings connected with this object
	 */
	public function isRatings() { }

	/**
	 * Check is this object allowed for location
	 * 
	 * @param ID/object/seo_name $search_location
	 */
	public function checkIsInLocation($search_location) { }
}
?>