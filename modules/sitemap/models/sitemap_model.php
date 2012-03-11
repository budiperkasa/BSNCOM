<?php

class sitemapModel extends Model
{
	public function getListingsByTypes($types)
	{
		$listings = array();
		foreach ($types AS $type) {
			$this->db->select('l.title AS title');
			$this->db->select('l.seo_title AS url');
			$this->db->select('l.last_modified_date AS last_modified_date');
			$this->db->from('listings AS l');
			$this->db->join('levels AS lev', 'l.level_id=lev.id', 'left');
			$this->db->join('types AS t', 't.id=lev.type_id', 'left');
			$this->db->join('users AS u', 'u.id=l.owner_id', 'left');
			$this->db->where('l.status', 1); // Active listings
			$this->db->where('u.status', 2); // Active users
			$this->db->where_not_in('l.title', 'untranslated'); // except untranslated listings
			$this->db->where('t.id', $type->id);
			$this->db->order_by('l.title');
			$query = $this->db->get();
			
			$listings[$type->id] = $query->result_array();
		}
		return $listings;
	}
	
	public function getContentPages()
	{
		$this->db->select('url');
		$this->db->select('title');
		$this->db->from('content_pages');
		$this->db->order_by('in_menu');
		$this->db->order_by('order_num');
		$query = $this->db->get();

		return $query->result_array();
	}
}
?>