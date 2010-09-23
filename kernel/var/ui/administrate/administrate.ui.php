<?php
/**
*	UI "Administrate"
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @access	public
* @package	Fluger CMS
*/
class ui_administrate extends user_interface
{
	public $title = 'Administrate';

	protected $deps = array(
		'main' => array(
			'administrate.menu',
			'administrate.home',
		),
		'home' => array(
			'order.order_list',
		)
	);
	
	public function __construct ()
	{
		parent::__construct(__CLASS__);
	}
	
	/**
	*	Main workspace
	* @access	protected
	*/
	public function sys_workspace()
	{
		$tmpl = new tmpl($this->pwd() . 'workspace.html');
		$su = data_interface::get_instance(AUTH_DI);
		response::send($tmpl->parse(array(
			'user' => $su->get_user()
		)), 'html');
	}
	
	/**
	*       Main administrate interface 
	*/
	protected function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'administrate.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Main menu
	*/
	protected function sys_menu()
	{
		$tmpl = new tmpl($this->pwd() . 'menu.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*       Main Home Tab
	*/
	protected function sys_home()
	{
		$tmpl = new tmpl($this->pwd() . 'home.js');
		response::send($tmpl->parse($this), 'js');
	}

	/**
	*	JS locale file
	*/
	protected function sys_app_lang()
	{
		$locale = $this->args['locale'];

		if (file_exists(LOCALES_PATH . "app-lang-{$locale}.js"))
			$file = LOCALES_PATH . "app-lang-{$locale}.js";
		else
			$file = LOCALES_PATH . "app-lang-default.js";

		response::send(file_get_contents($file), 'js');
	}
}
?>
