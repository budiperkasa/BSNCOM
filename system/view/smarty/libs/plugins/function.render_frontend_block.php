<?php
/**
 * Web 2.0 directory script smarty function
 * 
 * processes block of listings/users/reviews/.....
 *
 * @param array $params
 * block_type - according to block type (listings/users/reviews/.....) calls appropriate classes
 *
 * @param object $smarty
 * @return html
 */
function smarty_function_render_frontend_block($params, $smarty)
{
	if (is_object($params)) {
		foreach ($params as $key => $value) {
            $array[$key] = $value;
        }
        $params = $array;
	}

	// Pass params to block object
	if (isset($params['block_type'])) {
		include_once(MODULES_PATH . 'frontend/classes/blocks/block.class.php');

		$block_type = $params['block_type'];
		// --------------------------------------------------------------------------------------------
		// Execute special block object or default
		// --------------------------------------------------------------------------------------------
		if (is_file(MODULES_PATH . 'frontend/classes/blocks/' . $block_type . '_block.class.php')) {
			include_once(MODULES_PATH . 'frontend/classes/blocks/' . $block_type . '_block.class.php');
			$class_name = $block_type . 'BlockClass';
		} else {
			$class_name = 'blockClass';
		}
		$block = new $class_name($params);
		return $block->render();
	}
}
?>