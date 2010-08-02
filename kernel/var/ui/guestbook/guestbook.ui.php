<?php
/**
*	UI Guestbook
*
* @author       9*	
* @access	public
* @package	SBIN Diesel 	
*/
// see also guestbook.di.php 
class ui_guestbook extends user_interface
{
	public $title = 'Гостевая';

	protected $deps = array(
		'main' => array(
			'guestbook.guestbook_form'
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
		return 'guest book here';
	}

	public function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'guestbook.js');
		response::send($tmpl->parse($this), 'js');
	}

	/**
	*       Форма редактирования 
	*/
	public function sys_guestbook_form()
	{
		$tmpl = new tmpl($this->pwd() . 'guestbook_form.js');
		response::send($tmpl->parse($this), 'js');
	}
}
?>
