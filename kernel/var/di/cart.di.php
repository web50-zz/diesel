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

	/**
	*	Список записей
	*/
	public function _list()
	{
		return session::get(null, array(), $this->name);
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
