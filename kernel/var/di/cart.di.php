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
	
	public function get_records()
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
			$cati->what = array(
				'id',
				'title',
				array('di' => $gt, 'name' => 'name'),
				array('di' => $gp, 'name' => 'cost'),
			);
			$cati->set_args(array("_sid" => $ids));

			$records = $cati->_get();
			$i = $this->_list();
			foreach ($records as $n => $rec)
			{
				$records[$n]['count'] = $i[$rec['id']];
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
	public function _set($id, $count)
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
