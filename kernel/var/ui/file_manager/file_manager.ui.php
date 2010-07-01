<?php
/**
*	UI File-manager
*
* @author	Anthon S. Litvinenko <crazyfluger@gmail.com>
* @access	public
* @package	Fluger CMS
*/
class ui_file_manager extends user_interface
{
	public $title = 'File-manager';
	
        public function __construct () {
		parent::__construct(__CLASS__);
        }
	
	/**
	*       Main applications JS
	*/
	protected function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'file_manager.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/*
	*	Browser mode (for uploading images to CKEditor)
	*/
	protected function sys_browser()
	{
		$tmpl = new tmpl($this->pwd() . 'file_browser.html');
		response::send($tmpl->parse($this), 'html');
	}
}
?>
