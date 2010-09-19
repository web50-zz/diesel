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
		'order_id' => array('type' => 'integer', 'alias' => 'oid'),			// ID Заказа
		'item_id' => array('type' => 'integer'),					// ID Товара
		'count' => array('type' => 'integer'),						// Кол-во товаров
		'price1' => array('type' => 'float'),						// Стоимость товара оригинальная
		'price2' => array('type' => 'float'),						// Стоимость товара с учётом скидки
		'discbool' => array('type' => 'integer'),					// Использовалась скидка (0 - Нет, 1 - Да)
		'discount' => array('type' => 'float'),						// Размер скидки
		'access' => array('type' => 'int')						// Х.З. чё за поле
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
		$data = $this->get_list_data();
		response::send($data,'json');
	}



	public function get_list_data()
	{
	
		$this->_flush(true);
		// Объединяем с ДИ Товары
		$ci = $this->join_with_di('catalogue_item', array('item_id' => 'id'), array('title' => 'str_title','id'=>'item_id'));
		// Объединяем ДИ Товары с ДИ типы товаров
		$gt = $this->join_with_di('guide_type', array('type_id' => 'id'), array('name' => 'str_type'), $ci);
		$gg = $this->join_with_di('guide_group', array('group_id' => 'id'), array('name' => 'str_group','id'=>'group_id'), $ci);
		$data = $this->extjs_grid_json(array(
			'id', 'count', 'price1', 'price2', 'discbool', 'discount',
			array('di' => $ci, 'name' => 'title'),	// Наименование товара
			array('di' => $gg, 'name' => 'name'),	// Название группы
			array('di' => $gg, 'name' => 'id'),	// Ид группы
			array('di' => $gt, 'name' => 'name'),	// Тип товара
			array('di' => $ci, 'name' => 'id'),	// Ид  товара
		),false);
		return $data;
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
		$records = $cart->get_records(intval(request::get('method_of_payment', 0)));
		
		foreach ($records as $rec)
		{
			$this->set_args(array(
				'order_id' => $order_id,
				'item_id' => $rec['id'],
				'count' => $rec['count'],
				'price1' => $rec['price1'],
				'price2' => $rec['price2'],
				'discbool' => $rec['discbool'],
				'discount' => $rec['discount'],
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
