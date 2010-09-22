<?php
/**
*	UI Корзина на  фронтенде
*
* @author       9*	
* @access	public
* @package	SBIN Diesel 	
*/
class ui_market_basket extends user_interface
{
	public $title = 'Корзина';

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
}
?>
