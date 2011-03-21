<?php
/**
*	ПИ "Реестр настроек"
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @version	1.0
* @access	public
* @package	CFsCMS2(PE)
* @since	2008-12-13
*/
class ui_registry extends user_interface
{
	public $title = 'Реестр настроек';

	protected $deps = array(
		'main' => array(
			'registry.grid',
			'registry.item_form',
		),
		'grid' => array(
			'registry.type',
		),
		'item_form' => array(
			'registry.type',
		),
	);
	
	public function __construct ()
	{
		parent::__construct(__CLASS__);
	}
	
	/**
	*       Основная точка входа
	*/
	protected function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'main.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       ExtJS Grid - список
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
	*       Список типов
	*/
	protected function sys_type()
	{
		$tmpl = new tmpl($this->pwd() . 'type.js');
		response::send($tmpl->parse($this), 'js');
	}
}
?>
