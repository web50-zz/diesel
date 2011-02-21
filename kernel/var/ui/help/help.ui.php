<?php
/**
*	ПИ "Управление пользователями"
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @version	1.0
* @access	public
* @package	CFsCMS2(PE)
* @since	2008-12-13
*/
class ui_help extends user_interface
{
	public $title = 'Управление пользователями';
	
	public function __construct ()
	{
		parent::__construct(__CLASS__);
	}
	
	/**
	*       Управляющий JS админки
	*/
	protected function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'help.js');
		response::send($tmpl->parse($this), 'js');
	}
}
?>
