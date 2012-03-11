<?php

class registry
{
    private static $vars = array();

    public static function set($key, $var)
    {
        self::$vars[$key] = $var;
        return $var;
    }
    
     public static function add($key, $var)
    {
    	if ($vars = self::get($key)) {
    		self::$vars[$key] = array_merge(self::$vars[$key], $var);
    	} else {
    		$vars = array($key=>$var);
    	}
    }

    public static function get($key)
    {
        if (array_key_exists($key, self::$vars))
            return self::$vars[$key];
        else
            return false;
    }

    public static function remove($key)
    {
        unset(self::$vars[$key]);
    }
}

?>