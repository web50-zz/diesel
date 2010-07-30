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

	protected $deps = array(
		'main' => array(
			'faq.faq_form'
		),
	);

	
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

	public function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'faq.js');
		response::send($tmpl->parse($this), 'js');
	}

	/**
	*       Форма редактирования 
	*/
	public function sys_faq_form()
	{
		$tmpl = new tmpl($this->pwd() . 'faq_form.js');
		response::send($tmpl->parse($this), 'js');
	}


}
?>
