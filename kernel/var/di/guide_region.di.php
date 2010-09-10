<?php
/**
*	Интерфейс данных "Справочник: Регионы"
*
* @author       9*, Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class di_guide_region extends data_interface
{
	public $title = 'FAQ';

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
	protected $name = 'guide_region';

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
		'country_id' => array('type' => 'integer', 'alias' => 'cid'),
		'title' => array('type' => 'text'),
		'post_zone_id' => array('type' => 'integer'),
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
		$this->set_order('title');
		$pz = $this->join_with_di('guide_post_zone', array('post_zone_id' => 'id'), array('title' => 'pz_string'));
		$this->extjs_grid_json(array('id', 'title', 'post_zone_id', array('di' => $pz, 'name' => 'title')));
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
			$this->set_args(array('created_datetime' => date('Y-m-d H:i:s')), true);
			$this->set_args(array('creator_uid' => UID), true);
			$this->set_args(array('changed_datetime' => date('Y-m-d H:i:s')), true);
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
