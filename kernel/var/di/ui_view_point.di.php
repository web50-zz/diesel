<?php
/**
*	Интерфейс данных "Точки вывода UI"
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class di_ui_view_point extends data_interface
{
	public $title = 'Точки вывода UI';

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
	protected $name = 'ui_view_point';
	
	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => TRUE, 'readonly' => TRUE),
		'page_id' => array('type' => 'integer', 'alias' => 'pid'),
		'view_point' => array('type' => 'integer'),
		'ui_name' => array('type' => 'string'),
		'ui_configure' => array('type' => 'string'),
	);
	
	public function __construct () {
	    // Call Base Constructor
	    parent::__construct(__CLASS__);
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
	*	Получить конфигурацию страницы в виде JSON
	* @access protected
	*/
	protected function sys_page_configuration()
	{
		$this->_flush();
		response::send(array(
			'success' => true,
			'data' => $this->_get()
		), 'json');
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
		$data = $this->extjs_set_json(false);
		if ($data['data']['id'] > 0)
		{
			$this->push_args(array('_sid' => $data['data']['id']));
			$this->_get();
			$this->pop_args();
			$data['data'] = $this->get_results(0);
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
		$this->extjs_unset_json();
	}
}
?>
