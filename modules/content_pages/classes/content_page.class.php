<?php

class content_page
{
	private $id;
	private $url;
	private $title;
	private $meta_title;
	private $meta_description;
	private $in_menu;
	private $order_num;
	private $creation_date;
	
	public $content_fields;
	
	public function __construct($node_id = null)
	{
		if (empty($node_id)) {
			$this->id = 'new';
		}
		$this->content_fields = new contentFields(CONTENT_PAGES_GROUP_CUSTOM_NAME, 0, $node_id);
	}
	
	public function setPageFromArray($node_array)
    {
        $this->id = $node_array['id'];
		$this->url = $node_array['url'];
		$this->title = $node_array['title'];
		$this->meta_title = $node_array['meta_title'];
		$this->meta_description = $node_array['meta_description'];
		$this->in_menu = $node_array['in_menu'];
		if (isset($array['order_num']))
        	$this->order_num = $node_array['order_num'];
		$this->creation_date = $node_array['creation_date'];
		
		$this->content_fields->select();
    }
    
    public function inputMode()
    {
    	return $this->content_fields->inputMode();
    }
    
    public function outputMode()
    {
    	return $this->content_fields->outputMode();
    }
    
    public function validateFields($form)
    {
    	$this->content_fields->validate($form);
    }
    
    public function getPageFromForm($form)
	{
		if (isset($form['id']))
			$this->id = $form['id'];
		$this->url = $form['url'];
		$this->title = $form['title'];
		$this->meta_title = $form['meta_title'];
		$this->meta_description = $form['meta_description'];
		$this->in_menu = $form['in_menu'];
		
		$this->content_fields->getValuesFromForm($form);
	}
	
	public function saveFields($node_id, $form)
	{
		$this->content_fields->setObjectId($node_id);
		return $this->content_fields->save($form);
	}
	
	public function updateFields($form)
	{
		$this->content_fields->select();
		return $this->content_fields->update($form);
	}
	
	public function deleteFields()
	{
		$this->content_fields->select();
		return $this->content_fields->delete();
	}

    public function id()
    {
    	return $this->id;
    }
    
    public function url()
    {
    	return $this->url;
    }
    
    public function title()
    {
    	return $this->title;
    }
    
    public function meta_title()
    {
    	return $this->meta_title;
    }
    
    public function meta_description()
    {
    	return $this->meta_description;
    }
    
    public function inMenu()
    {
    	return $this->in_menu;
    }
    
    public function creationDate()
    {
    	return $this->creation_date;
    }
}
?>