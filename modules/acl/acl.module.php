<?php
class aclModule
{
	public $title = "Access Control List";
	public $version = "0.2";
	public $description = "ACL module first checks the list for an applicable entry in order to decide whether to proceed with the operation. If operation is not allowed '401 error' page raises on.";
	public $type = "core";

	public function hooks()
	{
		$hook['check_rights'] = array(
			'weight' => 1,
			'inclusions' => array(
				'admin/:any',
			),
		);
		
		return $hook;
	}
}
?>