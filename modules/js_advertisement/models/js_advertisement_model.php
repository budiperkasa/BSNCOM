<?php
class js_advertisementModel extends model
{
	private $_block_id;
	
	public function setBlockId($block_id)
	{
		$this->_block_id = $block_id;
	}
	
	public function getJsAdvertisementArray()
	{
		$query = $this->db->get('js_advertisement_blocks');
		return $query->result_array();
	}
	
	public function getNewBlock()
    {
		$block = new jsAdvertisementBlock;
        return $block;
    }
    
    public function getBlockFromForm($form)
    {
    	$block = new jsAdvertisementBlock;
		$block->setBlockFromArray($form);
		$block->id = $this->_block_id;
		return $block;
    }

	public function saveJsAdvertisementBlock($form)
	{
		$this->db->set('name', $form['name']);
		$this->db->set('mode', $form['mode']);
		$this->db->set('selector', $form['selector']);
		$this->db->set('code', $form['code']);
		return $this->db->insert('js_advertisement_blocks');
	}
	
	public function getJsAdvertisementBlockById()
	{
		$this->db->select();
		$this->db->from('js_advertisement_blocks');
		$this->db->where('id', $this->_block_id);
		$query = $this->db->get();
		$row = $query->row_array();

		$block = new jsAdvertisementBlock;
		$block->setBlockFromArray($row);
		return $block;
	}
	
	public function saveJsAdvertisementBlockById($form)
	{
		$this->db->set('name', $form['name']);
		$this->db->set('mode', $form['mode']);
		$this->db->set('selector', $form['selector']);
		$this->db->set('code', $form['code']);
		$this->db->where('id', $this->_block_id);
		return $this->db->update('js_advertisement_blocks');
	}
	
	public function deleteJsAdvertisementById()
	{
		return $this->db->delete('js_advertisement_blocks', array('id' => $this->_block_id));
	}
}
?>