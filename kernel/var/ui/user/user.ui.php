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
			'user.editForm'
		)
	);
	
	public function __construct ()
	{
		parent::__construct(__CLASS__);
	}
	
	/**
	*       Управляющий JS админки
	*/
	public function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'user.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Форма редактирования
	*/
	public function sys_editForm()
	{
		$tmpl = new tmpl($this->pwd() . 'editForm.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Простой список пользователей
	*/
	public function sys_list()
	{
		$tmpl = new tmpl($this->pwd() . 'list.js');
		response::send($tmpl->parse($this), 'js');
	}
}
?>
