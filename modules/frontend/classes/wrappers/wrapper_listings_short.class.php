<?php

class listingsShortWrapperClass extends wrapperClass
{
	public function render()
	{
		$listings_array = $this->params['items_array'];
		
		$CI = &get_instance();
		$view = $CI->load->view();
		
		$content_fields = array();
		$logo_enabled = false;
		$max_fields_count = 0;
		foreach ($listings_array AS $listing) {
			if ($listing->level->logo_enabled)
				$logo_enabled = true;
			if ($max_fields_count < count($listing->content_fields->getFieldsObjects())) {
				$max_fields_count = count($listing->content_fields->getFieldsObjects());
				foreach ($listing->content_fields->getFieldsObjects() AS $field) {
					if (!in_array($field->field->seo_name, $content_fields)) {
						$content_fields[serialize($field)] = $field->field->seo_name;
					}
				}
			}
		}

		$view->assign('content_fields', $content_fields);
		$view->assign('logo_enabled', $logo_enabled);

		if (isset($this->params['order_url'])) {
			$view->assign('order_url', $this->params['order_url']);
			$view->assign('orderby', $this->params['orderby']);
			$view->assign('direction', $this->params['direction']);
		}

		foreach ($listings_array AS $key=>$listing) {
			$fields_objects = $listing->content_fields->getFieldsObjects();
			$_index1 = 0;
			foreach ($content_fields AS $field=>$seo_name) {
				if (!array_key_exists($seo_name, $fields_objects)) {
					$field = unserialize($field);
					$field->field->value = '';
					$field->field->currency = '';

					if ($_index1 === 0) {
						$fields_objects = array_merge(array($seo_name=>$field), $fields_objects);
						$_index1 = $seo_name;
					} else {
						$fields_objects = array_push_after($fields_objects, array($seo_name=>$field), $_index1);
					}
				} else {
					$_index1 = $seo_name;
				}
			}
			$listings_array[$key]->content_fields->setFieldsObjects($fields_objects);
		}

		$view->assign('listings_array', $listings_array);
		return $view->fetch('frontend/wrappers/wrapper_listings_short.tpl');
	}
}

/**
 * Add elements to an array after a specific index or key
 *
 * @param array $src
 * @param array $in
 * @param int $pos
 * @return array
 */
function array_push_after($src, $in, $pos)
{
	if (is_int($pos)) {
		$R = array_merge(array_slice($src, 0, $pos+1), $in, array_slice($src, $pos+1));
	} else {
		foreach($src as $k=>$v) {
			$R[$k] = $v;
			if ($k == $pos)
				$R=array_merge($R, $in);
		}
	}
	return $R;
}
?>