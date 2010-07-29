<?php
/**
*	UI Subscribe 
*
* @author       9*	
* @access	public
* @package	SBIN Diesel 	
*/
class ui_subscribe extends user_interface
{
	public $title = 'Рассылка';

	
	public function __construct()
	{
		parent::__construct((func_num_args() > 0) ? func_get_arg(0) : __CLASS__);
		$this->files_path = dirname(__FILE__).'/'; 
	}

        public function pub_content()
        {
		$data = array();
		return $this->parse_tmpl('default.html',$data);
	}

	public function pub_double()
        {
		return 'Subscribe here';
	}
}
?>
