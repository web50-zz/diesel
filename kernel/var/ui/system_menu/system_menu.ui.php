<?php
/**
*	ПИ "Системное меню"
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class ui_system_menu extends user_interface
{
	public $title = 'Системное меню';

	protected $deps = array(
		'main' => array(
			'system_menu.tree',
			'system_menu.item_form',
		)
	);
	
	public function __construct()
	{
		parent::__construct(__CLASS__);
		$this->files_path = dirname(__FILE__).'/'; 
	}
	
	/**
	*       Main applications JS
	*/
	protected function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'main.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/*
	*	ExtJS Tree
	*/
	protected function sys_tree()
	{
		$tmpl = new tmpl($this->pwd() . 'tree.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/*
	*	ExtJS Item form
	*/
	protected function sys_item_form()
	{
		$tmpl = new tmpl($this->pwd() . 'item_form.js');
		response::send($tmpl->parse($this), 'js');
	}
}
?>
