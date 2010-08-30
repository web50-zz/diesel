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
		),
		'order_form' => array(
			'order.order_items'
		)
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
			$uiCart = user_interface::get_instance('cart');
			$diUser = data_interface::get_instance(AUTH_DI);
			$data = array(
				'args' => request::get(),
				'user' => $diUser->get_user(),
				'cart' => $uiCart->get_cart(intval(request::get('method_of_payment', 4)))
			);
			return $this->parse_tmpl('default.html',$data);
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
