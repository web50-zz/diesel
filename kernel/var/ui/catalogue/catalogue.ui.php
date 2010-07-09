<?php
/**
*	ПИ "Каталог"
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @access	public
*/
class ui_catalogue extends user_interface
{
	public $title = 'Каталог';

	protected $deps = array(
		'main' => array(
			'catalogue.item_form',
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
		$tmpl = new tmpl($this->pwd() . 'catalogue.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Edit form
	*/
	protected function sys_item_form()
	{
		$tmpl = new tmpl($this->pwd() . 'item_form.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Edit form
	*/
	protected function sys_configure_form()
	{
		$tmpl = new tmpl($this->pwd() . 'configure_form.js');
		response::send($tmpl->parse($this), 'js');
	}
}
?>
