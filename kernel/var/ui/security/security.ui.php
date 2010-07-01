<?php
/**
*	ПИ "Управление безопасностью"
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @version	1.0
* @access	public
* @package	CFsCMS2(PE)
* @since	2010-06-21
*/
class ui_security extends user_interface
{
	public $title = 'Управление безопастность';

	protected $deps = array(
		'main' => array(
			'group.main',
			'user.list',
		)
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
		$tmpl = new tmpl($this->pwd() . 'security.js');
		response::send($tmpl->parse($this), 'js');
	}

	/**
	*	ExtJS Grid of available entry points
	*/
	protected function sys_interfaces()
	{
		$tmpl = new tmpl($this->pwd() . 'interfaces.js');
		response::send($tmpl->parse($this), 'js');
	}
}
?>
