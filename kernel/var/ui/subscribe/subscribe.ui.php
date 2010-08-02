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

/*	
	protected $deps = array(
		'main' => array(
			'group.main',
			'user.list',
		)
	);
*/
	
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

	/**
	*       Управляющий JS админки
	*/
	protected function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'subscribe.js');
		response::send($tmpl->parse($this), 'js');
	}

	/**
	*	ExtJS Grid of available entry points
	*/
	protected function sys_interfaces()
	{
		$tmpl = new tmpl($this->pwd() . 'interfaces.js');
		response::send($tmpl->parse($this), 'js');
	}

}
?>
