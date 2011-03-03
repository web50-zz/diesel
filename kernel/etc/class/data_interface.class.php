<?php
/**
*	Базовый класс интерфейсы типа Источники Данных (ИД)
*
* @author	Litvinenko S. Anthon <crazyfluger@gmail.com>
* @access	public
* @package	FlugerCMS
*/
class data_interface extends base_interface
{
	/**
	* @access	private
	* @var	array	$registry	Регистр интерфейсов
	*/
	private	static $registry = array();
	
	/**
	* @access	protected
	* @var	string	$alias		ALIAS
	*/
	protected	$alias = null;
	
	/**
	* @access	public
	* @var	string	$title		Название интерфейса
	*/
	public		$title = 'Unnamed DI';
	
	/**
	* @access	protected
	* @var	mixed	$results	Результат работы скрипта
	*/
	protected	$results;
	
	/**
	* @access	public
	* @var	integer	$rcount		Кол-во полученных ркзультатов
	*/
	public	$rcount;
	
	/**
	* @access	public
	* @var	string	$what		Поля для выборки
	*/
	public	$what;
	
	/**
	* @access	public
	* @var	string	$from		Таблицы участвующие в выборке
	*/
	public	$from;
	
	/**
	* @access	public
	* @var	string	$where		Условия выборки
	*/
	public	$where;

	/**
	* @access	public
	* @var	string	$__order	Массив параметров для сортировки
	*/
	public $__order = array();

	/**
	* @access	public
	* @var	string	$__group	Массив параметров для группировки
	*/
	public $__group = array();
	
	/**
	* @access	public
	* @var	string	$insert_on_empty	Вставлять новую запись, если по условию ничего не найдено
	*/
	public	$insert_on_empty = false;
	
	/**
	* @access	protected
	* @var	integer:array	$lastChangedId	Id или массив Id последних измененных записей
	*/
	protected $lastChangedId;
	
	/**
	* @access	private
	* @var	boolean	$__ignore_next_flush	Игнорировать следующий вызов flush
	*/
	protected $__ignore_next_flush = false;

	/**
	*	Constructor which MUST be called from derived Class Constructor
	*/
	protected function __construct($strDerivedClassName)
	{
		parent::__construct($strDerivedClassName);
	}
  	
	/**
	*	Создать экземпляр класса указанного DI
	* @static
	* @param string $name Имя DI
	*/
	private static function set_instance($name)
	{
		try
		{
			$class = DI_CLASS_PREFIX . $name;
			class_exists($class);
			$object = new $class();
			$object->interfaceName = $name;
			self::$registry[$name] = $object;
		}
		catch(Exception $e)
		{
			throw new Exception('Can`t set data interface: ' . $e->getMessage());
		}
	}
	
	/**
	*	Получить экземпляр класса указанного DI
	* @static
	* @param string $name Имя DI
	*/
	public static function get_instance($name)
	{
		if (empty($name))
			throw new Exception('The name of data interface not present.');

		if (!isset(self::$registry[$name]))
		{
			self::set_instance($name);
			self::$registry[$name]->_init();
		}
		return self::$registry[$name];
	}
	
	/**
	*	Call method of Data Interface
	* @access	public
	* @param	string	$name	Call method`s name
	* @param	array	$args	Call arguments
	*/
	public function call($name, $args = array())
	{
		if (empty($name))
			throw new Exception('The name of method of data interface not present.');

		if ($this->check_access($this->interfaceName, DI_CALL_PREFIX . $name, 'di'))
		{
			$call_name = DI_CALL_PREFIX . $name;
			
			if (method_exists($this, $call_name))
			{
				$this->set_args($args);
				$this->$call_name();
				return true;
			}
			else
			{
				throw new Exception("The method `$name` not exist.");
			}
		}
		else
		{
			//throw new Exception("Permission denied to method `$name` of data interface `{$this->interfaceName}`.");
			return false;
		}
	}
	
	/**
	*	Задать массив результатов
	* @access	public
	* @param	array	$results	Массив результатов
	*/
	public function set_results($results)
	{
		$this->rcount = count($results);
		$this->results = $results;
	}
	
	/**
	*	Получить результаты
	* @access	public
	* @param	boolean|integer	$ind	Вернуть запись из массива результатов по индексу
	* @param	boolean|string	$field	Вернуть указанное поле
	*/
	public function get_results($ind = false, $field = false)
	{
		$results = array();
		if (!empty($field))
		{
			if ($ind !== false)
			{
				if (is_object($this->results[$ind]))
					$results = $this->results[$ind]->$field;
				else if (is_array($this->results[$ind]))
					$results = $this->results[$ind][$field];
				else
					return false;
			}
			else
			{
				foreach($this->results AS $rec)
				{
					if (is_object($rec))
						$results[] = $rec->$field;
					else if (is_array($rec))
						$results[] = $rec[$field];
					else
						return false;
				}
			}
		}
		else
		{
			$results = ($ind !== false) ? $this->results[$ind] : $this->results;
		}
		return $results;
	}
	
	/**
	*	Установить Id последн(его|их) элементов
	* @access	public
	* @param	integer|array	$id	Id или массив Id последних измененных записей
	*/
	public function set_lastChangedId($id)
	{
		$this->lastChangedId = $id;
	}
	
	/**
	*	Установить Id последн(его|их) элементов
	* @access	public
	* @param	boolean|integer	$ind	Вернуть элемент из массива по индексу
	*/
	public function get_lastChangedId($ind = false)
	{
		if (is_array($this->lastChangedId) && $ind !== false)
			return $this->lastChangedId[$ind];
		else
			return $this->lastChangedId;
	}

	/**
	*	Set number of affected rows
	* @param	integer	$count	Number of affected rows
	*/
	public function set_rowCount($count)
	{
		$this->rowCount = intval($count);
	}

	/**
	*	Get number of affected rows
	* @return	integer		Number of affected rows
	*/
	public function get_rowCount()
	{
		return (int)$this->rowCount;
	}
	
	/**
	*	Получить имя поля помеченного как serial
	* @access	public
	*/
	public function get_serial()
	{
		foreach ($this->fields as $field => $params)
		{
			if ($params['serial'] == 1)
				return $field;
		}
		return false;
	}
	
	/**
	*	Получить конфигурацию DI
	* @access public
	*/
	public function get_cfg()
	{
		return db_config::$params[$this->cfg];
	}
	
	/**
	*	Получить DB
	* @access public
	*/
	public function get_db()
	{
		return db_config::$params[$this->cfg]['dbs'][$this->db];
	}
	
	/**
	*	Получить имя ИД
	* @access public
	*/
	public function get_name()
	{
		return $this->name;
	}
	
	/**
	*	Получить имя, либо синоним ИД
	* @access public
	*/
	public function get_alias()
	{
		return (!empty($this->alias)) ? $this->alias : $this->name;
	}
	
	/**
	*	Установить синоним ИД
	* @access public
	*/
	public function set_alias($alias)
	{
		$this->alias = $alias;
	}
	
	/**
	*	Удалить синоним ИД
	* @access public
	*/
	public function unset_alias()
	{
		$this->alias = null;
	}
	
	/**
	*	Получить режим обработки данных
	* @access public
	*/
	public function get_mode()
	{
		return (!isset($this->mode) || empty($this->mode)) ? 'STANDARD' : $this->mode;
	}
	
	/**
	*	Установить группировку
	* @param	string	$field	Имя поля
	* @param	string	$di	Интерфейс данных
	*/
	public function set_group($field, $di = false)
	{
		if (!$di) $di = $this;
		$this->__group[] = array('field' => $field, 'di' => $di);
	}
	
	/**
	*	Установить сортировку выборки
	* @param	string	$field	Имя поля
	* @param	string	$dir	Направление сортировки ASC или DESC
	*/
	public function set_order($field, $dir = false, $di = false)
	{
		if ($di === false) $di = $this;
		$dir = (!in_array(strtoupper($dir), array('ASC', 'DESC'))) ? 'ASC' : strtoupper($dir);
		$this->__order[] = array('field' => $field, 'dir' => $dir, 'di' => $di);
	}
	
	/**
	*	Установить ограничение выборки
	* @param	integer	$start	Начало
	* @param	integer	$limit	Смещение
	*/
	public function set_limit($start, $limit)
	{
		$this->connector->set_limitation($start, $limit);
	}
	
	/**
	*	Установить синоним для поля
	*
	* @param	string|array	$field	Имя поля
	* @param	string		$alias	Синоним поля
	*/
	public function set_fields_join_alias($field, $alias)
	{
		if (!is_array($field))
			$this->fields[$field]['join_alias'] = $alias;
		else
			foreach ($field as $field_name)
				$this->fields[$field_name]['join_alias'] = $alias;
	}
	
	/**
	*	Получить синоним поля, при объединении
	*
	* @param	string		$field		Имя поля
	* @param	string|boolean	$return_alias	Вернуть указанное значение, если синоним не задан, по умолчанию FALSE
	* @return	string|boolean			Синоним поля, либо $return_alias
	*/
	public function get_fields_join_alias($field, $return_alias = false)
	{
		if (!empty($this->fields[$field]['join_alias']))
			return $this->fields[$field]['join_alias'];
		else
			return $return_alias;
	}
	
	/**
	*	Удалить все синонимы полей, используемые при объединении
	* @param	boolean|string	$field	Если указано имя поля, то удаляется значение только для него
	*/
	public function unset_fields_join_alias($field = false)
	{
		if ($field === false)
			$this->unset_fields_join_alias(array_keys($this->fields));
		else if (is_array($field))
			foreach ($field AS $field_name)
				$this->unset_fields_join_alias($field_name);
		else if (!empty($field) && isset($this->fields[$field]['join_alias']))
			unset($this->fields[$field]['join_alias']);
	}
	
	/**
	*	Возвращает синоним поля, если оно задано
	*
	* @param	string		$field		Имя поля
	* @param	string|boolean	$return_alias	Вернуть указанное значение, если синоним не задан, по умолчанию FALSE
	* @return	string|boolean			Синоним поля, либо $return_alias
	*/
	public function get_field_alias($field, $return_alias = false)
	{
		if (!empty($this->fields[$field]['alias']))
			return $this->fields[$field]['alias'];
		else
			return $return_alias;
	}
	
	/**
	*
	*/
	public function get_field_name_by_alias($alias)
	{
		foreach ($this->fields as $field_name => $field_params)
			if ($field_params['alias'] == $alias||$field_params['join_alias'] == $alias)
				return $field_name;
		return false;
	}

	/**
	*	Check if given field exists
	* @poption	string	$name	Field name
	* @return	boolean	TRUEif exists, else FALSE
	*/
	public function field_exists($name)
	{
		return array_key_exists($name, $this->fields);
	}
	
	/**
	*	Инициализация интерфейса
	*/
	private function _init()
	{
		$conType = db_config::$params[$this->cfg]['type'];
		$conName = CONNECTOR_CLASS_PREFIX . $conType;
		$this->connector = new $conName($this);
	}
	
	/**
	*	Сбросить служебные поля конектора
	* @param	boolean	$ignore_next	Игнорировать следующий вызов _flush
	*/
	public function _flush($ignore_next = false)
	{
		if ($this->__ignore_next_flush === false)
		{
			$this->where = null;
			$this->__order = array();
			$this->__group = array();
			$this->connector->_flush();
		}
		
		$this->__ignore_next_flush = $ignore_next;
	}
	
	/**
	*	Объеденить ИД с другим ИД
	* @param	mixed	$with_di	Имя ИД
	* @param	array	$on		Массив вида "левое_поле" => "правое_поле"
	* @param	array	$fields_alias	Массив синонимов полей при объединении (для $with_di)
	* @param	mixed	$by_di		Объединяющий ИД, по умолчанию текущий ИД
	* @param	string	$join_type	Тип объединения, по умолчанию определяется конектором
	* @return	mixed	Возвращает уникальное имя связки, формируемый конектором, либо FALSE
	*/
	public function join_with_di($with_di, $on, $fields_alias = array(), $by_di = null, $join_type = null)
	{
		// Если объединяющий ИД не был указан
		if ($by_di == null)
			$by_di =& $this;
		// Если было передано имя объединяющего ИД
		else if (!is_object($by_di))
			$by_di = data_interface::get_instance($by_di);
		
		// Если было передано имя объединяемого ИД
		if (!is_object($with_di))
			$with_di = clone data_interface::get_instance($with_di);
		
		if ($with_di->cfg != $by_di->cfg)
		{
			throw new Exception('Can`t join DI with different configuration.'."({$with_di->cfg} and {$by_di->cfg})");
		}
		
		return $this->connector->_join($by_di, $with_di, $on, $fields_alias, $join_type);
	}
	
	public function _get($query = false)
	{
		$this->connector->_get($query);
		return $this->get_results();
	}
	
	public function _set($query = false)
	{
		return $this->connector->_set($query);
	}
	
	public function _unset($query = false)
	{
		if (!$query && $this->args['records'] && !$this->args['_sid'])
		{
			$this->args['_sid'] = request::json2int($this->args['records']);
		}
		return $this->connector->_unset($query);
	}
	
	public function _clear()
	{
		return $this->connector->_clear();
	}
	
	/**
	*	Сформировать XML-пакет для ExtJS-формы
	* @access	public
	* @param	boolean	$with_response	Если TRUE то автоматически отсылается XML-пакет
	*/
	public function extjs_form_json($fields = false, $with_response = true)
	{
		$this->_flush();
		$this->connector->fetchMethod = PDO::FETCH_ASSOC;
		$this->what = $fields;
		$this->_get();
		$data = array(
			'success' => true,
			'data' => $this->get_results(0)
			);
		if ($with_response)
			response::send($data, 'json');
		else
			return $data;
	}
	
	/**
	*	Сформировать JSON-пакет для ExtJS-грида
	* @access	public
	* @param	boolean|array	$fields		Массив полей для выборки, если FALSE, то выбираются все поля
	* @param	boolean		$with_response	Если TRUE то автоматически отсылается JSON-пакет
	*/
	public function extjs_grid_json($fields = false, $with_response = true)
	{
		$this->_flush();
		$data = array();
		
		//$this->what = 'COUNT(*) AS `total`';
		//$this->_get();
		//$data['total'] = $this->get_results(0, 'total');
		
		$this->connector->fetchMethod = PDO::FETCH_ASSOC;
		$this->what = $fields;
		$this->NOT_prepare_where = false;

		$order = $this->get_args(array('sort', 'dir'));
		if (!empty($order))
			$this->set_order($order['sort'], $order['dir']);

		$limit = $this->get_args(array('start', 'limit'));
		if (!empty($limit))
			$this->set_limit($limit['start'], $limit['limit']);

		$data['success'] = true;
		$data['records'] = $this->_get();
		$data['total'] = $this->connector->_found_rows;
		
		if ($with_response)
			response::send($data, 'json');
		else
			return $data;
	}
	
	/**
	*	Сформировать JSON-пакет для ExtJS-дерева
	* @access	public
	* @param	boolean|array	$fields		Массив полей для выборки, если FALSE, то выбираются все поля
	* @param	integer		$deep_into	Уровень вложенности
	* @param	boolean		$with_response	Если TRUE то автоматически отсылается JSON-пакет
	*/
	public function extjs_slice_json($fields = false, $deep_into = 0, $with_response = true)
	{
		$assoc = !(count(array_keys($fields)) > 0);
		$this->_flush();
		$this->connector->fetchMethod = PDO::FETCH_ASSOC;
		$this->what = array_merge(array('left', 'right', 'level'), $fields);
		if ($deep_into == 1)
			$this->where.= $this->name.'_parent.level + 1 = '.$this->name.'.level';
		else if ($deep_into > 1)
			$this->where.= $this->name.'_parent.level > '.$this->name.'.level AND '.$this->name.'_parent.level + '.$deep_into.' <= '.$this->name.'.level';
		$this->NOT_prepare_where = false;
		$this->set_order('left');
		$this->_get();
		$data = array();
		foreach ($this->results AS $rec)
		{
			if ($assoc)
			{
				$node = array(
					'id' => $rec[$fields['id']],
					'text' => $rec[$fields['text']],
					'expanded' => ($rec['left'] + 1 == $rec['right'])
					);
			}
			else
			{
				$node = array_intersect_key($rec, array_fill_keys($fields, ''));
				$node['expanded'] = ($rec['left'] + 1 == $rec['right']);
			}
			$data[] = $node;
		}
		
		if ($with_response)
			response::send($data, 'json');
		else
			return $data;
	}
	
	/**
	*	Сохранить данные и сформировать JSON-пакет для ExtJS
	* @access	public
	* @param	boolean		$with_response	Если TRUE то автоматически отсылается JSON-пакет
	*/
	public function extjs_set_json($with_response = true)
	{
		try
		{
			$this->_set();
			$data = array(
				'success' => true,
				'data' => array(
					'id' => $this->get_lastChangedId(0)
					)
				);
		}
		catch(Exception $e)
		{
			$data = array(
				'success' => false,
				'errors' =>  $e->getMessage()
				);
		}
		
		
		if ($with_response)
			response::send($data, 'json');
		else
			return $data;
	}
	
	/**
	*	Удалить данные и сформировать JSON-пакет для ExtJS
	* @access	public
	* @param	boolean		$with_response	Если TRUE то автоматически отсылается JSON-пакет
	*/
	public function extjs_unset_json($with_response = true)
	{
		try
		{
			$this->_unset();
			$data = array(
				'success' => true,
				'data' => array(
					'id' => $this->get_lastChangedId(0)
					)
				);
		}
		catch(Exception $e)
		{
			$data = array(
				'success' => false,
				'errors' =>  $e->getMessage()
				);
		}
		
		
		if ($with_response)
			response::send($data, 'json');
		else
			return $data;
	}

	/**
	*	Сформировать дамп данных
	* @access	public
	* @param	bool	$only_structure	Сделать дамп только структуры
	*/
	public function make_dump($only_structure = FALSE)
	{
		if (method_exists($this->connector, 'dump_structure'))
		{
			file_system::write_to_file(DUMP_PATH . $this->name . '.strc.sql', $this->connector->dump_structure());
			if (!$only_structure)
				$this->connector->dump_data(DUMP_PATH . $this->name . '.data.sql');
		}
		else
		{
			throw new Exception("Connector has no dumper");
		}
	}

	/**
	*	Сформировать дамп данных
	* @access	public
	* @param	bool	$only_structure	Сделать дамп только структуры
	*/
	public function init_dump($only_structure = FALSE)
	{
	}
}
?>
