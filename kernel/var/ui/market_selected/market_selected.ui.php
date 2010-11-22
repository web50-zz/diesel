<?php
/**
*	ПИ "Магазин избранное"
*
* @author	9* 9@u9.ru  based on cart by Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class ui_market_selected extends user_interface
{
	public $title = 'Магазин избранное';
	
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
		$cart = data_interface::get_instance('market_selected');
		$data = array(
			'records' => $cart->get_records(),
			'is_logged' => authenticate::is_logged(),
			'storage'=>'/storage/',
		);
                return $this->parse_tmpl('default.html', $data);
        }
	
	protected function pub_pref_link()
	{
                return $this->parse_tmpl('front_link.html',array('path_to_fav'=>'/favorites/'));
	}

	/**
	*	Получить избранное  в виде HTML
	*/
	public function get_html_cart($method_of_payment)
	{
                return $this->parse_tmpl('table.html', $this->prepare_data($method_of_payment));
	}

	/**
	*	Подготовить данные избранного
	*/
	private function prepare_data($method_of_payment)
	{
		$cart = data_interface::get_instance('market_selected');
		$records = $cart->get_records($method_of_payment);
		$total_items = 0;
		foreach ($records as $i => $rec)
		{
			$total_items+= $rec['count'];
		}
		return array(
			'records' => $records,
			'total_items' => $total_items,
		);
	}

	/**
	*	Получить  избранное с описанием и HTML
	*/
	public function get_cart($method_of_payment)
	{
		$data = $this->prepare_data($method_of_payment);
		$data['html'] = $this->get_html_cart($method_of_payment);
		return $data;
	}

	/**
	*	Добавить элемент в избранное
	*/
	protected function pub_add()
	{
		$id = request::get('id');
		$di = data_interface::get_instance('market_selected');
		$di->_set($id);
		response::send(array(
			'success' => true,
			'count' => $di->_get($id)
		), 'json');
	}

	/**
	*	удалить  элемент 
	*/
	protected function pub_del()
	{
		$id = request::get('id');
		$di = data_interface::get_instance('market_selected');
		$di->_unset($id);
		response::send(array(
			'success' => true
		), 'json');
	}
}
?>
