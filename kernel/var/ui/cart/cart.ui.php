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
		$items = $cart->_list();
		if (!empty($items))
		{
			$ids = array_keys($items);
			$cati = data_interface::get_instance('catalogue_item');
			$cati->_flush(true);
			$gt = $cati->join_with_di('guide_type', array('type_id' => 'id'), array('name' => 'str_type'));
			$gp = $cati->join_with_di('guide_price', array('price_id' => 'id'), array('cost' => 'str_cost'));
			$cati->what = array(
				'id',
				'title',
				array('di' => $gt, 'name' => 'name'),
				array('di' => $gp, 'name' => 'cost'),
			);
			$cati->set_args(array("_sid" => $ids));
			//$cati->set_args(array("_sid" => $ids[0]));
			$records = $cati->_get();
		}
		$data = array(
			'cart' => $items,
			'records' => $records
		);
                return $this->parse_tmpl('default.html', $data);
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
			'count' => $di->_get($id)
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
			'success' => true
		), 'json');
	}
}
?>
