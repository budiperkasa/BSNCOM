<?php
class jsAdvertisementBlock
{
    public $id;
    public $name;
    public $mode;
    public $selector;
    public $code;

    public function __construct()
    {
        $this->id = 'new';
        $this->name = '';
        $this->mode = '';
        $this->selector = '';
        $this->code = '';
    }

    public function setBlockFromArray($array)
    {
    	if (isset($array['id']))
        	$this->id                = $array['id'];
        $this->name                  = $array['name'];
        $this->mode                  = $array['mode'];
        $this->selector              = $array['selector'];
        $this->code                  = $array['code'];
    }
}
?>