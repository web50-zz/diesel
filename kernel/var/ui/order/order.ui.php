<?php
/**
*	Пользовательский интерфейс "Заказы"
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class ui_order extends user_interface
{
	public $title = 'Заказы';

	protected $deps = array(
		'main' => array(
			'order.order_form'
		)
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
		$tmpl = new tmpl($this->pwd() . 'order.js');
		response::send($tmpl->parse($this), 'js');
	}

	/**
	*       Форма редактирования 
	*/
	public function sys_order_form()
	{
		$tmpl = new tmpl($this->pwd() . 'order_form.js');
		response::send($tmpl->parse($this), 'js');
	}


}
?>
