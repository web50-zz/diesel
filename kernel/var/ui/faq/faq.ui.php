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
			'faq.faq_form',
			'faq.parts_form',
			'faq.list',
			'faq.parts_list'
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

	public function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'faq.js');
		response::send($tmpl->parse($this), 'js');
	}

	/**
	*       Форма редактирования вопроса faq 
	*/
	public function sys_faq_form()
	{
		$tmpl = new tmpl($this->pwd() . 'faq_form.js');
		response::send($tmpl->parse($this), 'js');
	}

	/**
	*      Грид списка фопросов faq 
	*/
	public function sys_list()
	{
		$tmpl = new tmpl($this->pwd() . 'faq.list.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*  Грид разделов faq       
	*/
	public function sys_parts_list()
	{
		$tmpl = new tmpl($this->pwd() . 'faq.parts_list.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	/**
	*  Грид разделов faq       
	*/
	public function sys_parts_form()
	{
		$tmpl = new tmpl($this->pwd() . 'faq.parts_form.js');
		response::send($tmpl->parse($this), 'js');
	}

}
?>
