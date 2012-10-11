<?php
/**
*	Коннектор к MongoDB
*
* @author	Litvinenko S. Anthon <a.litvinenko@web50.ru>
* @access	public
* @package	SBIN Diesel
* @since	2012-10-08
*/
class connector_mongo
{
	/**
	* @access	protected
	* @var	object	$di	Ссылка на интерфейс данных, для которого создается коннектор
	*/
	protected $di;
	
	/**
	* @access	protected
	* @var	mixed	$cnh	Connection handler
	*/
	protected $cnh;
	
	/**
	* @access	protected
	* @var	mixed	$dbh	DB Handler
	*/
	protected $dbh;
	
	/**
	* @access	private
	* @var	mixed	$collection	Collection handler
	*/
	private $collection;
	
	/**
	* @access	private
	* @var	string	$_what		Поля для выборки
	*/
	private $_what;
	
	/**
	* @access	private
	* @var	string	$_fields	Поля для записи в БД
	*/
	private $_fields = array();
	
	/**
	* @access	private
	* @var	string	$_fields_values	Данные полей для записи в БД
	*/
	private $_fields_values = array();
	
	/**
	* @access	private
	* @var	string	$_where		Условия для выборки
	*/
	private $_where;
	
	/**
	* @access	private
	* @var	string	$_order		Сортировка выборки
	*/
	private $_order;
	
	/**
	* @access	private
	* @var	integer	$_limit		Ограничение выборки
	*/
	private $_limit = 0;
	
	/**
	* @access	private
	* @var	integer	$_skip		Начало выборки
	*/
	private $_skip = 0;

	/**
	* @access	public
	* @var	boolean	$debug		Записать запрос в debug-файл
	*/
	public $debug = FALSE;
	
	public function __construct($di)
	{
		$this->di = $di;
		$this->_init();
	}
	
	function __destruct()
	{
		unset($this->dbh);
	}
	
	protected function _init()
	{
		try
		{
			// new Mongo("mongodb://${username}:${password}@localhost:27017")
			// Getting configuration
			$cfg = $this->di->get_cfg();

			// Host string
			$strHost = (!empty($cfg['port'])) ? "{$cfg['host']}:{$cfg['port']}" : "{$cfg['host']}";

			// Authenticate string
			$strAuth = (!empty($cfg['user'])) ? "mongodb://{$cfg['user']}:{$cfg['pass']}@" : "";

			// Connecting
			$this->cnh = new Mongo($strAuth . $strHost);

			// Select DataBase
			$this->dbh = $this->cnh->selectDB($this->di->get_db());

			// Select Collection
			$this->collection = new MongoCollection($this->dbh, $this->di->get_name());
		}
		catch(Exception $e)
		{
			throw new Exception('Connector initialization error: '.$e->getMessage());
		}
	}

	/**
	*	Return current collection
	*/
	public function get_collection()
	{
		return $this->collection;
	}

	/**
	*	Return current cursor
	*/
	public function get_cursor()
	{
		return $this->_cursor;
	}
	
	/**
	*	Определить список ID затрагиваемых условиями
	*/
	private function _get_changed_id()
	{
		$cursor = $this->get_collection()->find($this->_where, array('_id'));
		return array_keys(iterator_to_array($cursor, true));
	}

	/**
	*	Сделать дамп структуры
	*/
	public function dump_structure()
	{
		/*
		$table = $this->di->get_alias();
		$query = "SHOW CREATE TABLE `{$table}`";
		$this->_get($query);
		return $this->di->get_results(0, 'Create Table');
		*/
		Throw new Exception(__FUNCTION__ . " is not implemented");
	}

	/**
	*	Инициализировать структуру
	* @param	string	$file		The file with structure
	*/
	public function init_structure($file)
	{
		/*
		$table = $this->di->get_alias();
		$this->query("DROP TABLE IF EXISTS `{$table}`");
		$this->query(file_get_contents($file));
		*/
		Throw new Exception(__FUNCTION__ . " is not implemented");
	}

	/**
	*	Сделать дамп данных
	* @param	string	$outfile	The file name for dump
	*/
	public function dump_data($outfile)
	{
		/*
		$dbname = $this->di->get_db();
		$table = $this->di->get_alias();
		//$query = "SELECT * INTO OUTFILE '{$outfile}' FROM `{$table}`";
		//$this->query($query);
		$cfg = $this->di->get_cfg();
		//$command = "mysqldump --skip-triggers --compact --no-create-info --opt -h {$cfg['host']} -u {$cfg['user']} -p{$cfg['pass']} {$dbname} {$table} > {$outfile}";
		$command = "mysqldump --opt --no-create-info  -h {$cfg['host']} -u {$cfg['user']} -p{$cfg['pass']} {$dbname} {$table} > {$outfile}";
		system($command , $return);
		*/
		Throw new Exception(__FUNCTION__ . " is not implemented");
	}

	/**
	*	Загрузить данные из файла
	* @param	string	$file		The file with data
	*/
	public function init_data($file)
	{
		/*
		$dbname = $this->di->get_db();
		$cfg = $this->di->get_cfg();
		if (!empty($cfg['pass']))
			$command = "mysql -h {$cfg['host']} -u {$cfg['user']} -p{$cfg['pass']} {$dbname} < {$file}";
		else
			$command = "mysql -h {$cfg['host']} -u {$cfg['user']} {$dbname} < {$file}";
		system($command , $return);
		*/
		Throw new Exception(__FUNCTION__ . " is not implemented");
	}
	
	/**
	*	Выполнить пользовательский запрос
	*
	* @param	string	$query	Запрос на языке SQL
	*/
	public function query($query)
	{
		/*
		try
		{
			$this->dbh->query($sql);
			return TRUE;
		}
		catch(Exception $e)
		{
			throw new Exception('Query error: '.$e->getMessage()."\nQuery: {$sql}");
		}
		*/
		Throw new Exception(__FUNCTION__ . " is not implemented");
	}
	
	/**
	*	Сбросить все служебные значения
	*/
	public function _flush()
	{
		$this->_cursor = null;
		$this->_what = array();
		$this->_where = array();
		$this->_order = array();
		$this->_limit = 0;
		$this->_skip = 0;
		$this->_fields = array();
		$this->_fields_values = array();
		return $this;
	}
	
	/**
	*	Сбросить все служебные значения
	*/
	public function _reset()
	{
		$this->_cursor = null;
		$this->_what = array();
		$this->_where = array();
		$this->_fields = array();
		$this->_fields_values = array();
		return $this;
	}
	
	/**
	*	Выполнить выборку из БД на основе условий указанных пользователем и внешних данных
	* @param	string	$query	Ручной запрос
	*/
	public function _get($query = false)
	{
		$this->_reset();
		$this->di->set_results(array());
		
		// execute query
		$this->_prepare_get();

		if ($this->debug)
			dbg::write("WHERE: " . print_r($this->_where, 1) . "\nFIELDS: " . print_r($this->_what, 1) . "\nSORTING: " . print_r($this->_order, 1) . "\nLIMIT: {$this->_skip}, {$this->_limit}");


		// retrieve all documents
		$this->_cursor = $this->get_collection()->find($this->_where, $this->_what);
		// Apply sorting
		if (!empty($this->_order))
		{
			var_dump($this->_order);
			$this->_cursor->sort($this->_order);
		}
		// Set limitation
		if ($this->_limit > 0)
			$this->_cursor->limit($this->_limit);
		// Skip records
		if ($this->_skip > 0)
			$this->_cursor->skip($this->_skip);
		// Send number of affected rows to DI
		$this->di->set_rowCount($this->_cursor->count());

		// Send results to DI
		$this->di->set_results(iterator_to_array($this->_cursor, true));

		return $this;
	}
	
	/**
	*	Объеденить ИД с другим ИД
	* @param	object	$by_di		Объединяющий ИД
	* @param	object	$with_di	Объединяемый ИД
	* @param	string	$on		Массив вида "левое_поле" => "правое_поле"
	* @param	array	$fields_alias	Массив синонимов полей при объединении (для $with_di)
	* @param	string	$join_type	Тип объединения, по умолчанию LEFT JOIN
	* @return	mixed	Возвращает уникальное имя связки, либо FALSE
	*/
	public function _join($by_di, $with_di, $on, $fields_alias, $join_type)
	{
		Throw new Exception('MongoDB does not support joins.');
	}
	
	/**
	*	Автоматическое формирование SQL для выборки данных из БД
	*/
	private function _prepare_get()
	{
		$this->_prepare_what();
		$this->_prepare_where();
		$this->_prepare_order();
		return $this;
	}
	
	/**
	*	Подготовить поля для выгрузки
	* @access	private
	*/
	private function _prepare_what()
	{
		if (!empty($this->di->what))
		{
			$set = array();

			foreach ($fields as $key => $field)
			{
				if ($field == '*')
				// Добавить все имеющиеся поля в DI
				{
					foreach ($this->di->fields as $sFld => $pFld)
					{
						/* TODO: Разобраться с преобразованием поолей в выборке
						if ($this->emptyZeroDate && $pFld['type'] == 'date')
							$set[] = "IF(`{$name}`.`{$sFld}` = '0000-00-00', '', `{$name}`.`{$sFld}`) AS `{$sFld}`";
						else if ($this->emptyZeroDate && $pFld['type'] == 'datetime')
							$set[] = "IF(`{$name}`.`{$sFld}` = '0000-00-00 00:00:00', '', `{$name}`.`{$sFld}`) AS `{$sFld}`";
						else
						*/
							$set[] = $sFld;
					}
				}
				elseif (is_string($field) && preg_match('/^[!](\w+)$/', $field, $matches) && ($n = array_search($matches[1], $set)) !== FALSE)
				// Удалить поле из списка имеющихся полей
				{
					unset($set[$n]);
				}
				else
				{
					$set[] = $field;
				}
			}
			
			if (!empty($set))
				$this->_what = $set;
			else
				throw new Exception('Не указаны поля для выборки.');
		}
		else
		{
			$this->_what = array();
		}
		return $this;
	}
	
	/**
	*	Сформировать условие по входящим параметрам
	* Параметры должны иметь префикс `_s` или `_n`
	* Также условие может быть дополнено пользовательским из поля this::where и значениями this::where_values
	*/
	private function _prepare_where()
	{
		if ($this->di->NOT_prepare_where) return $this;
		
		$set = array();
		// NOTE: Если установлено пользовательское условие выборки
		if (!empty($this->di->where))
		{
			// NOTE: Не вкурил, что за конструкция.
			//$this->_where_values = $this->where_values;
			$set[] = $this->di->where;
		}

		// Обработка специального поля _id
		$id1 = $this->di->get_args('_s_id');
		$id2 = $this->di->get_args('_id');
		if (!empty($id1) || !empty($id2))
		{
			$ids = (!empty($id1)) ? $id1 : $id2;
			if (is_array($ids))
			{
				$cond = array();
				foreach ($ids as $id) $cond[] = new MongoId($id);
				if (!empty($cond)) $set[] = array('_id' => array('$in' => $cond));
			}
			else
			{
				$set[] = array('_id' => new MongoId($ids));
			}
		}
		
		$this->_args = $this->di->get_args();
		
		foreach ($this->di->fields as $field => $params)
		{
			$cond = $this->_prepare_where_field($this->di, $field, $params);
			
			if (!empty($cond)) $set[] = $cond;
		}
		
		if (!empty($set))
			$this->_where = array('$and' => $set);

		return $this;
	}
	
	/**
	*	Подготовить данные для условия выборки
	*
	* @param	object	$di		Ссылка на объект ИД
	* @param	string	$field		Имя поля
	* @param	array	$params		Массив параметров поля
	* @param	boolean	$byAlias	Поиск по синониму поля, если TRUE, по умолчанию FALSE
	* @param	string	$alias		Предопределённый синоним, по умолчанию NULL
	*/
	private function _prepare_where_field($di, $field, $params, $byAlias = false, $alias = NULL)
	{
		if ($params['protected']) return FALSE;
		$sField = ($byAlias) ? $params['alias'] : $field;
		if ($byAlias && !empty($alias)) $sField = $alias;
		
		$str = '';
		$name = $di->get_alias();
		switch($params['type'])
		{
			case 'integer':
				if (isset($this->_args['_s'.$sField]))
				{
					if (is_array($this->_args['_s'.$sField]))
					{
						$vals = array_unique(array_map('intval', $this->_args['_s'.$sField]));
						if (!empty($vals))
						{
							$str = array($field => array('$in' => $vals));
						}
					}
					else if (strpos($this->_args['_s'.$sField], ',') !== FALSE)
					{
						$vals = array_unique(array_map('intval', explode(",", $this->_args['_s'.$sField])));
						if (!empty($vals))
						{
							$str = array($field => array('$in' => $vals));
						}
					}
					else if ('null' != strtolower($this->_args['_s'.$sField]))
					{
						$str = array("{$field}" => intval($this->_args['_s'.$sField]));
					}
					else
					{
						$str = array("{$field}" => null);
					}
				}
				else if (isset($this->_args['_n'.$sField]))
				{
					if (is_array($this->_args['_n'.$sField]))
					{
						$vals = array_unique(array_map('intval', $this->_args['_n'.$sField]));
						if (!empty($vals))
						{
							$str = array($field => array('$nin' => $vals));
						}
					}
					else if (strpos($this->_args['_n'.$sField], ',') !== FALSE)
					{
						$vals = array_unique(array_map('intval', explode(",", $this->_args['_n'.$sField])));
						if (!empty($vals))
						{
							$str = array($field => array('$nin' => $vals));
						}
					}
					else if ('null' != strtolower($this->_args['_n'.$sField]))
					{
						$str = array("{$field}" => array('$ne' => intval($this->_args['_n'.$sField])));
					}
					else
					{
						$str = array("{$field}" => array('$ne' => null));
					}
				}
				else if (isset($this->_args['_m'.$sField]))
				{
					$str = array("{$field}" => array('$gt' => intval($this->_args['_m'.$sField])));
				}
				else if (isset($this->_args['_l'.$sField]))
				{
					$str = array("{$field}" => array('$lt' => intval($this->_args['_l'.$sField])));
				}
			break;
			case 'float':
				if (isset($this->_args['_s'.$sField]))
				{
					if (is_array($this->_args['_s'.$sField]))
					{
						$vals = array_unique(array_map('floatval', $this->_args['_s'.$sField]));
						if (!empty($vals))
						{
							$str = array($field => array('$in' => $vals));
						}
					}
					else if (strpos($this->_args['_s'.$sField], ',') !== FALSE)
					{
						$vals = array_unique(array_map('floatval', explode(",", $this->_args['_s'.$sField])));
						if (!empty($vals))
						{
							$str = array($field => array('$in' => $vals));
						}
					}
					else if ('null' != strtolower($this->_args['_s'.$sField]))
					{
						$str = array("{$field}" => floatval($this->_args['_s'.$sField]));
					}
					else
					{
						$str = array("{$field}" => null);
					}
				}
				else if (isset($this->_args['_n'.$sField]))
				{
					if (is_array($this->_args['_n'.$sField]))
					{
						$vals = array_unique(array_map('floatval', $this->_args['_n'.$sField]));
						if (!empty($vals))
						{
							$str = array($field => array('$nin' => $vals));
						}
					}
					else if (strpos($this->_args['_n'.$sField], ',') !== FALSE)
					{
						$vals = array_unique(array_map('floatval', explode(",", $this->_args['_n'.$sField])));
						if (!empty($vals))
						{
							$str = array($field => array('$nin' => $vals));
						}
					}
					else if ('null' != strtolower($this->_args['_n'.$sField]))
					{
						$str = array("{$field}" => array('$ne' => floatval($this->_args['_n'.$sField])));
					}
					else
					{
						$str = array("{$field}" => array('$ne' => null));
					}
				}
				else if (isset($this->_args['_m'.$sField]))
				{
					$str = array("{$field}" => array('$gt' => floatval($this->_args['_m'.$sField])));
				}
				else if (isset($this->_args['_l'.$sField]))
				{
					$str = array("{$field}" => array('$lt' => floatval($this->_args['_l'.$sField])));
				}
			break;
			case 'date':
				if (isset($this->_args['_s'.$sField]))
				{
					$val = new MongoDate(strtotime($this->_args['_s' . $sField]));
					$str = array($field => $val);
				}
				else if (isset($this->_args['_n'.$sField]))
				{
					$val = new MongoDate(strtotime($this->_args['_n' . $sField]));
					$str = array($field => array('$ne' => $val));
				}
				else if (isset($this->_args['_sFrom'.$sField]))
				{
					$val = new MongoDate(strtotime($this->_args['_sFrom' . $sField]));
					$str = array($field => array('$gte' => $val));
				}
				else if (isset($this->_args['_sFrom'.$sField.'_day']))
				{
					$val = new MongoDate(strtotime(sprintf('%04d-%02d-%02d', $this->_args['_sFrom'.$sField.'_year'], $this->_args['_sFrom'.$sField.'_month'], $this->_args['_sFrom'.$sField.'_day'])));
					$str = array($field => array('$gte' => $val));
				}
				else if (isset($this->_args['_sTo'.$sField]))
				{
					$val = new MongoDate(strtotime($this->_args['_sTo'.$sField]));
					$str = array($field => array('$lte' => $val));
				}
				else if (isset($this->_args['_sTo'.$sField.'_day']))
				{
					$val = new MongoDate(strtotime(sprintf('%04d-%02d-%02d', $this->_args['_sTo'.$sField.'_year'], $this->_args['_sTo'.$sField.'_month'], $this->_args['_sTo'.$sField.'_day'])));
					$str = array($field => array('$lte' => $val));
				}
			break;
			case 'time':
				/* NOTE: Не нашёл решения для поиска по времени
				if (isset($this->_args['_s'.$sField]))
				{
					$str = "{$name}.{$field} = :_s{$sField}";
					$this->_where_values['_s'.$sField] = $this->_args['_s'.$sField];
				}
				else if (isset($this->_args['_n'.$sField]))
				{
					$str = "{$name}.{$field} <> :_n{$sField}";
					$this->_where_values['_n'.$sField] = $this->_args['_n'.$sField];
				}
				else if (isset($this->_args['_sFrom'.$sField]))
				{
					$str = "{$name}.{$field} >= :_sFrom{$sField}";
					$this->_where_values['_sFrom'.$sField] = $this->_args['_sFrom'.$sField];
				}
				else if (isset($this->_args['_sFrom'.$sField.'_hour']))
				{
					$str = "{$name}.{$field} >= :_sFrom{$sField}";
					$this->_where_values['_sFrom'.$sField] = sprintf('H:i:s', $this->_args['_sFrom'.$sField.'_hour'], $this->_args['_sFrom'.$sField.'_min'], $this->_args['_sFrom'.$sField.'_sec']);
				}
				else if (isset($this->_args['_sTo'.$sField]))
				{
					$str = "{$name}.{$field} <= :_sTo{$sField}";
					$this->_where_values['_sTo'.$sField] = $this->_args['_sTo'.$sField];
				}
				else if (isset($this->_args['_sTo'.$sField.'_hour']))
				{
					$str = "{$name}.{$field} <= :_sTo{$sField}";
					$this->_where_values['_sTo'.$sField] = sprintf('H:i:s', $this->_args['_sTo'.$sField.'_hour'], $this->_args['_sTo'.$sField.'_min'], $this->_args['_sTo'.$sField.'_sec']);
				}
				*/
				$str = array("{$field}" => $this->_args['_s'.$sField]);
			break;
			case 'datetime':
				if (isset($this->_args['_s'.$sField]))
				{
					$val = new MongoDate(strtotime($this->_args['_s' . $sField]));
					$str = array($field => $val);
				}
				else if (isset($this->_args['_n'.$sField]))
				{
					$val = new MongoDate(strtotime($this->_args['_n' . $sField]));
					$str = array($field => array('$ne' => $val));
				}
				else if (isset($this->_args['_sFrom'.$sField]))
				{
					$val = new MongoDate(strtotime($this->_args['_sFrom' . $sField]));
					$str = array($field => array('$gte' => $val));
				}
				else if (isset($this->_args['_sFrom'.$sField.'_day']))
				{
					$val = new MongoDate(strtotime(sprintf('%04d-%02d-%02d H:i:s', $this->_args['_sFrom'.$sField.'_year'], $this->_args['_sFrom'.$sField.'_month'], $this->_args['_sFrom'.$sField.'_day'], $this->_args['_sFrom'.$sField.'_hour'], $this->_args['_sFrom'.$sField.'_min'], $this->_args['_sFrom'.$sField.'_sec'])));
					$str = array($field => array('$gte' => $val));
				}
				else if (isset($this->_args['_sTo'.$sField]))
				{
					$val = new MongoDate(strtotime($this->_args['_sTo'.$sField]));
					$str = array($field => array('$lte' => $val));
				}
				else if (isset($this->_args['_sTo'.$sField.'_day']))
				{
					$val = new MongoDate(strtotime(sprintf('%04d-%02d-%02d H:i:s', $this->_args['_sTo'.$sField.'_year'], $this->_args['_sTo'.$sField.'_month'], $this->_args['_sTo'.$sField.'_day'], $this->_args['_sTo'.$sField.'_hour'], $this->_args['_sTo'.$sField.'_min'], $this->_args['_sTo'.$sField.'_sec'])));
					$str = array($field => array('$lte' => $val));
				}
			break;
			case 'string': case 'text':
				if (isset($this->_args['_s'.$sField]))
				{
					if ('null' != strtolower($this->_args['_s'.$sField]))
					{
						$str = "{$name}.{$field} LIKE :_s{$sField}";
						$this->_where_values['_s'.$sField] = $this->_args['_s'.$sField];
					}
					else
					{
						$str = "{$name}.{$field} IS NULL";
					}
				}
				else if (isset($this->_args['_n'.$sField]))
				{
					if ('null' != strtolower($this->_args['_n'.$sField]))
					{
						$str = "{$name}.{$field} NOT LIKE :_n{$sField}";
						$this->_where_values['_n'.$sField] = $this->_args['_n'.$sField];
					}
					else
					{
						$str = "{$name}.{$field} IS NOT NULL";
					}
				}
			break;
			default:
				if (isset($this->_args['_s'.$sField]))
				{
					if ('null' != strtolower($this->_args['_s'.$sField]))
						$str = array("{$field}" => $this->_args['_s'.$sField]);
					else
						$str = array("{$field}" => null);
				}
				else if (isset($this->_args['_n'.$sField]))
				{
					if ('null' != strtolower($this->_args['_n'.$sField]))
						$str = array("{$field}" => array('$ne' => $this->_args['_n'.$sField]));
					else
						$str = array("{$field}" => array('$ne' => null));
				}
			break;
		}
		
		// NOTE: Если значение по имени поля не было найдено, и есть alias то ищем по нему
		if (empty($str) && !empty($params['alias']) && $sField != $params['alias'])
			return $this->_prepare_where_field($di, $field, $params, true);
		else
			return $str;
	}

	/**
	*	Подготовить группировку для запроса
	*/
	private function _prepare_group()
	{
		/* TODO: Разобраться с группировкой
		зырить здесть: http://www.php.net/manual/en/mongocollection.group.php
		if (!empty($this->di->__group))
		{
			$x = array();

			foreach ($this->di->__group as $rec)
			{
				$table = $rec['di']->get_alias();
				$x[] = "`{$table}`.`{$rec['field']}`";
			}

			$this->_group = "GROUP BY " . join(', ', $x);
		}
		*/
		Throw new Exception(__FUNCTION__ . " is not implemented");
	}

	/**
	*	Подготовить сортировку для запроса
	*/
	private function _prepare_order()
	{
		if (!empty($this->di->__order))
		{
			$x = array();

			foreach ($this->di->__order as $rec)
			{
				$dir = strtoupper($rec['dir']);
				$x[$rec['field']] = ($dir == 'DESC') ? '-1' : '1';
			}
			
			$this->_order = $x;
		}
		return $this;
	}

	/**
	*	Установить группировку для выборки
	* @param	string	$field	Имя поля
	* @param	string	$table	Имя таблицы или её alias-name
	*/
	public function set_group($field, $table)
	{
		/*
		$this->_group = "GROUP BY `{$table}`.`{$field}`";
		*/
		Throw new Exception(__FUNCTION__ . " is not implemented");
	}
	
	/**
	*	Установить сортировку выборки
	* @param	string	$field	Имя поля
	* @param	string	$dir	Направление сортировки ASC или DESC
	*/
	public function set_order($field, $dir = 'ASC', $di)
	{
		$dir = strtoupper($dir);
		if (!empty($field) && preg_match('/^\w+$/', $field)
			&& ($dir == 'ASC' OR $dir == 'DESC'))
			$x[$field] = ($dir == 'DESC') ? -1 : 1;
		return $this;
	}
	
	/**
	*	Установить ограничение выборки
	* @param	integer	$start	Начало
	* @param	integer	$limit	Смещение
	*/
	public function set_limitation($start = 0, $limit)
	{
		$this->_limit = intval($limit);
		$this->_skip = intval($start);
	}
	
	/**
	*	Сохранить данные
	* @access	public
	*/
	public function _set()
	{
		$this->_reset();
		$this->_prepare_set();
		
		if (empty($this->_where))
		// NOTE: Не указано никаких условий, добавляем запись
		{
			// Добовляем записи
			$this->get_collection()->insert($this->_fields_values);
		}
		else
		{
			// NOTE: Считаем сколько записей можно найти по такому условию
			$count = $this->get_collection()->count($this->_where);
			
			if ($count > 0)
			// NOTE: Найдены записи по данным условиям, поэтому обновляем их
			{
				var_dump($this->_fields_values);
				// Определить ID записей, которые будут затронуты обновлением
				if (($ids = $this->_get_changed_id()) !== false)
					$this->di->set_lastChangedId($ids);
				// Обновляем записи
				$this->get_collection()->update($this->_where, array('$set' => $this->_fields_values));
			}
			else if ($this->di->insert_on_empty == true)
			// NOTE: Записей по данным условиям не найдено, поэтому добавляем их, согласно флагу
			{
				// Добовляем записи
				$this->get_collection()->insert($this->_fields_values);
			}
		}
		return $this;
	}
	
	/**
	*	Подготовить SQL для сохранения данных
	* @todo	Автоматическое формирование SQL для сохранения данных в БД
	*/
	private function _prepare_set()
	{
		$this->_prepare_data();
		$this->_prepare_where();
		if (empty($this->_fields))
			throw new Exception('Undefined fields for set.');
		return $this;
	}
	
	/**
	*	Подготовить данные для сохранения в БД
	*/
	private function _prepare_data()
	{
		// NOTE: Если установлено пользовательское условие выборки
		$this->_args = $this->di->get_args();
		// NOTE: Перебираем все описанные в ID поля и ищем значения во входящих параметрах
		foreach ($this->di->fields as $key => $value)
		{
			$this->_prepare_data_field($key, $value);
		}
		return $this;
	}
	
	/**
	*	Подготовить данные для записи в БД
	*/
	private function _prepare_data_field($field, $params, $byAlias = false)
	{
		if ($params['readonly'] === TRUE) return '';
		
		$sField = ($byAlias) ? $params['alias'] : $field;
		
		switch($params['type'])
		{
			case 'fkey':
			case 'serial':
				if (intval($this->_args[$sField]) == 0) continue;
			case 'integer':
				if (isset($this->_args[$sField]))
				{
					$this->_fields[] = $field;
					$this->_fields_values[$field] = intval($this->_args[$sField]);
				}
			break;
			case 'double':
				if (isset($this->_args[$sField]))
				{
					$this->_fields[] = $field;
					$this->_fields_values[$field] = floatval($this->_args[$sField]);
				}
			break;
			case 'date':
				if (isset($this->_args[$sField]))
				{
					$this->_fields[] = $field;
					$this->_fields_values[$field] = new MongoDate(strtotime($this->_args[$sField]));
				}
				else if (isset($this->_args[$sField.'_day']))
				{
					$this->_fields[] = $field;
					$this->_fields_values[$field] = new MongoDate(strtotime(sprintf('%04d-%02d-%02d', $this->_args[$sField.'_year'], $this->_args[$sField.'_month'], $this->_args[$sField.'_day'])));
				}
			break;
			case 'time':
				/** TODO: Разобраться с полем типа время в MongoDB
				if (isset($this->_args[$sField]))
				{
					$this->_fields[] = $field;
					$this->_fields_values[$field] = $this->_args[$sField];
				}
				else if (isset($this->_args[$sField.'_hour']))
				{
					$this->_fields[] = $field;
					$this->_fields_values[$field] = sprintf('H:i:s', $this->_args[$sField.'_hour'], $this->_args[$sField.'_min'], $this->_args[$sField.'_sec']);
				}
				*/
				$this->_fields[] = $field;
				$this->_fields_values[$field] = $this->_args[$sField];
			break;
			case 'datetime':
				if (isset($this->_args[$sField]))
				{
					$this->_fields[] = $field;
					$this->_fields_values[$field] = new MongoDate(strtotime($this->_args[$sField]));
				}
				else if (isset($this->_args[$sField.'_day']))
				{
					$this->_fields[] = $field;
					$this->_fields_values[$field] = new MongoDate(strtotime(sprintf('%04d-%02d-%02d H:i:s', $this->_args[$sField.'_year'], $this->_args[$sField.'_month'], $this->_args[$sField.'_day'], $this->_args[$sField.'_hour'], $this->_args[$sField.'_min'], $this->_args[$sField.'_sec'])));
				}
			break;
			case 'password':
				if (!empty($this->_args[$sField]))
				{
					//$this->_fields[] = array('name' => $field, 'function' => "PASSWORD(:{$field})");
					$this->_fields[] = $field;
					$this->_fields_values[$field] = crypt($this->_args[$sField]);
				}
			break;
			default:
				if (isset($this->_args[$sField]))
				{
					$this->_fields[] = $field;
					$this->_fields_values[$field] = stripslashes($this->_args[$sField]);
				}
			break;
		}
		
		// NOTE: Если значение по имени поля не было найдено, и есть alias то ищем по нему
		if (!empty($params['alias']) && empty($str) && $sField != $params['alias'])
			return $this->_prepare_data_field($field, $params, true);
		else
			return $this;
	}
	
	/**
	*	Удалить данные
	*/
	public function _unset()
	{
		$this->_reset()
			->_prepare_unset();

		// Определить ID записей, которые будут затронуты удалением
		if (($ids = $this->_get_changed_id()) !== false)
			$this->di->set_lastChangedId($ids);

		$this->get_collection()->remove($this->_where);
		
		return $this;
	}
	
	/**
	*	Подготовить SQL для удаления данных
	* @todo	Автоматическое формирование SQL для удаления данных из БД
	*/
	private function _prepare_unset()
	{
		$this->_prepare_where();
		return $this;
	}
	
	/**
	*	Проверить, существует ли такая таблица
	* @access	public
	*/
	public function _exists()
	{
		/*
		$sql = 'SHOW TABLE LIKE "' . $this->di->get_name() . '"';
		$count = $this->exec($sql, array(), TRUE);
		return (boolean)(count($count) > 0);
		*/
		Throw new Exception(__FUNCTION__ . " is not implemented");
	}
	
	/**
	*	Сбросить таблицу в исходное состояние
	* @access	public
	*/
	public function _clear()
	{
		// NOTE: <a.litvinenko@web50.ru> - Возможно грязный хак, но аналога TRUNCATE пока не нашёл
		$this->get_collection()->remove();
		return $this;
	}
}
?>
