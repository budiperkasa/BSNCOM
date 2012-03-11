<?php

abstract class wrapperClass
{
	protected $params = array();
	
	public function __construct($params)
	{
		$this->params = $params;
	}
	
	/*public function render()
	{
		$CI = &get_instance();
		$view = $CI->load->view();
		$view->assign($this->params);
		if (isset($this->params['view_name']) && $view->template_exists('frontend/wrappers/wrapper_' . $this->params['view_name'] . '.tpl'))
			$template = 'frontend/wrappers/wrapper_' . $this->params['view_name'] . '.tpl';
		else 
			$template = 'frontend/wrappers/wrapper_default.tpl';
		return $view->fetch($template);
	}*/
}
?>