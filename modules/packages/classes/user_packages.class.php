<?php

/**
 * Contains all packages, those user added to his account
 *
 */
class userPackagesClass
{
	public $user;
	public $packages = array();
	
	public function __construct($user_id)
	{
		$CI = &get_instance();
		$CI->load->model('users', 'users');
		$this->user = $CI->users->getUserById($user_id);
	}

	public function setPackage($row)
	{
		$user_package = new userPackage($row);
		$user_package->buildPackageObj();
		$user_package->setUserObj($this->user);
		$user_package->countListingsLeft();
		$this->packages[] = $user_package;
	}
}

/**
 * Contains row from 'packages_users' table and its package object
 *
 */
class userPackage
{
	public $id;
	public $user_id;
	public $user;
	public $package_id;
	public $package;
	// 1 - Active
	// 2 - Blocked
	// 3 - Not Paid
	public $status;
	public $creation_date;
	
	/**
	 * place where counts listings, those we may use
	 * $listings_left[level_id] = int(listings left)
	 *
	 * @var array
	 */
	public $listings_left;
	
	public function __construct($array)
	{
		foreach ($array AS $key=>$value) {
			$this->$key = $value;
		}
	}
	
	public function buildPackageObj()
	{
		$CI = &get_instance();
		$CI->load->model('packages', 'packages');
		$this->package = $CI->packages->getPackageById($this->package_id);
	}
	
	public function setUserObj($user = null)
	{
		if (!$user) {
			$CI = &get_instance();
			$CI->load->model('users', 'users');
			$this->user = $CI->users->getUserById($this->user_id);
		} else {
			$this->user = $user;
		}
	}
	
	public function countListingsLeft()
	{
		$CI = &get_instance();
		$CI->load->model('packages', 'packages');
		
		foreach ($this->package->items AS $level_id=>$pack_listings_count) {
			$used_listings_count = $CI->packages->getUsedListingsCount($this->id, $level_id);
			if ($pack_listings_count !== 'unlimited')
				$this->listings_left[$level_id] = $pack_listings_count - $used_listings_count;
			else 
				$this->listings_left[$level_id] = $pack_listings_count;
		}
	}
}
?>