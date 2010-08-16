<?php
/**
*	Интерфейс данных "Заказы: товары"
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class di_order_item extends data_interface
{
	public $title = 'Заказы: товары';

	/**
	* @var	string	$cfg	Имя конфигурации БД
	*/
	protected $cfg = 'localhost';
	
	/**
	* @var	string	$db	Имя БД
	*/
	protected $db = 'db1';
	
	/**
	* @var	string	$name	Имя таблицы
	*/
	protected $name = 'order_item';

	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => TRUE, 'readonly' => TRUE),
		'order_id' => array('type' => 'integer'),
		'item_id' => array('type' => 'integer'),
		'count' => array('type' => 'integer'),
		'cost' => array('type' => 'integer'),
	);
	
	public function __construct()
	{
	    // Call Base Constructor
	    parent::__construct(__CLASS__);
	}

	/**
	*	Список записей
	*/
	protected function sys_list()
	{
		$this->_flush();
		$this->extjs_grid_json();
	}
	
	/**
	*	Получить данные элемента в виде JSON
	* @access protected
	*/
	protected function sys_get()
	{
		$this->_flush();
		$this->extjs_form_json();
	}
	
	/**
	*	Сохранить данные и вернуть JSON-пакет для ExtJS
	* @access protected
	*/
	protected function sys_set()
	{
		$this->_flush();
		$this->insert_on_empty = true;
		$this->extjs_set_json();
	}

	/**
	*	Запомнить набор корзины в таблицу
	* @access public
	* @param	integer	$order_id	ID заказа
	*/
	public function remember_cart($order_id)
	{
		$cart = data_interface::get_instance('cart');
		$records = $cart->get_records();
		
		foreach ($records as $rec)
		{
			$this->set_args(array(
				'order_id' => $order_id,
				'item_id' => $rec['id'],
				'count' => $rec['count'],
				'cost' => $rec['str_cost'],
			));
			$this->_flush();
			$this->insert_on_empty = true;
			$this->_set();
			$cart->_unset($rec['id']);
		}

		return true;
	}
	
	/**
	*	Удалить данные и вернуть JSON-пакет для ExtJS
	* @access protected
	*/
	protected function sys_unset()
	{
		$this->_flush();
		$this->extjs_unset_json();
	}
}
?>
