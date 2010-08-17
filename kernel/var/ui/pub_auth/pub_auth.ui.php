<?php
/**
*	UI Public auth 
*
* @author       9*	
* @access	public
* @package	SBIN Diesel 	
*/
class ui_pub_auth extends user_interface
{
	public $title = 'Публичная авторизация';

	
	public function __construct()
	{
		parent::__construct((func_num_args() > 0) ? func_get_arg(0) : __CLASS__);
		$this->files_path = dirname(__FILE__).'/'; 
	}

        public function pub_content()
        {
		$data = array();

		if (authenticate::is_logged())
		{
			$su = data_interface::get_instance(AUTH_DI);
			$data ['user'] = $su->get_user();
			return $this->parse_tmpl('logged.html',$data);
		}
		else
		{
			return $this->parse_tmpl('login.html',$data);
		}
	}
}
?>
