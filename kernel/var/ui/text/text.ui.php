<?php
/**
*	ПИ "Текст"
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @version	1.0
* @access	public
* @package	CFsCMS2(PE)
* @since	2008-12-13
*/
class ui_text extends user_interface
{
	public $title = 'Текст';
	
	public function __construct ()
	{
		parent::__construct(__CLASS__);
	}
        
        /**
        *       Отрисовка контента для внешней части
        */
        public function pub_content()
        {
		$tmpl = new tmpl($this->pwd() . 'content.html');
                $di = data_interface::get_instance('text');
		$di->set_args($this->args);
                return $tmpl->parse($di->get());
        }
	
	/**
	*       Управляющий JS админки
	*/
	public function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'text.js');
		response::send($tmpl->parse($this), 'js');
	}
}
?>
