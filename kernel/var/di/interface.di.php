<?php
/**
*	Data Interface "Interfaces"
*
* @author	Anthon S. Litvinenko <a.litvinenko@web50.ru>
* @package	SBIN Diesel
*/
class di_interface extends data_interface
{
	public $title = 'The Interfaces';

	/**
	* @var	string	$cfg	DB configurations name
	*/
	protected $cfg = 'localhost';
	
	/**
	* @var	string	$db	DB name
	*/
	protected $db = 'db1';
	
	/**
	* @var	string	$name	Tables name
	*/
	protected $name = 'interface';
	
	/**
	* @var	array	$fields	Tables configuration
	*/
	public $fields = array(
		'id' => array('type' => 'integer', 'serial' => TRUE, 'readonly' => TRUE),
		'exist' => array('type' => 'boolean'),
		'type' => array('type' => 'string'),
		'reg_date' => array('type' => 'datetime'),
		'name' => array('type' => 'string'),
		'human_name' => array('type' => 'string'),
		'entry_point' => array('type' => 'string'),
		'human_entry_point' => array('type' => 'string'),
	);
	
	public function __construct () {
	    // Call Base Constructor
	    parent::__construct(__CLASS__);
	}

	/**
	*	System interfaces syncronization
	* @access protected
	*/
	protected function sys_sync()
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

		// Обрабатываем список всех ИД
		$this->process_entry_points($this->get_di_array(), 'di');

		// Обрабатываем список всех ИП
		$this->process_entry_points($this->get_ui_array(), 'ui');

		// Удаляем все ТВ, которые в процессе синхронизации не были отмечены как существующие
		$this->_flush();
		$this->insert_on_empty = false;
		$this->push_args(array(
			'_sexist' => 0,
		));
		$this->_unset();
		// Получаем массив ID удалённых записей и удаляем их из таблицы связей с группами
		$ig = data_interface::get_instance('interface_group');
		$ids = (array)$this->get_lastChangedId();
		foreach ($ids as $id)
		{
			$ig->remove_interface_from_groups($id);
		}
		$this->pop_args();

		response::send(array('success' => true), 'json');
	}

	/**
	*	Process entry points in given interfaces
	*
	* @option	array	$interfaces	The array of interfaces
	* @option	string	$iType		The type of interface
	*/
	private function process_entry_points($interfaces, $iType)
	{
		foreach ($interfaces as $iName => $iObj)
		{
			// Получить список всех точек входа (ТВ)
			$entry_points = $iObj->get_entry_poins('/^(?:sys|pub)_\w+/');

			// Пребмраем полученные ТВ
			foreach ($entry_points as $i => $entry_point)
			{
				// Проверить наличие в системе указаной ТВ
				if (($id = $this->check_entry_point_exist($iType, $iName, $entry_point)) > 0)
				// Если указанная ТВ зарегистрирована, то отмечаем её как существующую
				{
					$this->_flush();
					$this->insert_on_empty = false;
					$this->push_args(array(
						'_sid' => $id,
						'exist' => 1,
						'human_name' => $iObj->title,
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
						'type' => $iType,
						'name' => $iName,
						'human_name' => $iObj->title,
						'entry_point' => $entry_point,
					));
					$this->_set();
					$this->pop_args();
				}
			}
		}
	}

	/**
	*	Check if entry point exist
	* @option	string	$type		The type of interface
	* @option	string	$name		The name of interface
	* @option	string	$entry_point	The entry point`s name
	* @return	integer			ID of record
	*/
	private function check_entry_point_exist($type, $name, $entry_point)
	{
		$this->connector->exec(
			"SELECT `id` FROM `{$this->name}` WHERE `type` = :type AND `name` = :name AND `entry_point` = :entry_point", // Query
			array(					// Array of data
				'type' => $type,
				'name' => $name,
				'entry_point' => $entry_point
			),
			true					// memorize results
		);
		return intval($this->get_results(0, 'id'));
	}

	/**
	*	Get array of DI
	* @access	private
	* @return	array	The array of DI objects
	*/
	private function get_di_array()
	{
		$dis = array();
		// Получить список всех ИП
		$dh = dir(DI_PATH);

		while (($iFile = $dh->read()) !== FALSE)
		{
			if (preg_match('/^(\w+)\.di\.php$/', $iFile, $match))
			{
				$iName = $match[1];

				if ($iObj = data_interface::get_instance($iName))
				{
					$dis[$iName] = $iObj;
				}
			}
		}

		$dh->close();
		return $dis;
	}

	/**
	*	Get array of UI
	* @access	private
	* @return	array	The array of UI objects
	*/
	private function get_ui_array()
	{
		$uis = array();
		// Получить список всех ИП
		$dh = dir(UI_PATH);

		while (($iName = $dh->read()) !== FALSE)
		{
			if (is_dir(UI_PATH . $iName) && preg_match('/^\w+$/', $iName))
			{
				if ($iObj = user_interface::get_instance($iName))
				{
					$uis[$iName] = $iObj;
				}
			}
		}

		$dh->close();
		return $uis;
	}
	
	/**
	*	Get users in group
	*/
	public function sys_intefaces_in_group()
	{
		$this->_flush(true);
		$gu = $this->join_with_di('interface_group', array('id' => 'interface_id', intval($this->get_args('gid')) => 'group_id'), array('group_id' => 'gid'));
		return $this->extjs_grid_json(array('id', 'type', 'name', 'human_name', 'entry_point', 'human_entry_point'));
	}

	/**
	*	Get records list in JSON
	* @access protected
	*/
	protected function sys_list()
	{
		$this->_flush();
		$this->extjs_grid_json(array('id', 'type', 'name', 'human_name', 'entry_point', 'human_entry_point'));
	}
	
	/**
	*	Get record in JSON
	* @access protected
	*/
	protected function sys_get()
	{
		$this->_flush();
		$this->extjs_form_json();
	}
	
	/**
	*	Set data to storage and return results in JSON
	* @access protected
	*/
	protected function sys_set()
	{
		$this->_flush();
		$this->insert_on_empty = true;
		$this->extjs_set_json();
	}
	
	/**
	*	Unset data to storage and return results in JSON
	* @access protected
	*/
	protected function sys_unset()
	{
		$this->_flush();
		$this->extjs_unset_json();
	}
}
?>
