<?php
/**
*	Интерфейс данных "Новости"
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @package	CFsCMS2(PE)
*/
class di_news extends data_interface
{
	public $title = 'Лента новостей';
	
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
	protected $name = 'news';
	
	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => 1),
		'release_date' => array('type' => 'date'),
		'title' => array('type' => 'string'),
		'source' => array('type' => 'string'),
		'author' => array('type' => 'string'),
		'content' => array('type' => 'text')
	);
	
        public function __construct () {
            // Call Base Constructor
            parent::__construct(__CLASS__);
        }
	
	/**
	*	Получить JSON-пакет данных для ExtJS-грида
	* @access protected
	*/
	protected function sys_list()
	{
		$this->_flush(true);
		$sc = $this->join_with_di('structure_content', array('id' => 'cid'), array('pid' => 'pid'));
		$this->extjs_grid_json(array('id', 'release_date', 'title', 'author', 'source'));
	}
	
	/**
	*	Получить данные для ExtJS-формы
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
