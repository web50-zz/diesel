<?php
/**
*	ПИ "Корзина заказа"
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class ui_cart extends user_interface
{
	public $title = 'Корзина заказа';
	
	public function __construct ()
	{
		parent::__construct(__CLASS__);
		$this->files_path = dirname(__FILE__).'/'; 
	}
        
        /**
        *       Отрисовка контента для внешней части
        */
        public function pub_content()
        {
		$cart = data_interface::get_instance('cart');
		$data = array(
			'records' => $cart->get_records()
		);
                return $this->parse_tmpl('default.html', $data);
        }

	/**
	*	Расчитать общую сумму корзины
	*/
	public function calculate()
	{
		$summ = 0;
		$cart = data_interface::get_instance('cart');
		$records = $cart->get_records();

		foreach ($records as $rec)
		{
			$summ+= (float)$rec['str_cost'] * $rec['count'];
		}

		return $summ;
	}

	/**
	*	Пересчёт корзины
	*/
	protected function pub_recalc()
	{
		$counts = request::get('count', array());
		$di = data_interface::get_instance('cart');

		foreach ($counts as $id => $count)
		{
			$di->_set($id, $count);
		}

		response::send(array(
			'success' => true,
			'summ' => $this->calculate()
		), 'json');
	}

	/**
	*	Добавить элемент в корзину
	*/
	protected function pub_add()
	{
		$id = request::get('id');
		$di = data_interface::get_instance('cart');
		$di->_set($id);
		response::send(array(
			'success' => true,
			'count' => $di->_get($id),
			'summ' => $this->calculate()
		), 'json');
	}

	/**
	*	Добавить элемент в корзину
	*/
	protected function pub_del()
	{
		$id = request::get('id');
		$di = data_interface::get_instance('cart');
		$di->_unset($id);
		response::send(array(
			'success' => true,
			'summ' => $this->calculate()
		), 'json');
	}
}
?>
