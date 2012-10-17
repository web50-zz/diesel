<?php
/**
*	Интерфейс данных "Точки вызова"
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class di_entry_point extends data_interface
{
	public $title = 'Точки вызова';

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
	protected $name = 'entry_point';
	
	/**
	* @var	array	$fields	Конфигурация таблицы
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => TRUE, 'readonly' => TRUE),
		'exist' => array('type' => 'boolean'),
		'reg_date' => array('type' => 'datetime'),
		'interface_id' => array('type' => 'integer'),
		'name' => array('type' => 'string'),
		'human_name' => array('type' => 'string'),
	);
	
	public function __construct()
	{
	    // Call Base Constructor
	    parent::__construct(__CLASS__);
	}

	/**
	*	Get UI entry point
	*/
	protected function sys_public()
	{
		$this->_flush(true);
		$this->connector->fetchMethod = PDO::FETCH_ASSOC;
		$in = $this->join_with_di('interface', array('interface_id' => 'id'), array('name' => 'interface_name'));
		$this->what = array(
			'SUBSTRING(`' . $this->get_alias() . '`.`name`, 5)' => 'name',
			'human_name'
		);
		$this->set_args(array('_sname' => 'pub_%'), true);
		$this->_get()
		response::send($this->get_results(), 'json');
	}
		
	/**
	*	Get entry points in group
	*/
	protected function sys_in_group()
	{
		$this->_flush(true);
		$where = array();
		$in = $this->join_with_di('interface', array('interface_id' => 'id'), array('human_name' => 'interface_name'));
		$gu = $this->join_with_di('entry_point_group', array('id' => 'entry_point_id', intval($this->get_args('gid')) => 'group_id'), array('group_id' => 'gid'));

		if (!empty($this->args['type']))
		{
			$ait = $in->get_alias();
			$where[] = "`{$ait}`.`type` LIKE '{$this->args['type']}'";
		}

		if (!empty($this->args['query']))
		{
			//$this->args["_sinterface_name"] = "%{$this->args['query']}%";
			$aep = $this->get_alias();
			$ait = $in->get_alias();
			$where[] = "(`{$aep}`.`name` LIKE '{$this->args['query']}'  OR `{$ait}`.`human_name` LIKE '{$this->args['query']}')";
		}

		$this->where = join(' AND ', $where);

		return $this->extjs_grid_json(array(
			'id', 'name',
			array('di' => $in, 'name' => 'human_name'),
			array('di' => $in, 'name' => 'type')
		));
	}

	/**
	*	Register all available entry points, and remove unavailable
	*/
	public function register($interfaces)
	{
		// Сбросить со всех записей признака проверен (поле check)
		$this->_flush();
		$this->insert_on_empty = false;
		$this->push_args(array(
			'exist' => 0,
			'_sexist' => 1,
		));
		$this->_set();
		$this->pop_args();

		foreach ($interfaces as $type => $data)
		{
			$this->process_entry_points($data, $type);
		}


		// Удаляем все ТВ, которые в процессе синхронизации не были отмечены как существующие
		$this->_flush();
		$this->insert_on_empty = false;
		$this->push_args(array(
			'_sexist' => 0,
		));
		$this->_unset();
	}

	/**
	*	Process entry points in given interfaces
	*
	* @option	array	$interfaces	The array of interfaces
	* @option	string	$type		The type of interface
	*/
	private function process_entry_points($interfaces, $type)
	{
		foreach ($interfaces as $iName => $data)
		{
			$iObj = $data['obj'];
			$iRec = $data['rec'];
			// Получить список всех точек входа (ТВ)
			$entry_points = $iObj->get_entry_poins('/^(?:sys|pub)_\w+/');

			// Пребмраем полученные ТВ
			foreach ($entry_points as $i => $entry_point)
			{
				// Проверить наличие в системе указаной ТВ
				if (($id = $this->check_entry_point_exist($entry_point, $iRec->id)) > 0)
				// Если указанная ТВ зарегистрирована, то отмечаем её как существующую
				{
					$this->_flush();
					$this->insert_on_empty = false;
					$this->push_args(array(
						'_sid' => $id,
						'exist' => 1,
						'human_name' => substr($entry_point, 4)
					));
					$this->_set();
					$this->pop_args();
				}
				else
				// Если данная ТВ не зарегистрированна, то регистрируем её
				{
					$this->_flush();
					$this->insert_on_empty = true;
					$this->push_args(array(
						'reg_date' => date('Y-m-d H:i:s'),
						'exist' => 1,
						'interface_id' => $iRec->id,
						'name' => $entry_point,
						'human_name' => substr($entry_point, 4)
					));
					$this->_set();
					$this->pop_args();
				}
			}
		}
	}

	/**
	*	Check if entry point exist
	* @option	string	$name		The name of entry_point
	* @option	string	$interface_id	The ID of interface
	* @return	integer			ID of record
	*/
	private function check_entry_point_exist($name, $interface_id)
	{
		$this->_flush();
		$this->push_args(array(
			'_sname' => $name,
			'_sinterface_id' => $interface_id
		));
		$this->_get();
		$id = intval($this->get_results(0, 'id'));
		$this->pop_args();
		return $id;
	}
	
	/**
	*	Список записей
	*/
	protected function sys_list()
	{
		$this->_flush();
		if (!empty($this->args['query']) && !empty($this->args['field']))
		{
			$this->args["_s{$this->args['field']}"] = "%{$this->args['query']}%";
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
