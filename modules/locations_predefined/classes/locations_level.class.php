<?php
class locations_level
{
    public $id;
    public $name;
    public $order_num;

    public function __construct()
    {
        $this->id = 'new';
        $this->name = '';
        $this->order_num = 0;
    }

    public function setLocationsLevelFromArray($array)
    {
    	if (isset($array['id']))
        	$this->id                    = $array['id'];
        $this->name                      = $array['name'];
        if (isset($array['order_num']))
        	$this->order_num             = $array['order_num'];
    }
}
?>