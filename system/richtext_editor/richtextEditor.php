<?php
include_once(BASEPATH . 'richtext_editor/fckeditor/fckeditor.php');

class richtextEditor extends FCKeditor
{
	public function __construct($instanceName, $instanceValue = '')
	{
		parent::__construct($instanceName);
		
		$this->BasePath = base_url() . 'system/richtext_editor/fckeditor/';
        $this->Value = $instanceValue;
        // Default height and width
        $this->Height = 400;
        $this->Width = '100%';

        $system_settings = registry::get('system_settings');
        $this->Config['AutoDetectLanguage'] = false;
        $this->Config['DefaultLanguage'] = $system_settings['default_language'];
	}
}
?>