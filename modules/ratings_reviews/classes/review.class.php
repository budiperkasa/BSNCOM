<?php
include_once(MODULES_PATH . 'ratings_reviews/classes/rating.class.php');
include_once(MODULES_PATH . 'ratings_reviews/classes/avg_rating.class.php');
include_once(MODULES_PATH . 'ratings_reviews/classes/objects/listings_object.class.php');

class review
{
	public $id;
	public $objects_table;
	public $object_id;
	public $object;
	public $parent_id;
	public $review;
	public $user_id;
	public $user;
	public $anonym_name;
	public $anonym_email;
	public $date_added;
	public $ip;
	public $rating;
	public $is_richtext;

	/**
	 * 1 - Active
	 * 2 - Spam
	 */
	public $status;

	/**
	 * 'admin' or 'user' mode
	 */
	private $mode = 'user';

	/**
	 * when review in comments tree - it may have children threads
	 */
	public $children = array();
	
	public function __construct($array)
	{
		$this->id = $array['id'];
		$this->objects_table = $array['objects_table'];
		$this->object_id = $array['object_id'];
		$this->parent_id = $array['parent_id'];
		$this->review = $array['review'];
		$this->user_id = $array['user_id'];
		$this->anonym_name = $array['anonym_name'];
		$this->anonym_email = $array['anonym_email'];
		$this->date_added = $array['date_added'];
		$this->ip = $array['ip'];
		$this->status = $array['status'];
	}
	
	public function getUser()
	{
		if ($this->user_id) {
			// not anonym
			$CI = &get_instance();
			$CI->load->model('users', 'users');
			$this->user = $CI->users->getUserById($this->user_id);
			return $this->user;
		} else 
			return false;
	}
	
	public function getObject()
	{
		$class = $this->objects_table . 'Object';
		if ($this->object = new $class($this->object_id)) {
			// Will there be richtext to place/edit review?
			$this->is_richtext = $this->object->isRichtext();
			return $this->object;
		} else 
			return false;
	}
	
	public function reviewer_name()
	{
		if ($this->user)
			return $this->user->login;
		else 
			return $this->anonym_name;
	}
	
	public function reviewer_email()
	{
		if ($this->user)
			return $this->user->email;
		else 
			return $this->anonym_email;
	}
	
	public function setMode($mode)
	{
		$this->mode = $mode;
	}
	
	/**
	 * Assign rating object of rated by review's author
	 *
	 * @param string $objects_table
	 * @param int $object_id
	 */
	public function setRating($objects_table = null, $object_id = null)
	{
		if (is_null($objects_table))
			$objects_table = $this->objects_table;
		if (is_null($object_id))
			$object_id = $this->object_id;
		
		// not anonym
		if ($this->user_id) {
			$CI = &get_instance();
			$CI->load->model('ratings', 'ratings_reviews');
			$this->rating = $CI->ratings->getRatingByUserId($objects_table, $object_id, $this->user_id);
		}
	}
	
	public function attachChild($review_obj)
	{
		$this->children[] = $review_obj;
	}

	/**
	 * When edit review body - places richtext with content or pure text if richtext option disabled
	 *
	 * @return string/html
	 */
	public function body()
	{
		if ($this->is_richtext) {
			include_once(BASEPATH . 'richtext_editor/richtextEditor.php');
			$Editor = new richtextEditor('review_body', $this->review);
			$Editor->Width = 550;
			$Editor->Height = 200;
			$Editor->ToolbarSet = 'Review';
			$Editor->Config['ImageUpload'] = false;
			$Editor->Config['ImageBrowser'] = false;
			return $Editor->CreateHtml();
		} else 
			return $this->review;
	}
	
	public function view($comment_area_enabled = true, $template = null)
	{
		$CI = &get_instance();

		$user_login = $CI->session->userdata('user_login');

		$view = $CI->load->view();
		$view->assign('review', $this);
		$view->assign('user_login', $user_login);
		$view->assign('comment_area_enabled', $comment_area_enabled);
		if (is_null($template))
			return $view->fetch('ratings_reviews/review_item-' . $this->mode . '.tpl');
		else
			return $view->fetch($template);
	}
}
?>