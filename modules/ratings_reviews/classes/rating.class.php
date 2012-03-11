<?php
include_once(MODULES_PATH . 'ratings_reviews/classes/objects/listings_object.class.php');

class rating
{
	public $id;
	public $user_id;
	public $user;
	public $date_added;
	public $ip;
	public $value = 0;
	public $objects_table;
	public $object_id;
	public $object;

	public function __construct($array)
	{
		$this->id = $array['id'];
		$this->objects_table = $array['objects_table'];
		$this->object_id = $array['object_id'];
		$this->user_id = $array['user_id'];
		$this->value = $array['value'];
		$this->date_added = $array['date_added'];
		$this->ip = $array['ip'];
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
	
	public function view()
	{
		$CI = &get_instance();
		$view = $CI->load->view();
		$view->assign('rating', $this);
		return $view->fetch('ratings_reviews/rating.tpl');
	}
}
?>