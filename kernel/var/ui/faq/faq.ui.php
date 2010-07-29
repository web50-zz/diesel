<?php
/**
*	UI FAQ 
*
* @author       9*	
* @access	public
* @package	SBIN Diesel 	
*/
// see also faq.di.php 
class ui_faq extends user_interface
{
	public $title = 'FAQ';

	
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
		return 'faq here';
	}
}
?>
