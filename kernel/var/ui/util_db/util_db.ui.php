<?php
/**
*	UI UTIL Database 
*
* @author	 9*
* @access	public
* @package	SBIN Diesel
*/
class ui_util_db extends user_interface
{
	public $title = 'Util DB';
		public $deps = array('main' => array(
			'util_db.dump_form'
			)
		);

        public function __construct () {
		parent::__construct(__CLASS__);
        }
	
	/**
	*       Main applications JS
	*/
	protected function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'main.js');
		response::send($tmpl->parse($this), 'js');
	}
	
	protected function sys_dump_form()
	{
		$tmpl = new tmpl($this->pwd() . 'dump_form.js');
		response::send($tmpl->parse($this), 'js');
	}
}
?>
