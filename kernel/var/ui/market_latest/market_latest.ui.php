<?php
/**
*	UI Market latest products 
*
* @author       9*	
* @access	public
* @package	SBIN Diesel 	
*/
// see also guestbook.di.php 
class ui_market_latest extends user_interface
{
	public $title = 'Новинки магазина';

	
	public function __construct()
	{
		parent::__construct((func_num_args() > 0) ? func_get_arg(0) : __CLASS__);
		$this->files_path = dirname(__FILE__).'/'; 
	}

        public function pub_long()
        {
		$data = array();
		return $this->parse_tmpl('default.html',$data);
	}

	public function pub_short()
        {
		$data = array();
		return $this->parse_tmpl('short.html',$data);
	}
}
?>
