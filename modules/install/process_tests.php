<?php

$tests_results = array();
$tests_status = processTests($tests_results);

foreach ($tests_results AS $result) {
	if ($result['status'] == 'success')
		echo "<font color='green'><b>Success!</b> " . $result['msg'] . "</font>";
	else 
		echo "<font color='red'><b>Failed!</b> " . $result['msg'] . "</font>";
	echo "<br /><br />";
}

echo "<br /><br />";

if ($tests_status == 'Fail') {
	exit("<font color='red'><b>Pre-Installation tests Failed! Solve issues and repeat installation.</b></font>");
}


function processTests(&$tests_results)
{
	include(BASEPATH . 'config/config.php');
	/*define('ROOT', 'Z:\home\ci.com\www\test_install' . DIRECTORY_SEPARATOR);
	define('BASEPATH', 'Z:\home\ci.com\www\test_install\system' . DIRECTORY_SEPARATOR);
	define('LANGPATH', ROOT . 'languages' . DIRECTORY_SEPARATOR);
	include('Z:/home/ci.com/www/test_install/system/helpers/directory_helper.php');*/
	$tests_status = 'Success';
	
	// --------------------------------------------------------------------------------------------
	// Check mod_rewrite server module for Linux servers
	// --------------------------------------------------------------------------------------------
	if (!$config['enable_query_strings'])
		if (isset($_SERVER['HTTP_MOD_REWRITE']) && $_SERVER['HTTP_MOD_REWRITE'] == 'On') {
			$tests_results[] = array('msg'=>'mod_rewrite loaded!', 'status'=>'success');
		} else {
			$tests_results[] = array('msg'=>'mod_rewrite must be loaded! or enable <i>enable_query_strings</i> in config and fill in <i>index_page</i> variable!', 'status'=>'fail');
			$tests_status = 'Fail';
		}

	// --------------------------------------------------------------------------------------------
	// Safe Mode must be disabled
	// --------------------------------------------------------------------------------------------
	if (!ini_get('safe_mode')) {
		$tests_results[] = array('msg'=>'Safe Mode disabled!', 'status'=>'success');
	} else {
		$tests_results[] = array('msg'=>'Safe Mode must be disabled!', 'status'=>'fail');
		$tests_status = 'Fail';
	}
	
	// --------------------------------------------------------------------------------------------
	// Check directories writeable
	// --------------------------------------------------------------------------------------------
	$paths_to_check = array(
		LANGPATH,
		ROOT . 'users_content'.DIRECTORY_SEPARATOR,
		BASEPATH . 'cache'.DIRECTORY_SEPARATOR,
		BASEPATH . 'view'.DIRECTORY_SEPARATOR.'smarty'.DIRECTORY_SEPARATOR.'templates_c'.DIRECTORY_SEPARATOR
	);
	$additional_subpaths = array();
	foreach ($paths_to_check AS $path) {
		if (is_array($map = directory_map($path))) {
			foreach ($map AS $key=>$subpath) {
				if (!is_numeric($key) && is_array($subpath)) {
					$additional_subpaths[] = $path . $key;
					foreach ($subpath AS $subkey=>$subsubpath) {
						if (!is_numeric($subkey) && is_array($subsubpath)) {
							$additional_subpaths[] = $path . $key . DIRECTORY_SEPARATOR . $subkey;
						}
					}
				}
			}
		}
	}
	$paths_to_check = array_merge($paths_to_check, $additional_subpaths);
	foreach ($paths_to_check AS $path) {
		$testFileName = "testFile.txt";
		if ($testFileHandle = @fopen($path . $testFileName, 'w')) {
			$tests_results[] = array('msg'=>'Directory: "' . $path . '" writeable!', 'status'=>'success');
			@fclose($testFileHandle);
			@unlink($path . $testFileName);
		} else {
			$tests_results[] = array('msg'=>'Directory: "' . $path . '" must be writeable!', 'status'=>'fail');
			$tests_status = 'Fail';
		}
	}
	
	// --------------------------------------------------------------------------------------------
	// Test sockets
	// --------------------------------------------------------------------------------------------
	$fp = fsockopen("www.paypal.com", 80, $errno, $errstr, 30);
	if (!$fp) {
		$tests_results[] = array('msg'=>'External connections not working!', 'status'=>'fail');
		$tests_status = 'Fail';
	} else {
		$tests_results[] = array('msg'=>'External connections work!', 'status'=>'success');
	}
	
	// --------------------------------------------------------------------------------------------
	// Check cURL
	// --------------------------------------------------------------------------------------------
	if (extension_loaded('curl')) {
		$tests_results[] = array('msg'=>'cURL extension loaded!', 'status'=>'success');
	} else {
		$tests_results[] = array('msg'=>'cURL extension must be loaded!', 'status'=>'fail');
		$tests_status = 'Fail';
	}
		
	// --------------------------------------------------------------------------------------------
	// Check GD library
	// --------------------------------------------------------------------------------------------
	if (extension_loaded('gd') && @is_callable("imagecreatefromgif")) {
		$tests_results[] = array('msg'=>'GD Library loaded!', 'status'=>'success');
	} else {
		$tests_results[] = array('msg'=>'GD Library must be loaded!', 'status'=>'fail');
		$tests_status = 'Fail';
	}
	
	// --------------------------------------------------------------------------------------------
	// Check MBString library
	// --------------------------------------------------------------------------------------------
	if (extension_loaded('mbstring') && @is_callable("mb_strlen")) {
		$tests_results[] = array('msg'=>'MBString Library loaded!', 'status'=>'success');
	} else {
		$tests_results[] = array('msg'=>'MBString Library must be loaded!', 'status'=>'fail');
		$tests_status = 'Fail';
	}
	
	// --------------------------------------------------------------------------------------------
	// Check DOM/XML extension
	// --------------------------------------------------------------------------------------------
	if (extension_loaded('dom')) {
		$tests_results[] = array('msg'=>'DOM/XML extension loaded!', 'status'=>'success');
	} else {
		$tests_results[] = array('msg'=>'DOM/XML extension must be loaded!', 'status'=>'fail');
		$tests_status = 'Fail';
	}
	
	// --------------------------------------------------------------------------------------------
	// Check JSON extension (yes, there are such hostings wthout json!!)
	// --------------------------------------------------------------------------------------------
	if (extension_loaded('json')) {
		$tests_results[] = array('msg'=>'JSON extension loaded!', 'status'=>'success');
	} else {
		$tests_results[] = array('msg'=>'JSON extension must be loaded!', 'status'=>'fail');
		$tests_status = 'Fail';
	}

	return $tests_status;
}
?>