<?php
/**
*	UI Market latest products 
*
* @author	elgarat 	
* @access	public
* @package	SBIN Diesel 	
*/
// see also guestbook.di.php 
class ui_registration extends user_interface
{
	public $title = 'Форма регистрации';

	
	public function __construct()
	{
		parent::__construct((func_num_args() > 0) ? func_get_arg(0) : __CLASS__);
		$this->files_path = dirname(__FILE__).'/'; 
	}

        public function pub_registration_form()
        {
		$data = array();
		return $this->parse_tmpl('default.html',$data);
	}

}
?>
