<?php
/**
*	Интерфейс данных "Справочник: Страны"
*
* @author       9*, Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class di_guide_country extends data_interface
{
	public $title = 'Справочник: Страны';

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
	protected $name = 'guide_country';

	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => TRUE, 'readonly' => TRUE),
		'created_datetime' => array('type' => 'datetime'),
		'creator_uid' => array('type' => 'integer'),
		'changed_datetime' => array('type'=>'datetime'),
		'changer_uid' => array('type'=>'integer'),
		'deleted_datetime' => array('type'=>'datetime'),
		'deleter_uid' => array('type'=>'integer'),
		'title' => array('type' => 'string'),
		'title_eng' => array('type' => 'string'),
		'code' => array('type' => 'string'),
		'cost' => array('type' => 'float'),
		'ccy' => array('type' => 'float'),
	);
	
	public function __construct ()
	{
	    // Call Base Constructor
	    parent::__construct(__CLASS__);
	}
	
	/**
	*	Получить данные элемента в виде JSON
	* @access protected
	*/
	public function get()
	{
		$this->_flush();
		$this->connector->fetchMethod = PDO::FETCH_ASSOC;
		return $this->_get();
	}

	/**
	*	Список записей
	*/
	protected function sys_list()
	{
		$this->_flush(true);
		$ccy = $this->join_with_di('guide_currency', array('ccy' => 'id'), array('name' => 'ccy_string'));
		$this->extjs_grid_json(array(
			'id',
			'IF (`'.$this->get_alias().'`.`title` != "", `'.$this->get_alias().'`.`title`, `'.$this->get_alias().'`.`title_eng`)' => 'title',
			'code', 'cost', 'ccy',
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
	*	Получить данные элемента в виде JSON
	* @access protected
	*/
	protected function sys_item()
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

		if ($this->get_args('_sid') > 0)
		{
			$this->set_args(array('changed_datetime' => date('Y-m-d H:i:s')), true);
			$this->set_args(array('changer_uid' => UID), true);
		}
		else
		{
			$this->set_args(array('changed_datetime' => date('Y-m-d H:i:s')), true);
			$this->set_args(array('creator_uid' => UID), true);
			$this->set_args(array('created_datetime' => date('Y-m-d H:i:s')), true);
			$this->set_args(array('changer_uid' => UID), true);
		}

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
