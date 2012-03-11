<?php
include_once(MODULES_PATH . 'listings/classes/listing.class.php');
include_once(MODULES_PATH . 'acl/classes/content_acl.class.php');

//include_once(MODULES_PATH . 'ratings_reviews/classes/reviews_block.class.php');

class ratings_reviewsController extends controller
{
    public function admin_ratings($listing_id)
	{
		$this->load->model('ratings', 'ratings_reviews');
		$this->load->model('listings');
		$this->listings->setListingId($listing_id);
		$level_id = $this->listings->getLevelIdByListingId();
		
		$content_access_obj = contentAcl::getInstance();
		$content_access_obj->checkListingAccess($listing_id, 'Manage all listings');

		$listing = new listing($level_id, $listing_id);
		$listing->setListingFromArray($this->listings->getListingRowById(), $this->listings->getListingCategories(), $this->listings->getListingLocations());
		
		$ratings = $this->ratings->getRatings('listings', $listing_id);
		$avg_rating = $this->ratings->buildAverageRating($ratings, 'listings', $listing_id);
		
		if (strpos($this->session->userdata('back_page'), 'admin/listings/search/') !== FALSE) {
	    	registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_SEARCH_LISTINGS_TITLE,
	    		'admin/listings/view/' . $listing->id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
	    		LANG_ADMIN_RATINGS_TITLE,
	    	));
    	} elseif (strpos($this->session->userdata('back_page'), 'admin/listings/my/') !== FALSE) {
    		registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_VIEW_MY_LISTINGS_TITLE,
	    		'admin/listings/view/' . $listing->id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
	    		LANG_ADMIN_RATINGS_TITLE,
	    	));
    	}

		$view = $this->load->view();
		$view->assign('listing', $listing);
		$view->assign('listing_id', $listing_id);
		$view->assign('ratings', $ratings);
		$view->assign('avg_rating', $avg_rating);
		
		$this->session->set_userdata('ratings_back_page', uri_string());
		$view->display('ratings_reviews/admin_listing_ratings.tpl');
	}

	public function admin_reviews($listing_id)
	{
		$this->load->model('reviews', 'ratings_reviews');
		$this->load->model('listings');
		$this->listings->setListingId($listing_id);
		$level_id = $this->listings->getLevelIdByListingId();
		
		$content_access_obj = contentAcl::getInstance();
		$content_access_obj->checkListingAccess($listing_id, 'Manage all listings');

		$listing = new listing($level_id, $listing_id);
		$listing->setListingFromArray($this->listings->getListingRowById(), $this->listings->getListingCategories(), $this->listings->getListingLocations());

		if (strpos($this->session->userdata('back_page'), 'admin/listings/search/') !== FALSE) {
	    	registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_SEARCH_LISTINGS_TITLE,
	    		'admin/listings/view/' . $listing->id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
	    		LANG_ADMIN_REVIEWS_TITLE,
	    	));
    	} elseif (strpos($this->session->userdata('back_page'), 'admin/listings/my/') !== FALSE) {
    		registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_VIEW_MY_LISTINGS_TITLE,
	    		'admin/listings/view/' . $listing->id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
	    		LANG_ADMIN_REVIEWS_TITLE,
	    	));
    	}

		$view = $this->load->view();
		$view->assign('listing', $listing);
		$view->assign('listing_id', $listing_id);

		$this->session->set_userdata('reviews_back_page', uri_string());
		$view->display('ratings_reviews/admin_listing_reviews.tpl');
	}
}
?>