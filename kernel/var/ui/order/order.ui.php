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
		//'main' => array(
		//	'order.order_form',
		//	'order.order_list',
		//	'order.filter_form',
		//),
		//'order_form' => array(
		//	'order.order_items'
		//)

		'main' => array(
			'order.order_list',
			'order.filter_form',
		),
		'order_list' => array(
			'order.order_form',
		),
		'order_form' => array(
			'order.order_items'
		),
	);
	
	public function __construct()
	{
		parent::__construct((func_num_args() > 0) ? func_get_arg(0) : __CLASS__);
		$this->files_path = dirname(__FILE__).'/'; 
	}

        public function pub_content()
        {
		if (request::get('order-confirm') == 'yes')
		{
			$diOrder = data_interface::get_instance('order');
			$diOItem = data_interface::get_instance('order_item');
			// Создаём запись заказа и получает ID
			$result = $diOrder->set(request::get());

			if ($result['success'] == true)
			{
				// Сохраняем карзину в талицу
				$diOItem->remember_cart($result['data']['id']);
			}

			// возвращаем результат
			return $this->parse_tmpl('success.html', $data);
		}
		else
		{
			$diClient = data_interface::get_instance('market_clients');
			$uiCart = user_interface::get_instance('cart');
			$user = $diClient->get_data();
			$cart = $uiCart->get_cart(intval(request::get('method_of_payment', $user->clnt_payment_pref)));

			if ($cart['total_items'] > 0)
			{
				$data = array(
					'args' => request::get(),
					'user' => $user,
					'cart' => $cart
				);
				return $this->parse_tmpl('default.html',$data);
			}
			else
			{
				response::redirect('/');
			}
		}
	}

	/**
	*	Пересчитать корзину
	*/
	public function pub_recalc()
	{
		$uiCart = user_interface::get_instance('cart');
		$cart = $uiCart->get_cart(intval(request::get('method_of_payment', 4)));
		unset($cart['records']);
		return response::send($cart, 'json');
	}

	public function sys_main()
	{
		$tmpl = new tmpl($this->pwd() . 'order.js');
		response::send($tmpl->parse($this), 'js');
	}

	/**
	*       Список заказов 
	*/
	public function sys_order_list()
	{
		$tmpl = new tmpl($this->pwd() . 'order_list.js');
		response::send($tmpl->parse($this), 'js');
	}

	/**
	*       Форма поиска 
	*/
	public function sys_filter_form()
	{
		$tmpl = new tmpl($this->pwd() . 'filter_form.js');
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

	/**
	*       Список заказанных товаров 
	*/
	public function sys_order_items()
	{
		$tmpl = new tmpl($this->pwd() . 'order_items.js');
		response::send($tmpl->parse($this), 'js');
	}
}
?>
