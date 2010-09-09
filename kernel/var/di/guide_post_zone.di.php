<?php
/**
*	Интерфейс данных "Справочник: Валюты"
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class di_guide_post_zone extends data_interface
{
	public $title = 'Справочник: Валюты';

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
	protected $name = 'guide_post_zone';
	
	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => TRUE, 'readonly' => TRUE),
		'title' => array('type' => 'string'),
		'cost' => array('type' => 'float'),
		'ccy' => array('type' => 'integer'),
	);
	
	public function __construct()
	{
	    // Call Base Constructor
	    parent::__construct(__CLASS__);
	}

	/**
	*	Список записей для ComboBox
	*/
	protected function sys_combolist()
	{
		$this->_flush();
		$data = $this->extjs_grid_json(array('id', 'title'), false);
		if ($this->get_args('with_empty') == 'yes')
			array_unshift($data['records'], array('id' => '', 'title' => 'Все'));
		response::send($data, 'json');
	}
	
	/**
	*	Список записей
	*/
	protected function sys_list()
	{
		$this->_flush(true);
		$ccy = $this->join_with_di('guide_currency', array('ccy' => 'id'), array('name' => 'ccy_string'));
		$this->extjs_grid_json(array(
			'id', 'title', 'cost', 'ccy',
			array('di' => $ccy, 'name' => 'name')
		));
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
