<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 * @author	   Ladygin (webcoderu@gmail.com) 
 * @copyright  2008 Ladygin (http://coolweb.su)
 * @license    New BSD License
 * @version    0.2
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES 
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT 
 * SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, 
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED 
 * TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR 
 * BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN 
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN 
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH 
 * DAMAGE.
*/


/**
 * Cache library
 *
 * Cache based on Zend_Cache
 * http://framework.zend.com/manual/ru/zend.cache.html
 *
 * @version    0.2
 */
class Cache {
    
    /**
     * Объект Zend Cache
     * 
     * @var object
     */
    private $Zend;
    
    /**
     * Fronted
     * 
     * @var string
     */
    private $frontend = 'Core';
    /**
     * Backend
     * 
     * @var string
     */
    private $backend  = 'File';
    /**
     * Frontend настройки
     * 
     * @var array
     */
    private $frontendOption = array(
    	'lifetime'                  => CACHE_LIFETIME,
	   	'automatic_cleaning_factor' => 0,
	    'automatic_serialization'	=> true
    );
    /**
     * Backend настройки
     * 
     * @var array
     */
    private $backendOption  = array(
    	'cache_dir'              => './system/cache/',
	    'read_control_type'      => 'strlen',
	    'file_locking'           => false,
	    'hashed_directory_level' => 0,
	    'file_name_prefix'		 => 'cache'
    );
    /**
     * Путь до библиотек Zend Framework
     * 
     * @var string
     */
    private $path_to_zend = 'system/';
    
    /**
     * Здесь сохраняются имена опций которые дальше нельзя изменить.
     * 
     * @var array
     */
    private $block = array();
    
    
    function __construct($params = array())
    {
    	$CI = &get_instance();
    	if (!$CI->config->item('enable_cache')) {
    		$this->frontendOption = array_merge($this->frontendOption, array('caching' => false));
    	}
    	
		if ($this->path_to_zend == NULL)
		{
			$this->path_to_zend = dirname(dirname(FCPATH));
		}
		
		ini_set('include_path',
		ini_get('include_path') . PATH_SEPARATOR . $this->path_to_zend );
		require_once (string) 'Zend/Cache' . EXT;
		
		$this->Zend = Zend_Cache::factory($this->frontend, $this->backend, $this->frontendOption, $this->backendOption);
		
		log_message('debug', "Cache Class Initialized");
    }
    
    /**
     * Инициализация настроек кэширования
     * 
     * @param array $params settings
     */
	function initialize($params = array())
	{
	    
		if (count($params) > 0)	{
		    
			foreach ($params as $key => $val) {
			    
				if (isset($this->$key))	{
				    
					$this->$key = $val;
				}
			}		
		}
	}    
    
	/**
	 * Изменение настроек после инициализации объекта Zend Cache
	 * 
	 * @param string $name имя опции
	 * @param string $value новое значение
	 * @return boolean 
	 */
	public function set_settings($name, $value)
	{
	    
	    if ( in_array($name, $this->block) ) {
	        
	        return false;
	    }
	    
	    $this->Zend->setOption($name, $value);
	    return TRUE;
	}
    
	
	/**
	 * Блокирование изменения опции
	 * После блокировки необходимой опции, её никак нельзя будет изменить в дальнейшем.
	 * 
	 * @param string $name имя опции
	 * @return boolean
	 */
	public function set_block($name = null)
	{
	    
	    if ( $name == null) {
	        return false;
	    }
	    
	    if ( !in_array($name, $this->block)) {
	        
	        $this->block[] = $name;
	        return true;
	    }
	    return false;
	}
	
	/**
	 * Загрузка данных из кэша
	 * 
	 * @param string $id уникальный индетификатор кэша
	 * @param boolean $doNotTestCacheValidity если установлено в true, то данные из кэша не будут протэстированы
	 * @param boolean $doNotUnserialize не распаковывать (даже если automatic_serialization true) => для внутреннего пользования
	 * @return mixed cached datas
	 */
    public function load($id, $doNotTestCacheValidity = false, $doNotUnserialize = false)
    {
        // Automatically attach current language code to cache ID
    	$id .= '_' . registry::get('current_language_db_code');
    	// Automatically attach current design theme to cache ID
		if ($current_language_db_obj = registry::get('current_language_db_obj')) {
        	$theme = $current_language_db_obj->custom_theme;
		} else {
			$system_settings = registry::get('system_settings');
			$theme = $system_settings['design_theme'];
		}
    	$id .= '_' . $theme;

        return $this->Zend->load($id, $doNotTestCacheValidity, $doNotUnserialize);
    }
    
    /**
     * Сохранение данных в кэш
     * 
     * @param mixed $data данные которые нужно сохранить (можно массив, если automatic_serialization включен)
     * @param cache $id уникальный индентификатор кэша (если не установлено, будет использоваться последний идентификатор кэша)
     * @param array $tags тэги для кэша
     * @param int $specificLifetime возможно установить уникальное время жизни кэша для текущих данных
     * @param $priority приоритет кэширования для текущих данных
     * @return boolean
     */
    public function save($data, $id = null, $tags = array(), $time = false, $priority = 8)
    {
    	// Automatically attach current language code to cache ID
    	$id .= '_' . registry::get('current_language_db_code');
    	// Automatically attach current design theme to cache ID
		if ($current_language_db_obj = registry::get('current_language_db_obj')) {
        	$theme = $current_language_db_obj->custom_theme;
		} else {
			$system_settings = registry::get('system_settings');
			$theme = $system_settings['design_theme'];
		}
    	$id .= '_' . $theme;

        return $this->Zend->save($data, $id, $tags, $time, $priority);
    }
    
    /**
     * Удаление данных из кэша
     * 
     * @param string $id индентификатор кэша для удаления
     * @return boolean
     */
    public function remove($id)
    {
        
        $this->Zend->remove($id);
    }
    
    /**
     * Чистка кэша
     * 
     * Из документации:
     * Available modes are :
     * 'all' (default)  => remove all cache entries ($tags is not used)
     * 'old'            => remove too old cache entries ($tags is not used)
     * 'matchingTag'    => remove cache entries matching all given tags
     *                     ($tags can be an array of strings or a single string)
     * 'notMatchingTag' => remove cache entries not matching one of the given tags
     *                     ($tags can be an array of strings or a single string)
     *
     * @param mixed $tags
     * @param string $mode
     * @return boolean
     */
    public function clean($mode = 'matchingTag', $tags = array())
    {
        
        return $this->Zend->clean($mode, $tags);
    }
    
    /**
     * Получение массива индентификаторов сохранённого кэша
     * 
     * @return array
     */
    public function get_ids()
    {
    	
    	return $this->Zend->getIds();
    }
    
    /**
     * Получение массива тэгов сохранённого кэша
     * 
     * @return array
     */
    public function get_tags()
    {
    	
    	return $this->Zend->getTags();
    }
    
    /**
     * Изменение жизни кэша
     *
     * @param string $id индентификатор кэша
     * @param int $extraLifetime 
     * @return boolean
     */
    public function touch($id, $extraLifetime)
    {
    	
    	return $this->Zend->touch($id, $extraLifetime);
    }
}
?>