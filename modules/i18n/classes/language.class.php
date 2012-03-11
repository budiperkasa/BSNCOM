<?php

class language
{
	public $id;
	public $active;
	public $name;
	public $code;
	public $db_code;
	public $flag;
	public $custom_theme;
	public $decimals_separator;
	public $thousands_separator;
	public $date_format;
	public $time_format;
	public $order_num;

	private $file;
	
	public function __construct()
    {
        $this->id = 'new';
        $this->active = 1;
        $this->name = '';
        $this->code = '';
        $this->db_code = '';
        $this->flag = '';
        $this->custom_theme = 'default';
        $this->decimals_separator = '.';
        $this->thousands_separator = '';
        $this->date_format = '%m/%d/%y';
        $this->time_format = '%H:%M:%S';
        $this->order_num = 0;
    }
	
	public function setLanguageFromArray($array)
    {
    	if (isset($array['id']))
        	$this->id = $array['id'];
        if (isset($array['active']))
        	$this->active = $array['active'];
        $this->name      = $array['name'];
        $this->code      = $array['code'];
        $this->db_code      = $array['db_code'];
        $this->flag      = $array['flag'];
        $this->custom_theme      = $array['custom_theme'];
        $this->decimals_separator      = $array['decimals_separator'];
        $this->thousands_separator      = $array['thousands_separator'];
        $this->date_format      = $array['date_format'];
        $this->time_format      = $array['time_format'];
    }
}
?>