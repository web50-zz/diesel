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
			'group.grid',
			'group.item_form',
			'security.interfaces'
		),
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
		$tmpl = new tmpl($this->pwd() . 'main.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       ExtJS Grid
	*/
	protected function sys_grid()
	{
		$tmpl = new tmpl($this->pwd() . 'grid.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       ExtJs Form
	*/
	protected function sys_item_form()
	{
		$tmpl = new tmpl($this->pwd() . 'item_form.js');
		response::send($tmpl->parse($this), 'js');
	}
}
?>
