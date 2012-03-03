<?php
class content_pagesModel extends model
{
	private $_node_id;
	
	public function setNodeId($node_id)
	{
		$this->_node_id = $node_id;
	}
	
	public function getNodeIdByUrl($url)
	{
		$this->db->select('id');
		$this->db->from('content_pages');
		$this->db->where('url', $url);
		$query = $this->db->get();
		if ($query->num_rows()) {
			$row = $query->row_array();
			return $row['id'];
		} else 
			return false;
	}
	
	public function getNodeById()
	{
		$this->db->select();
		$this->db->from('content_pages');
		$this->db->where('id', $this->_node_id);
		$query = $this->db->get();
		
		return $query->row_array();
	}
	
	/**
     * is there content page with such url in the DB?
     *
     * @param string $node_url
     */
    public function is_unique_node($node_url)
    {
    	$this->db->select();
		$this->db->from('content_pages');
		$this->db->where('url', $node_url);
		if (!is_null($this->_node_id)) {
			$this->db->where('id !=', $this->_node_id);
		}
		$query = $this->db->get();

		return $query->num_rows();
    }
	
	public function selectNodes()
    {
	    $this->db->select();
	    $this->db->from('content_pages');
	    $this->db->order_by('order_num');
    	$query = $this->db->get();

    	return $query->result_array();
    }
    
    /**
     * saves the order of pages by its weight
     *
     * @param string $serialized_order
     */
    public function setPagesOrder($serialized_order)
    {
    	$a = explode("=", $serialized_order);
    	$start = 1;
    	foreach ($a AS $row) {
    		$b = explode("&", $row);
    		foreach ($b AS $id) {
    			$id = trim($id, "_id");
    			if (is_numeric($id)) {
    				$this->db->set('order_num', $start++);
    				$this->db->where('id', $id);
    				$this->db->update('content_pages');
    			}
    		}
    	}
    }
    
    public function savePage($form)
    {
    	$this->db->select_max('order_num');
    	$query = $this->db->get('content_pages');
    	if ($row = $query->row())
    		$order_num = $row->order_num + 1;
    	else 
    		$order_num = 1;

		$this->db->set('url', $form['url']);
		$this->db->set('title', $form['title']);
		$this->db->set('meta_title', $form['meta_title']);
		$this->db->set('meta_description', $form['meta_description']);
		$this->db->set('in_menu', $form['in_menu']);
		$this->db->set('creation_date', date("Y-m-d H:i:s"));
		$this->db->set('order_num', $order_num);
        
		if ($this->db->insert('content_pages')) {
    		$content_page_id = $this->db->insert_id();
    		
    		$system_settings = registry::get('system_settings');
        	if (isset($system_settings['language_areas_enabled']) && $system_settings['language_areas_enabled']) {
        		translations::saveTranslations(array('content_pages', 'title', $content_page_id));
        	}
        	return $content_page_id;
		}
		return false;
    }
    
    public function savePageById($form)
    {
		$this->db->set('url', $form['url']);
		$this->db->set('title', $form['title']);
		$this->db->set('meta_title', $form['meta_title']);
		$this->db->set('meta_description', $form['meta_description']);
		$this->db->set('in_menu', $form['in_menu']);
		$this->db->where('id', $this->_node_id);
		return $this->db->update('content_pages');
    }
    
    public function deletePageById()
    {
		return $this->db->delete('content_pages', array('id' => $this->_node_id));
    }
    
    /**
	 * select content pages with in_menu flag
	 *
	 * @return array
	 */
	public function getContentPagesForFront()
	{
		$this->db->select('url');
		$this->db->select('title');
		$this->db->from('content_pages');
		$this->db->where('in_menu', 1);
		//$this->db->where_not_in('title', 'untranslated'); // except untranslated pages
		$this->db->order_by('order_num');
		$query = $this->db->get();
		
		return $query->result_array();
	}
}
?>