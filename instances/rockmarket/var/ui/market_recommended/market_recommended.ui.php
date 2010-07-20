<?php
/**
*	UI Merket Recommended blocks 
*
* @author	9* <9@u9.ru>
* @access	public
* @package	SBIN Diesel
*/
class ui_market_recommended extends user_interface 
{

	public $title = 'Market recommended blocks';

	public function __construct()
	{
		parent::__construct((func_num_args() > 0) ? func_get_arg(0) : __CLASS__);
		$this->files_path = dirname(__FILE__).'/'; 
		$this->templates['main'] = 'market_recommended_main.html';
	}
	
	public function  pub_market_recommended_main()
	{
		$data['here'] = 'test';
		$html = $this->parse_tmpl($this->templates['main'],$data);
		return $html;
	}

}
?>
