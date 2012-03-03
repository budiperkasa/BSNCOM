<?php
class ratings_reviewsModule
{
	public $title = "Ratings and Reviews";
	public $version = "0.1";
	public $description = "Ratings and Reviews module.";
	public $type = "core";
	public $permissions = array(
		'Manage all ratings',
		'Manage self ratings',
		'Manage all reviews',
		'Manage self reviews');

	public $lang_files = "ratings_reviews.php";
	
	public function routes()
	{
		$route['rate/:any/:num'] = array(
			'action' => 'rate',
		);
		
		$route['reviews/add/:any/:num'] = array(
			'action' => 'add_review',
		);
		
		$route['reviews/refresh/'] = array(
			'controller' => 'ratings_reviews',
			'action' => 'refresh_reviews',
		);

		// --------------------------------------------------------------------------------------------
		// Manage ratings & reviews routes
		// --------------------------------------------------------------------------------------------
		$route['admin/ratings/delete/'] = array(
			'title' => LANG_DELETE_RATINGS,
			'action' => 'massRatingsDelete',
			'access' => array('Manage all ratings', 'Manage self ratings'),
		);
		
		$route['admin/reviews/delete/'] = array(
			'title' => LANG_DELETE_REVIEWS,
			'action' => 'massReviewsDelete',
			'access' => array('Manage all reviews', 'Manage self reviews'),
		);
		
		$route['admin/reviews/spam/'] = array(
			'action' => 'massReviewsSpam',
			'access' => array('Manage all reviews', 'Manage self reviews'),
		);
		
		$route['admin/reviews/active/'] = array(
			'action' => 'massReviewsActive',
			'access' => array('Manage all reviews', 'Manage self reviews'),
		);
		
		$route['admin/reviews/edit/:num'] = array(
			'title' => LANG_EDIT_REVIEWS_TITLE,
			'action' => 'edit_reviews',
			'access' => array('Manage all reviews', 'Manage self reviews'),
		);
		
		$route['admin/reviews/:any/search/(.*)'] = array(
			'title' => LANG_SEARCH_REVIEWS_TITLE,
			'action' => 'search_reviews',
			'access' => array('Manage all reviews'),
		);

		return $route;
	}
}
?>