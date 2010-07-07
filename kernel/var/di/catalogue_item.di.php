<?php
/**
*	Интерфейс данных "Каталог: товары"
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @version	1.0
* @access	public
*/
class di_catalogue_item extends data_interface
{
	public $title = 'Каталог: товары';

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
	protected $name = 'catalogue_item';
	
	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => TRUE, 'readonly' => TRUE),
		'exist' => array('type' => 'integer'),
		'title' => array('type' => 'string'),
		'description' => array('type' => 'text'),
		'cost' => array('type' => 'float'),
	);
	
	public function __construct()
	{
	    // Call Base Constructor
	    parent::__construct(__CLASS__);
	}
	
	/**
	*	Список записей
	*/
	protected function sys_list()
	{
		$this->_flush(true);
		$sc = $this->join_with_di('structure_content', array('id' => 'cid'), array('pid' => 'pid'));
		$this->extjs_grid_json(array('id', 'exist', 'title', 'cost'));
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
		$data = $this->extjs_set_json(false);
		if ($this->args['_sid'] == 0)
		{
			$sc = data_interface::get_instance('structure_content');
			$sc->save_link($this->args['pid'], $data['data']['id'], $this->name);
		}
		response::send($data, 'json');
	}
	
	/**
	*	Удалить данные и вернуть JSON-пакет для ExtJS
	* @access protected
	*/
	protected function sys_unset()
	{
		$this->_flush();
		$data = $this->extjs_unset_json(false);
		$ids = $this->get_lastChangedId();
		
		if (($ids > 0 || count($ids) > 0) && $this->args['_spid'] > 0)
		{
			$sc = data_interface::get_instance('structure_content');
			$sc->remove_link($this->args['_spid'], $ids, $this->name);
		}

		response::send($data, 'json');
	}
}
?>
