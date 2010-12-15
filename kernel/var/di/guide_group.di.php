<?php
/**
*	Интерфейс данных "Справочник: Группы"
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class di_guide_group extends data_interface
{
	public $title = 'Справочник: Группы';

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
	protected $name = 'guide_group';
	
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
		if (!empty($this->args['_sname']))
			$this->args['_sname'] = "{$this->args['_sname']}%";
		else
			unset($this->args['_sname']);

		$this->_flush();
		$this->_flush(true);	// Это не баг - это что бы "extjs_grid_json" не обнулял сортировку
		$this->set_order('name');
		$this->extjs_grid_json(array('id', 'name'));
	}
	
	/**
	*	Список записей
	*/
	protected function sys_list()
	{
		$this->_flush(true);
		if($this->args['name'] !='')
		{
			$where[] = '`name` LIKE "%'.$this->args['name'].'%"'; 
		}
		
		if (!empty($where))
		{
			$this->where = join(' AND ',$where);
		}

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
