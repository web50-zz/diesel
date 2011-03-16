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
class ui_user extends user_interface
{
	public $title = 'Управление пользователями';

	protected $deps = array(
		'main' => array(
			'user.grid',
			'user.item_form',
		),
		'user_list' => array(
			'user.grid',
		),
		'grid' => array(
			'user.languages',
		),
		'item_form' => array(
			'user.languages',
		),
	);
	
	public function __construct ()
	{
		parent::__construct(__CLASS__);
	}
	
	/**
	*       Управляющий JS админки
	*/
	protected function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'main.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       ExtJS Grid - список пользователей
	*/
	protected function sys_grid()
	{
		$tmpl = new tmpl($this->pwd() . 'grid.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Форма редактирования
	*/
	protected function sys_item_form()
	{
		$tmpl = new tmpl($this->pwd() . 'item_form.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Список доступных языков
	*/
	protected function sys_languages()
	{
		$tmpl = new tmpl($this->pwd() . 'languages.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Простой список пользователей
	*/
	protected function sys_list()
	{
		$tmpl = new tmpl($this->pwd() . 'list.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Простой список пользователей, с возможностью выбора
	*/
	protected function sys_user_list()
	{
		$tmpl = new tmpl($this->pwd() . 'user_list.js');
		response::send($tmpl->parse($this), 'js');
	}
}
?>
