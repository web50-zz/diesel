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
		'reg_date' => array('type' => 'datetime'),
		'exist' => array('type' => 'boolean'),
		'type' => array('type' => 'string'),
		'name' => array('type' => 'string'),
		'human_name' => array('type' => 'string'),
	);
	
	public function __construct () {
	    // Call Base Constructor
	    parent::__construct(__CLASS__);
	}

	/**
	*	Get UI that have public entry_points
	*/
	protected function sys_public()
	{
		$this->_flush(true);
		$ep = $this->join_with_di('entry_point', array('id' => 'interface_id'), array('name' => 'entry_point'));
		$this->set_args(array('_sentry_point' => 'pub_%'));
		$this->what = array('name', 'human_name');
		$this->set_group('id');
		$this->set_order('human_name');
		response::send($this->_get(), 'json');
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

		$ep = data_interface::get_instance('entry_point');
		$interfaces = array(
			'ui' => $this->get_di_array(),
			'di' => $this->get_ui_array(),
		);
		$ep->register($interfaces);

		// Удаляем все ТВ, которые в процессе синхронизации не были отмечены как существующие
		$this->_flush();
		$this->insert_on_empty = false;
		$this->push_args(array(
			'_sexist' => 0,
		));
		$this->_unset();
		// Получаем массив ID удалённых записей и удаляем их из таблицы связей с группами
		$epg = data_interface::get_instance('entry_point_group');
		$ids = (array)$this->get_lastChangedId();
		foreach ($ids as $id)
		{
			$epg->remove_entry_point_from_groups($id);
		}
		$this->pop_args();

		response::send(array('success' => true), 'json');
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
					$dis[$iName] = array(
						'obj' => $iObj,
						'rec' => $this->register($iName, 'di', $iObj)
					);
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
					$uis[$iName] = array(
						'obj' => $iObj,
						'rec' => $this->register($iName, 'ui', $iObj)
					);
				}
			}
		}

		$dh->close();
		return $uis;
	}

	/**
	*	Зарегистрировать интерфейс
	*/
	private function register($name, $type, $obj)
	{
		$this->_flush();
		$this->push_args(array('_sname' => $name, '_stype' => $type));
		$this->_get();
		$record = $this->get_results(0);
		$this->pop_args();

		if (!empty($record))
		{
			$this->_flush();
			$this->insert_on_empty = false;
			$this->push_args(array(
				'_sid' => $record->id,
				'exist' => 1,
				'human_name' => isset($obj->title) ? $obj->title : $name
			));
			$this->_set();
			$this->pop_args();
		}
		else
		{
			$this->_flush();
			$this->insert_on_empty = true;
			$record = array(
				'reg_date' => date('Y-m-d H:i:s'),
				'exist' => 1,
				'type' => $type,
				'name' => $name,
				'human_name' => isset($obj->title) ? $obj->title : $name
			);
			$this->push_args($record);
			$this->_set();
			$this->pop_args();
			$record['id'] = $this->get_lastChangedId(0);
		}
		return (object)$record;
	}

	/**
	*	Получить запись из таблицы по указанному интерфейсу
	* @param	string	$name	Имя интерфейса
	* @param	string	$type	Тип интерфейса
	*/
	private function get($name, $type)
	{
	}

	/**
	*	Get records list in JSON
	* @access protected
	*/
	protected function sys_list()
	{
		$this->_flush();
		$this->extjs_grid_json(array('id', 'type', 'name', 'human_name'));
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
