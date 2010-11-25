<?php
/**
*	Интерфейс данных "магазин Избранное"
*
* @author	 9*	
* @package	SBIN Diesel
*/
class di_market_selected extends data_interface
{
	public $title = 'Заказы';

	/**
	* @var	string	$cfg	Имя конфигурации БД
	*/
	protected $cfg = 'session';
	
	/**
	* @var	string	$name	Имя таблицы
	*/
	protected $name = 'market_selected';

	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => TRUE, 'readonly' => TRUE),
	);
	
	public function __construct ()
	{
	    // Call Base Constructor
	    parent::__construct(__CLASS__);
	}
	
	public function get_records($method_of_payment = 0)
	{
		$records = array();
		$ids = array_keys($this->_list());
		if (!empty($ids))
		{
			$cati = data_interface::get_instance('catalogue_item');
			$cati->set_args(array("_sid" => $ids));
			$rec = $cati->get_items();
			$records = $rec['records'];
			$i = $this->_list();
			foreach ($records as $n => $rec)
			{
				$count = $i[$rec['id']];
				$records[$n]['count'] = $count;
				$records[$n]['price1'] = $rec['str_cost'];
				$records[$n]['price2'] = $cost;
				$records[$n]['summ'] = sprintf("%0.2f", $cost * $count);
			}
		}

		return (array)$records;
	}

	/**
	*	Список записей
	*/
	public function _list()
	{
		return (array)session::get(null, array(), $this->name);
	}
	
	/**
	*	Получить данные элемента в виде JSON
	* @access protected
	*/
	public function _get($id)
	{
		return session::get($id, 0, $this->name);
	}
	
	/**
	*	Сохранить данные и вернуть JSON-пакет для ExtJS
	* @access protected
	*/
	public function _set($id, $count = 0)
	{
		$count = (!$count) ? $this->_get($id) + 1 : $count;
		return session::set($id, $count, $this->name);
	}
	
	/**
	*	Удалить данные и вернуть JSON-пакет для ExtJS
	* @access protected
	*/
	public function _unset($id)
	{
		return session::del($id, $this->name);
	}
}
?>
