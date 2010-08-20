<?php
/**
*	Клиенты магазина
*
* @author	9*  cloned out on ui_order
* @package	SBIN Diesel
*/
class ui_market_clients extends user_interface
{
	public $title = 'Клиенты';

	protected $deps = array(
		'main' => array(
			'market_clients.market_client_form'
		),
		'market_client_form' => array(
			'market_clients.market_client_orders'
		)
	);
	
	public function __construct()
	{
		parent::__construct((func_num_args() > 0) ? func_get_arg(0) : __CLASS__);
		$this->files_path = dirname(__FILE__).'/'; 
	}

	public function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'market_clients.js');
		response::send($tmpl->parse($this), 'js');
	}

	/**
	*       Форма редактирования 
	*/
	public function sys_market_client_form()
	{
		$tmpl = new tmpl($this->pwd() . 'market_client_form.js');
		response::send($tmpl->parse($this), 'js');
	}

	/**
	*       Список заказов 
	*/
	public function sys_market_client_orders()
	{
		$tmpl = new tmpl($this->pwd() . 'market_client_orders.js');
		response::send($tmpl->parse($this), 'js');
	}
}
?>
