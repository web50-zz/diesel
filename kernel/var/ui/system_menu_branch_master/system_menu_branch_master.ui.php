<?php
/**
*
* @author	9* <9@u9.ru> 21062013
* @package	SBIN Diesel
*/
class ui_system_menu_branch_master extends user_interface
{
	public $title = 'Системное меню бранч мастер';

	protected $deps = array(
		'main' => array(
			'system_menu_branch_master.grid',
			'system_menu_branch_master.item_form',
			'system_menu_branch_master.item_import_form',
		),
		'selector' => array(
			'system_menu_branch_master.grid',
		),
	);
	
	public function __construct()
	{
		parent::__construct(__CLASS__);
	}
	
	/**
	*       Основной JS
	*/
	protected function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'main.js');
		response::send($tmpl->parse($this), 'js');
	}

	/**
	*      ExtJS - Grid
	*/
	protected function sys_grid()
	{
		$tmpl = new tmpl($this->pwd() . 'grid.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       ExtJS - Форма редактирования
	*/
	protected function sys_item_form()
	{
		$tmpl = new tmpl($this->pwd() . 'item_form.js');
		response::send($tmpl->parse($this), 'js');
	}
	/**
	*       ExtJS - Форма редактирования
	*/
	protected function sys_item_import_form()
	{
		$tmpl = new tmpl($this->pwd() . 'item_import_form.js');
		response::send($tmpl->parse($this), 'js');
	}
	protected function sys_selector()
	{
		$tmpl = new tmpl($this->pwd() . 'selector.js');
		response::send($tmpl->parse($this), 'js');
	}


}
?>
