<?php
/**
*	ПИ "Менеджер входа в кабинет"
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @version	1.0
* @access	public
* @package	CFsCMS2(PE)
* @since	2008-12-13
*/
class ui_login extends user_interface
{
	public $title = 'Менеджер входа в кабинет';
	
	public function __construct()
	{
		parent::__construct(__CLASS__);
	}
	
	/**
	*	Форма фхода в кабинет
	* @access	protected
	*/
	public function main()
	{
		$args = request::get(array('user', 'secret'));
		$data = array();
		
		try
		{
			if (!empty($args))
				authenticate::login();
		}
		catch(Exception $e)
		{
			dbg::write($e->getMessage(), LOG_PATH . 'access.log');
			$data['errors'] = $e->getMessage();
		}
		
		if (!authenticate::is_logged())
		{
			$tmpl = new tmpl($this->pwd() . 'login.html');
			response::send($tmpl->parse($data), 'html');
		}
		else
		{
			response::redirect('/');
		}
	}

	/**
	*	Форма фхода в админку
	* @access	protected
	*/
	public function admin()
	{
		$args = request::get(array('user', 'secret'));
		$data = array(
			'LC' => LC::get()
		);
		
		try
		{
			if (!empty($args))
				authenticate::login();
		}
		catch(Exception $e)
		{
			dbg::write($e->getMessage(), LOG_PATH . 'adm_access.log');
			$data['errors'] = $e->getMessage();
		}
		
		if (!authenticate::is_logged())
		{
			$tmpl = new tmpl($this->pwd() . 'login.html');
			response::send($tmpl->parse($data), 'html');
		}
		else
		{
			response::redirect(URI_PREFIX);
		}
	}
}
?>
