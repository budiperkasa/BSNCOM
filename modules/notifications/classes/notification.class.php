<?php
class notificationClass
{
	private $id;
	private $subject;
	private $body;
	
	public function setNotificationFromArray($array)
	{
		$this->id          = $array['id'];
		$this->subject     = $array['subject'];
		$this->body        = $array['body'];
	}
	
	public function getId()
	{
		return $this->id;
	}

	public function getSubject()
	{
		return $this->subject;
	}
	
	public function getBody()
	{
		return $this->body;
	}
}
?>