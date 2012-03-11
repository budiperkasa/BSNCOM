<?php

class goodsContentClass
{
	/**
	 * goods object ID
	 *
	 * @var int
	 */
	public $goods_id;
	
	/**
	 * goods object title
	 *
	 * @var string
	 */
	public $goods_title;


	/**
	 * return goods name
	 *
	 * @return string
	 */
	public function name()
	{
		return $this->name;
	}
	
	/**
	 * return goods category name
	 *
	 * @return string
	 */
	public function category()
	{
		return $this->category;
	}
	
	/**
	 * By default each payment item has an url to view
	 *
	 * @return bool
	 */
	public function showUrl()
	{
		return true;
	}
}
?>