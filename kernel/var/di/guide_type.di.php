<?php
/**
*	Интерфейс данных "Справочник: Типы"
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class di_guide_type extends data_interface
{
	public $title = 'Справочник: Типы';

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
	protected $name = 'guide_type';
	
	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => TRUE, 'readonly' => TRUE),
		'name' => array('type' => 'string'),
		'description' => array('type' => 'text'),
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
		$data = $this->extjs_grid_json(array('id', 'name'), false);
		if ($this->get_args('with_empty') == 'yes')
			array_unshift($data['records'], array('id' => '', 'name' => 'Все'));
		response::send($data, 'json');
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
