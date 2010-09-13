<?php
/**
*	Интерфейс данных "Заказы"
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class di_cart extends data_interface
{
	public $title = 'Заказы';

	/**
	* @var	string	$cfg	Имя конфигурации БД
	*/
	protected $cfg = 'session';
	
	/**
	* @var	string	$name	Имя таблицы
	*/
	protected $name = 'cart';

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
			$cati->_flush(true);
			$cati->connector->fetchMethod = PDO::FETCH_ASSOC;
			$gt = $cati->join_with_di('guide_type', array('type_id' => 'id'), array('name' => 'str_type'));
			$gp = $cati->join_with_di('guide_price', array('price_id' => 'id'), array('cost' => 'str_cost'));
			$gc = $cati->join_with_di('guide_collection', array('collection_id' => 'id'), array('name' => 'str_collection'));
			$cati->what = array(
				'id',
				'title',
				array('di' => $gt, 'name' => 'name'),
				array('di' => $gp, 'name' => 'cost'),
				array('di' => $gc, 'name' => 'discount')
			);
			$cati->set_args(array("_sid" => $ids));

			$records = $cati->_get();
			$i = $this->_list();
			foreach ($records as $n => $rec)
			{
				$count = $i[$rec['id']];

				if ($method_of_payment == 4 && $rec['discount'] > 0)
				{
					$cost = sprintf("%0.2f", ceil($rec['str_cost'] * ((100 - $rec['discount']) / 100)));
					$records[$n]['discbool'] = 1;
					$records[$n]['discount'] = $rec['discount'];
				}
				else
				{
					$cost = $rec['str_cost'];
					$records[$n]['discbool'] = 0;
					$records[$n]['discount'] = 0;
				}

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
