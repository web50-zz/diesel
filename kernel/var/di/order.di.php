<?php
/**
*	Интерфейс данных "Заказы"
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class di_order extends data_interface
{
	public $title = 'Заказы';

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
	protected $name = 'order';

	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => TRUE, 'readonly' => TRUE),
		'created_datetime' => array('type' => 'datetime'),	// Дата создания
		'user_id' => array('type' => 'integer'),		// ID пользователя
		'status' => array('type' => 'integer'),			// Статус заказа
		'country_id' => array('type' => 'integer'),		// Страна
		'region_id' => array('type' => 'integer'),		// Регион
		'address' => array('type' => 'string'),			// Адрес
		'method_of_payment' => array('type' => 'integer'),	// Способ оплаты
		'discount' => array('type' => 'float'),			// Скидка
		'total_items' => array('type' => 'interger'),		// Общее кол-во товаров в заказе
		'total_items_cost' => array('type' => 'float'),		// Общая стоимость всех товаров (без учёта доставки)
		'number_of_parcels' => array('type' => 'integer'),	// Кол-во почтовых отправлений (из расчёта 6-ть в посылке)
		'delivery_cost' => array('type' => 'float'),		// Стоимость доставки
		'total_cost' => array('type' => 'float'),		// Общая стоимость заказа с учётом доставки
		'comments' => array('type' => 'text'),			// Коментарий
	);
	
	public function __construct ()
	{
	    // Call Base Constructor
	    parent::__construct(__CLASS__);
	}

	/**
	*	Список записей
	*/
	protected function sys_list()
	{
		$this->_flush(true);
		$user = $this->join_with_di('user', array('user_id' => 'id'), array('name' => 'str_user_name'));
		$pt = $this->join_with_di('guide_pay_type', array('method_of_payment' => 'id'), array('title' => 'pt_string'));
		$this->set_order('id', 'DESC');
		$this->extjs_grid_json(array(
			'id', 'created_datetime', 'status', 'discount', 'total_items', 'total_items_cost', 'delivery_cost', 'total_cost',
			array('di' => $user, 'name' => 'name'),
			array('di' => $pt, 'name' => 'title')
		));
	}
	
	/**
	*	Получить данные элемента в виде JSON
	* @access protected
	*/
	protected function sys_get()
	{
		$this->_flush(true);
		$user = $this->join_with_di('user', array('user_id' => 'id'), array('name' => 'str_user_name'));
		$this->extjs_form_json(array(
			'id', 'created_datetime', 'status', 'method_of_payment', 'discount', 'total_items', 'total_items_cost', 'number_of_parcels', 'delivery_cost', 'total_cost', 'comments',
			array('di' => $user, 'name' => 'name')
		));
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
	*	Сохранить данные
	* @access	public
	* @param	array	$data	Datas
	*/
	public function set($data)
	{
		$uiCart = user_interface::get_instance('cart');
		$cart = $uiCart->get_cart(intval(request::get('method_of_payment', 4)));

		$this->push_args((array)$data);
		$this->set_args(array(
			'created_datetime' => date('Y-m-d H:i:s'),
			'user_id' => (integer)UID,
			'status' => 0,
			'total_items' => $cart['total_items'],
			'total_items_cost' => $cart['total_summ'],
			'number_of_parcels' => $cart['parcels'],
			'delivery_cost' => $cart['delivery_cost'],
			'total_cost' => $cart['total_cost'],
		), true);
		$this->_flush();
		$this->insert_on_empty = true;
		$results = $this->extjs_set_json(false);
		$this->pop_args();
		return $results;
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
