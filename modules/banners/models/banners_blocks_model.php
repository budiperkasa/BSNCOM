<?php
include_once(MODULES_PATH . 'banners/classes/banners_block.class.php');
include_once(MODULES_PATH . 'banners/classes/banner.class.php');

class banners_blocksModel extends model
{
	private $_block_id;
	private $_cached_blocks = array();
	
	public function setBlockId($block_id)
	{
		$this->_block_id = $block_id;
	}
	
	public function getBannersBlocks()
	{
		$array = $this->db->get('banners_blocks')->result_array();
		foreach ($array AS $row) {
			$banners_block = new bannersBlock;
			$banners_block->setBlockFromArray($row);
			$this->_cached_blocks[$row['id']] = $banners_block;
		}
		return $this->_cached_blocks;
	}
	
	public function getNewBlock()
    {
		$block = new bannersBlock;
        return $block;
    }
    
    public function getBlockFromForm($form)
    {
    	$form['block_size'] = $form['banner_width'] . '*' . $form['banner_height'];
    	
    	$block = new bannersBlock;
		$block->setBlockFromArray($form);
		return $block;
    }

	public function saveBannersBlock($form)
	{
		$this->db->set('name', $form['name']);
		$this->db->set('mode', $form['mode']);
		$this->db->set('selector', $form['selector']);
		$this->db->set('block_size', $form['banner_width'] . '*' . $form['banner_height']);
		$this->db->set('active_years', $form['active_years']);
        $this->db->set('active_months', $form['active_months']);
        $this->db->set('active_days', $form['active_days']);
        $this->db->set('clicks_limit', $form['clicks_limit']);
        $this->db->set('limit_mode', $form['limit_mode']);
        $this->db->set('allow_remote_banners', $form['allow_remote_banners']);
		if ($this->db->insert('banners_blocks')) {
			$banners_block_id = $this->db->insert_id();
			
			$system_settings = registry::get('system_settings');
        	if (isset($system_settings['language_areas_enabled']) && $system_settings['language_areas_enabled']) {
        		translations::saveTranslations(array('banners_blocks', 'name', $banners_block_id));
        	}
        	return $banners_block_id;
		}
	}
	
	public function getBannersBlockById($block_id = null)
	{
		if (is_null($block_id))
			$block_id = $this->_block_id;
		
		if (isset($this->_cached_blocks[$block_id]))
			return $this->_cached_blocks[$block_id];
		
		$this->db->select();
		$this->db->from('banners_blocks');
		$this->db->where('id', $block_id);
		$row = $this->db->get()->row_array();

		$block = new bannersBlock;
		$block->setBlockFromArray($row);
		$this->_cached_blocks[$block_id] = $block;

		return $this->_cached_blocks[$block_id];
	}
	
	public function saveBannersBlockById($form)
	{
		$this->db->set('name', $form['name']);
		$this->db->set('mode', $form['mode']);
		$this->db->set('selector', $form['selector']);
		$this->db->set('block_size', $form['banner_width'] . '*' . $form['banner_height']);
		$this->db->set('active_years', $form['active_years']);
        $this->db->set('active_months', $form['active_months']);
        $this->db->set('active_days', $form['active_days']);
        $this->db->set('clicks_limit', $form['clicks_limit']);
        $this->db->set('limit_mode', $form['limit_mode']);
        $this->db->set('allow_remote_banners', $form['allow_remote_banners']);
		$this->db->where('id', $this->_block_id);
		return $this->db->update('banners_blocks');
	}
	
	public function deleteBannersBlockById()
	{
		return $this->db->delete('banners_blocks', array('id' => $this->_block_id));
	}
}
?>