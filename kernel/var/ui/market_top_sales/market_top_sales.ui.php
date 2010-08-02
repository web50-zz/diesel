<?php
/**
*	UI Top Sales 
*
* @author       9*	
* @access	public
* @package	SBIN Diesel 	
*/
class ui_market_top_sales extends user_interface
{
	public $title = 'TOP продаж';

	
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
		return 'Top sales here';
	}
}
?>