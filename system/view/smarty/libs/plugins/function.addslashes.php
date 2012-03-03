<?php
/**
 * Web 2.0 Classifieds script smarty function
 *
 * @param array $params
 * string - the string to be slashed
 * 
 * @param object $smarty
 * @return string
 */
function smarty_function_addslashes($params, $smarty)
{
	return addslashes($params['string']);
}
?>