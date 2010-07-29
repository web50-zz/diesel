<?php
/**
*	ПИ "Новости"
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @version	1.0
* @access	public
* @package	CFsCMS2(PE)
* @since	2008-12-13
*/
class ui_news extends user_interface
{
	public $title = 'Новости';
	
	public function __construct()
	{
		parent::__construct((func_num_args() > 0) ? func_get_arg(0) : __CLASS__);
		$this->files_path = dirname(__FILE__).'/'; 
	}
        
	/**
        *       Отрисовка контента для внешней части
        */
        public function pub_content()
        {
		/*$tmpl = new tmpl($this->pwd() . 'content.html');*/
                $di = data_interface::get_instance('news');
		$di->set_args($this->args);
//                return $tmpl->parse($di->_get());
//		$data = array();
		$data['news'] = $di->_get();
//		dbg::show($data);
		return $this->parse_tmpl('default.html',$data);
        }
	
	/**
	*       Управляющий JS админки
	*/
	public function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'news.js');
		response::send($tmpl->parse($this), 'js');
	}


	public function pub_double()
        {
		return 'guest book here';
	}


}
?>
