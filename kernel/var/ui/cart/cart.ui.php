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
        protected function pub_content()
        {
		$cart = data_interface::get_instance('cart');
		$data = array(
			'records' => $cart->get_records(),
			'is_logged' => authenticate::is_logged()
		);
                return $this->parse_tmpl('default.html', $data);
        }

	/**
	*	Получить корзину в виде HTML
	*/
	public function get_html_cart($method_of_payment)
	{
                return $this->parse_tmpl('table.html', $this->prepare_data($method_of_payment));
	}

	/**
	*	Подготовить данные корзины
	*/
	private function prepare_data($method_of_payment)
	{
		$cart = data_interface::get_instance('cart');
		$records = $cart->get_records();
		$total_items = 0;
		$total_summ = 0;
		foreach ($records as $i => $rec)
		{
			if ($method_of_payment == 4)
			{
				$s = sprintf("%0.2f", ceil($rec['str_cost'] * ((100 - $rec['discount']) / 100)));
				$records[$i]['str_cost'] = $s;
			}
			else
			{
				$s = $rec['str_cost'];
			}

			$records[$i]['total_cost'] = sprintf("%0.2f", $s * $rec['count']);

			$total_summ+= $s;
			$total_items+= $rec['count'];
		}
		$parcels = ceil($total_items / 6);
		$delivery_cost = ($method_of_payment == 2) ? 220 : $parcels * 200;
		return array(
			'records' => $records,
			'total_items' => $total_items,
			'total_summ' => sprintf("%0.2f", $total_summ),
			'parcels' => $parcels,
			'delivery_cost' => sprintf("%0.2f", $delivery_cost),
			'total_cost' => sprintf("%0.2f", $total_summ + $delivery_cost),
			'method_of_payment' => $method_of_payment,
		);
	}

	/**
	*	Получить корзину с описанием и HTML
	*/
	public function get_cart($method_of_payment)
	{
		$data = $this->prepare_data($method_of_payment);
		$data['html'] = $this->get_html_cart($method_of_payment);
		return $data;
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
