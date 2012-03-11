<?php

class blockClass
{
	protected $params = array();
	protected $wrapper_object = null;

	/**
	 * wrapper object builds like   wrapper_`block_name`_`view_name`.class.php
	 *
	 * @param array $params
	 */
	public function __construct($params)
	{
		$this->params = $params;
		$this->params['all_params'] = $params;
		
		if (!isset($this->params['items_array'])) {
			$this->params['items_array'] = $this->getItems();
		}
		
		// --------------------------------------------------------------------------------------------
		// Execute special wrapper class or default
		// --------------------------------------------------------------------------------------------
		if (isset($this->params['view_name']))
			if (is_file(MODULES_PATH . 'frontend/classes/wrappers/wrapper_' . $this->params['block_type'] . '_' . $this->params['view_name'] . '.class.php')) {
				include_once(MODULES_PATH . 'frontend/classes/wrappers/wrapper.class.php');
				include_once(MODULES_PATH . 'frontend/classes/wrappers/wrapper_' . $this->params['block_type'] . '_' . $this->params['view_name'] . '.class.php');
				$class = $this->params['block_type'] . ucfirst($this->params['view_name']) . 'WrapperClass';
				$this->wrapper_object = new $class($this->params);
			}
	}
	
	/**
	 * when items array doesn't passed into the block - it must be extracted here
	 * will be rewriten in children
	 *
	 */
	protected function getItems()
	{
		if (isset($this->params['items_array']))
			return $this->params['items_array'];
	}
	
	/**
	 * block contains listings wrapper, listings wrapper contains listings
	 * block -> listings wrapper -> listings
	 *
	 * @return html
	 */
	public function render()
	{
		$CI = &get_instance();
		$block = $CI->load->view();

		// --------------------------------------------------------------------------------------------
		// Render special template or default
		// --------------------------------------------------------------------------------------------
		if (isset($this->params['block_template']) && $block->template_exists($this->params['block_template'])) {
        	$block_template = $this->params['block_template'];
        } else {
        	$block_template = 'frontend/blocks/default.tpl';
        }
        // --------------------------------------------------------------------------------------------

		if ($this->wrapper_object)
			$block->assign('wrapper_object', $this->wrapper_object);
		$block->assign($this->params);
		return $block->fetch($block_template);
	}
	
	public function compileCacheId()
	{
		$cache_id = array();
		foreach ($this->params AS $key=>$param) {
			if (is_object($param) && isset($param->id))
				$cache_id[] = get_class($param) . '_' . $param->id;
			elseif (is_array($param))
				foreach ($param AS $subparam) {
					if (is_object($subparam) && isset($subparam->id))
						$cache_id[] = get_class($subparam) . '_' .$subparam->id;
					elseif (is_numeric($subparam))
						$cache_id[] = $key . '_' .$subparam;
				}
			elseif (!is_object($param) && !is_array($param))
				$cache_id[] = $param;
		}
		return $cache_id;
	}
}
?>