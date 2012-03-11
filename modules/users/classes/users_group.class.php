<?php
class users_group
{
    public $id;
    public $name;
    public $default_group;
    public $may_register;
    public $is_own_page;
    public $meta_enabled;
    public $use_seo_name;
    public $logo_enabled;
    public $logo_size;
    public $logo_thumbnail_size;

    public function __construct()
    {
        $this->id = 'new';
        $this->name = '';
        $this->default_group = '';
        $this->may_register = true;
        $this->is_own_page = true;
        $this->meta_enabled = true;
        $this->use_seo_name = true;
        $this->logo_enabled = true;
        $this->logo_size = 147 . '*' . 120;
        $this->logo_thumbnail_size = 64 . '*' . 64;
    }

    public function setUsersGroupFromArray($array)
    {
    	if (isset($array['id']))
        	$this->id = $array['id'];
        $this->name = $array['name'];
        $this->default_group = $array['default_group'];
        $this->may_register = $array['may_register'];
        $this->is_own_page = $array['is_own_page'];
        $this->meta_enabled = $array['meta_enabled'];
        $this->use_seo_name = $array['use_seo_name'];
        $this->logo_enabled = $array['logo_enabled'];
        $this->logo_size = $array['logo_size'];
        $this->logo_thumbnail_size = $array['logo_thumbnail_size'];
    }
    
    public function explodeSize($value, $part)
    {
    	if ($part === 'width')
    		$part = 0;
    	if ($part === 'height')
    		$part = 1;

        $a = explode('*', $this->$value);
        return $a[$part];
    }
}
?>