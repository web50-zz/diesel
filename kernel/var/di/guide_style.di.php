<?php
/**
*	Интерфейс данных "Справочник: Стили"
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class di_guide_style extends data_interface
{
	public $title = 'Справочник: Стили';

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
	protected $name = 'guide_style';
	
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
	*	Get styles in item
	*/
	public function get_styles_in_item($id)
	{
		$this->_flush(true);
		$this->push_args(array('_niid' => 'null'));
		$gu = $this->join_with_di('catalogue_style', array('id' => 'style_id', $id => 'catalogue_item_id'), array('catalogue_item_id' => 'iid'));
		$results = $this->extjs_grid_json(array('id', 'name'), false);
		$this->pop_args();
		return $results['records'];
	}
	
	/**
	*	Get styles in item
	*/
	public function sys_styles_in_item()
	{
		// Быстрый поиск по стилю - сюда
		if ($this->args['_sname'] == '') unset($this->args['_sname']);
		$this->_flush(true);
		$gu = $this->join_with_di('catalogue_style', array('id' => 'style_id', intval($this->get_args('iid')) => 'catalogue_item_id'), array('catalogue_item_id' => 'iid'));
		$this->set_order('name');
		return $this->extjs_grid_json(array('id', 'name'));
	}

	/**
	*	Список записей для ComboBox
	*/
	protected function sys_combolist()
	{
		$this->_flush();
		$this->extjs_grid_json(array('id', 'name'));
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
		$cs = data_interface::get_instance('catalogue_style');
		$this->_flush();
		$data = $this->extjs_unset_json(false);

		// Remove all links between catalogue items and styles
		$ids = (array)$this->get_lastChangedId();
		if (($ids > 0 || count($ids) > 0))
		{
			$cs->remove_items_from_style($ids);
		}

		response::send($data, 'json');
	}
}
?>
