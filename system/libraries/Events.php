<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class events 
{
	private static $events = array();
	
	public static function setEvent($event_name, $file, $module_name, $function_name)
	{
		self::$events['name'][] = $event_name;
		self::$events['file'][] = $file;
		self::$events['module'][] = $module_name;
		self::$events['function_name'][] = $function_name;
	}
	
	public static function callEvent()
	{
		// Move event's name to the end of the args array
		$args = func_get_args();
		$called_event_name = $args[0];
		if (count($args) > 1) {
			array_shift($args);
		}
		$args[] = $called_event_name;

		if (!empty(self::$events)) {
			foreach (self::$events['name'] AS $key=>$event) {
				if ($event == $called_event_name) {
					Controller::run_function(self::$events['file'][$key], self::$events['module'][$key], self::$events['function_name'][$key], $args);
				}
			}
		}
	}
}