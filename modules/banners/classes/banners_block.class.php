<?php
class bannersBlock
{
    public $id;
    public $name;
    public $mode;
    public $selector;
    public $block_size;
    public $active_years;
    public $active_months;
    public $active_days;
    public $clicks_limit;
    public $limit_mode;
    public $allow_remote_banners;

    public function __construct()
    {
        $this->id = 'new';
        $this->name = '';
        $this->mode = '';
        $this->selector = '';
        $this->block_size = '180*300';
        $this->active_years = 0;
        $this->active_months = 0;
        $this->active_days = 0;
        $this->clicks_limit = 0;
        $this->limit_mode = '';
        $this->allow_remote_banners = 0;
    }

    public function setBlockFromArray($array)
    {
    	if (isset($array['id']))
        	$this->id                = $array['id'];
        $this->name                  = $array['name'];
        $this->mode                  = $array['mode'];
        $this->selector              = $array['selector'];
        $this->block_size            = $array['block_size'];
        $this->active_years          = $array['active_years'];
        $this->active_months         = $array['active_months'];
        $this->active_days           = $array['active_days'];
        $this->clicks_limit          = $array['clicks_limit'];
        $this->limit_mode            = $array['limit_mode'];
        if (isset($array['allow_remote_banners']))
        	$this->allow_remote_banners  = $array['allow_remote_banners'];
    }
    
    public function explodeSize($value, $part)
    {
        $a = explode('*', $this->$value);
        return $a[$part];
    }
}
?>