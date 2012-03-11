<?php
include_once(MODULES_PATH . 'acl/classes/content_acl.class.php');

class typeClass
{
    public $id;
    public $name;
    public $seo_name;
    public $meta_title;
    public $meta_description;
    public $locations_enabled;
    public $order_num;
    public $search_type;
    public $what_search;
    public $where_search;
    public $categories_search;
    public $categories_type;

    public $levels;

    public function __construct()
    {
        $this->id = 'new';
        $this->name = '';
        $this->seo_name = '';
        $this->meta_title = '';
        $this->meta_description = '';
        $this->locations_enabled = 1;
        $this->zip_enabled = 1;
        $this->order_num = 0;
        $this->search_type = 'global';
        $this->what_search = 1;
        $this->where_search = 1;
        $this->categories_search = 1;
        $this->categories_type = 'global';
        $this->levels = array();
    }

    public function setTypeFromArray($array)
    {
    	if (isset($array['id']))
        	$this->id                    = $array['id'];
        $this->name                      = $array['name'];
        $this->seo_name                  = $array['seo_name'];
        $this->meta_title                = $array['meta_title'];
        $this->meta_description          = $array['meta_description'];
        $this->locations_enabled         = $array['locations_enabled'];
        $this->zip_enabled               = $array['zip_enabled'];
        $this->search_type               = $array['search_type'];
        $this->what_search               = $array['what_search'];
        $this->where_search              = $array['where_search'];
        $this->categories_search         = $array['categories_search'];
        $this->categories_type           = $array['categories_type'];
        
        if (isset($array['order_num']))
        	$this->order_num                 = $array['order_num'];
    }

    public function buildLevels()
    {
    	if (!$this->levels) {
	    	$CI = &get_instance();
	    	$CI->load->model('levels', 'types_levels');
	    	$CI->levels->setTypeId($this->id);
	    	$this->levels = $CI->levels->getLevelsOfType();
	    	return $this->levels;
    	} else {
    		return $this->levels;
    	}
    }
    
    public function isAnyLevelAvailable()
    {
    	$content_access_obj = contentAcl::getInstance();
    	foreach ($this->levels AS $level_id=>$level) {
			if ($content_access_obj->isContentPermission('levels', $level->id))
				return true;
		}
		return false;
    }
    
    public function getBaseUrl()
    {
    	return 'types/' . $this->seo_name;
    }
    
    public function getUrl()
    {
    	$CI = &get_instance();
    	if (($current_location = $CI->session->userdata('current_location_seo_name')) && $this->locations_enabled) {
			return 'location/' . $current_location . '/' . $this->getBaseUrl();
		} else {
			return $this->getBaseUrl();
		}
    }
}
?>