<?php
/**
*	ПИ "Пользователи системы"
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @access	public
*/
class ui_group extends user_interface
{
	public $title = 'Группы пользователей';

	protected $deps = array(
		'main' => array(
			'group.editForm',
			'security.interfaces'
		)
	);
	
	public function __construct ()
	{
		parent::__construct(__CLASS__);
	}
	
	/**
	*       Main interface
	*/
	protected function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'group.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Edit form
	*/
	protected function sys_editForm()
	{
		$tmpl = new tmpl($this->pwd() . 'editForm.js');
		response::send($tmpl->parse($this), 'js');
	}
}
?>
